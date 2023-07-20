<?php

/*
WP Full Stripe
https://paymentsplugin.com
Complete Stripe payments integration for Wordpress
Mammothology
6.1.1
https://paymentsplugin.com
*/

class MM_WPFS {

	const VERSION = '6.1.1';
	const REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS = 'wpfs_rendered_forms';

	const HANDLE_WP_FULL_STRIPE_JS = 'wp-full-stripe-js';

	const SHORTCODE_FULLSTRIPE_FORM = 'fullstripe_form';
    const HANDLE_WP_FULL_STRIPE_UTILS_JS = 'wp-full-stripe-utils-js';
	const HANDLE_SPRINTF_JS = 'sprintf-js';
	const HANDLE_STRIPE_JS_V_3 = 'stripe-js-v3';
	const HANDLE_STYLE_WPFS_VARIABLES = 'wpfs-variables-css';
	const HANDLE_STYLE_WPFS_FORMS = 'wpfs-forms-css';
	const HANDLE_GOOGLE_RECAPTCHA_V_2 = 'google-recaptcha-v2';
    const SOURCE_GOOGLE_RECAPTCHA_V2_API_JS = 'https://www.google.com/recaptcha/api.js';

	// Generic form types
	const FORM_TYPE_PAYMENT = 'payment';
    const FORM_TYPE_SUBSCRIPTION = 'subscription';
    const FORM_TYPE_DONATION = 'donation';
    const FORM_TYPE_SAVE_CARD = 'save_card';

	const FORM_TYPE_INLINE_PAYMENT = 'inline_payment';
	const FORM_TYPE_CHECKOUT_PAYMENT = 'checkout_payment';
	const FORM_TYPE_INLINE_SUBSCRIPTION = 'inline_subscription';
    const FORM_TYPE_CHECKOUT_SUBSCRIPTION = 'checkout_subscription';
	const FORM_TYPE_INLINE_SAVE_CARD = 'inline_save_card';
    const FORM_TYPE_CHECKOUT_SAVE_CARD = 'checkout_save_card';
    const FORM_TYPE_INLINE_DONATION = 'inline_donation';
    const FORM_TYPE_CHECKOUT_DONATION = 'checkout_donation';

    // legacy form types, used only for shortcodes
    const FORM_TYPE_POPUP_PAYMENT = 'popup_payment';
    const FORM_TYPE_POPUP_SUBSCRIPTION = 'popup_subscription';
    const FORM_TYPE_POPUP_SAVE_CARD = 'popup_save_card';
    const FORM_TYPE_POPUP_DONATION = 'popup_donation';

    const FORM_TYPE_ADMIN_CREATE_FORM = 'createForm';
    const FORM_TYPE_ADMIN_CONFIGURE_STRIPE_ACCOUNT = 'configureStripeAccount';
    const FORM_TYPE_ADMIN_CONFIGURE_CUSTOMER_PORTAL = 'configureMyAccount';
    const FORM_TYPE_ADMIN_CONFIGURE_SECURITY = 'configureSecurity';
    const FORM_TYPE_ADMIN_CONFIGURE_EMAIL_OPTIONS = 'configureEmailOptions';
    const FORM_TYPE_ADMIN_CONFIGURE_EMAIL_TEMPLATES = 'configureEmailTemplates';
    const FORM_TYPE_ADMIN_CONFIGURE_FORMS_OPTIONS = 'configureFormsOptions';
    const FORM_TYPE_ADMIN_CONFIGURE_FORMS_APPEARANCE = 'configureFormsAppearance';
    const FORM_TYPE_ADMIN_CONFIGURE_WP_DASHBOARD = 'configureWpDashboard';
    const FORM_TYPE_ADMIN_INLINE_SAVE_CARD_FORM = 'inlineSaveCardForm';
    const FORM_TYPE_ADMIN_CHECKOUT_SAVE_CARD_FORM = 'checkoutSaveCardForm';
    const FORM_TYPE_ADMIN_INLINE_DONATION_FORM = 'inlineDonationForm';
    const FORM_TYPE_ADMIN_CHECKOUT_DONATION_FORM = 'checkoutDonationForm';
    const FORM_TYPE_ADMIN_ADD_CUSTOM_FIELD = 'addCustomField';
    const FORM_TYPE_ADMIN_ADD_SUGGESTED_DONATION_AMOUNT = 'addSuggestedDonationAmount';
    const FORM_TYPE_ADMIN_INLINE_PAYMENT_FORM = 'inlinePaymentForm';
    const FORM_TYPE_ADMIN_CHECKOUT_PAYMENT_FORM = 'checkoutPaymentForm';
    const FORM_TYPE_ADMIN_INLINE_SUBSCRIPTION_FORM = 'inlineSubscriptionForm';
    const FORM_TYPE_ADMIN_CHECKOUT_SUBSCRIPTION_FORM = 'checkoutSubscriptionForm';
    const FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES = 'addPlanProperties';
    const FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES = 'editProductProperties';
    const FORM_TYPE_ADMIN_SEND_TEST_EMAIL = 'sendTestEmail';

    const FORM_LAYOUT_INLINE = 'inline';
    const FORM_LAYOUT_CHECKOUT = 'checkout';

    const STRIPE_API_MODE_TEST = 'test';
    const STRIPE_API_MODE_LIVE = 'live';

    const REDIRECT_TYPE_SHOW_CONFIRMATION_MESSAGE = 'showConfirmationMessage';
    const REDIRECT_TYPE_TO_PAGE_OR_POST = 'redirectToPageOrPost';
    const REDIRECT_TYPE_TO_CUSTOM_URL = 'redirecToCustomUrl';

    const VAT_RATE_TYPE_NO_VAT = 'no_vat';
	const VAT_RATE_TYPE_FIXED_VAT = 'fixed_vat';
	const VAT_RATE_TYPE_CUSTOM_VAT = 'custom_vat';

	const NO_VAT_PERCENT = 0.0;

	const DEFAULT_BILLING_COUNTRY_INITIAL_VALUE = 'US';

	const PREFERRED_LANGUAGE_AUTO = 'auto';

	const DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT = 10;

	const PAYMENT_TYPE_LIST_OF_AMOUNTS = 'list_of_amounts';
	const PAYMENT_TYPE_CUSTOM_AMOUNT = 'custom_amount';
	const PAYMENT_TYPE_SPECIFIED_AMOUNT = 'specified_amount';
	const PAYMENT_TYPE_CARD_CAPTURE = 'card_capture';

	const CURRENCY_USD = 'usd';

	const OPTION_API_TEST_SECRET_KEY = 'secretKey_test';
    const OPTION_API_TEST_PUBLISHABLE_KEY = 'publishKey_test';
    const OPTION_API_LIVE_SECRET_KEY = 'secretKey_live';
    const OPTION_API_LIVE_PUBLISHABLE_KEY = 'publishKey_live';
    const OPTION_API_MODE = 'apiMode';
    const OPTION_FILL_IN_EMAIL_FOR_LOGGED_IN_USERS = 'lock_email_field_for_logged_in_users';
	const OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA = 'secure_inline_forms_with_google_recaptcha';
	const OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA = 'secure_checkout_forms_with_google_recaptcha';
	const OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA = 'secure_subscription_update_with_google_recaptcha';
	const OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY = 'google_recaptcha_site_key';
	const OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY = 'google_recaptcha_secret_key';
	const OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS = 'my_account_subscribers_cancel_subscriptions';
    const OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS = 'my_account_subscribers_updowngrade_subscriptions';
    const OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS = 'my_account_when_cancel_subscriptions';
    const OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION = 'my_account_show_invoices_section';
    const OPTION_CUSTOMER_PORTAL_SHOW_ALL_INVOICES = 'my_account_show_all_invoices';
	const OPTION_DECIMAL_SEPARATOR_SYMBOL = 'decimal_separator_symbol';
	const OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE = 'show_currency_symbol_instead_of_code';
	const OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION = 'show_currency_sign_first';
	const OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT = 'put_whitespace_between_currency_and_amount';
    const OPTION_LAST_WEBHOOK_EVENT_TEST = 'last_webhook_event_test';
    const OPTION_LAST_WEBHOOK_EVENT_LIVE = 'last_webhook_event_live';
    const OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS = 'email_receipt_sender_address';
    const OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES = 'email_notification_bcc_addresses';
    const OPTION_EMAIL_TEMPLATES = 'email_receipts';
    const OPTION_RECEIPT_EMAIL_TYPE = 'receiptEmailType';
    const OPTION_FORM_CUSTOM_CSS = 'form_css';

