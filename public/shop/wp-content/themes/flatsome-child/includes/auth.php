<?php
function auth_woocomerce_by_phone($user, $username, $password) {

  if (ctype_digit($username)) {

    $matchingUsers = get_users(array(
        'meta_key'     => 'billing_phone',
        'meta_value'   => $username,
        'meta_compare' => '='
    ));

    if (!empty($matchingUsers) && is_array($matchingUsers)) {
        $firstMatchingUser = reset($matchingUsers);
        $username = $firstMatchingUser->user_login;
    } else {
        return new WP_Error('invalid_phone', __('No user found with this phone number.', 'woocommerce'));
    }

    // Proceed with the normal authentication using the matched user's login name
    return wp_authenticate_username_password(null, $username, $password);

  } else {
      return $user;
  }
}
add_filter('authenticate', 'auth_woocomerce_by_phone', 20, 3);

function change_strength_woocommerce_password( $strength ) {
    return 1;
}
add_filter( 'woocommerce_min_password_strength', 'change_strength_woocommerce_password' );
?>