<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.04.10.
 * Time: 14:06
 */
class MM_WPFS_CustomerPortalService {

	const PARAM_CARD_UPDATE_SESSION = 'wpfs-card-update-session';
	const PARAM_CARD_UPDATE_SECURITY_CODE = 'wpfs-security-code';
	const COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID = 'WPFS_CARD_UPDATE_SESSION_ID';
	const TEMPLATE_ENTER_EMAIL_ADDRESS = 'enter-email-address';
	const TEMPLATE_ENTER_SECURITY_CODE = 'enter-security-code';
    const TEMPLATE_SELECT_ACCOUNT = 'select-account';
	const TEMPLATE_CUSTOMER_PORTAL = 'customer-portal';
    const TEMPLATE_INVALID_SESSION = 'invalid-session';
	const SESSION_STATUS_WAITING_FOR_CONFIRMATION = 'waiting_for_confirmation';
    const SESSION_STATUS_WAITING_FOR_ACCOUNT_SELECTION = 'waiting_for_account_selection';
	const SESSION_STATUS_CONFIRMED = 'confirmed';
	const SESSION_STATUS_INVALIDATED = 'invalidated';
	const SECURITY_CODE_STATUS_PENDING = 'pending';
	const SECURITY_CODE_STATUS_SENT = 'sent';
	const SECURITY_CODE_STATUS_CONSUMED = 'consumed';
	const SECURITY_CODE_REQUEST_LIMIT = 5;
	const SECURITY_CODE_INPUT_LIMIT = 5;
	const COOKIE_ACTION_SET = 'set';
	const COOKIE_ACTION_REMOVE = 'remove';
	const CARD_UPDATE_SESSION_VALID_UNTIL_HOURS = 1;
	const URL_RECAPTCHA_API_SITEVERIFY = 'https://www.google.com/recaptcha/api/siteverify';
	const SOURCE_GOOGLE_RECAPTCHA_V2_API_JS = 'https://www.google.com/recaptcha/api.js';
	const ASSET_DIR_CUSTOMER_PORTAL = 'customer-portal';
	const ASSET_ENTER_EMAIL_ADDRESS_PHP = 'wpfs-enter-email-address.php';
    const ASSET_ENTER_SECURITY_CODE_PHP = 'wpfs-enter-security-code.php';
    const ASSET_SELECT_ACCOUNT_PHP = 'wpfs-select-account.php';
	const ASSET_CUSTOMER_PORTAL_PHP = 'wpfs-customer-portal.php';
    const ASSET_INVALID_SESSION_PHP = 'wpfs-invalid-session.php';
	const ASSET_WPFS_CUSTOMER_PORTAL_CSS = 'wpfs-customer-portal.css';
	const ASSET_WPFS_CUSTOMER_PORTAL_JS = 'wpfs-customer-portal.js';
	const CARD_AMERICAN_EXPRESS = 'Amex';
	const CARD_DINERS_CLUB = 'Diners';
	const CARD_DISCOVER = 'Discover';
	const CARD_JCB = 'JCB';
	const CARD_MASTERCARD = 'MasterCard';
	const CARD_UNIONPAY = 'UnionPay';
	const CARD_VISA = 'Visa';
	const CARD_UNKNOWN = 'Unknown';
	const PARAM_WPFS_SUBSCRIPTION_ID = 'wpfs-subscription-id';
	const PARAM_EMAIL_ADDRESS = 'emailAddress';
	const PARAM_GOOGLE_RE_CAPTCHA_RESPONSE = 'googleReCAPTCHAResponse';
    const FULLSTRIPE_SHORTCODE_CUSTOMER_PORTAL = 'fullstripe_customer_portal';
	const FULLSTRIPE_SHORTCODE_MANAGE_SUBSCRIPTIONS = 'fullstripe_manage_subscriptions';

	const JS_VARIABLE_AJAX_URL = 'ajaxUrl';
	const JS_VARIABLE_REST_URL = 'restUrl';
	const JS_VARIABLE_STRIPE_KEY = 'stripeKey';
	const JS_VARIABLE_GOOGLE_RECAPTCHA_SITE_KEY = 'googleReCaptchaSiteKey';

	const SHORTCODE_ATTRIBUTE_NAME_AUTHENTICATION = 'authentication';
	const SHORTCODE_ATTRIBUTE_VALUE_AUTHENTICATION = 'Wordpress';

	const HANDLE_STRIPE_JS_V_3 = 'stripe-js-v3';
	const HANDLE_GOOGLE_RECAPTCHA_V_2 = 'google-recaptcha-v2';
	const HANDLE_MANAGE_SUBSCRIPTIONS_CSS = 'wpfs-manage-subscriptions-css';
	const HANDLE_MANAGE_SUBSCRIPTIONS_JS = 'wpfs-manage-subscriptions-js';

	const WPFS_PLUGIN_SLUG = 'wp-full-stripe';
	const WPFS_REST_API_VERSION = 'v1';
	const WPFS_REST_ROUTE_MANAGE_SUBSCRIPTIONS_SUBSCRIPTION = 'manage-subscriptions/subscription';

	const INVOICE_DISPLAY_HEAD_LIMIT = 5;

	const INVOICE_DISPLAY_MODE_FEW = 0;
	const INVOICE_DISPLAY_MODE_HEAD = 1;
	const INVOICE_DISPLAY_MODE_ALL = 2;

	const ATTRIBUTE_NAME_PRODUCT_EXPANDED = 'productExpanded';
    const ATTRIBUTE_NAME_LINES_EXPANDED = 'linesExpanded';

    const ACTION_NAME_CHECK_CUSTOMER_PORTAL_SESSIONS = 'fullstripe_check_customer_portal_sessions';

	/* @var bool */
	private $debugLog = false;
	/* @var MM_WPFS_LoggerService */
	private $loggerService = null;
	/* @var MM_WPFS_Logger */
	private $logger = null;
	/* @var $db MM_WPFS_Database */
	private $db = null;
	/* @var $stripe MM_WPFS_Stripe */
	private $stripe = null;
	/* @var $mailer MM_WPFS_Mailer */
	private $mailer = null;

	public function __construct() {
		$this->setup();
		$this->hooks();
	}

	private function setup() {
		$this->db            = new MM_WPFS_Database();
		$this->stripe        = new MM_WPFS_Stripe( MM_WPFS::getStripeAuthenticationToken() );
		$this->mailer        = new MM_WPFS_Mailer();
		$this->loggerService = new MM_WPFS_LoggerService();
		$this->logger        = $this->loggerService->createManageCardsAndSubscriptionsLogger( MM_WPFS_CustomerPortalService::class );
	}

	private function hooks() {

        add_shortcode( self::FULLSTRIPE_SHORTCODE_CUSTOMER_PORTAL, array( $this, 'renderShortCode' ) );
		add_shortcode( self::FULLSTRIPE_SHORTCODE_MANAGE_SUBSCRIPTIONS, array( $this, 'renderShortCode' ) );

		add_action( self::ACTION_NAME_CHECK_CUSTOMER_PORTAL_SESSIONS, array(
			$this,
            'checkSessionsAndCodes'
		) );

		add_action( 'wp_ajax_wp_full_stripe_create_card_update_session', array(
			$this,
            'handleSessionRequest'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_create_card_update_session', array(
			$this,
            'handleSessionRequest'
		) );
		add_action( 'wp_ajax_wp_full_stripe_reset_card_update_session', array(
			$this,
            'handleResetSessionRequest'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_reset_card_update_session', array(
			$this,
            'handleResetSessionRequest'
		) );
		add_action( 'wp_ajax_wp_full_stripe_validate_security_code', array(
			$this,
			'handleSecurityCodeValidationRequest'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_validate_security_code', array(
			$this,
			'handleSecurityCodeValidationRequest'
		) );
        add_action( 'wp_ajax_wp_full_stripe_select_customer_portal_account', array(
            $this,
            'handleSelectAccountRequest'
        ) );
        add_action( 'wp_ajax_nopriv_wp_full_stripe_select_customer_portal_account', array(
            $this,
            'handleSelectAccountRequest'
        ) );
        add_action( 'wp_ajax_wp_full_stripe_show_customer_portal_account_selector', array(
            $this,
            'handleShowAccountSelectorRequest'
        ) );
        add_action( 'wp_ajax_nopriv_wp_full_stripe_show_customer_portal_account_selector', array(
            $this,
            'handleShowAccountSelectorRequest'
        ) );
		add_action( 'wp_ajax_wp_full_stripe_update_card', array(
            $this,
			'handleCardUpdateRequest'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_update_card', array(
			$this,
			'handleCardUpdateRequest'
		) );
		add_action( 'wp_ajax_wp_full_stripe_cancel_my_subscription', array(
			$this,
			'handleSubscriptionCancellationRequest'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_cancel_my_subscription', array(
			$this,
			'handleSubscriptionCancellationRequest'
		) );
		add_action( 'wp_ajax_wp_full_stripe_toggle_invoice_view', array(
			$this,
			'toggleInvoiceView'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_toggle_invoice_view', array(
			$this,
			'toggleInvoiceView'
		) );

		// tnagy register REST API Endpoint for Manage Subscriptions
		add_action( 'rest_api_init', array( $this, 'registerRESTAPIRoutes' ) );

		// tnagy WPFS-861: prevent caching of pages generated by the shortcode
		add_action( 'send_headers', array( $this, 'addCacheControlHeader' ), 10, 1 );

		add_filter( 'script_loader_tag', array( $this, 'addAsyncDeferAttributes' ), 10, 2 );

	}

	public static function onActivation() {
		if ( ! wp_next_scheduled( self::ACTION_NAME_CHECK_CUSTOMER_PORTAL_SESSIONS ) ) {
			wp_schedule_event( time(), 'hourly', self::ACTION_NAME_CHECK_CUSTOMER_PORTAL_SESSIONS );
		}
	}

	public static function onDeactivation() {
		wp_clear_scheduled_hook( self::ACTION_NAME_CHECK_CUSTOMER_PORTAL_SESSIONS );
	}

    /**
     * @param $plan \StripeWPFS\Subscription
     *
     * @return boolean
     */
    public static function isDonationPlan( $subscription ) {
        $isDonationPlan = false;

        if ( isset( $subscription->plan )) {
            $isDonationPlan = strpos($subscription->plan->id, MM_WPFS::DONATION_PLAN_ID_PREFIX) === 0;
        }
        if ( !$isDonationPlan ) {
            $isDonationPlan = strpos( $subscription->items->data[0]->price->lookup_key, MM_WPFS::DONATION_PLAN_ID_PREFIX) === 0;
        }

        return $isDonationPlan;
    }

    public function checkSessionsAndCodes() {
		try {

			// tnagy invalidate expired sessions
			$this->db->invalidateExpiredCustomerPortalSessions( self::CARD_UPDATE_SESSION_VALID_UNTIL_HOURS );

			// tnagy invalidate sessions where security code request and security code input limits reached
			$this->db->invalidateCustomerPortalSessionsBySecurityCodeRequestLimit( self::SECURITY_CODE_REQUEST_LIMIT );
			$this->db->invalidateCustomerPortalSessionsBySecurityCodeInputLimit( self::SECURITY_CODE_INPUT_LIMIT );

			// tnagy remove invalidated sessions
			$invalidatedSessionIdObjects = $this->db->findInvalidatedCustomerPortalSessionIds();
			$invalidatedSessionIds       = array_map( function ( $o ) {
				return $o->id;
			}, $invalidatedSessionIdObjects );
			if ( isset( $invalidatedSessionIds ) && sizeof( $invalidatedSessionIds ) > 0 ) {
				$this->db->deleteSecurityCodesBySessions( $invalidatedSessionIds );
				$this->db->deleteInvalidatedCustomerPortalSessions( $invalidatedSessionIds );
			}

		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$this->logger->error( __FUNCTION__, $e->getMessage(), $e );
		}
	}

    private function getSessionByCookie() {
        $res = null;

        $sessionHash = $this->findSessionCookieValue();
        if ( ! is_null( $sessionHash )) {
            $res = $this->findCustomerPortalSessionByHash( $sessionHash );
        }

        return $res;
    }

    private function validateWordpressSession( $session ) {
        $result = $session;

        if ( ! is_null( $session ) ) {
            $isWaitingForAccountSelection = $this->isWaitingForAccountSelection( $session );
            $isConfirmed = $this->isConfirmed( $session);

            if ( ! $isWaitingForAccountSelection && ! $isConfirmed  ) {
                $result = null;
            }
        }

        return $result;
    }

    private function validateSaaSSession($session ) {
        $result = $session;

        if ( ! is_null( $session ) ) {
            $isWaitingForConfirmation = $this->isWaitingForConfirmation( $session );
            $isWaitingForAccountSelection = $this->isWaitingForAccountSelection( $session );
            $isConfirmed = $this->isConfirmed( $session);

            if ( ! $isWaitingForConfirmation && ! $isWaitingForAccountSelection && ! $isConfirmed ) {
                $result = null;
            }
        }

        return $result;
    }