    const OPTION_VALUE_RECEIPT_EMAIL_PLUGIN = 'plugin';

	const CANCEL_SUBSCRIPTION_IMMEDIATELY = 'immediately';
    const CANCEL_SUBSCRIPTION_AT_PERIOD_END = 'atPeriodEnd';

	const DECIMAL_SEPARATOR_SYMBOL_DOT = 'dot';
	const DECIMAL_SEPARATOR_SYMBOL_COMMA = 'comma';

	const CHARGE_TYPE_IMMEDIATE = 'immediate';
	const CHARGE_TYPE_AUTHORIZE_AND_CAPTURE = 'authorize_and_capture';

	const PAYMENT_METHOD_CARD = 'card';

	const STRIPE_CHARGE_STATUS_SUCCEEDED = 'succeeded';
	const STRIPE_CHARGE_STATUS_PENDING = 'pending';
	const STRIPE_CHARGE_STATUS_FAILED = 'failed';

	const PAYMENT_STATUS_UNKNOWN = 'unknown';
	const PAYMENT_STATUS_FAILED = 'failed';
	const PAYMENT_STATUS_REFUNDED = 'refunded';
	const PAYMENT_STATUS_EXPIRED = 'expired';
	const PAYMENT_STATUS_PAID = 'paid';
	const PAYMENT_STATUS_AUTHORIZED = 'authorized';
	const PAYMENT_STATUS_PENDING = 'pending';
	const PAYMENT_STATUS_RELEASED = 'released';

	const REFUND_STATUS_SUCCEEDED = 'succeeded';
	const REFUND_STATUS_FAILED = 'failed';
	const REFUND_STATUS_PENDING = 'pending';
	const REFUND_STATUS_CANCELED = 'canceled';

	const SUBSCRIPTION_STATUS_ENDED = 'ended';
	const SUBSCRIPTION_STATUS_CANCELLED = 'cancelled';

	const AMOUNT_SELECTOR_STYLE_RADIO_BUTTONS = 'radio-buttons';
	const AMOUNT_SELECTOR_STYLE_DROPDOWN = 'dropdown';
	const AMOUNT_SELECTOR_STYLE_BUTTON_GROUP = 'button-group';

	const PLAN_SELECTOR_STYLE_DROPDOWN = 'dropdown';
	const PLAN_SELECTOR_STYLE_RADIO_BUTTONS = 'radio-buttons';

	const JS_VARIABLE_WPFS_FORM_OPTIONS = 'wpfsFormSettings';
	const JS_VARIABLE_AJAX_URL = 'ajaxUrl';
	const JS_VARIABLE_STRIPE_KEY = 'stripeKey';
	const JS_VARIABLE_GOOGLE_RECAPTCHA_SITE_KEY = 'googleReCaptchaSiteKey';
	const JS_VARIABLE_L10N = 'l10n';
	const JS_VARIABLE_FORM_FIELDS = 'formFields';

	const ACTION_NAME_BEFORE_SAVE_CARD = 'fullstripe_before_card_capture';
	const ACTION_NAME_AFTER_SAVE_CARD = 'fullstripe_after_card_capture';
	const ACTION_NAME_BEFORE_CHECKOUT_SAVE_CARD = 'fullstripe_before_checkout_card_capture';
	const ACTION_NAME_AFTER_CHECKOUT_SAVE_CARD = 'fullstripe_after_checkout_card_capture';

	const ACTION_NAME_BEFORE_PAYMENT_CHARGE = 'fullstripe_before_payment_charge';
	const ACTION_NAME_AFTER_PAYMENT_CHARGE = 'fullstripe_after_payment_charge';
	const ACTION_NAME_BEFORE_CHECKOUT_PAYMENT_CHARGE = 'fullstripe_before_checkout_payment_charge';
	const ACTION_NAME_AFTER_CHECKOUT_PAYMENT_CHARGE = 'fullstripe_after_checkout_payment_charge';

    const ACTION_NAME_BEFORE_DONATION_CHARGE = 'fullstripe_before_donation_charge';
    const ACTION_NAME_AFTER_DONATION_CHARGE = 'fullstripe_after_donation_charge';
    const ACTION_NAME_BEFORE_CHECKOUT_DONATION_CHARGE = 'fullstripe_before_checkout_donation_charge';
    const ACTION_NAME_AFTER_CHECKOUT_DONATION_CHARGE = 'fullstripe_after_checkout_donation_charge';

    const ACTION_NAME_BEFORE_SUBSCRIPTION_CHARGE = 'fullstripe_before_subscription_charge';
	const ACTION_NAME_AFTER_SUBSCRIPTION_CHARGE = 'fullstripe_after_subscription_charge';
	const ACTION_NAME_BEFORE_CHECKOUT_SUBSCRIPTION_CHARGE = 'fullstripe_before_checkout_subscription_charge';
	const ACTION_NAME_AFTER_CHECKOUT_SUBSCRIPTION_CHARGE = 'fullstripe_after_checkout_subscription_charge';

	const ACTION_NAME_BEFORE_SUBSCRIPTION_CANCELLATION = 'fullstripe_before_subscription_cancellation';
	const ACTION_NAME_AFTER_SUBSCRIPTION_CANCELLATION = 'fullstripe_after_subscription_cancellation';

    const ACTION_NAME_BEFORE_SUBSCRIPTION_UPDATE = 'fullstripe_before_subscription_update';
    const ACTION_NAME_AFTER_SUBSCRIPTION_UPDATE = 'fullstripe_after_subscription_update';

    const ACTION_NAME_BEFORE_SUBSCRIPTION_ACTIVATION = 'fullstripe_before_subscription_activation';
    const ACTION_NAME_AFTER_SUBSCRIPTION_ACTIVATION = 'fullstripe_after_subscription_activation';

    const FILTER_NAME_GET_VAT_PERCENT = 'fullstripe_get_vat_percent';
	const FILTER_NAME_SELECT_SUBSCRIPTION_PLAN = 'fullstripe_select_subscription_plan';
	const FILTER_NAME_SET_CUSTOM_AMOUNT = 'fullstripe_set_custom_amount';
	const FILTER_NAME_ADD_TRANSACTION_METADATA = 'fullstripe_add_transaction_metadata';
	const FILTER_NAME_MODIFY_EMAIL_MESSAGE = 'fullstripe_modify_email_message';
	const FILTER_NAME_MODIFY_EMAIL_SUBJECT = 'fullstripe_modify_email_subject';
	const FILTER_NAME_GET_UPGRADE_DOWNGRADE_PLANS = 'fullstripe_get_upgrade_downgrade_plans';

	const STRIPE_OBJECT_ID_PREFIX_PAYMENT_INTENT = 'pi_';
	const STRIPE_OBJECT_ID_PREFIX_CHARGE = 'ch_';
	const PAYMENT_OBJECT_TYPE_UNKNOWN = 'Unknown';
	const PAYMENT_OBJECT_TYPE_STRIPE_PAYMENT_INTENT = '\StripeWPFS\PaymentIntent';
	const PAYMENT_OBJECT_TYPE_STRIPE_CHARGE = '\StripeWPFS\Charge';

