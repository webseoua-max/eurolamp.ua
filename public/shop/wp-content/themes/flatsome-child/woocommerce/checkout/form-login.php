<?php
/**
 * Checkout login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	return;
}

?>
<style>
	.message-login {
		display: inline-flex;
		align-items: center;
		/*margin-bottom: 10px;
		border-radius: 4px;
		border: 2px solid #8cbe22;
		padding: 0.5em;*/

	}
	.message-login svg {
		margin-right: 4px;
		transform: translateY(-2px);
	}
	.message-login a {
		display: inline-block;
		margin-left: 4px;
		padding: 4px 8px;
		border: 1px solid #3a550d;
		color: #3a550d;
		transition: 0.2s;
	}
	.message-login a:hover {
		border-color: #8cbe22;
		color: #8cbe22;
	}
</style>
<div class="woocommerce-form-login-toggle" data-file="form-login">
	<p class="woocommerce-info message-wrapper message-login">
		<svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none">
			<path d="M19 5L5 19M17.5 20C19.1667 20 20 19.1429 20 17C20 14.8571 19.1667 14 17.5 14C15.8333 14 15 14.8571 15 17C15 19.1429 15.8333 20 17.5 20ZM6.5 10C8.16667 10 9 9.14286 9 7C9 4.85714 8.16667 4 6.5 4C4.83333 4 4 4.85714 4 7C4 9.14286 4.83333 10 6.5 10Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
		<?php pll_e( 'Увійдіть для нарахування знижки. ') ?>
		<a href="#" class="showlogin"><?php pll_e( 'вхід в акаунт') ?></a>
	</p>
	<!-- <?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Returning customer?', 'woocommerce' ) ) . ' <a href="#" class="showlogin">' . esc_html__( 'Click here to login', 'woocommerce' ) . '</a>', 'notice' ); ?> -->
</div>
<?php

woocommerce_login_form(
	array(
		'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce' ),
		'redirect' => wc_get_checkout_url(),
		'hidden'   => true,
	)
);