    /**
     * @param $session
     * @return string|null
     */
    protected function determineCookieAction( $session ) {
        $res = null;

        if ( $session === null ) {
            $res = self::COOKIE_ACTION_REMOVE;
        }

        return $res;
    }

    /**
     * @param $email
     * @return int
     */
    protected function getNumberOfStripeCustomersByEmail( $email ) : int {
        $result = 0;
        $stripeCustomers = $this->stripe->getCustomersByEmail( $email );

        foreach ( $stripeCustomers as $stripeCustomer ) {
            if ( isset( $stripeCustomer ) && ( ! isset( $stripeCustomer->deleted ) || ! $stripeCustomer->deleted ) ) {
                $result += 1;
            }
        }

        return $result;
    }

    /**
     * @param $session
     * @return bool
     */
    protected function isAccountSelectionNeeded( $session ): bool {
        return $this->getNumberOfStripeCustomersByEmail( $session->email ) > 1;
    }

    /**
     * @param $session
     * @return string
     */
    protected function getTemplateToShowBySession($session ) {
        $result = self::TEMPLATE_ENTER_EMAIL_ADDRESS;

        if ( is_null( $session ) ) {
            $result = self::TEMPLATE_ENTER_EMAIL_ADDRESS;
        } elseif ( $this->isWaitingForConfirmation( $session ) ) {
            $result = self::TEMPLATE_ENTER_SECURITY_CODE;
        } elseif ( $this->isWaitingForAccountSelection( $session ) ) {
            $result = self::TEMPLATE_SELECT_ACCOUNT;
        } elseif ( $this->isConfirmed( $session ) ) {
            $result = self::TEMPLATE_CUSTOMER_PORTAL;
        }

        return $result;
    }