	const SUBSCRIBER_STATUS_CANCELLED = 'cancelled';
	const SUBSCRIBER_STATUS_RUNNING = 'running';
	const SUBSCRIBER_STATUS_ENDED = 'ended';
	const SUBSCRIBER_STATUS_INCOMPLETE = 'incomplete';

    const DONATION_STATUS_UNKNOWN = 'unknown';
    const DONATION_STATUS_PAID = 'paid';
    const DONATION_STATUS_RUNNING = 'running';
    const DONATION_STATUS_REFUNDED = 'refunded';

    const HTTP_PARAM_NAME_PLAN = 'wpfsPlan';
	const HTTP_PARAM_NAME_AMOUNT = 'wpfsAmount';

    const DONATION_PLAN_ID_PREFIX = "wpfsDonationPlan";

    const EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT = 'paymentMade';
    const EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT_STRIPE = 'paymentMadeStripe';
    const EMAIL_TEMPLATE_ID_CARD_SAVED = 'cardCaptured';
    const EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT = 'subscriptionStarted';
    const EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT_STRIPE = 'subscriptionStartedStripe';
    const EMAIL_TEMPLATE_ID_SUBSCRIPTION_ENDED = 'subscriptionFinished';
    const EMAIL_TEMPLATE_ID_DONATION_RECEIPT = 'donationMade';
    const EMAIL_TEMPLATE_ID_DONATION_RECEIPT_STRIPE = 'donationMadeStripe';
    const EMAIL_TEMPLATE_ID_CUSTOMER_PORTAL_SECURITY_CODE = 'cardUpdateConfirmationRequest';

    const COUNTRY_CODE_UNITED_STATES = 'US';
    const FIELD_VALUE_TAX_RATE_NO_TAX = 'taxRateNoTax';
    const FIELD_VALUE_TAX_RATE_FIXED = 'taxRateFixed';
    const FIELD_VALUE_TAX_RATE_DYNAMIC = 'taxRateDynamic';

    public static $instance;

	private $debugLog = false;

	/** @var MM_WPFS_Customer */
	private $customer = null;
	/** @var MM_WPFS_Admin */
	private $admin = null;
	/** @var MM_WPFS_Database */
	private $database = null;
	/** @var MM_WPFS_Stripe */
	private $stripe = null;
	/** @var MM_WPFS_Admin_Menu */
	private $adminMenu = null;
	/** @var MM_WPFS_TransactionDataService */
	private $transactionDataService = null;
	/** @var MM_WPFS_CustomerPortalService */
	private $cardUpdateService = null;
    /** @var MM_WPFS_ThankYou */
    private $thankYou = null;
	/** @var MM_WPFS_CheckoutSubmissionService */
	private $checkoutSubmissionService = null;
	/**
	 * @var bool Choose to load scripts and styles the WordPress way. We should move this field to a wp_option later.
	 */
	private $loadScriptsAndStylesWithActionHook = false;
    /**
     * @var bool Turn this off if you don't want to load the form CSS styles
     */
	private $includeDefaultStyles = true;

	public function __construct() {

		$this->includes();
		$this->setup();
		$this->hooks();

	}

	function includes() {

		include 'wpfs-localization.php';
		include 'wp/class-wp-list-table.php';
        include 'wpfs-tables.php';
        include 'wpfs-languages.php';
		include 'wpfs-admin.php';
		include 'wpfs-admin-menu.php';
        include 'wpfs-form-views.php';
		include 'wpfs-form-models.php';
        include 'wpfs-form-validators.php';
        include 'wpfs-admin-views.php';
        include 'wpfs-admin-models.php';
        include 'wpfs-admin-validators.php';
		include 'wpfs-assets.php';
        include 'wpfs-pricing.php';
		include 'wpfs-customer-portal-service.php';
        include 'wpfs-thank-you.php';
		include 'wpfs-checkout-charge-handler.php';
		include 'wpfs-checkout-submission-service.php';
		include 'wpfs-countries.php';
        include 'wpfs-states.php';
		include 'wpfs-currencies.php';
		include 'wpfs-customer.php';
		include 'wpfs-database.php';
		include 'wpfs-logger-service.php';
		include 'wpfs-mailer.php';
		include 'wpfs-news-feed-url.php';
		include 'wpfs-patcher.php';
		include 'wpfs-payments.php';
		include 'wpfs-help.php';
		include 'wpfs-transaction-data-service.php';
		include 'wpfs-web-hook-events.php';
        include 'wpfs-api.php';
        include 'wpfs-macros.php';
        include 'wpfs-utils.php';
        include 'wpfs-recaptcha.php';
        include 'wpfs-shortcode.php';

		do_action( 'fullstripe_includes_action' );
	}

	function setup() {

		//set option defaults
		$options = get_option( 'fullstripe_options' );
		if ( ! $options || $options['fullstripe_version'] != self::VERSION ) {
			$this->set_option_defaults( $options );
			// tnagy reload saved options
			$options = get_option( 'fullstripe_options' );
		}
		$this->update_option_defaults( $options );

		MM_WPFS_LicenseManager::getInstance()->activateLicenseIfNeeded();

		//setup subclasses to handle everything
		$this->admin                     = new MM_WPFS_Admin();
		$this->adminMenu                 = new MM_WPFS_Admin_Menu();
		$this->customer                  = new MM_WPFS_Customer();
		$this->database                  = new MM_WPFS_Database();
		$this->stripe                    = new MM_WPFS_Stripe( MM_WPFS::getStripeAuthenticationToken() );
		$this->transactionDataService    = new MM_WPFS_TransactionDataService();
		$this->cardUpdateService         = new MM_WPFS_CustomerPortalService();
		$this->checkoutSubmissionService = new MM_WPFS_CheckoutSubmissionService();
        $this->thankYou                  = new MM_WPFS_ThankYou();

		do_action( 'fullstripe_setup_action' );
	}

