<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

class MM_WPFS_ReCaptcha {
    const URL_RECAPTCHA_API_SITEVERIFY = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @param $googleReCAPTCHAResponse
     *
     * @return array|bool|mixed|object|WP_Error
     */
    public static function verifyReCAPTCHA( $googleReCAPTCHAResponse ) {
        $googleReCAPTCHASecretKey = self::getSecretKey();

        if (!is_null($googleReCAPTCHASecretKey) && !is_null($googleReCAPTCHAResponse)) {
            $inputArray = array(
                'secret' => $googleReCAPTCHASecretKey,
                'response' => $googleReCAPTCHAResponse,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            );
            $request = wp_remote_post(
                self::URL_RECAPTCHA_API_SITEVERIFY,
                array(
                    'timeout' => 10,
                    'sslverify' => true,
                    'body' => $inputArray
                )
            );
            if (!is_wp_error($request)) {
                $request = json_decode(wp_remote_retrieve_body($request));

                return $request;
            } else {
                return false;
            }
        }

        return false;
    }

    public static function getSecretKey() {
        $googleReCAPTCHASecretKey = null;
        $options = get_option('fullstripe_options');

        if (array_key_exists(MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY, $options)) {
            $googleReCAPTCHASecretKey = $options[MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY];
        }

        return $googleReCAPTCHASecretKey;
    }

    public static function getSiteKey() {
        $googleReCAPTCHASiteKey = null;
        $options = get_option('fullstripe_options');

        if (array_key_exists(MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY, $options)) {
            $googleReCAPTCHASiteKey = $options[MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY];
        }

        return $googleReCAPTCHASiteKey;
    }

    public static function getSecureInlineForms() {
        $options = get_option('fullstripe_options');

        if (array_key_exists(MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA, $options)) {
            return $options[MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA] == '1';
        }

        return false;
    }

    public static function getSecureCheckoutForms() {
        $options = get_option('fullstripe_options');

        if (array_key_exists(MM_WPFS::OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA, $options)) {
            return $options[MM_WPFS::OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA] == '1';
        }

        return false;
    }

    public static function getSecureCustomerPortal() {
        $options = get_option('fullstripe_options');

        if (array_key_exists(MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA, $options)) {
            return $options[MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA] == '1';
        }

        return false;
    }
}