	public function renderShortCode( $attributes ) {
        $session = null;

		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'renderShortCode(): COOKIE=' . print_r( $_COOKIE, true ) );
		}

		if ( self::isWordpressAuthenticationNeeded( $attributes ) ) {
			$content           = null;
			$cookieAction      = null;

            $session = $this->validateWordpressSession( $this->getSessionByCookie() );

			if ( is_user_logged_in() ) {
				$stripeCustomer = null;
				$createSession  = false;

				$user = wp_get_current_user();

				if ( ! is_null( $session ) ) {
					if ( $user->user_email !== $session->email ) {
						$this->invalidateSession( $session );

						$stripeCustomer = MM_WPFS_Utils::findExistingStripeCustomerAnywhereByEmail(
							$this->db,
							$this->stripe,
							$user->user_email
						);
						if ( ! is_null( $stripeCustomer ) ) {
							$createSession = true;
						}
						$session = null;
					} else {
						$stripeCustomer = MM_WPFS_Utils::findExistingStripeCustomerAnywhereByEmail(
							$this->db,
							$this->stripe,
							$session->email
						);
						$createSession  = false;
					}
				} else {
					$stripeCustomer = MM_WPFS_Utils::findExistingStripeCustomerAnywhereByEmail(
						$this->db,
						$this->stripe,
						$user->user_email
					);
					if ( ! is_null( $stripeCustomer ) ) {
						$createSession = true;
					}
				}

				if ( $createSession ) {
					$session = $this->createCardUpdateSessionForWPUser( $user, $stripeCustomer );

                    if ( ! $this->isAccountSelectionNeeded( $session )) {
                        $this->confirmSession( $session );
                    } else {
                        $this->makeSessionWaitingForAccountSelection( $session );
                    }

					$cookieAction = self::COOKIE_ACTION_SET;
				}

				if ( ! is_null( $session ) ) {
					$model = new MM_WPFS_CustomerPortalModel();
					$model->setAuthenticationType( MM_WPFS_CustomerPortalModel::AUTHENTICATION_TYPE_WORDPRESS );

                    if ( $this->isWaitingForAccountSelection( $session) ) {
                        $this->fetchAccountDataIntoCustomerPortalModel( $model, $session->email );

                        $this->enqueueCardUpdateScript( $cookieAction, is_null( $session ) ? null : $session->hash, $model );
                        $content = $this->renderSelectAccountForm( $attributes, $model );
                    } elseif ( $this->isConfirmed( $session ) ) {
                        $stripeCustomer = $this->stripe->retrieveCustomer( $session->stripeCustomerId );
                        $this->fetchDataIntoCustomerPortalModel( $model, $stripeCustomer );

                        $this->enqueueCardUpdateScript( $cookieAction, is_null( $session ) ? null : $session->hash, $model );
                        $content = $this->renderCardsAndSubscriptionsTable( $attributes, $model );
                    }
				} else {
					$content = __( 'You haven\'t made any payments yet', 'wp-full-stripe' );
				}
			} else {
				if ( ! is_null( $session ) ) {
					$this->invalidateSession( $session );

					$cookieAction = self::COOKIE_ACTION_REMOVE;
					$this->enqueueCardUpdateScript( $cookieAction, $session->hash, null );
				}

				$content = "You are not logged in";
			}
		} else {
            $session      = $this->validateSaaSSession( $this->getSessionByCookie() );
            $cookieAction = $this->determineCookieAction( $session );

            $model = new MM_WPFS_CustomerPortalModel();
            $model->setAuthenticationType( MM_WPFS_CustomerPortalModel::AUTHENTICATION_TYPE_PLUGIN );

            if ( $this->isWaitingForAccountSelection( $session) ) {
                if ( ! $this->isAccountSelectionNeeded( $session )) {
                    $this->confirmSession( $session );
                }
            }

            $templateToShow = $this->getTemplateToShowBySession( $session );

            if ( self::TEMPLATE_ENTER_EMAIL_ADDRESS === $templateToShow ) {
                $this->enqueueCardUpdateScript( $cookieAction, is_null( $session ) ? null : $session->hash, $model );
                $content = $this->renderEmailForm( $attributes );
            } elseif ( self::TEMPLATE_ENTER_SECURITY_CODE === $templateToShow ) {
                $this->enqueueCardUpdateScript( $cookieAction, is_null( $session ) ? null : $session->hash, $model );
                $content = $this->renderSecurityCodeForm( $attributes );
            } elseif ( self::TEMPLATE_SELECT_ACCOUNT === $templateToShow ) {
                $this->fetchAccountDataIntoCustomerPortalModel( $model, $session->email );

                $this->enqueueCardUpdateScript( $cookieAction, is_null( $session ) ? null : $session->hash, $model );
                $content = $this->renderSelectAccountForm( $attributes, $model );
            } elseif ( self::TEMPLATE_CUSTOMER_PORTAL === $templateToShow ) {
                $stripeCustomer = $this->stripe->retrieveCustomer( $session->stripeCustomerId );
                $this->fetchDataIntoCustomerPortalModel( $model, $stripeCustomer );

                $this->enqueueCardUpdateScript( $cookieAction, is_null( $session ) ? null : $session->hash, $model );
                $content = $this->renderCardsAndSubscriptionsTable( $attributes, $model );
            } else {
                $content = $this->renderInvalidSession( $attributes );
            }
        }

        return $content;
	}

	private static function isWordpressAuthenticationNeeded( $shortcode_attributes ) {
		$res = false;

		if ( isset( $shortcode_attributes ) && is_array( $shortcode_attributes ) ) {
			if ( array_key_exists( self::SHORTCODE_ATTRIBUTE_NAME_AUTHENTICATION, $shortcode_attributes ) &&
			     $shortcode_attributes[ self::SHORTCODE_ATTRIBUTE_NAME_AUTHENTICATION ] === self::SHORTCODE_ATTRIBUTE_VALUE_AUTHENTICATION
			) {
				$res = true;
			}
		}

		return $res;
	}

	/**
	 * @return null|string
	 */
	private function findSessionCookieValue() {
		return isset( $_COOKIE[ self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID ] ) ? sanitize_text_field( $_COOKIE[ self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID ] ) : null;
	}

	/**
	 * @param $customerPortalSessionHash
	 *
	 * @return array|null|object
	 */
	private function findCustomerPortalSessionByHash($customerPortalSessionHash ) {
		return $this->db->findCustomerPortalSessionByHash( $customerPortalSessionHash );
	}

	/**
	 * @param $cardUpdateSession
	 *
	 * @return bool
	 */
	private function isConfirmed( $cardUpdateSession ) {
		if ( isset( $cardUpdateSession ) && isset( $cardUpdateSession->status ) ) {
			return self::SESSION_STATUS_CONFIRMED === $cardUpdateSession->status;
		} else {
			return false;
		}
	}

	/**
	 * @param $session
	 */
	private function invalidateSession($session ) {
        $session->status = self::SESSION_STATUS_INVALIDATED;

		$this->db->updateCustomerPortalSession( $session->id, array( 'status' => self::SESSION_STATUS_INVALIDATED ) );
	}

	/**
	 * @param $wpUser
	 * @param $stripeCustomer
	 *
	 * @return null
	 */
	private function createCardUpdateSessionForWPUser( $wpUser, $stripeCustomer ) {
		$options           = get_option( 'fullstripe_options' );
		$liveMode          = $options['apiMode'] === 'live';
		$cardUpdateSession = $this->createSession( $wpUser->user_email, $liveMode, $stripeCustomer->id );

		return $cardUpdateSession;
	}

	public function createSession($stripeCustomerEmail, $liveMode, $stripeCustomerId ) {

		$salt = wp_generate_password( 16, false );
		$data = time() . '|' . $stripeCustomerEmail . '|' . $liveMode . '|' . $stripeCustomerId . '|' . $salt;

		$sessionHash = hash( 'sha256', $data );

		$insertResult = $this->db->insertCustomerPortalSession( $stripeCustomerEmail, $liveMode, $stripeCustomerId, $sessionHash );

		if ( $insertResult !== - 1 ) {
			return $this->findValidSessionById( $insertResult );
		}

		return null;
	}

	private function findValidSessionById( $sessionId ) {
		$sessions = $this->db->findCustomerPortalSessionsById( $sessionId );

		$validSession = null;
		if ( isset( $sessions ) ) {
			foreach ( $sessions as $session ) {
				if ( is_null( $validSession ) && ! $this->isInvalidatedSession( $session ) ) {
					$validSession = $session;
				}
			}
		}

		return $validSession;
	}

	/**
	 * @param $cardUpdateSession
	 *
	 * @return bool
	 */
	private function isInvalidatedSession($cardUpdateSession ) {
		if ( isset( $cardUpdateSession ) && isset( $cardUpdateSession->status ) ) {
			return self::SESSION_STATUS_INVALIDATED === $cardUpdateSession->status;
		} else {
			return false;
		}
	}

	/**
	 * @param $session
	 *
	 * @return false|int
	 */
	private function confirmSession($session ) {
        $session->status = self::SESSION_STATUS_CONFIRMED;

		return $this->db->updateCustomerPortalSession( $session->id, array( 'status' => $session->status ) );
	}

    /**
     * @param $session
     *
     * @return false|int
     */
    private function makeSessionWaitingForAccountSelection($session ) {
        $session->status = self::SESSION_STATUS_WAITING_FOR_ACCOUNT_SELECTION;

        return $this->db->updateCustomerPortalSession( $session->id, array( 'status' => $session->status ) );
    }

    private function getActiveStripeCustomersByEmail( $email ) {
        $result = [];

        $stripeCustomers = $this->stripe->getCustomersByEmail( $email );
        foreach ( $stripeCustomers as $stripeCustomer ) {
            if ( isset( $stripeCustomer ) && ( ! isset( $stripeCustomer->deleted ) || ! $stripeCustomer->deleted ) ) {
                array_push( $result, $stripeCustomer );
            }
        }

        return $result;
    }


    /**
     * @param $model MM_WPFS_CustomerPortalModel
     * @param $email
     * @return void
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    private function fetchAccountDataIntoCustomerPortalModel( $model, $email ) {
        $accounts = [];
        $stripeCustomers = $this->getActiveStripeCustomersByEmail( $email );

        /* @var $stripeCustomer \StripeWPFS\Customer */
        foreach ( $stripeCustomers as $stripeCustomer ) {
            $filterParams = [
                'customer'  => $stripeCustomer->id,
                'limit'     => 100
            ];
            $stripeSubscriptions = $this->stripe->listSubscriptionsWithParams( $filterParams );

            $account = new MM_WPFS_CustomerPortalAccount();
            $account->setStripeCustomerId( $stripeCustomer->id );
            $account->setName( $stripeCustomer->name );
            $account->setCreatedAt( $stripeCustomer->created );
            $account->setCreatedAtLabel( MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat( $stripeCustomer->created ));
            $account->setNumberOfSubscriptions( count( $stripeSubscriptions ) );

            array_push( $accounts, $account );
        }

        $model->setAccountEmail( $email );
        $model->setAccounts( $accounts );
        $model->setAccountSelectorNeeded( true );
    }

	/**
	 * @param MM_WPFS_CustomerPortalModel $model
	 * @param \StripeWPFS\Customer $stripeCustomer
	 */
	private function fetchDataIntoCustomerPortalModel($model, $stripeCustomer ) {
		/**
		 * @var null|\StripeWPFS\PaymentMethod
		 */
		$defaultPaymentMethod = null;
		/**
		 * @var null|\StripeWPFS\Source
		 */
		$defaultSource = null;
		if ( isset( $stripeCustomer ) && $stripeCustomer instanceof \StripeWPFS\Customer ) {
			$model->setStripeCustomer( $stripeCustomer );

            $paymentMethodParams = array(
                'customer' => $stripeCustomer->id,
                'type'     => 'card'
            );
			$paymentMethods = $this->stripe->listPaymentMethodsWithParams( $paymentMethodParams );

			if ( isset( $paymentMethods ) && isset( $paymentMethods->data ) ) {
				foreach ( $paymentMethods->data as $paymentMethod ) {
					if ( is_null( $defaultPaymentMethod ) ) {
						if ( 'card' === $paymentMethod->type && $paymentMethod->id == $stripeCustomer->invoice_settings->default_payment_method ) {
							$defaultPaymentMethod = $paymentMethod;
						}
					}
				}
			}
			if ( isset( $stripeCustomer->sources ) && isset( $stripeCustomer->sources->data ) ) {
				foreach ( $stripeCustomer->sources->data as $source ) {
					if ( is_null( $defaultSource ) ) {
						if ( $source->object == 'card' && $source->id == $stripeCustomer->default_source ) {
							$defaultSource = $source;
						}
					}
				}
			}
		}
		if ( isset( $defaultPaymentMethod ) ) {
			$model->setDefaultPaymentMethod( $defaultPaymentMethod );
		}
		if ( isset( $defaultSource ) ) {
			$model->setDefaultSource( $defaultSource );
		}
		$card = $this->getCurrentCard( $model );
		$this->updateModelWithCard( $model, $card );
		$model->setSubscriptions( $this->prepareSubscriptions( $stripeCustomer ) );
		$model->setInvoices( $this->prepareInvoices( $stripeCustomer ) );

        $stripeCustomers = $this->getActiveStripeCustomersByEmail( $stripeCustomer->email );
        $model->setAccountSelectorNeeded( count( $stripeCustomers ) > 1 );
    }

	/**
	 * @param MM_WPFS_CustomerPortalModel $model
	 *
	 * @return mixed
	 */
	private function getCurrentCard( $model ) {
		$card = null;
		if ( ! is_null( $model->getDefaultPaymentMethod() ) ) {
			$card = $model->getDefaultPaymentMethod()->card;
		} elseif ( ! is_null( $model->getDefaultSource() ) ) {
			$card = $model->getDefaultSource();
		}

		return $card;

	}

	/**
	 * @param MM_WPFS_CustomerPortalModel $model
	 * @param \StripeWPFS\Card|\StripeWPFS\Source $card
	 */
	private function updateModelWithCard( $model, $card ) {
		if ( ! is_null( $card ) ) {
			$model->setCardNumber( $card->last4 );
			if ( $this->isAmericanExpress( $card ) ) {
				$model->setCardName( self::CARD_AMERICAN_EXPRESS );
				$model->setCardImageUrl( MM_WPFS_Assets::images( 'amex.png' ) );
			} elseif ( $this->isDinersClub( $card ) ) {
				$model->setCardName( self::CARD_DINERS_CLUB );
				$model->setCardImageUrl( MM_WPFS_Assets::images( 'diners-club.png' ) );
			} elseif ( $this->isDiscover( $card ) ) {
				$model->setCardName( self::CARD_DISCOVER );
				$model->setCardImageUrl( MM_WPFS_Assets::images( 'discover.png' ) );
			} elseif ( $this->isJCB( $card ) ) {
				$model->setCardName( self::CARD_JCB );
				$model->setCardImageUrl( MM_WPFS_Assets::images( 'jcb.png' ) );
			} elseif ( $this->isMasterCard( $card ) ) {
				$model->setCardName( self::CARD_MASTERCARD );
				$model->setCardImageUrl( MM_WPFS_Assets::images( 'mastercard.png' ) );
			} elseif ( $this->isUnionPay( $card ) ) {
				$model->setCardName( self::CARD_UNIONPAY );
				$model->setCardImageUrl( MM_WPFS_Assets::images( 'unionpay.png' ) );
			} elseif ( $this->isVisa( $card ) ) {
				$model->setCardName( self::CARD_VISA );
				$model->setCardImageUrl( MM_WPFS_Assets::images( 'visa.png' ) );
			} elseif ( $this->isUnknownCard( $card ) ) {
				$model->setCardName( self::CARD_UNKNOWN );
				$model->setCardImageUrl( MM_WPFS_Assets::images( 'generic.png' ) );
			} else {
				$model->setCardName( self::CARD_UNKNOWN );
				$model->setCardImageUrl( MM_WPFS_Assets::images( 'generic.png' ) );
			}
		}
	}

	/**
	 * @param \StripeWPFS\Card $card
	 *
	 * @return bool
	 */
	private function isAmericanExpress( $card ) {
		return $this->isBrandOf( self::CARD_AMERICAN_EXPRESS, $card->brand );
	}

	/**
	 * Checks if a \StripeWPFS\Card's brand is matching a known brand stripped and lowercased.
	 *
	 * @param $knownBrand
	 * @param $currentBrand
	 *
	 * @return bool
	 */
	private function isBrandOf( $knownBrand, $currentBrand ) {
		$strippedKnownBrand   = trim( preg_replace( '/\s+/', ' ', $knownBrand ) );
		$strippedCurrentBrand = trim( preg_replace( '/\s+/', ' ', $currentBrand ) );

		return strtolower( $strippedKnownBrand ) === strtolower( $strippedCurrentBrand );
	}

	/**
	 * @param \StripeWPFS\Card $card
	 *
	 * @return bool
	 */
	private function isDinersClub( $card ) {
		return $this->isBrandOf( self::CARD_DINERS_CLUB, $card->brand );
	}

	/**
	 * @param \StripeWPFS\Card $card
	 *
	 * @return bool
	 */
	private function isDiscover( $card ) {
		return $this->isBrandOf( self::CARD_DISCOVER, $card->brand );
	}

	/**
	 * @param \StripeWPFS\Card $card
	 *
	 * @return bool
	 */
	private function isJCB( $card ) {
		return $this->isBrandOf( self::CARD_JCB, $card->brand );
	}

	/**
	 * @param \StripeWPFS\Card $card
	 *
	 * @return bool
	 */
	private function isMasterCard( $card ) {
		return $this->isBrandOf( self::CARD_MASTERCARD, $card->brand );
	}

	/**
	 * @param \StripeWPFS\Card $card
	 *
	 * @return bool
	 */
	private function isUnionPay( $card ) {
		return $this->isBrandOf( self::CARD_UNIONPAY, $card->brand );
	}

	/**
	 * @param \StripeWPFS\Card $card
	 *
	 * @return bool
	 */
	private function isVisa( $card ) {
		return $this->isBrandOf( self::CARD_VISA, $card->brand );
	}

	/**
	 * @param \StripeWPFS\Card $card
	 *
	 * @return bool
	 */
	private function isUnknownCard( $card ) {
		return $this->isBrandOf( self::CARD_UNKNOWN, $card->brand );
	}


    /**
     * @param $subscriptions
     */
	private function prepareSubscriptionProducts( & $subscriptions ) {
	    foreach ( $subscriptions->data as $subscription ) {
            foreach ( $subscription->items->data as $item ) {
                $product = $this->stripe->retrieveProduct( $item->price->product );
                $item->price[ self::ATTRIBUTE_NAME_PRODUCT_EXPANDED ] = $product;
            }
        }
    }

	/**
	 * @param \StripeWPFS\Customer $stripeCustomer
	 *
	 * @return array
	 * @throws StripeWPFS\Exception\ApiErrorException
	 */
	private function prepareSubscriptions( $stripeCustomer ) {
		$subscriptions = array();
		if ( isset( $stripeCustomer ) ) {
		    $subscriptionParams = array(
                'customer' => $stripeCustomer->id,
                'expand'   => array(
                    'data.items.data.price'
                )
            );

		    $customerSubscriptions = $this->stripe->listSubscriptionsWithParams( $subscriptionParams );
            $this->prepareSubscriptionProducts( $customerSubscriptions );

			$subscriptions = $customerSubscriptions->data;
		}

		return $subscriptions;
	}

    private function prepareInvoiceProducts( & $invoices ) {
        foreach ( $invoices->data as $invoice ) {
            $invoiceParams = array(
                'expand' => array(
                    'lines.data.price.product'
                )
            );

            $invoiceExpanded = $this->stripe->retrieveInvoiceWithParams(
                $invoice->id,
                $invoiceParams
            );

            $invoice[ self::ATTRIBUTE_NAME_LINES_EXPANDED ] = $invoiceExpanded->lines;
        }
    }

    /**
	 * @param \StripeWPFS\Customer $stripeCustomer
	 *
	 * @return array
	 * @throws StripeWPFS\Exception\ApiErrorException
	 */
	private function prepareInvoices( $stripeCustomer ) {
		$invoices = array();
		if ( isset( $stripeCustomer ) ) {
			$invoiceParams = array(
				'customer' => $stripeCustomer->id,
			);

			$invoices = $this->stripe->listInvoicesWithParams( $invoiceParams );
			$this->prepareInvoiceProducts($invoices );
		}

		return $invoices;
	}


    /**
     * @param $subscriptionId
     *
     * @return int
     */
	private function getDonationAmount( $subscriptionId ) {
        $donationAmount = 0;

        $donationRecord = $this->db->getDonationByStripeSubscriptionId( $subscriptionId );
        if ( !is_null( $donationRecord )) {
            $donationAmount = $donationRecord->amount;
        }

        return $donationAmount;
    }

    /**
     * @param $subscriptions array
     * @param $managedSubscriptions array
     */
	private function buildManagedSubscriptionsArray( $subscriptions, &$managedSubscriptions ) {
		foreach ( $subscriptions as $subscription ) {
			$donationAmount = 0;
			if ( self::isDonationPlan( $subscription ) ) {
				$donationAmount = $this->getDonationAmount( $subscription->id );
			}

			$managedSubscriptionEntry      = new MM_WPFS_ManagedSubscriptionEntry( $subscription, $donationAmount );
			$availablePlansForSubscription = $managedSubscriptionEntry->isDonationEntry() ? array() : $this->findAvailablePlansByForm( $managedSubscriptionEntry->getSubscriptionFormName(), $managedSubscriptionEntry->getPlanId() );
			$model                         = $managedSubscriptionEntry->toModel( $this->getPriceAndIntervalLabelForPlans( $availablePlansForSubscription ) );
			array_push( $managedSubscriptions, $model );
		}
	}

    /**
     * @param $invoice \StripeWPFS\Invoice
     * @return bool
     */
    private function shouldDisplayInvoice( $invoice ) {
        return $invoice->total !== 0;
    }

	/**
	 * @param $cookieAction
	 * @param $cardUpdateSessionHash
	 * @param MM_WPFS_CustomerPortalModel $model
	 */
	private function enqueueCardUpdateScript( $cookieAction, $cardUpdateSessionHash, $model ) {
		$options = get_option( 'fullstripe_options' );

		if ( $this->debugLog ) {
			MM_WPFS_Utils::log(
				'enqueueCardUpdateScript() CALLED, cookieAction=' . $cookieAction
				. ', cardUpdateSessionHash=' . $cardUpdateSessionHash
				. ', subscriptions=' . ( is_null( $model ) ? '0' : count( $model->getSubscriptions() ) )
			);
		}

		wp_register_style( MM_WPFS::HANDLE_STYLE_WPFS_VARIABLES, MM_WPFS_Assets::css( 'wpfs-variables.css' ), null, MM_WPFS::VERSION );
		wp_enqueue_style( self::HANDLE_MANAGE_SUBSCRIPTIONS_CSS, MM_WPFS_Assets::css( self::ASSET_WPFS_CUSTOMER_PORTAL_CSS ), array( MM_WPFS::HANDLE_STYLE_WPFS_VARIABLES ), MM_WPFS::VERSION );

		wp_register_script( self::HANDLE_STRIPE_JS_V_3, 'https://js.stripe.com/v3/', array( 'jquery' ) );
		wp_register_script( MM_WPFS::HANDLE_SPRINTF_JS, MM_WPFS_Assets::scripts( 'sprintf.min.js' ), null, MM_WPFS::VERSION );

		if (MM_WPFS_ReCaptcha::getSecureCustomerPortal()) {
			$source = add_query_arg(
				array(
					'render' => 'explicit'
				),
				MM_WPFS::SOURCE_GOOGLE_RECAPTCHA_V2_API_JS
			);
			wp_register_script( MM_WPFS::HANDLE_GOOGLE_RECAPTCHA_V_2, $source, null, MM_WPFS::VERSION, true /* in footer */ );
			$dependencies = array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-spinner',
				'jquery-ui-selectmenu',
				'underscore',
				'backbone',
				MM_WPFS::HANDLE_SPRINTF_JS,
				self::HANDLE_STRIPE_JS_V_3,
				MM_WPFS::HANDLE_GOOGLE_RECAPTCHA_V_2
			);
		} else {
			$dependencies = array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-spinner',
				'jquery-ui-selectmenu',
				'underscore',
				'backbone',
				MM_WPFS::HANDLE_SPRINTF_JS,
				self::HANDLE_STRIPE_JS_V_3
			);
		}

		wp_enqueue_script(
			self::HANDLE_MANAGE_SUBSCRIPTIONS_JS,
			MM_WPFS_Assets::scripts( self::ASSET_WPFS_CUSTOMER_PORTAL_JS ),
			$dependencies,
			MM_WPFS::VERSION
		);

        $wpfsCustomerPortalSettings = array(
            self::JS_VARIABLE_AJAX_URL                      => admin_url( 'admin-ajax.php' ),
            self::JS_VARIABLE_REST_URL                      => get_rest_url( null ),
            self::JS_VARIABLE_GOOGLE_RECAPTCHA_SITE_KEY     => MM_WPFS_ReCaptcha::getSiteKey()
        );
        if ( $options['apiMode'] === 'test' ) {
            $wpfsCustomerPortalSettings[ self::JS_VARIABLE_STRIPE_KEY ] = $options['publishKey_test'];
        } else {
            $wpfsCustomerPortalSettings[ self::JS_VARIABLE_STRIPE_KEY ] = $options['publishKey_live'];
        }

		$sessionData = array();
        $wpfsMyAccountOptions  = array();

		$sessionData['i18n'] = array(
			'confirmSubscriptionCancellationMessage'        => __( 'Are you sure you\'d like to cancel the selected subscriptions?', 'wp-full-stripe' ),
			'confirmSingleSubscriptionCancellationMessage'  => __( 'Are you sure you\'d like to cancel this subscription?', 'wp-full-stripe' ),
            'confirmSingleSubscriptionActivationMessage'    => __( 'Are you sure you\'d like to activate this subscription again?', 'wp-full-stripe' ),
			'selectAtLeastOneSubscription'                  => __( 'Select at least one subscription!', 'wp-full-stripe' ),
			'cancelSubscriptionSubmitButtonCaptionDefault'  =>
			/* translators: Default button text for cancelling subscriptions - disabled state */
				__( 'Cancel subscription', 'wp-full-stripe' ),
			'cancelSubscriptionSubmitButtonCaptionSingular' =>
			/* translators: Button text for cancelling one subscription */
				__( 'Cancel 1 subscription', 'wp-full-stripe' ),
			'cancelSubscriptionSubmitButtonCaptionPlural'   =>
			/* translators: Button text for cancelling several subscriptions at once */
				__( 'Cancel %d subscriptions', 'wp-full-stripe' )
		);

		$stripeSubscriptions = array();
		if ( ! is_null( $model ) ) {
            $this->buildManagedSubscriptionsArray( $model->getSubscriptions(), $stripeSubscriptions );
		}
		$sessionData['stripe']['subscriptions'] = $stripeSubscriptions;

		if ( $options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION ] ) {
			$stripeInvoices = array();
			if ( ! is_null( $model ) ) {
				foreach ( $model->getInvoices() as $invoice ) {
                    if ( $this->shouldDisplayInvoice( $invoice ) ) {
                        $managedInvoiceEntry = new MM_WPFS_ManagedInvoiceEntry( $invoice );
                        array_push( $stripeInvoices, $managedInvoiceEntry->toModel() );
                    }
				}
			}

			if ( count( $stripeInvoices ) <= self::INVOICE_DISPLAY_HEAD_LIMIT ) {
                $wpfsMyAccountOptions['invoiceDisplayMode'] = self::INVOICE_DISPLAY_MODE_FEW;
			} elseif ( $options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_ALL_INVOICES ] ) {
                $wpfsMyAccountOptions['invoiceDisplayMode'] = self::INVOICE_DISPLAY_MODE_ALL;
			} else {
                $wpfsMyAccountOptions['invoiceDisplayMode'] = self::INVOICE_DISPLAY_MODE_HEAD;
				$stripeInvoices                             = array_slice( $stripeInvoices, 0, self::INVOICE_DISPLAY_HEAD_LIMIT );
			}

			$sessionData['stripe']['invoices'] = $stripeInvoices;
            $wpfsMyAccountOptions['showInvoicesSection'] = 1;
		} else {
            $wpfsMyAccountOptions['showInvoicesSection'] = 0;
		}

		// Converting the string ('0' or '1') to int, it makes dealing with it in javascript easier
        $wpfsMyAccountOptions['letSubscribersCancelSubscriptions'] = $options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS ] + 0;
        $wpfsMyAccountOptions['letSubscribersUpdowngradeSubscriptions'] = $options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS ] + 0;

		if ( self::COOKIE_ACTION_SET === $cookieAction ) {
			$sessionData['action']                = 'setCookie';
			$sessionData['cookieName']            = self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID;
			$sessionData['cookieValidUntilHours'] = self::CARD_UPDATE_SESSION_VALID_UNTIL_HOURS;
			$sessionData['cookiePath']            = COOKIEPATH;
			$sessionData['cookieDomain']          = COOKIE_DOMAIN;
		} elseif ( self::COOKIE_ACTION_REMOVE === $cookieAction ) {
			$sessionData['cookieName'] = self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID;
			$sessionData['action']     = 'removeCookie';
		}
		if ( ! is_null( $cardUpdateSessionHash ) ) {
			$sessionData['sessionId'] = $cardUpdateSessionHash;
		}

        $wpfsCustomerPortalSettings[ 'sessionData' ] = $sessionData;
        $wpfsCustomerPortalSettings[ 'preferences' ] = $wpfsMyAccountOptions;

        wp_localize_script( self::HANDLE_MANAGE_SUBSCRIPTIONS_JS, 'wpfsCustomerPortalSettings', $wpfsCustomerPortalSettings );
	}

	/**
	 * @param $attributes
	 * @param MM_WPFS_CustomerPortalModel $model
	 *
	 * @return string
	 */
	public function renderCardsAndSubscriptionsTable( $attributes, $model ) {
		ob_start();
		/** @noinspection PhpIncludeInspection */
		include MM_WPFS_Assets::templates( self::ASSET_DIR_CUSTOMER_PORTAL . DIRECTORY_SEPARATOR . self::ASSET_CUSTOMER_PORTAL_PHP );
		$content = ob_get_clean();

		return $content;
	}

	/**
	 * @param $session
	 *
	 * @return bool
	 */
	private function isWaitingForConfirmation( $session ) {
		if ( isset( $session ) && isset( $session->status ) ) {
			return self::SESSION_STATUS_WAITING_FOR_CONFIRMATION === $session->status;
		} else {
			return false;
		}
	}

    /**
     * @param $session
     *
     * @return bool
     */
    private function isWaitingForAccountSelection( $session ) {
        if ( isset( $session ) && isset( $session->status ) ) {
            return self::SESSION_STATUS_WAITING_FOR_ACCOUNT_SELECTION === $session->status;
        } else {
            return false;
        }
    }

    /**
	 * @return null|string
	 */
	private function findSecurityCodeByRequest() {
		return isset( $_REQUEST[ self::PARAM_CARD_UPDATE_SECURITY_CODE ] ) ? sanitize_text_field( $_REQUEST[ self::PARAM_CARD_UPDATE_SECURITY_CODE ] ) : null;
	}

	/**
	 * @param $cardUpdateSession
	 *
	 * @return bool
	 */
	private function securityCodeInputExhausted( $cardUpdateSession ) {
		if ( isset( $cardUpdateSession ) && isset( $cardUpdateSession->securityCodeInput ) ) {
			return $cardUpdateSession->securityCodeInput >= self::SECURITY_CODE_INPUT_LIMIT;
		}

		return true;
	}

	/**
	 * @param $cardUpdateSession
	 */
	private function incrementSecurityCodeInput( $cardUpdateSession ) {
		$this->db->incrementSecurityCodeInput( $cardUpdateSession->id );
	}

	/**
	 * @param $cardUpdateSession
	 * @param $securityCode
	 *
	 * @return array
	 */
	public function validateSecurityCode( $cardUpdateSession, $securityCode ) {
		$valid                = false;
		$matchingSecurityCode = null;
		if ( isset( $cardUpdateSession ) && isset( $securityCode ) ) {
			$sanitizedSecurityCode = sanitize_text_field( $securityCode );
			$matchingSecurityCode  = $this->db->find_security_code_by_session_and_code( $cardUpdateSession->id, $sanitizedSecurityCode );
			if ( ! is_null( $matchingSecurityCode ) && $matchingSecurityCode->status !== self::SECURITY_CODE_STATUS_CONSUMED ) {
				$valid = true;
			}
		}

		if ( MM_WPFS_Utils::isDemoMode() ) {
			$valid = true;
		}

		return array( 'valid' => $valid, 'securityCode' => $matchingSecurityCode );
	}

	/**
	 * @param $customerPortalSession
	 * @param $matchingSecurityCode
	 */
	private function confirmCustomerPortalSessionWithSecurityCode($customerPortalSession, $matchingSecurityCode ) {
		$this->db->updateCustomerPortalSession( $customerPortalSession->id, array( 'status' => self::SESSION_STATUS_WAITING_FOR_ACCOUNT_SELECTION ) );
		$this->db->updateSecurityCode( $matchingSecurityCode->id, array(
			'consumed' => current_time( 'mysql' ),
			'status'   => self::SECURITY_CODE_STATUS_CONSUMED
		) );
	}

	/**
	 * @return null|string
	 */
	private function findSessionHashByRequest() {
		return isset( $_REQUEST[ self::PARAM_CARD_UPDATE_SESSION ] ) ? sanitize_text_field( $_REQUEST[ self::PARAM_CARD_UPDATE_SESSION ] ) : null;
	}

	public function renderEmailForm( $attributes ) {

		ob_start();
		/** @noinspection PhpIncludeInspection */
		include MM_WPFS_Assets::templates( self::ASSET_DIR_CUSTOMER_PORTAL . DIRECTORY_SEPARATOR . self::ASSET_ENTER_EMAIL_ADDRESS_PHP );
		$content = ob_get_clean();

		return $content;

	}

	public function renderSecurityCodeForm( $attributes ) {
		ob_start();
		/** @noinspection PhpIncludeInspection */
		include MM_WPFS_Assets::templates( self::ASSET_DIR_CUSTOMER_PORTAL . DIRECTORY_SEPARATOR . self::ASSET_ENTER_SECURITY_CODE_PHP );
		$content = ob_get_clean();

		return $content;
	}

    public function renderSelectAccountForm( $attributes, $model ) {
        ob_start();
        /** @noinspection PhpIncludeInspection */
        include MM_WPFS_Assets::templates( self::ASSET_DIR_CUSTOMER_PORTAL . DIRECTORY_SEPARATOR . self::ASSET_SELECT_ACCOUNT_PHP );
        $content = ob_get_clean();

        return $content;
    }

    public function renderInvalidSession($attributes ) {
		ob_start();
		/** @noinspection PhpIncludeInspection */
		include MM_WPFS_Assets::templates( self::ASSET_DIR_CUSTOMER_PORTAL . DIRECTORY_SEPARATOR . self::ASSET_INVALID_SESSION_PHP );
		$content = ob_get_clean();

		return $content;
	}

	public function handleSessionRequest() {

		$return = array();

		try {

			$stripeCustomerEmail     = isset( $_POST[ self::PARAM_EMAIL_ADDRESS ] ) ? sanitize_email( $_POST[ self::PARAM_EMAIL_ADDRESS ] ) : null;
			$googleReCAPTCHAResponse = isset( $_POST[ self::PARAM_GOOGLE_RE_CAPTCHA_RESPONSE ] ) ? sanitize_text_field( $_POST[ self::PARAM_GOOGLE_RE_CAPTCHA_RESPONSE ] ) : null;

			$validRequest = true;
			if ( is_null( $stripeCustomerEmail ) || ! filter_var( $stripeCustomerEmail, FILTER_VALIDATE_EMAIL ) ) {
				$return['success']    = false;
				$return['message']    = __( 'The entered email address is invalid.', 'wp-full-stripe' );
				$return['fieldError'] = self::PARAM_EMAIL_ADDRESS;
				$validRequest         = false;
			}
			$verifyReCAPTCHA = MM_WPFS_ReCaptcha::getSecureCustomerPortal();
			if ( $verifyReCAPTCHA && $validRequest ) {
				if ( is_null( $googleReCAPTCHAResponse ) ) {
					$return['success']    = false;
					$return['message']    =
						/* translators: Captcha validation error message displayed when the form is submitted without completing the captcha challenge */
						__( 'Please prove that you are not a robot. ', 'wp-full-stripe' );
					$return['fieldError'] = self::PARAM_GOOGLE_RE_CAPTCHA_RESPONSE;
					$validRequest         = false;
				} else {
					$googleReCAPTCHVerificationResult = MM_WPFS_ReCaptcha::verifyReCAPTCHA($googleReCAPTCHAResponse);
					// MM_WPFS_Utils::log( 'googleReCAPTCHVerificationResult=' . print_r( $googleReCAPTCHVerificationResult, true ) );
					if ( $googleReCAPTCHVerificationResult === false ) {
						$return['success']    = false;
						$return['message']    =
							/* translators: Captcha validation error message displayed when the form is submitted without completing the captcha challenge */
							__( 'Please prove that you are not a robot. ', 'wp-full-stripe' );
						$return['fieldError'] = self::PARAM_GOOGLE_RE_CAPTCHA_RESPONSE;
						$validRequest         = false;
					} elseif ( ! isset( $googleReCAPTCHVerificationResult->success ) || $googleReCAPTCHVerificationResult->success === false ) {
						$return['success']    = false;
						$return['message']    =
							/* translators: Captcha validation error message displayed when the form is submitted without completing the captcha challenge */
							__( 'Please prove that you are not a robot. ', 'wp-full-stripe' );
						$return['fieldError'] = self::PARAM_GOOGLE_RE_CAPTCHA_RESPONSE;
						if ( $this->debugLog ) {
							MM_WPFS_Utils::log( 'handleCardUpdateSessionRequest(): reCAPTCHA error response=' . print_r( $googleReCAPTCHVerificationResult, true ) );
						}
						$validRequest = false;
					}
				}
			}

			$stripeCustomer = null;
			if ( $validRequest ) {
				$stripeCustomer = MM_WPFS_Utils::findExistingStripeCustomerAnywhereByEmail(
					$this->db,
					$this->stripe,
					$stripeCustomerEmail
				);
				if ( is_null( $stripeCustomer ) ) {
					$return['success']    = false;
					$return['message']    = __( 'The entered email address is invalid.', 'wp-full-stripe' );
					$return['fieldError'] = self::PARAM_EMAIL_ADDRESS;
					$validRequest         = false;
				}
			}

			if ( $validRequest ) {

				$session = $this->findValidSessionByEmailAndCustomer( $stripeCustomerEmail, $stripeCustomer->id );

				if ( ! is_null( $session ) ) {
					$sessionCookieValue = $this->findSessionCookieValue();
					if ( $session->hash !== $sessionCookieValue ) {
						$this->invalidateSession( $session );
						$session = null;
					}
				}

				if ( is_null( $session ) || $this->isInvalidatedSession( $session ) ) {
					$options           = get_option( 'fullstripe_options' );
					$liveMode          = $options['apiMode'] === 'live';
					$session = $this->createSession( $stripeCustomerEmail, $liveMode, $stripeCustomer->id );
				}

				$this->createSessionCookie( $session );

				$this->createAndSendSecurityCodeAsEmail( $session, $stripeCustomer );

				$return['success'] = true;
			}

		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return['success']    = false;
			$return['ex_code']    = $e->getCode();
			$return['ex_message'] = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;

	}

	private function findValidSessionByEmailAndCustomer($stripeCustomerEmail, $stripeCustomerId ) {
		$sessions = $this->db->findCustomerPortalSessionsByEmailAndCustomer( $stripeCustomerEmail, $stripeCustomerId );

		$validSession = null;
		if ( isset( $sessions ) ) {
			foreach ( $sessions as $session ) {
				if ( is_null( $validSession ) && ! $this->isInvalidatedSession( $session ) ) {
					$validSession = $session;
				}
			}
		}

		return $validSession;
	}

	/**
	 * @param $session
	 */
	private function createSessionCookie($session ) {
		if ( isset( $session ) ) {
			setcookie( self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID, $session->hash, time() + HOUR_IN_SECONDS, "/", COOKIE_DOMAIN );
		}
	}

    public static function generateSecurityCode() {
        return wp_generate_password( 8, false );
    }

	private function createAndSendSecurityCodeAsEmail( $cardUpdateSession, $stripeCustomer ) {
		try {
			if ( isset( $cardUpdateSession ) && isset( $cardUpdateSession->status ) ) {
				if ( self::SESSION_STATUS_WAITING_FOR_CONFIRMATION === $cardUpdateSession->status ) {
					if ( ! $this->securityCodeRequestExhausted( $cardUpdateSession ) ) {
						$securityCode   = self::generateSecurityCode();
						$securityCodeId = $this->db->insert_security_code( $cardUpdateSession->id, $securityCode );
						if ( $securityCodeId !== - 1 ) {
							$this->incrementSecurityCodeRequest( $cardUpdateSession );
							if ( ! MM_WPFS_Utils::isDemoMode() ) {
							    $transactionData = MM_WPFS_TransactionDataService::createMyAccountLoginData( $stripeCustomer, $cardUpdateSession->hash, $securityCode );
							    $this->mailer->sendMyAccountLoginRequest( $transactionData );
							}
							$this->db->updateSecurityCode( $securityCodeId, array(
								'sent'   => current_time( 'mysql' ),
								'status' => self::SECURITY_CODE_STATUS_SENT
							) );
						}
					} else {
						$this->invalidateSession( $cardUpdateSession );
					}
				}
			}
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
		}
	}

	/**
	 * @param $cardUpdateSession
	 *
	 * @return bool
	 */
	private function securityCodeRequestExhausted( $cardUpdateSession ) {
		if ( isset( $cardUpdateSession ) && isset( $cardUpdateSession->securityCodeRequest ) ) {

			return $cardUpdateSession->securityCodeRequest >= self::SECURITY_CODE_REQUEST_LIMIT;
		}

		return true;
	}

	private function incrementSecurityCodeRequest( $cardUpdateSession ) {
		$this->db->increment_security_code_request( $cardUpdateSession->id );
	}

	public function handleResetSessionRequest() {
		$return = array();

		try {

			$cardUpdateSessionHash = $this->findSessionCookieValue();
			if ( ! is_null( $cardUpdateSessionHash ) ) {
				$cardUpdateSession = $this->findCustomerPortalSessionByHash( $cardUpdateSessionHash );
				if ( ! is_null( $cardUpdateSession ) ) {
					if ( $this->isWaitingForAccountSelection( $cardUpdateSession ) || $this->isConfirmed( $cardUpdateSession ) || $this->isWaitingForConfirmation( $cardUpdateSession ) ) {
						$this->invalidateSession( $cardUpdateSession );
					}
				}
			}

			$return['success'] = true;
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return['success']    = false;
			$return['ex_code']    = $e->getCode();
			$return['ex_message'] = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;

	}

	public function handleSecurityCodeValidationRequest() {
		$return = array();

		try {

			$cardUpdateSessionHash = $this->findSessionCookieValue();
			$securityCode          = isset( $_POST['securityCode'] ) ? sanitize_text_field( $_POST['securityCode'] ) : null;
			if ( is_null( $securityCode ) || empty( $securityCode ) ) {
				$return['success'] = false;
				$return['message'] =
					/* translators: Login form validation error when no security code is entered */
					__( 'Enter a security code', 'wp-full-stripe' );
			} else {
				$success = false;
				if ( ! is_null( $cardUpdateSessionHash ) ) {
					$cardUpdateSession = $this->findCustomerPortalSessionByHash( $cardUpdateSessionHash );
					if ( ! is_null( $cardUpdateSession ) && $this->isWaitingForConfirmation( $cardUpdateSession ) && ! $this->securityCodeInputExhausted( $cardUpdateSession ) ) {
						$this->incrementSecurityCodeInput( $cardUpdateSession );
						$validationResult     = $this->validateSecurityCode( $cardUpdateSession, $securityCode );
						$valid                = $validationResult['valid'];
						$matchingSecurityCode = $validationResult['securityCode'];
						if ( $valid ) {
							$this->confirmCustomerPortalSessionWithSecurityCode( $cardUpdateSession, $matchingSecurityCode );
							$success = true;
						} else {
							$this->deleteCardUpdateSessionCookie();
						}
					}
				}

				if ( $success ) {
					$return['success'] = true;
				} else {
					$return['success'] = false;
					$return['message'] =
						/* translators: Login form validation error when an invalid security code is entered */
						__( 'Enter a valid security code', 'wp-full-stripe' );
				}
			}
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return['success']    = false;
			$return['ex_code']    = $e->getCode();
			$return['ex_message'] = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

    public function handleSelectAccountRequest() {
        $return = array();

        try {
            $stripeCustomerId = isset( $_POST['customerId'] ) ? sanitize_text_field( $_POST['customerId'] ) : null;

            if ( empty( $stripeCustomerId ) ) {
                $return['success'] = false;
                $return['message'] =
                    /* translators: Customer portal error when no Stripe customer was selected */
                    __( 'Select an account', 'wp-full-stripe' );
            } else {
                $sessionHash = $this->findSessionCookieValue();
                $success = false;

                if ( ! is_null( $sessionHash ) ) {
                    $session = $this->findCustomerPortalSessionByHash( $sessionHash );
                    if ( ! is_null( $session ) && $this->isWaitingForAccountSelection( $session ) ) {
                        $stripeCustomers = $this->getActiveStripeCustomersByEmail( $session->email );

                        $foundCustomer = false;
                        foreach( $stripeCustomers as $stripeCustomer ) {
                            if ( $stripeCustomer->id === $stripeCustomerId ) {
                                $foundCustomer = true;
                                break;
                            }
                        }

                        if ( $foundCustomer ) {
                            $session->stripeCustomerId = $stripeCustomerId;
                            $this->db->updateCustomerPortalSession( $session->id, array( 'stripeCustomerId' => $session->stripeCustomerId ) );

                            $this->confirmSession( $session );

                            $success = true;
                        }
                    }
                }

                if ( $success ) {
                    $return['success'] = true;
                } else {
                    $return['success'] = false;
                    $return['message'] =
                        /* translators: Login form validation error when an invalid security code is entered */
                        __( 'Select an account', 'wp-full-stripe' );
                }
            }
        } catch ( Exception $e ) {
            MM_WPFS_Utils::logException( $e, $this );
            $return['success']    = false;
            $return['ex_code']    = $e->getCode();
            $return['ex_message'] = $e->getMessage();
        }

        header( "Content-Type: application/json" );
        echo json_encode( $return );
        exit;
    }

    public function handleShowAccountSelectorRequest() {
        $return = array();

        try {
            $success = false;

            $sessionHash = $this->findSessionCookieValue();
            if ( ! is_null( $sessionHash ) ) {
                $session = $this->findCustomerPortalSessionByHash( $sessionHash );
                if ( ! is_null( $session ) && $this->isConfirmed( $session ) ) {
                    $this->makeSessionWaitingForAccountSelection( $session );
                }
            }

            if ( $success ) {
                $return['success'] = true;
            } else {
                $return['success'] = false;
                $return['message'] =
                    /* translators: Cannot transition the Customer portal to selecting an account because it's in a wrong state  */
                    __( 'Cannot transition to selecting an account from the current state', 'wp-full-stripe' );
            }
        } catch ( Exception $e ) {
            MM_WPFS_Utils::logException( $e, $this );
            $return['success']    = false;
            $return['ex_code']    = $e->getCode();
            $return['ex_message'] = $e->getMessage();
        }

        header( "Content-Type: application/json" );
        echo json_encode( $return );
        exit;
    }


    private function deleteCardUpdateSessionCookie() {
		unset( $_COOKIE[ self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID ] );
		setcookie( self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID, '', time() - DAY_IN_SECONDS );
	}

	public function handleCardUpdateRequest() {
		$return = array();
		try {
			$stripePaymentMethodId = isset( $_POST['paymentMethodId'] ) ? sanitize_text_field( $_POST['paymentMethodId'] ) : null;
			$stripeSetupIntentId   = isset( $_POST['setupIntentId'] ) ? sanitize_text_field( $_POST['setupIntentId'] ) : null;
			if ( $this->debugLog ) {
				MM_WPFS_Utils::log( 'handleCardUpdateRequest(): stripePaymentMethodId=' . print_r( $stripePaymentMethodId, true ) );
			}
			$cardUpdateSessionHash = $this->findSessionCookieValue();
			if ( ! is_null( $stripePaymentMethodId ) && ! is_null( $cardUpdateSessionHash ) ) {
				$cardUpdateSession = $this->findCustomerPortalSessionByHash( $cardUpdateSessionHash );
				if ( ! is_null( $cardUpdateSession ) && $this->isConfirmed( $cardUpdateSession ) ) {
					$stripeCustomer = $this->stripe->retrieveCustomer( $cardUpdateSession->stripeCustomerId );
					if ( isset( $stripeCustomer ) ) {
						$stripePaymentMethod = $this->stripe->validatePaymentMethodCVCCheck( $stripePaymentMethodId );
						if ( isset( $stripePaymentMethod ) ) {
							$stripeSetupIntent = null;
							if ( is_null( $stripeSetupIntentId ) ) {
								$stripeSetupIntent   = $this->stripe->createSetupIntentWithPaymentMethod( $stripePaymentMethod->id );
								$stripeSetupIntentId = $stripeSetupIntent->id;
								$stripeSetupIntent->confirm();
							}
							$stripeSetupIntent = $this->stripe->retrieveSetupIntent( $stripeSetupIntentId );
							if ( $stripeSetupIntent instanceof \StripeWPFS\SetupIntent ) {
								$return['setupIntentId'] = $stripeSetupIntent->id;
								if (
									\StripeWPFS\SetupIntent::STATUS_REQUIRES_ACTION === $stripeSetupIntent->status
									&& 'use_stripe_sdk' === $stripeSetupIntent->next_action->type
								) {
									if ( $this->debugLog ) {
										MM_WPFS_Utils::log( __FUNCTION__ . '(): SetupIntent requires action...' );
									}
									$return['success']                 = false;
									$return['requiresAction']          = true;
									$return['setupIntentClientSecret'] = $stripeSetupIntent->client_secret;
									$return['message']                 =
										/* translators: Banner message of a pending card saving transaction requiring a second factor authentication (SCA/PSD2) */
										__( 'Saving this card requires additional action before completion!', 'wp-full-stripe' );
								} elseif ( \StripeWPFS\SetupIntent::STATUS_SUCCEEDED === $stripeSetupIntent->status ) {
									if ( $this->debugLog ) {
										MM_WPFS_Utils::log( __FUNCTION__ . '(): SetupIntent succeeded.' );
									}
									$this->stripe->attachPaymentMethodToCustomerIfMissing( $stripeCustomer, $stripePaymentMethod, true );
									$return['success'] = true;
									$return['message'] = __( 'The default credit card has been updated successfully!', 'wp-full-stripe' );
								} else {
									// This is an internal error, no need to localize it
									$errorMessage         = sprintf( "Unknown SetupIntent status '%s'!", $stripeSetupIntent->status );
									$return['success']    = false;
									$return['ex_message'] = $errorMessage;
									MM_WPFS_Utils::log( 'handleCardUpdateRequest(): ERROR: ' . $errorMessage );
								}
							} else {
								// This is an internal error, no need to localize it
								$errorMessage         = 'Invalid SetupIntent!';
								$return['success']    = false;
								$return['ex_message'] = $errorMessage;
								MM_WPFS_Utils::log( 'handleCardUpdateRequest(): ERROR: ' . $errorMessage );
							}
						} else {
							// This is an internal error, no need to localize it
							$errorMessage         = 'Stripe PaymentMethod not found!';
							$return['success']    = false;
							$return['ex_message'] = $errorMessage;
							MM_WPFS_Utils::log( 'handleCardUpdateRequest(): ERROR: ' . $errorMessage );
						}
					} else {
						// This is an internal error, no need to localize it
						$errorMessage         = 'Stripe Customer not found!';
						$return['success']    = false;
						$return['ex_message'] = $errorMessage;
						MM_WPFS_Utils::log( 'handleCardUpdateRequest(): ERROR: ' . $errorMessage );
					}
				} else {
					// This is an internal error, no need to localize it
					$errorMessage         = 'Invalid Card Update Session!';
					$return['success']    = false;
					$return['ex_message'] = $errorMessage;
					MM_WPFS_Utils::log( 'handleCardUpdateRequest(): ERROR: ' . $errorMessage );
				}
			} else {
				// This is an internal error, no need to localize it
				$errorMessage         = 'Invalid POST parameters!';
				$return['success']    = false;
				$return['ex_message'] = $errorMessage;
				MM_WPFS_Utils::log( 'handleCardUpdateRequest(): ERROR: ' . $errorMessage );
			}
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return['success']    = false;
			$return['ex_code']    = $e->getCode();
			$return['ex_message'] = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;

	}

	public function toggleInvoiceView() {
		$return = array();
		try {
			$cardUpdateSessionHash = $this->findSessionCookieValue();
			if ( ! is_null( $cardUpdateSessionHash ) ) {
				$cardUpdateSession = $this->findCustomerPortalSessionByHash( $cardUpdateSessionHash );
				if ( ! is_null( $cardUpdateSession ) && $this->isConfirmed( $cardUpdateSession ) ) {
					$options = get_option( 'fullstripe_options' );

					$currentView = $options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_ALL_INVOICES ];
					$currentView ^= 1;
					$options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_ALL_INVOICES ] = $currentView;
					update_option( 'fullstripe_options', $options );

					$return['success'] = true;
				} else {
					$return['success'] = false;
					$return['message'] = 'Account session is not confirmed.';
				}
			} else {
				$return['success'] = false;
				$return['message'] = 'No valid account session found.';
			}
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return['success']    = false;
			$return['message']    = 'Invoice view toggle failed.';
			$return['ex_code']    = $e->getCode();
			$return['ex_message'] = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}


    /**
     * @param $subscriptionId string
     *
     * @throws Exception
     */
    private function cancelSubscriptionInDatabase( $subscriptionId ) {
        $subscriptionParams = array(
            'expand' => array(
                'items.data.price'
            )
        );
        $subscription = $this->stripe->retrieveSubscriptionWithParams( $subscriptionId, $subscriptionParams );

        if ( self::isDonationPlan($subscription) ) {
            $this->db->cancelDonationByStripeSubscriptionId( $subscriptionId );
        } else {
            $this->db->cancelSubscriptionByStripeSubscriptionId( $subscriptionId );
        }
    }

    /**
     * @param $subscriptionId string
     *
     * @throws Exception
     */
    private function activateSubscriptionInDatabase( $subscriptionId ) {
        $this->db->updateSubscriptionToRunning( $subscriptionId );
    }

    public function handleSubscriptionCancellationRequest() {
		$return  = array();

        $cancelAtPeriodEnd = MM_WPFS_Utils::getCancelSubscriptionsAtPeriodEnd();

		try {
			$subscriptionIdsToCancel = isset( $_POST[ self::PARAM_WPFS_SUBSCRIPTION_ID ] ) ? $_POST[ self::PARAM_WPFS_SUBSCRIPTION_ID ] : null;
			if ( isset( $subscriptionIdsToCancel ) && count( $subscriptionIdsToCancel ) > 0 ) {
				$cardUpdateSessionHash = $this->findSessionCookieValue();
				if ( ! is_null( $subscriptionIdsToCancel ) && ! is_null( $cardUpdateSessionHash ) ) {
					$cardUpdateSession = $this->findCustomerPortalSessionByHash( $cardUpdateSessionHash );
					if ( ! is_null( $cardUpdateSession ) && $this->isConfirmed( $cardUpdateSession ) ) {
						$stripeCustomer = $this->stripe->retrieveCustomer( $cardUpdateSession->stripeCustomerId );
						if ( isset( $stripeCustomer ) ) {
							foreach ( $subscriptionIdsToCancel as $subscriptionId ) {
                                $this->cancelSubscriptionInDatabase( $subscriptionId );
								$this->stripe->cancelSubscription( $stripeCustomer->id, $subscriptionId, $cancelAtPeriodEnd );
							}
						}
					}
				}
				$return['success'] = true;
				$return['message'] = __( 'The subscriptions have been cancelled', 'wp-full-stripe' );
			} else {
				$return['success'] = false;
				$return['message'] = __( 'Select at least one subscription!', 'wp-full-stripe' );
			}

		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return['success']    = false;
			$return['ex_code']    = $e->getCode();
			$return['ex_message'] = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	/**
	 * Fetch Stripe Subscriptions for a given customer to supply data for a Backbone Collection
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function handleSubscriptionsFetchRequest( WP_REST_Request $request ) {
		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'handleSubscriptionsFetchRequest(): CALLED, json_params=' . print_r( $request->get_json_params(), true ) );
		}

		$data = array();
		try {

			$cardUpdateSessionHash = $this->findSessionCookieValue();
			if ( ! is_null( $cardUpdateSessionHash ) ) {
				$cardUpdateSession = $this->findCustomerPortalSessionByHash( $cardUpdateSessionHash );
				if ( ! is_null( $cardUpdateSession ) ) {
					$model = new MM_WPFS_CustomerPortalModel();
					if ( is_user_logged_in() ) {
						$model->setAuthenticationType( MM_WPFS_CustomerPortalModel::AUTHENTICATION_TYPE_WORDPRESS );
					} else {
						$model->setAuthenticationType( MM_WPFS_CustomerPortalModel::AUTHENTICATION_TYPE_PLUGIN );
					}
					$stripeCustomer = MM_WPFS_Utils::findExistingStripeCustomerAnywhereByEmail(
						$this->db,
						$this->stripe,
						$cardUpdateSession->email
					);
					$this->fetchDataIntoCustomerPortalModel( $model, $stripeCustomer );

                    $this->buildManagedSubscriptionsArray( $model->getSubscriptions(), $data );
				}
			}

		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );

			return new WP_Error( $e->getCode(), $e->getMessage() );
		}

		return new WP_REST_Response( $data, 200 );

	}

    /**
	 * Update subscription
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function handleSubscriptionUpdateRequest( WP_REST_Request $request ) {
		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'handleSubscriptionUpdateRequest(): CALLED, json_params=' . print_r( $request->get_json_params(), true ) );
		}
		$data = array();

		$cancelAtPeriodEnd = MM_WPFS_Utils::getCancelSubscriptionsAtPeriodEnd();

        try {
			$updatedSubscription = $request->get_json_params();
			if ( is_array( $updatedSubscription ) ) {
				if ( array_key_exists( 'id', $updatedSubscription ) && array_key_exists( 'action', $updatedSubscription ) ) {
					$stripeSubscriptionId = sanitize_text_field( $updatedSubscription['id'] );
					if ( 'cancel' === $updatedSubscription['action'] ) {
						$cardUpdateSessionHash = $this->findSessionCookieValue();
						if ( ! is_null( $stripeSubscriptionId ) && ! is_null( $cardUpdateSessionHash ) ) {
							$cardUpdateSession = $this->findCustomerPortalSessionByHash( $cardUpdateSessionHash );
							if ( ! is_null( $cardUpdateSession ) && $this->isConfirmed( $cardUpdateSession ) ) {
								$stripeCustomer = $this->stripe->retrieveCustomer( $cardUpdateSession->stripeCustomerId );
								if ( isset( $stripeCustomer ) ) {
                                    $this->cancelSubscriptionInDatabase( $stripeSubscriptionId );
									$this->stripe->cancelSubscription( $stripeCustomer->id, $stripeSubscriptionId, $cancelAtPeriodEnd );
								}
							}
						}
					} elseif ( 'activate' === $updatedSubscription['action'] ) {
                        $cardUpdateSessionHash = $this->findSessionCookieValue();
                        if ( ! is_null( $stripeSubscriptionId ) && ! is_null( $cardUpdateSessionHash ) ) {
                            $cardUpdateSession = $this->findCustomerPortalSessionByHash( $cardUpdateSessionHash );
                            if ( ! is_null( $cardUpdateSession ) && $this->isConfirmed( $cardUpdateSession ) ) {
                                $stripeCustomer = $this->stripe->retrieveCustomer( $cardUpdateSession->stripeCustomerId );
                                if ( isset( $stripeCustomer ) ) {
                                    $this->activateSubscriptionInDatabase( $stripeSubscriptionId );
                                    $this->stripe->activateCancelledSubscription( $stripeSubscriptionId );
                                }
                            }
                        }
                    }
				} elseif ( array_key_exists( 'id', $updatedSubscription ) ) {
					$stripeSubscriptionId = sanitize_text_field( $updatedSubscription['id'] );
					$newPlanId            = sanitize_text_field( $updatedSubscription['newPlanId'] );
					if ( array_key_exists( 'planQuantity', $updatedSubscription ) ) {
						$newQuantity = sanitize_text_field( $updatedSubscription['planQuantity'] );
					} else {
						$newQuantity = 1;
					}
					if ( isset( $stripeSubscriptionId ) && isset( $newPlanId ) && is_numeric( $newQuantity ) && $newQuantity > 0 ) {
						$cardUpdateSessionHash = $this->findSessionCookieValue();
						if ( ! is_null( $stripeSubscriptionId ) && ! is_null( $cardUpdateSessionHash ) ) {
							$cardUpdateSession = $this->findCustomerPortalSessionByHash( $cardUpdateSessionHash );
							if ( ! is_null( $cardUpdateSession ) && $this->isConfirmed( $cardUpdateSession ) ) {
								$stripeCustomer = $this->stripe->retrieveCustomer( $cardUpdateSession->stripeCustomerId );
								if ( isset( $stripeCustomer ) ) {
									$success = $this->stripe->updateSubscriptionPlanAndQuantity( $stripeCustomer->id, $stripeSubscriptionId, $newPlanId, $newQuantity );
									if ( $success ) {
										$this->db->updateSubscriptionPlanAndQuantityByStripeSubscriptionId( $stripeSubscriptionId, $newPlanId, $newQuantity );
									}
								}
							}
						}
					}
				}
				$data['success'] = true;
				$data['message'] = __( 'The subscription has been updated successfully', 'wp-full-stripe' );
			}
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );

			return new WP_Error( $e->getCode(), $e->getMessage() );
		}

		return new WP_REST_Response( $data, 200 );

	}

	/**
	 * Adds Cache-Control HTTP header if a page is displayed with the "Manage Subscriptions" shortcode
	 *
	 * @param $theWPObject
	 */
	public function addCacheControlHeader( $theWPObject ) {
		$started = round( microtime( true ) * 1000 );
		if ( ! is_null( $theWPObject ) && isset( $theWPObject->request ) ) {
			$pageByPath = get_page_by_path( $theWPObject->request );
			if ( ! is_null( $pageByPath ) && isset( $pageByPath->post_content ) ) {
				if ( has_shortcode( $pageByPath->post_content, self::FULLSTRIPE_SHORTCODE_CUSTOMER_PORTAL ) ||
				     has_shortcode( $pageByPath->post_content, self::FULLSTRIPE_SHORTCODE_MANAGE_SUBSCRIPTIONS )
				) {
					header( 'Cache-Control: no-store, no-cache, must-revalidate' );
				}
			}
		}
		$finished = round( microtime( true ) * 1000 ) - $started;
		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'addCacheControlHeader(): finished in ' . $finished . 'ms' );
		}
	}

	public function addAsyncDeferAttributes( $tag, $handle ) {
		if ( MM_WPFS::HANDLE_GOOGLE_RECAPTCHA_V_2 !== $handle ) {
			return $tag;
		}

		return str_replace( ' src', ' async defer src', $tag );
	}

	/**
	 * Register WPFS Manage Subscriptions REST API routes
	 */
	public function registerRESTAPIRoutes() {
		register_rest_route( $this->getRESTAPINamespace(), $this->getBaseRoute(), array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'handleSubscriptionsFetchRequest' ),
				'args'     => array(),
                'permission_callback' => '__return_true'
			)
		) );
		register_rest_route( $this->getRESTAPINamespace(), $this->getItemRoute(), array(
			array(
				'methods'  => WP_REST_Server::EDITABLE,
				'callback' => array( $this, 'handleSubscriptionUpdateRequest' ),
				'args'     => array(),
                'permission_callback' => '__return_true'
			)
		) );
	}

	private function getRESTAPINamespace() {
		return self::WPFS_PLUGIN_SLUG . '/' . self::WPFS_REST_API_VERSION;
	}

	private function getBaseRoute() {
		return '/' . self::WPFS_REST_ROUTE_MANAGE_SUBSCRIPTIONS_SUBSCRIPTION;
	}

	private function getItemRoute() {
		return $this->getBaseRoute() . '/' . '(?P<id>[\w]+)';
	}


	private function getSubscriptionFormPlans( $formName ) {
	    $plans = array();

        $form = $this->db->getInlineSubscriptionFormByName( $formName );
        if ( is_null( $form ) ) {
            $form = $this->db->getCheckoutSubscriptionFormByName( $formName );
        }

        if ( isset( $form )) {
            try {
                $plans = json_decode( $form->decoratedPlans );
            } catch ( Exception $ex ) {
                MM_WPFS_Utils::log( __CLASS__ . "." . __FUNCTION__ . "() - Cannot decode form plans." );
            }
        } else {
            MM_WPFS_Utils::log( __CLASS__ . "." . __FUNCTION__ . "() - Form not found: '${formName}'.");
        }

        return $plans;
    }


    /**
     * @param $formPlanIds
     *
     * @return array
     */
    private function getOrderedPlansFromIds( $formPlanIds ) : array {
        $plans        = $this->stripe->getSubscriptionPlans();
        $orderedPlans = array();

        foreach ( $plans as $plan ) {
            $i = array_search( $plan->id, $formPlanIds );
            if ( $i !== false ) {
                $orderedPlans[ $i ] = $plan;
            }
        }
        ksort( $orderedPlans );

        return $orderedPlans;
    }

	/**
	 * @param $formName
     * @param $currentPlanId
	 *
	 * @return array
	 */
	private function findAvailablePlansByForm($formName, $currentPlanId ) {
        $formPlanIds = MM_WPFS_Pricing::extractPriceIdsFromProductsStatic(
            $this->getSubscriptionFormPlans( $formName )
        );

        $formPlanIds = apply_filters( MM_WPFS::FILTER_NAME_GET_UPGRADE_DOWNGRADE_PLANS, $formPlanIds, $formName );
        if ( is_null( $formPlanIds ) || !is_array( $formPlanIds )) {
            $formPlanIds = array();
        }

        if ( array_search( $currentPlanId, $formPlanIds ) === false ) {
            array_push( $formPlanIds, $currentPlanId );
        }

        $stripePlans = $this->getOrderedPlansFromIds( $formPlanIds );

        return $stripePlans;
	}

	/**
	 * @param \StripeWPFS\Price $plan
	 *
	 * @return string
	 * @throws Exception
	 */
	protected function getPriceAndIntervalLabelForPlan( \StripeWPFS\Price $plan ): string {
		$currency        = $plan->currency;
		$interval        = $plan->recurring->interval;
		$intervalCount   = $plan->recurring->interval_count;
		$amount          = $plan->unit_amount;
		$formattedAmount = MM_WPFS_Currencies::formatAndEscapeByMyAccount(
			$currency,
			$amount,
			true,
			true );

		return MM_WPFS_Localization::getPriceAndIntervalLabel( $interval, $intervalCount, $formattedAmount );
	}

	/**
	 * @param \StripeWPFS\Price $plan
	 *
	 * @return array
	 * @throws Exception
	 */
	protected function getSummaryLabelsForPlan(\StripeWPFS\Price $plan, $priceAndIntervalLabel ) {
		return array(
		    sprintf(
                /*
                 * translators:
                 * p1: plan/product name
                 * p2: plan/product formatted price and interval label
                 */
                __(
                    'Your subscription plan will be changed to %1$s. The new subscription fee will be %2$s.',
                    'wp-full-stripe'
                ),
                $plan->product->name,
                $priceAndIntervalLabel
		    ),
            sprintf(
                /*
                 * translators:
                 * p1: plan/product name
                 * p2: plan/product formatted price and interval label
                 */
                    __(
                        'Your subscription plan will be changed to %1$s. The new subscription fee will be @QUANTITY@x %2$s.',
                        'wp-full-stripe'
                    ),
                    $plan->product->name,
                    $priceAndIntervalLabel
            )
        );
	}

	/**
	 * @param array $availablePlans
	 *
	 * @return array
	 */
	private function getPriceAndIntervalLabelForPlans( array $availablePlans ) {
		$plansWithPriceAndIntervalLabels = array();
		foreach ( $availablePlans as $plan ) {
			$plan->priceAndIntervalLabel = $this->getPriceAndIntervalLabelForPlan( $plan );
			$summaryLabels = $this->getSummaryLabelsForPlan( $plan, $plan->priceAndIntervalLabel );
			$plan->summaryLabelSingular = $summaryLabels[0];
            $plan->summaryLabelPlural = $summaryLabels[1];

			array_push( $plansWithPriceAndIntervalLabels, $plan );
		}

		return $plansWithPriceAndIntervalLabels;
	}

}