	function set_option_defaults( $options ) {
		if ( ! $options ) {

			$emailReceipts = MM_WPFS_Mailer::getDefaultEmailTemplates();

			/** @noinspection PhpUndefinedClassInspection */
			$default_options = array(
				'secretKey_test'                                                  => 'YOUR_TEST_SECRET_KEY',
				'publishKey_test'                                                 => 'YOUR_TEST_PUBLISHABLE_KEY',
				'secretKey_live'                                                  => 'YOUR_LIVE_SECRET_KEY',
				'publishKey_live'                                                 => 'YOUR_LIVE_PUBLISHABLE_KEY',
				'apiMode'                                                         => 'test',
				MM_WPFS::OPTION_FORM_CUSTOM_CSS                                   => "",
				'receiptEmailType'                                                => MM_WPFS::OPTION_VALUE_RECEIPT_EMAIL_PLUGIN,
				'email_receipts'                                                  => json_encode( $emailReceipts ),
                MM_WPFS::OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS                 => get_bloginfo( 'admin_email' ),
                MM_WPFS::OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES                  => json_encode( array() ),
				'admin_payment_receipt'                                           => '0',
				'lock_email_field_for_logged_in_users'                            => '1',
				'fullstripe_version'                                              => self::VERSION,
				'webhook_token'                                                   => $this->createWebhookToken(),
				'custom_input_field_max_count'                                    => MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT,
				MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA        => '0',
				MM_WPFS::OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA      => '0',
				MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA => '0',
				MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY                        => 'YOUR_GOOGLE_RECAPTCHA_SITE_KEY',
				MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY                      => 'YOUR_GOOGLE_RECAPTCHA_SECRET_KEY',
                MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS   => '1',
                MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS   => '0',
                MM_WPFS::OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS              => MM_WPFS::CANCEL_SUBSCRIPTION_IMMEDIATELY,
				MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION                  => '1',
				MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_ALL_INVOICES                      => '0',
				MM_WPFS::OPTION_DECIMAL_SEPARATOR_SYMBOL                          => MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT,
				MM_WPFS::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE              => '1',
				MM_WPFS::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION              => '1',
				MM_WPFS::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT        => '0',
				MM_WPFS::OPTION_LAST_WEBHOOK_EVENT_TEST                           => null,
                MM_WPFS::OPTION_LAST_WEBHOOK_EVENT_LIVE                           => null
			);

			$edd_options   = MM_WPFS_LicenseManager::getInstance()->getLicenseOptionDefaults();
			$final_options = array_merge( $default_options, $edd_options );

			update_option( 'fullstripe_options', $final_options );
		} else {

			// different version

			$options['fullstripe_version'] = self::VERSION;
			if ( ! array_key_exists( 'secretKey_test', $options ) ) {
				$options['secretKey_test'] = 'YOUR_TEST_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_test', $options ) ) {
				$options['publishKey_test'] = 'YOUR_TEST_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'secretKey_live', $options ) ) {
				$options['secretKey_live'] = 'YOUR_LIVE_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_live', $options ) ) {
				$options['publishKey_live'] = 'YOUR_LIVE_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'apiMode', $options ) ) {
				$options['apiMode'] = 'test';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_FORM_CUSTOM_CSS, $options ) ) {
				$options[MM_WPFS::OPTION_FORM_CUSTOM_CSS] = "";
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_RECEIPT_EMAIL_TYPE, $options ) ) {
				$options[MM_WPFS::OPTION_RECEIPT_EMAIL_TYPE] = MM_WPFS::OPTION_VALUE_RECEIPT_EMAIL_PLUGIN;
			}
            if ( ! array_key_exists( MM_WPFS::OPTION_EMAIL_TEMPLATES, $options ) ) {
                $emailReceipts             = MM_WPFS_Mailer::getDefaultEmailTemplates();
                $options[ MM_WPFS::OPTION_EMAIL_TEMPLATES ] = json_encode( $emailReceipts );
            } else {
                $emailReceipts = json_decode( $options[ MM_WPFS::OPTION_EMAIL_TEMPLATES ] );
                MM_WPFS_Mailer::updateMissingEmailTemplatesWithDefaults($emailReceipts);
                $options[ MM_WPFS::OPTION_EMAIL_TEMPLATES ] = json_encode( $emailReceipts );
            }
			if ( ! array_key_exists( MM_WPFS::OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS, $options ) ) {
				$options[ MM_WPFS::OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS ] = get_bloginfo( 'admin_email' );
			}
            if ( ! array_key_exists( MM_WPFS::OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES, $options ) ) {
                $options[ MM_WPFS::OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES ] = json_encode( array() );
            }
			if ( ! array_key_exists( 'admin_payment_receipt', $options ) ) {
				$options['admin_payment_receipt'] = '0';
			}
			if ( ! array_key_exists( 'lock_email_field_for_logged_in_users', $options ) ) {
				$options['lock_email_field_for_logged_in_users'] = '1';
			}
			if ( ! array_key_exists( 'webhook_token', $options ) ) {
				$options['webhook_token'] = $this->createWebhookToken();
			}
			if ( ! array_key_exists( 'custom_input_field_max_count', $options ) ) {
				$options['custom_input_field_max_count'] = MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT;
			} elseif ( $options['custom_input_field_max_count'] != MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT ) {
				$options['custom_input_field_max_count'] = MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT;
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
				$options[ MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
				$options[ MM_WPFS::OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
				$options[ MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY, $options ) ) {
				$options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY ] = 'YOUR_GOOGLE_RECAPTCHA_SITE_KEY';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY, $options ) ) {
				$options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY ] = 'YOUR_GOOGLE_RECAPTCHA_SECRET_KEY';
			}
            if ( ! array_key_exists( MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS, $options ) ) {
                $options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS ] = '1';
            }
            if ( ! array_key_exists( MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS, $options ) ) {
                $options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS ] = '1';
            }
            if ( ! array_key_exists( MM_WPFS::OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS, $options ) ) {
                $options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS ] = MM_WPFS::CANCEL_SUBSCRIPTION_IMMEDIATELY;
            }
			if ( ! array_key_exists( MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION, $options ) ) {
				$options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION ] = '1';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_ALL_INVOICES, $options ) ) {
				$options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_ALL_INVOICES ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_DECIMAL_SEPARATOR_SYMBOL, $options ) ) {
				$options[ MM_WPFS::OPTION_DECIMAL_SEPARATOR_SYMBOL ] = MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT;
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE, $options ) ) {
				$options[ MM_WPFS::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE ] = '1';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION, $options ) ) {
				$options[ MM_WPFS::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION ] = '1';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT, $options ) ) {
				$options[ MM_WPFS::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT ] = '0';
			}
            if ( ! array_key_exists( MM_WPFS::OPTION_LAST_WEBHOOK_EVENT_TEST, $options ) ) {
                $options[ MM_WPFS::OPTION_LAST_WEBHOOK_EVENT_TEST ] = null;
            }
            if ( ! array_key_exists( MM_WPFS::OPTION_LAST_WEBHOOK_EVENT_LIVE, $options ) ) {
                $options[ MM_WPFS::OPTION_LAST_WEBHOOK_EVENT_LIVE ] = null;
            }

			MM_WPFS_LicenseManager::getInstance()->setLicenseOptionDefaultsIfEmpty( $options );

			update_option( 'fullstripe_options', $options );
		}

		// also, if version changed then the DB might be out of date
		MM_WPFS::setup_db( false );
	}

	/**
	 * Generates a unique random token for authenticating webhook callbacks.
	 *
	 * @return string
	 */
	private function createWebhookToken() {
		$siteURL           = get_site_url();
		$randomToken       = hash( 'md5', rand() );
		$generatedPassword = substr( hash( 'sha512', rand() ), 0, 6 );

		return hash( 'md5', $siteURL . '|' . $randomToken . '|' . $generatedPassword );
	}

	public static function setup_db( $network_wide ) {
		if ( $network_wide ) {
			MM_WPFS_Utils::log( "setup_db() - Activating network-wide" );
			if ( function_exists( 'get_sites' ) && function_exists( 'get_current_network_id' ) ) {
				$site_ids = get_sites( array( 'fields' => 'ids', 'network_id' => get_current_network_id() ) );
			} else {
				$site_ids = MM_WPFS_Database::get_site_ids();
			}

			foreach ( $site_ids as $site_id ) {
				switch_to_blog( $site_id );
				self::setup_db_single_site();
				restore_current_blog();
			}
		} else {
			MM_WPFS_Utils::log( "setup_db() - Activating for single site" );
			self::setup_db_single_site();
		}
	}

	public static function setup_db_single_site() {
		MM_WPFS_Database::fullstripe_setup_db();
		MM_WPFS_Patcher::apply_patches();
	}

    function update_option_defaults( $options ) {
		if ( $options ) {
			if ( ! array_key_exists( 'secretKey_test', $options ) ) {
				$options['secretKey_test'] = 'YOUR_TEST_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_test', $options ) ) {
				$options['publishKey_test'] = 'YOUR_TEST_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'secretKey_live', $options ) ) {
				$options['secretKey_live'] = 'YOUR_LIVE_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_live', $options ) ) {
				$options['publishKey_live'] = 'YOUR_LIVE_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'apiMode', $options ) ) {
				$options['apiMode'] = 'test';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_FORM_CUSTOM_CSS, $options ) ) {
				$options[MM_WPFS::OPTION_FORM_CUSTOM_CSS] = "";
			}
			if ( ! array_key_exists( 'receiptEmailType', $options ) ) {
				$options['receiptEmailType'] = MM_WPFS::OPTION_VALUE_RECEIPT_EMAIL_PLUGIN;
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_EMAIL_TEMPLATES, $options ) ) {
				$emailReceipts             = MM_WPFS_Mailer::getDefaultEmailTemplates();
				$options[ MM_WPFS::OPTION_EMAIL_TEMPLATES ] = json_encode( $emailReceipts );
			} else {
				$emailReceipts = json_decode( $options[ MM_WPFS::OPTION_EMAIL_TEMPLATES ] );
                MM_WPFS_Mailer::updateMissingEmailTemplatesWithDefaults($emailReceipts);
                $options[ MM_WPFS::OPTION_EMAIL_TEMPLATES ] = json_encode( $emailReceipts );
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS, $options ) ) {
				$options[ MM_WPFS::OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS ] = get_bloginfo( 'admin_email' );
			}
            if ( ! array_key_exists( MM_WPFS::OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES, $options ) ) {
                $options[ MM_WPFS::OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES ] = json_encode( array() );
            }
			if ( ! array_key_exists( 'admin_payment_receipt', $options ) ) {
				$options['admin_payment_receipt'] = 'no';
			} else {
				if ( $options['admin_payment_receipt'] == '0' ) {
					$options['admin_payment_receipt'] = 'no';
				}
				if ( $options['admin_payment_receipt'] == '1' ) {
					$options['admin_payment_receipt'] = 'website_admin';
				}
			}
			if ( ! array_key_exists( 'lock_email_field_for_logged_in_users', $options ) ) {
				$options['lock_email_field_for_logged_in_users'] = '1';
			}
			if ( ! array_key_exists( 'webhook_token', $options ) ) {
				$options['webhook_token'] = $this->createWebhookToken();
			}
			if ( ! array_key_exists( 'custom_input_field_max_count', $options ) ) {
				$options['custom_input_field_max_count'] = MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT;
			} elseif ( $options['custom_input_field_max_count'] != MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT ) {
				$options['custom_input_field_max_count'] = MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT;
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
				$options[ MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
				$options[ MM_WPFS::OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
				$options[ MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY, $options ) ) {
				$options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY ] = 'YOUR_GOOGLE_RECAPTCHA_SITE_KEY';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY, $options ) ) {
				$options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY ] = 'YOUR_GOOGLE_RECAPTCHA_SECRET_KEY';
			}
            if ( ! array_key_exists( MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS, $options ) ) {
                $options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS ] = '1';
            }
            if ( ! array_key_exists( MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS, $options ) ) {
                $options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS ] = '0';
            }
            if ( ! array_key_exists( MM_WPFS::OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS, $options ) ) {
                $options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS ] = MM_WPFS::CANCEL_SUBSCRIPTION_IMMEDIATELY;
            }
			if ( ! array_key_exists( MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION, $options ) ) {
				$options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION ] = '1';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_ALL_INVOICES, $options ) ) {
				$options[ MM_WPFS::OPTION_CUSTOMER_PORTAL_SHOW_ALL_INVOICES ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_DECIMAL_SEPARATOR_SYMBOL, $options ) ) {
				$options[ MM_WPFS::OPTION_DECIMAL_SEPARATOR_SYMBOL ] = MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT;
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE, $options ) ) {
				$options[ MM_WPFS::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE ] = '1';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION, $options ) ) {
				$options[ MM_WPFS::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION ] = '1';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT, $options ) ) {
				$options[ MM_WPFS::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT ] = '0';
			}
            if ( ! array_key_exists( MM_WPFS::OPTION_LAST_WEBHOOK_EVENT_LIVE, $options ) ) {
                $options[ MM_WPFS::OPTION_LAST_WEBHOOK_EVENT_LIVE ] = null;
            }
            if ( ! array_key_exists( MM_WPFS::OPTION_LAST_WEBHOOK_EVENT_TEST, $options ) ) {
                $options[ MM_WPFS::OPTION_LAST_WEBHOOK_EVENT_TEST ] = null;
            }

			MM_WPFS_LicenseManager::getInstance()->setLicenseOptionDefaultsIfEmpty( $options );

			update_option( 'fullstripe_options', $options );
		}
	}

    /**
     * @param $liveMode
     *
     * @return string
     */
	public static function getStripeAuthenticationTokenByMode( $liveMode ) {
        $options = get_option( 'fullstripe_options' );

        $token = $liveMode ? $options['secretKey_live'] : $options['secretKey_test'];

        return $token;
    }

    /**
     * @return string
     */
    public static function getStripeAuthenticationToken() {
	    return self::getStripeAuthenticationTokenByMode( self::isStripeAPIInLiveMode() );
    }

    /**
     * @return bool
     */
    public static function isStripeAPIInLiveMode() {
        $options = get_option( 'fullstripe_options' );

        return $options['apiMode'] === MM_WPFS::STRIPE_API_MODE_LIVE;
    }

    /**
     * @return mixed
     */
    public static function getStripeTestAuthenticationToken() {
        $options = get_option( 'fullstripe_options' );

        return $options['secretKey_test'];
    }

    /**
     * @return mixed
     */
    public static function getStripeLiveAuthenticationToken() {
        $options = get_option( 'fullstripe_options' );

        return $options['secretKey_live'];
    }

	function hooks() {

		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );

		add_action( 'fullstripe_update_email_template_defaults', array( $this, 'updateEmailTemplateDefaults' ), 10, 0 );
		add_action( 'wp_head', array( $this, 'fullstripe_wp_head' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'fullstripe_enqueue_scripts_and_styles' ) );

        add_shortcode( self::SHORTCODE_FULLSTRIPE_FORM, array( $this, 'fullstripe_form' ) );

        do_action( 'fullstripe_main_hooks_action' );
	}

	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new MM_WPFS();
		}

		return self::$instance;
	}

	public function updateEmailTemplateDefaults() {
        MM_WPFS_Mailer::updateDefaultEmailTemplatesInOptions();
    }

    public static function getCustomFieldMaxCount() {
		$options = get_option( 'fullstripe_options' );
		if ( is_array( $options ) && array_key_exists( 'custom_input_field_max_count', $options ) ) {
			$customInputFieldMaxCount = $options['custom_input_field_max_count'];
			if ( is_numeric( $customInputFieldMaxCount ) ) {
				return $customInputFieldMaxCount;
			}
		}

		return self::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT;
	}

	public function plugin_action_links( $links, $file ) {
		static $currentPlugin;

		if ( ! $currentPlugin ) {
			$currentPlugin = plugin_basename( 'wp-full-stripe/wp-full-stripe.php' );
		}

		if ( $file == $currentPlugin ) {
			$settingsLabel =
				/* translators: Link label displayed on the Plugins page in WP admin */
				__( 'Settings', 'wp-full-stripe-admin' );
			$settingsLink  = '<a href="' . menu_page_url( MM_WPFS_Admin_Menu::SLUG_SETTINGS, false ) . '">' . esc_html( $settingsLabel ) . '</a>';
			array_unshift( $links, $settingsLink );
		}

		return $links;
	}

    /**
	 * Generalized function to handle the new shortcode format
	 *
	 * @param $atts
	 *
	 * @return mixed|void
	 */
	function fullstripe_form( $atts ) {

		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'fullstripe_form(): CALLED' );
		}

		$form_type = self::FORM_TYPE_INLINE_PAYMENT;
		$form_name = 'default';
		if ( array_key_exists( 'type', $atts ) ) {
			$form_type = $atts['type'];
		}
		if ( array_key_exists( 'name', $atts ) ) {
			$form_name = $atts['name'];
		}
        $form_type = MM_WPFS_Shortcode::normalizeShortCodeFormType($form_type);
		$form = $this->getFormByTypeAndName( $form_type, $form_name );

		ob_start();
		if ( ! is_null( $form ) ) {
			$options           = get_option( 'fullstripe_options' );
			$lock_email        = $options['lock_email_field_for_logged_in_users'];
			$email_address     = '';
			$is_user_logged_in = is_user_logged_in();
			if ( '1' == $lock_email && $is_user_logged_in ) {
				$current_user  = wp_get_current_user();
				$email_address = $current_user->user_email;
			}

			$view = null;
			if ( self::FORM_TYPE_INLINE_PAYMENT === $form_type ) {
				$view = new MM_WPFS_InlinePaymentFormView( $form );
				$view->setCurrentEmailAddress( $email_address );
			} elseif ( self::FORM_TYPE_INLINE_SUBSCRIPTION === $form_type ) {
				$stripeRecurringPrices = $this->stripe->getRecurringPrices();
				$view         = new MM_WPFS_InlineSubscriptionFormView( $form, $stripeRecurringPrices );
				$view->setCurrentEmailAddress( $email_address );
			} elseif ( self::FORM_TYPE_INLINE_SAVE_CARD === $form_type ) {
				$view = new MM_WPFS_InlineSaveCardFormView( $form );
				$view->setCurrentEmailAddress( $email_address );
            } elseif ( self::FORM_TYPE_INLINE_DONATION === $form_type ) {
                /** @noinspection PhpUnusedLocalVariableInspection */
                $view = new MM_WPFS_InlineDonationFormView( $form );
			} elseif ( self::FORM_TYPE_CHECKOUT_PAYMENT === $form_type ) {
				/** @noinspection PhpUnusedLocalVariableInspection */
				$view = new MM_WPFS_CheckoutPaymentFormView( $form );
			} elseif ( self::FORM_TYPE_CHECKOUT_SUBSCRIPTION === $form_type ) {
                $stripeRecurringPrices = $this->stripe->getRecurringPrices();
				/** @noinspection PhpUnusedLocalVariableInspection */
				$view = new MM_WPFS_CheckoutSubscriptionFormView( $form, $stripeRecurringPrices );
			} elseif ( self::FORM_TYPE_CHECKOUT_SAVE_CARD === $form_type ) {
				/** @noinspection PhpUnusedLocalVariableInspection */
				$view = new MM_WPFS_CheckoutSaveCardFormView( $form );
            } elseif ( self::FORM_TYPE_CHECKOUT_DONATION === $form_type ) {
                /** @noinspection PhpUnusedLocalVariableInspection */
                $view = new MM_WPFS_CheckoutDonationFormView( $form );
            }

			$selectedPlanId = null;
			if ( $view instanceof MM_WPFS_SubscriptionFormView ) {
				$isSimpleButtonSubscription = $view instanceof MM_WPFS_CheckoutSubscriptionFormView && 1 == $form->simpleButtonLayout;
				if ( ! $isSimpleButtonSubscription ) {
					$selectedPlanParamValue = isset( $_GET[ self::HTTP_PARAM_NAME_PLAN ] ) ? sanitize_text_field( $_GET[ self::HTTP_PARAM_NAME_PLAN ] ) : null;
					// $selectedPlanId is used in the view included below
					$selectedPlanId = apply_filters( self::FILTER_NAME_SELECT_SUBSCRIPTION_PLAN, null, $view->getFormName(), $view->getSelectedStripePlanIds(), $selectedPlanParamValue );
				}
			}

			if ( $view instanceof MM_WPFS_PaymentFormView &&
			     MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $form->customAmount
			) {
				$customAmountParamValue = isset( $_GET[ self::HTTP_PARAM_NAME_AMOUNT ] ) ? sanitize_text_field( $_GET[ self::HTTP_PARAM_NAME_AMOUNT ] ) : null;

				if ( ! empty( $customAmountParamValue ) ) {
					$customAmount = apply_filters( self::FILTER_NAME_SET_CUSTOM_AMOUNT, 0, $view->getFormName(), $customAmountParamValue );

					if ( $customAmount !== 0 ) {
						$customAmountAttributes                                          = $view->customAmount()->attributes( false );
						$customAmountAttributes[ MM_WPFS_FormViewConstants::ATTR_VALUE ] = MM_WPFS_Currencies::formatByForm( $form, $form->currency, $customAmount, false, false );
						$view->customAmount()->setAttributes( $customAmountAttributes );
					}
				}
			}

			if ( false === $this->loadScriptsAndStylesWithActionHook ) {
				$renderedForms = self::getRenderedForms()->renderLater( $form_type );
				if ( $renderedForms->getTotal() == 1 ) {
					$this->fullstripe_load_css();
					$this->fullstripe_load_js();
					$this->fullstripe_set_common_js_variables();
				}
			}

			$popupFormSubmit = null;
			if ( isset( $_GET[ MM_WPFS_CheckoutSubmissionService::STRIPE_CALLBACK_PARAM_WPFS_POPUP_FORM_SUBMIT_HASH ] ) ) {
				$submitHash = $_GET[ MM_WPFS_CheckoutSubmissionService::STRIPE_CALLBACK_PARAM_WPFS_POPUP_FORM_SUBMIT_HASH ];
				/** @noinspection PhpUnusedLocalVariableInspection */
				$popupFormSubmit = $this->checkoutSubmissionService->retrieveSubmitEntry( $submitHash );
				if ( $this->debugLog ) {
					MM_WPFS_Utils::log( 'fullstripe_form(): popupFormSubmit=' . print_r( $popupFormSubmit, true ) );
				}

				if ( isset( $popupFormSubmit ) && $popupFormSubmit->formHash === $view->getFormHash() ) {
					if (
						MM_WPFS_CheckoutSubmissionService::POPUP_FORM_SUBMIT_STATUS_CREATED === $popupFormSubmit->status
						|| MM_WPFS_CheckoutSubmissionService::POPUP_FORM_SUBMIT_STATUS_PENDING === $popupFormSubmit->status
						|| MM_WPFS_CheckoutSubmissionService::POPUP_FORM_SUBMIT_STATUS_COMPLETE === $popupFormSubmit->status
					) {
						// tnagy we do not render messages for created/complete submissions
						$popupFormSubmit = null;
					} else {
						// tnagy we set the form submission to complete, the last message will be shown when the shortcode renders
						$this->checkoutSubmissionService->updateSubmitEntryWithComplete( $popupFormSubmit );
					}
				}
			}

			/** @noinspection PhpIncludeInspection */
			include MM_WPFS_Assets::templates( 'forms/wpfs-form.php' );
		} else {
			include MM_WPFS_Assets::templates( 'forms/wpfs-form-not-found.php' );
		}

		$content = ob_get_clean();

		return apply_filters( 'fullstripe_form_output', $content );
	}

	/**
	 * Returns a form from database identified by type and name.
	 *
	 * @param $formType
	 * @param $formName
	 *
	 * @return mixed|null
	 */
	function getFormByTypeAndName( $formType, $formName ) {
		$form = null;

		if ( self::FORM_TYPE_INLINE_PAYMENT === $formType ) {
			$form = $this->database->getInlinePaymentFormByName( $formName );
		} elseif ( self::FORM_TYPE_INLINE_SUBSCRIPTION === $formType ) {
			$form = $this->database->getInlineSubscriptionFormByName( $formName );
		} elseif ( self::FORM_TYPE_INLINE_SAVE_CARD === $formType ) {
			$form = $this->database->getInlinePaymentFormByName( $formName );
        } elseif ( self::FORM_TYPE_INLINE_DONATION === $formType ) {
            $form = $this->database->getInlineDonationFormByName( $formName );
		} elseif ( self::FORM_TYPE_CHECKOUT_PAYMENT === $formType ) {
			$form = $this->database->getCheckoutPaymentFormByName( $formName );
		} elseif ( self::FORM_TYPE_CHECKOUT_SUBSCRIPTION === $formType ) {
			$form = $this->database->getCheckoutSubscriptionFormByName( $formName );
		} elseif ( self::FORM_TYPE_CHECKOUT_SAVE_CARD === $formType ) {
			$form = $this->database->getCheckoutPaymentFormByName( $formName );
        } elseif ( self::FORM_TYPE_CHECKOUT_DONATION === $formType ) {
            $form = $this->database->getCheckoutDonationFormByName( $formName );
        }

		return $form;
	}

    /**
	 * @return WPFS_RenderedFormData
	 */
	public static function getRenderedForms() {
		if ( ! array_key_exists( self::REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS, $_REQUEST ) ) {
			$_REQUEST[ self::REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS ] = new WPFS_RenderedFormData();
		}

		return $_REQUEST[ self::REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS ];
	}

	/**
	 * Register and enqueue WPFS styles
	 */
	public function fullstripe_load_css() {
		if ( $this->includeDefaultStyles ) {

			wp_register_style( self::HANDLE_STYLE_WPFS_VARIABLES, MM_WPFS_Assets::css( 'wpfs-variables.css' ), null, MM_WPFS::VERSION );
			wp_register_style( self::HANDLE_STYLE_WPFS_FORMS, MM_WPFS_Assets::css( 'wpfs-forms.css' ), array( self::HANDLE_STYLE_WPFS_VARIABLES ), MM_WPFS::VERSION );

			wp_enqueue_style( self::HANDLE_STYLE_WPFS_FORMS );
		}

		do_action( 'fullstripe_load_css_action' );
	}

	/**
	 * Register and enqueue WPFS scripts
	 */
	public function fullstripe_load_js() {
		$source = add_query_arg(
			array(
				'render' => 'explicit'
			),
			self::SOURCE_GOOGLE_RECAPTCHA_V2_API_JS
		);
		wp_register_script( self::HANDLE_GOOGLE_RECAPTCHA_V_2, $source, null, MM_WPFS::VERSION, true /* in footer */ );
		wp_register_script( self::HANDLE_SPRINTF_JS, MM_WPFS_Assets::scripts( 'sprintf.min.js' ), null, MM_WPFS::VERSION );
		wp_register_script( self::HANDLE_STRIPE_JS_V_3, 'https://js.stripe.com/v3/', array( 'jquery' ) );
		wp_register_script( self::HANDLE_WP_FULL_STRIPE_UTILS_JS, MM_WPFS_Assets::scripts( 'wpfs-utils.js' ), null, MM_WPFS::VERSION );

		wp_enqueue_script( self::HANDLE_SPRINTF_JS );
		wp_enqueue_script( self::HANDLE_STRIPE_JS_V_3 );
		wp_enqueue_script( self::HANDLE_WP_FULL_STRIPE_UTILS_JS );
		if (
			MM_WPFS_ReCaptcha::getSecureInlineForms()
			|| MM_WPFS_ReCaptcha::getSecureCheckoutForms()
		) {
			$dependencies = array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-selectmenu',
				'jquery-ui-autocomplete',
				'jquery-ui-tooltip',
				'jquery-ui-spinner',
				self::HANDLE_SPRINTF_JS,
				self::HANDLE_WP_FULL_STRIPE_UTILS_JS,
				self::HANDLE_STRIPE_JS_V_3,
				self::HANDLE_GOOGLE_RECAPTCHA_V_2
			);
		} else {
			$dependencies = array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-selectmenu',
				'jquery-ui-autocomplete',
				'jquery-ui-tooltip',
				'jquery-ui-spinner',
				self::HANDLE_SPRINTF_JS,
				self::HANDLE_WP_FULL_STRIPE_UTILS_JS,
				self::HANDLE_STRIPE_JS_V_3
			);
		}
		wp_enqueue_script( self::HANDLE_WP_FULL_STRIPE_JS, MM_WPFS_Assets::scripts( 'wpfs.js' ), $dependencies, MM_WPFS::VERSION );

		do_action( 'fullstripe_load_js_action' );
	}

	function fullstripe_set_common_js_variables() {
        $options = get_option( 'fullstripe_options' );

        $wpfsFormOptions = array(
            self::JS_VARIABLE_AJAX_URL                      => admin_url( 'admin-ajax.php' ),
            self::JS_VARIABLE_GOOGLE_RECAPTCHA_SITE_KEY     => MM_WPFS_ReCaptcha::getSiteKey(),
            self::JS_VARIABLE_FORM_FIELDS                   => array(
                'inlinePayment'         => MM_WPFS_InlinePaymentFormView::getFields(),
                'inlineSaveCard'        => MM_WPFS_InlineSaveCardFormView::getFields(),
                'inlineSubscription'    => MM_WPFS_InlineSubscriptionFormView::getFields(),
                'inlineDonation'        => MM_WPFS_InlineDonationFormView::getFields(),
                'checkoutPayment'       => MM_WPFS_CheckoutPaymentFormView::getFields(),
                'checkoutSaveCard'      => MM_WPFS_CheckoutSaveCardFormView::getFields(),
                'checkoutSubscription'  => MM_WPFS_CheckoutSubscriptionFormView::getFields(),
                'checkoutDonation'      => MM_WPFS_CheckoutDonationFormView::getFields(),
            ),
            self::JS_VARIABLE_L10N                          => array(
                'validation_errors'                      => array(
                    'internal_error'                         =>
                    /* translators: Banner message of internal error when no error message is returned by the application */
                        __( 'An internal error occurred.', 'wp-full-stripe' ),
                    'internal_error_title'                   =>
                    /* translators: Banner title of internal error */
                        __( 'Internal Error', 'wp-full-stripe' ),
                    'mandatory_field_is_empty'               =>
                    /* translators: Error message for required fields when empty.
                     * p1: custom input field label
                     */
                        __( "Please enter a value for '%s'", 'wp-full-stripe' ),
                    'custom_payment_amount_value_is_invalid' =>
                    /* translators: Field validation error message when payment amount is empty or invalid */
                        __( 'Payment amount is invalid', 'wp-full-stripe' ),
                    'invalid_payment_amount'                 =>
                    /* translators: Banner message when the payment amount cannot be determined (the form has been tampered with) */
                        __( 'Cannot determine payment amount', 'wp-full-stripe' ),
                    'invalid_payment_amount_title'           =>
                    /* translators: Banner title when the payment amount cannot be determined (the form has been tampered with) */
                        __( 'Invalid payment amount', 'wp-full-stripe' )
                ),
                'stripe_errors'                          => array(
                    MM_WPFS_Stripe::INVALID_NUMBER_ERROR               => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_NUMBER_ERROR ),
                    MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_MONTH     => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_MONTH ),
                    MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_YEAR      => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_YEAR ),
                    MM_WPFS_Stripe::INVALID_EXPIRY_MONTH_ERROR         => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_EXPIRY_MONTH_ERROR ),
                    MM_WPFS_Stripe::INVALID_EXPIRY_YEAR_ERROR          => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_EXPIRY_YEAR_ERROR ),
                    MM_WPFS_Stripe::INVALID_CVC_ERROR                  => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_CVC_ERROR ),
                    MM_WPFS_Stripe::INCORRECT_NUMBER_ERROR             => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INCORRECT_NUMBER_ERROR ),
                    MM_WPFS_Stripe::EXPIRED_CARD_ERROR                 => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::EXPIRED_CARD_ERROR ),
                    MM_WPFS_Stripe::INCORRECT_CVC_ERROR                => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INCORRECT_CVC_ERROR ),
                    MM_WPFS_Stripe::INCORRECT_ZIP_ERROR                => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INCORRECT_ZIP_ERROR ),
                    MM_WPFS_Stripe::CARD_DECLINED_ERROR                => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::CARD_DECLINED_ERROR ),
                    MM_WPFS_Stripe::MISSING_ERROR                      => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::MISSING_ERROR ),
                    MM_WPFS_Stripe::PROCESSING_ERROR                   => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::PROCESSING_ERROR ),
                    MM_WPFS_Stripe::MISSING_PAYMENT_INFORMATION        => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::MISSING_PAYMENT_INFORMATION ),
                    MM_WPFS_Stripe::COULD_NOT_FIND_PAYMENT_INFORMATION => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::COULD_NOT_FIND_PAYMENT_INFORMATION )
                ),
                'subscription_charge_interval_templates' => array(
                    'daily'            => __( 'Subscription will be charged every day.', 'wp-full-stripe' ),
                    'weekly'           => __( 'Subscription will be charged every week.', 'wp-full-stripe' ),
                    'monthly'          => __( 'Subscription will be charged every month.', 'wp-full-stripe' ),
                    'yearly'           => __( 'Subscription will be charged every year.', 'wp-full-stripe' ),
                    'y_days'           => __( 'Subscription will be charged every %d days.', 'wp-full-stripe' ),
                    'y_weeks'          => __( 'Subscription will be charged every %d weeks.', 'wp-full-stripe' ),
                    'y_months'         => __( 'Subscription will be charged every %d months.', 'wp-full-stripe' ),
                    'y_years'          => __( 'Subscription will be charged every %d years.', 'wp-full-stripe' ),
                    'x_times_daily'    => __( 'Subscription will be charged every day, for %d occasions.', 'wp-full-stripe' ),
                    'x_times_weekly'   => __( 'Subscription will be charged every week, for %d occasions.', 'wp-full-stripe' ),
                    'x_times_monthly'  => __( 'Subscription will be charged every month, for %d occasions.', 'wp-full-stripe' ),
                    'x_times_yearly'   => __( 'Subscription will be charged every year, for %d occasions.', 'wp-full-stripe' ),
                    'x_times_y_days'   => __( 'Subscription will be charged every %1$d days, for %2$d occasions.', 'wp-full-stripe' ),
                    'x_times_y_weeks'  => __( 'Subscription will be charged every %1$d weeks, for %2$d occasions.', 'wp-full-stripe' ),
                    'x_times_y_months' => __( 'Subscription will be charged every %1$d months, for %2$d occasions.', 'wp-full-stripe' ),
                    'x_times_y_years'  => __( 'Subscription will be charged every %1$d years, for %2$d occasions.', 'wp-full-stripe' ),
                ),
	            'products' => array(
	            	'default_product_name' => __( 'My product', 'wp-full-stripe'),
                    'other_amount_label'   => __('Other amount', 'wp-full-stripe'),
	            )
            )
        );
        if ( $options['apiMode'] === 'test' ) {
            $wpfsFormOptions[ self::JS_VARIABLE_STRIPE_KEY ] = $options['publishKey_test'];
        } else {
            $wpfsFormOptions[ self::JS_VARIABLE_STRIPE_KEY ] = $options['publishKey_live'];
        }

        wp_localize_script( self::HANDLE_WP_FULL_STRIPE_JS, self::JS_VARIABLE_WPFS_FORM_OPTIONS, $wpfsFormOptions );
	}

	function fullstripe_wp_head() {
		//output the custom css
		$options = get_option( 'fullstripe_options' );
		echo '<style type="text/css" media="screen">' . $options[MM_WPFS::OPTION_FORM_CUSTOM_CSS] . '</style>';
	}

	/**
	 * Register and enqueue styles and scripts to load for this addon
	 */
	public function fullstripe_enqueue_scripts_and_styles() {
		if ( $this->loadScriptsAndStylesWithActionHook ) {
			global $wp;
			if ( $this->debugLog ) {
				MM_WPFS_Utils::log( 'fullstripe_enqueue_scripts_and_styles(): CALLED, wp=' . print_r( $wp, true ) );
			}
			if ( ! is_null( $wp ) && isset( $wp->request ) ) {
				$pageByPath = get_page_by_path( $wp->request );
				if ( ! is_null( $pageByPath ) && isset( $pageByPath->post_content ) ) {
					if (
						has_shortcode( $pageByPath->post_content, self::SHORTCODE_FULLSTRIPE_FORM )
					) {
						$this->fullstripe_load_css();
						$this->fullstripe_load_js();
						$this->fullstripe_set_common_js_variables();
					}
				}
			}
		}
	}

    /**
	 * @return MM_WPFS_Admin_Menu
	 */
	public function getAdminMenu() {
		return $this->adminMenu;
	}

    /**
	 * @return MM_WPFS_Admin
	 */
	public function getAdmin() {
		return $this->admin;
	}
}

