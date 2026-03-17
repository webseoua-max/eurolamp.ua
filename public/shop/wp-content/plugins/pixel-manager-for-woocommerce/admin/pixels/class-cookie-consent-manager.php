<?php
/**
 * Name: Cookie Consent Management
 * Version:  1.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
class PMW_CookieConsentManagement {
  public $plugin_prefix;
  // check if third party cookie prevention is active
  public function is_cookie_prevention_active(){
    $cookie_prevention = false;
    // later, turn it off in order to allow cookies in case they have been actively approved
    $cookie_prevention = apply_filters_deprecated('wgact_cookie_prevention', [$cookie_prevention], '1.10.4', 'pmw_cookie_prevention');
    $cookie_prevention = apply_filters('pmw_cookie_prevention', $cookie_prevention);
    // check if the Moove third party cookie prevention is on
    if ($this->is_moove_cookie_prevention_active()) {
        $cookie_prevention = true;
    }
    // check if the Cookie Notice Plugin third party cookie prevention is on
    if ($this->is_cookie_notice_plugin_cookie_prevention_active()) {
        $cookie_prevention = true;
    }
    // check if the Cookie Law Info third party cookie prevention is on
    if ($this->is_cookie_law_info_cookie_prevention_active()) {
        $cookie_prevention = true;
    }
    // check if marketing cookies have been approved by Borlabs
    if ($this->check_borlabs_gave_marketing_consent()) {
        $cookie_prevention = false;
    }
    return $cookie_prevention;
  }

  public function check_borlabs_gave_marketing_consent() {
    // check if Borlabs is running
    if (function_exists('BorlabsCookieHelper')) {
      $get_plugins = get_plugins();
      $borlabs_info = $get_plugins["borlabs-cookie/borlabs-cookie.php"];
      // check if Borlabs minimum version is installed
      // the minimum version I know of that supports gaveConsent('marketing') is 2.2.4
      if (version_compare('2.1.0', $borlabs_info['Version'], '<=')) {
        if (BorlabsCookieHelper()->gaveConsent('google-ads') || BorlabsCookieHelper()->gaveConsent('pixel-manager-for-woocommerce')) {
            return true;
        }
      }
    }
    return false;
  }

  public function set_plugin_prefix($name) {
      $this->plugin_prefix = $name;
  }

  // return the cookie contents, if the cookie is set
  public function get_cookie($cookie_name) {
      return isset($_cookie[$cookie_name])?sanitize_text_field($_cookie[$cookie_name]):null;
  }

  // check if the Cookie Law Info plugin prevents third party cookies
  // /cookie-law-info/
  public function is_cookie_law_info_cookie_prevention_active() {
    $cookie_consent_management_cookie = $this->get_cookie('cookielawinfo-checkbox-non-necessary');
    if ($cookie_consent_management_cookie == 'no') {
        return true;
    } else {
        return false;
    }
  }

  // check if the Cookie Notice Plugin prevents third party cookies
  // /cookie-notice/
  public function is_cookie_notice_plugin_cookie_prevention_active() {
    $cookie_consent_management_cookie = $this->get_cookie('cookie_notice_accepted');
    if ($cookie_consent_management_cookie == 'false') {
        return true;
    } else {
        return false;
    }
  }

  // check if the Moove GDPR Cookie Compliance prevents third party cookies
  // /gdpr-cookie-compliance/
  public function is_moove_cookie_prevention_active() {
    if (isset($_COOKIE['moove_gdpr_popup'])) {
      $cookie_consent_management_cookie = sanitize_text_field($_COOKIE['moove_gdpr_popup']);
      $cookie_consent_management_cookie = json_decode(stripslashes($cookie_consent_management_cookie), true);
      if ( is_array($cookie_consent_management_cookie) && !empty($cookie_consent_management_cookie) && array_key_exists('thirdparty', $cookie_consent_management_cookie) && $cookie_consent_management_cookie['thirdparty'] == 0) {
          return true;
      } else {
          return false;
      }
    } else {
        return false;
    }
  }
}