class MM_WPFS_CustomerPortalAccount {
    protected $stripeCustomerId;
    protected $name;
    protected $createdAt;
    protected $createdAtLabel;
    protected $numberOfSubscriptions;

    /**
     * @return mixed
     */
    public function getStripeCustomerId() {
        return $this->stripeCustomerId;
    }

    /**
     * @param mixed $stripeCustomerId
     */
    public function setStripeCustomerId( $stripeCustomerId ) {
        $this->stripeCustomerId = $stripeCustomerId;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt( $createdAt ) {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getNumberOfSubscriptions() {
        return $this->numberOfSubscriptions;
    }

    /**
     * @param mixed $numberOfSubscriptions
     */
    public function setNumberOfSubscriptions( $numberOfSubscriptions ) {
        $this->numberOfSubscriptions = $numberOfSubscriptions;
    }

    /**
     * @return mixed
     */
    public function getCreatedAtLabel() {
        return $this->createdAtLabel;
    }

    /**
     * @param mixed $createdAtLabel
     */
    public function setCreatedAtLabel( $createdAtLabel ) {
        $this->createdAtLabel = $createdAtLabel;
    }
}

class MM_WPFS_CustomerPortalModel {

	const AUTHENTICATION_TYPE_PLUGIN = 'Plugin';
	const AUTHENTICATION_TYPE_WORDPRESS = 'Wordpress';

	/**
	 * @var \StripeWPFS\Customer
	 */
	private $stripeCustomer;
	/**
	 * @var \StripeWPFS\Card
	 */
	private $defaultSource;
	/**
	 * @var \StripeWPFS\PaymentMethod
	 */
	private $defaultPaymentMethod;
	/**
	 * @var string
	 */
	private $cardImageUrl;
	/**
	 * @var string
	 */
	private $cardName;
	/**
	 * @var string
	 */
	private $cardNumber;
	/**
	 * @var array
	 */
	private $subscriptions = array();
	/**
	 * @var array
	 */
	private $products = array();
	/**
	 * @var string
	 */
	private $authenticationType;
	/**
	 * @var array
	 */
	private $invoices = array();
    /**
     * @var array
     */
    private $accounts = array();
    /**
     * @var bool
     */
    private $accountSelectorNeeded = array();
    /**
     * @var string
     */
    private $accountEmail;

    /**
     * @return bool
     */
    public function isAccountSelectorNeeded() {
        return $this->accountSelectorNeeded;
    }

    /**
     * @param bool $accountSelectorNeeded
     */
    public function setAccountSelectorNeeded( $accountSelectorNeeded ) {
        $this->accountSelectorNeeded = $accountSelectorNeeded;
    }

    /**
     * @return array
     */
    public function getAccounts(): array {
        return $this->accounts;
    }
    /**
     * @param array $accounts
     */
    public function setAccounts( array $accounts ) {
        $this->accounts = $accounts;
    }

	/**
	 * @return array
	 */
	public function getInvoices() {
		return $this->invoices;
	}

	/**
	 * @param array $invoices
	 */
	public function setInvoices( $invoices ) {
		$this->invoices = $invoices;
	}

	/**
	 * @return string
	 */
	public function getAuthenticationType() {
		return $this->authenticationType;
	}

	/**
	 * @param string $authenticationType
	 */
	public function setAuthenticationType( $authenticationType ) {
		$this->authenticationType = $authenticationType;
	}

	/**
	 * @return \StripeWPFS\Customer
	 */
	public function getStripeCustomer() {
		return $this->stripeCustomer;
	}

	/**
	 * @param \StripeWPFS\Customer $stripeCustomer
	 */
	public function setStripeCustomer( $stripeCustomer ) {
		$this->stripeCustomer = $stripeCustomer;
	}

	/**
	 * @return \StripeWPFS\Card
	 */
	public function getDefaultSource() {
		return $this->defaultSource;
	}

	/**
	 * @param \StripeWPFS\Card $defaultSource
	 */
	public function setDefaultSource( $defaultSource ) {
		$this->defaultSource = $defaultSource;
	}

	/**
	 * @return \StripeWPFS\PaymentMethod
	 */
	public function getDefaultPaymentMethod() {
		return $this->defaultPaymentMethod;
	}

	/**
	 * @param \StripeWPFS\PaymentMethod $defaultPaymentMethod
	 */
	public function setDefaultPaymentMethod( $defaultPaymentMethod ) {
		$this->defaultPaymentMethod = $defaultPaymentMethod;
	}

	/**
	 * @return string
	 */
	public function getCardImageUrl() {
		return $this->cardImageUrl;
	}

	/**
	 * @param string $cardImageUrl
	 */
	public function setCardImageUrl( $cardImageUrl ) {
		$this->cardImageUrl = $cardImageUrl;
	}

	/**
	 * @return string
	 */
	public function getCardName() {
		return $this->cardName;
	}

	/**
	 * @param string $cardName
	 */
	public function setCardName( $cardName ) {
		$this->cardName = $cardName;
	}

	/**
	 * @return string
	 */
	public function getCardNumber() {
		return $this->cardNumber;
	}

	/**
	 * @param string $cardNumber
	 */
	public function setCardNumber( $cardNumber ) {
		$this->cardNumber = $cardNumber;
	}

	/**
	 * @return string
	 */
	public function getFormattedCardNumber() {
		return sprintf( 'x-%s', $this->cardNumber );
	}

	public function getExpiration() {
		if ( isset( $this->defaultPaymentMethod ) ) {
			return sprintf( '%02d / %d', $this->defaultPaymentMethod->card->exp_month, $this->defaultPaymentMethod->card->exp_year );
		} elseif ( isset( $this->defaultSource ) ) {
			return sprintf( '%02d / %d', $this->defaultSource->exp_month, $this->defaultSource->exp_year );
		}

		return '';
	}

	/**
	 * @return array
	 */
	public function getSubscriptions() {
		return $this->subscriptions;
	}

	/**
	 * @param array $subscriptions
	 */
	public function setSubscriptions( $subscriptions ) {
		$this->subscriptions = $subscriptions;
	}

	/**
	 * @return array
	 */
	public function getProducts() {
		return $this->products;
	}

	/**
	 * @param array $products
	 */
	public function setProducts( $products ) {
		$this->products = $products;
	}

	/**
	 * @return null|string
	 */
	public function getCustomerEmail() {
		if ( isset( $this->stripeCustomer ) ) {
			return $this->stripeCustomer->email;
		}

		return null;
	}

    /**
     * @return string
     */
    public function getAccountEmail(): string {
        return $this->accountEmail;
    }

    /**
     * @param string $accountEmail
     */
    public function setAccountEmail(string $accountEmail) {
        $this->accountEmail = $accountEmail;
    }
}

class MM_WPFS_ManagedInvoiceEntry {
	/**
	 * @var \StripeWPFS\Invoice
	 */
	private $invoice;

	/**
	 * MM_WPFS_ManagedInvoiceEntry constructor.
	 *
	 * @param \StripeWPFS\Invoice $invoice
	 */
	public function __construct( $invoice ) {
		$this->invoice = $invoice;
	}

	/**
	 * Convert this object to a standard class instance for Backbone.js
	 */
	public function toModel() {
		$model                     = new stdClass();
		$model->id                 = $this->getValue();
		$model->priceLabel         = $this->getPriceLabel();
		$model->created            = $this->getCreated();
		$model->invoiceNumber      = $this->getInvoiceNumber();
		$model->invoiceUrl         = $this->geInvoiceUrl();
        $model->lineItemsLabel     = $this->getLineItemsLabel();

		return $model;
	}

	public function getLineItemsLabel() {
	    $result = '';

	    $first = true;
	    foreach ( $this->invoice->linesExpanded->data as $lineItem ) {
            if ( !$first ) {
                $result .= '• ';
            }
	        if ( $lineItem->quantity > 1 ) {
	            $result .= $lineItem->quantity . 'x ';
            }
            $result .= $lineItem->price->product->name;
	        $result .= ' ';

	        $first = false;
        }

	    return $result;
    }

	public function getValue() {
		return $this->invoice->id;
	}

	public function getPriceLabel() {
        $formattedAmount = MM_WPFS_Currencies::formatAndEscapeByMyAccount(
            $this->invoice->currency,
            $this->invoice->total,
            true,
            true );

        return $formattedAmount;
	}

	public function getCreated() {
        return MM_WPFS_Utils::formatTimestampWithWordpressDateFormat( $this->invoice->created );
	}

	public function getInvoiceNumber() {
		$invoiceNumber = $this->invoice->number;

		return $invoiceNumber;
	}

	public function geInvoiceUrl() {
		$invoiceUrl = $this->invoice->invoice_pdf;

		return $invoiceUrl;
	}

}

class MM_WPFS_ManagedSubscriptionEntry {

	const PARAM_WPFS_SUBSCRIPTION_ID = 'wpfs-subscription-id[]';

	/**
	 * @var \StripeWPFS\Subscription
	 */
	private $subscription;
	private $donationAmount;

	/**
	 * MM_WPFS_ManagedSubscriptionEntry constructor.
	 *
	 * @param \StripeWPFS\Subscription $subscription
	 */
	public function __construct($subscription, $recurringDonationAmount = 0 ) {
		$this->subscription = $subscription;
		$this->donationAmount = $recurringDonationAmount;
	}

	public function isDonationEntry() {
        return $this->donationAmount > 0;
    }

	/**
	 * Convert this object to a standard class instance for Backbone.js
	 *
	 * @param array $availablePlans
	 *
	 * @return stdClass
	 * @throws Exception
	 */
	public function toModel( $availablePlans = array() ) {
		$model                = new stdClass();
		$model->id            = $this->getValue();
		$model->idAttribute   = $this->getId();
		$model->nameAttribute = $this->getName();
		$model->status        = $this->getStatus();
		$model->statusClass   = $this->getClass();
		$model->created       = $this->getCreated();
		if ( count( $this->subscription->items->data ) == 1 ) {
			$model->planId                     = $this->getPlanId();
			$model->planName                   = $this->getPlanName();
			$model->planQuantity               = $this->getPlanQuantity();
			$model->allowMultipleSubscriptions = $this->isAllowMultipleSubscriptions();
			$model->maximumPlanQuantity        = $this->getMaximumPlanQuantity();
			$model->planLabel                  = $this->getPlanLabel();
			$model->priceAndIntervalLabel      = $this->getPriceAndIntervalLabel();
			$summaryLabels                     = $this->getSummaryLabels();
			$model->summaryLabelSingular       = $summaryLabels[0];
            $model->summaryLabelPlural         = $summaryLabels[1];
			$model->newPlanId                  = '';
			$model->availablePlans             = $availablePlans;
		} else {
			$model->planId                     = null;
			$model->planName                   =
				/* translators: Displayed in the subscription list when a subscription is composed of more than one subscription plan */
				__( 'Multiple plans', 'wp-full-stripe' );
			$model->planQuantity               = 1;
			$model->allowMultipleSubscriptions = false;
			$model->maximumPlanQuantity        = 0;
			$model->planLabel                  = $model->planName;
			$model->priceAndIntervalLabel      = null;
            $model->summaryLabelSingular       = null;
            $model->summaryLabelPlural         = null;
			$model->newPlanId                  = null;
			$model->availablePlans             = array();
		}

		if ( $this->subscription->status = \StripeWPFS\Subscription::STATUS_ACTIVE &&
		     $this->subscription->cancel_at_period_end === true ) {
            $model->cancelAtPeriodEnd = true;
            $model->canceled = MM_WPFS_Utils::formatTimestampWithWordpressDateFormat( $this->subscription->current_period_end );
        } else {
            $model->cancelAtPeriodEnd = false;
            $model->canceled = null;
        }

		return $model;
	}

	public function getValue() {
		return $this->subscription->id;
	}

	public function getId() {
		return sprintf( 'wpfs-subscription--%s', $this->subscription->id );
	}

	public function getName() {
		return self::PARAM_WPFS_SUBSCRIPTION_ID;
	}

	public function getStatus() {
        $locStatus = '';

        if ( \StripeWPFS\Subscription::STATUS_TRIALING === $this->subscription->status ) {
            /* translators: The 'Trialing' subscription status */
            $locStatus = __( 'Trialing', 'wp-full-stripe' );
        } elseif ( \StripeWPFS\Subscription::STATUS_ACTIVE === $this->subscription->status ) {
            /* translators: The 'Active' subscription status */
            $locStatus = __( 'Active', 'wp-full-stripe' );
        } elseif ( \StripeWPFS\Subscription::STATUS_PAST_DUE === $this->subscription->status ) {
            /* translators: The 'Past due' subscription status */
            $locStatus = __( 'Past due', 'wp-full-stripe' );
        } elseif ( \StripeWPFS\Subscription::STATUS_CANCELED === $this->subscription->status ) {
            /* translators: The 'Canceled' subscription status */
            $locStatus = __( 'Canceled', 'wp-full-stripe' );
        } elseif ( \StripeWPFS\Subscription::STATUS_UNPAID === $this->subscription->status ) {
            /* translators: The 'Unpaid' subscription status */
            $locStatus = __( 'Unpaid', 'wp-full-stripe' );
        }

        return $locStatus;
	}

	public function getClass() {
		$clazz = '';
		if ( \StripeWPFS\Subscription::STATUS_TRIALING === $this->subscription->status ) {
			$clazz = 'wpfs-subscription-status--trialing';
		} elseif ( \StripeWPFS\Subscription::STATUS_ACTIVE === $this->subscription->status ) {
			$clazz = 'wpfs-subscription-status--active';
		} elseif ( \StripeWPFS\Subscription::STATUS_PAST_DUE === $this->subscription->status ) {
			$clazz = 'wpfs-subscription-status--past-due';
		} elseif ( \StripeWPFS\Subscription::STATUS_CANCELED === $this->subscription->status ) {
			$clazz = 'wpfs-subscription-status--canceled';
		} elseif ( \StripeWPFS\Subscription::STATUS_UNPAID === $this->subscription->status ) {
			$clazz = 'wpfs-subscription-status--unpaid';
		}

		return $clazz;
	}

	public function getCreated() {
        return MM_WPFS_Utils::formatTimestampWithWordpressDateFormat( $this->subscription->created );
	}

	/**
	 * @return string|null
	 */
	public function getPlanId() {
		$planId = null;
		$price  = $this->subscription->items->data[0]->price;
		if ( $price instanceof \StripeWPFS\Price ) {
			$planId = $price->id;
		}

		return $planId;
	}

	/**
	 * @return string
	 */
	public function getPlanName() {
		// This is an unwanted plan name, no need to localize it
		$planName = 'Unknown';
		$product  = $this->findProductInSubscription();
		if ( $product instanceof \StripeWPFS\Product ) {
			$planName = $product->name;
		}

		return $planName;
	}

	/**
	 * @return null|\StripeWPFS\Product
	 */
	private function findProductInSubscription() {
        return $this->subscription->items->data[0]->price->productExpanded;
	}

	private function getPlanQuantity() {
        return isset( $this->subscription->items->data[0]->quantity ) ? $this->subscription->items->data[0]->quantity : 1;
	}

    /**
     * @return array
     */
	private function getSummaryLabels() : array {
        return array(
            sprintf(
            /*
             * translators:
             * p1: plan/product formatted price and interval label
             */
                __(
                    'The new subscription fee will be %1$s.',
                    'wp-full-stripe'
                ),
                $this->getPriceAndIntervalLabel()
            ),
            sprintf(
            /*
             * translators:
             * p1: plan/product name
             */
                __(
                    'The new subscription fee will be @QUANTITY@x %1$s.',
                    'wp-full-stripe'
                ),
                $this->getPriceAndIntervalLabel()
            )
        );
    }

	/**
	 * @return bool
	 */
	public function isAllowMultipleSubscriptions() {
		$allowMultipleSubscriptions = false;
		if ( isset( $this->subscription->metadata ) && isset( $this->subscription->metadata->allow_multiple_subscriptions ) ) {
			$allowMultipleSubscriptions = boolval( $this->subscription->metadata->allow_multiple_subscriptions );
		}

		return $allowMultipleSubscriptions;
	}

	/**
	 * @return bool|int
	 */
	public function getMaximumPlanQuantity() {
		$maximumPlanQuantity = 0;
		if ( isset( $this->subscription->metadata ) && isset( $this->subscription->metadata->maximum_quantity_of_subscriptions ) ) {
			$maximumPlanQuantity = intval( $this->subscription->metadata->maximum_quantity_of_subscriptions );
		}

		return $maximumPlanQuantity;
	}

	public function getPlanLabel() {
		$planName = $this->getPlanName();
		$quantity = isset( $this->subscription->items->data[0]->quantity ) ? $this->subscription->items->data[0]->quantity : 1;

		//todo: localize this
		return $quantity == 1 ? $planName : sprintf( '%d%s %s', $quantity, 'x', $planName );
	}

	public function getPriceAndIntervalLabel() {
		$subscriptionItem  = $this->subscription->items->data[0];

		return $this->getPriceAndIntervalLabelForSubscriptionItem( $subscriptionItem );
	}

	public function getSubscriptionFormName() {
		if ( isset( $this->subscription->metadata ) && isset( $this->subscription->metadata->form_name ) ) {
			return $this->subscription->metadata->form_name;
		}
		return null;
	}

	/**
	 * @param \StripeWPFS\SubscriptionItem $subscriptionItem
	 *
	 * @return string
	 * @throws Exception
	 */
	protected function getPriceAndIntervalLabelForSubscriptionItem( \StripeWPFS\SubscriptionItem $subscriptionItem ): string {
		$currency      = $subscriptionItem->price->currency;
		$interval      = $subscriptionItem->price->recurring->interval;
		$intervalCount = $subscriptionItem->price->recurring->interval_count;

		$amount = 0;
		if ( MM_WPFS_CustomerPortalService::isDonationPlan( $this->subscription ) ) {
			$amount = $this->donationAmount;
		} else {
			// For graduated and volume prices the amount can be null
			$amount = is_null( $subscriptionItem->price->unit_amount ) ? 0 : $subscriptionItem->price->unit_amount;
		}

		$formattedAmount = MM_WPFS_Currencies::formatAndEscapeByMyAccount(
			$currency,
			$amount,
			true,
			true );

		return MM_WPFS_Localization::getPriceAndIntervalLabel( $interval, $intervalCount, $formattedAmount );
	}

}