class WPFS_RenderedFormData {

	private $inlinePayments = 0;
	private $inlineSubscriptions = 0;
	private $checkoutPayments = 0;
	private $checkoutSubscriptions = 0;
	private $inlineDonations = 0;
    private $checkoutDonations = 0;

	public function renderLater($type ) {
		if ( MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION === $type ) {
			$this->checkoutSubscriptions += 1;
        } elseif ( MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION === $type ) {
            $this->inlineSubscriptions += 1;
        } elseif ( MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT === $type ) {
            $this->checkoutPayments += 1;
		} elseif ( MM_WPFS::FORM_TYPE_INLINE_PAYMENT === $type ) {
			$this->inlinePayments += 1;
        } elseif ( MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD === $type ) {
            $this->checkoutPayments += 1;
		} elseif ( MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD === $type ) {
			$this->inlinePayments += 1;
		} elseif ( MM_WPFS::FORM_TYPE_CHECKOUT_DONATION === $type ) {
            $this->checkoutDonations += 1;
        } elseif ( MM_WPFS::FORM_TYPE_INLINE_DONATION === $type ) {
            $this->inlineDonations += 1;
        }

		return $this;
	}

	/**
	 * @return int
	 */
	public function getInlinePayments() {
		return $this->inlinePayments;
	}

	/**
	 * @return int
	 */
	public function getInlineSubscriptions() {
		return $this->inlineSubscriptions;
	}

	/**
	 * @return int
	 */
	public function getCheckoutPayments() {
		return $this->checkoutPayments;
	}

	/**
	 * @return int
	 */
	public function getCheckoutSubscriptions() {
		return $this->checkoutSubscriptions;
	}

    /**
     * @return int
     */
    public function getInlineDonations() {
        return $this->inlineDonations;
    }

    /**
     * @return int
     */
    public function getCheckoutDonations() {
        return $this->checkoutDonations;
    }

    /**
	 * @return int
	 */
	public function getTotal() {
		return $this->inlinePayments + $this->inlineSubscriptions + $this->checkoutPayments + $this->checkoutSubscriptions + $this->inlineDonations + $this->checkoutDonations;
	}

}

/**
 * Class WPFS_UserFriendlyException
 *
 * This exception can be thrown in action and event hooks, and it's content (title, message)
 * will be displayed as a global message above the form which has invoked it.
 */
class WPFS_UserFriendlyException extends Exception {
    protected $_title;

    /**
     * WPFS_UserFriendlyException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct( $message, $code, $previous );
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) {
        $this->_title = $title;
    }
}


MM_WPFS::getInstance();
