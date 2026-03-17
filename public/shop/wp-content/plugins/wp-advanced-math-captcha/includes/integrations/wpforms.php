<?php
/**
 * Backend: add [cf7ic-demo1] shortcode above submit button on WPForms forms
 */
function ai1ic_add_to_wpforms_dup()
{
    echo apply_shortcodes('[cf7ic-demo1]');
}

add_action('wpforms_display_submit_before', 'ai1ic_add_to_wpforms_dup', 30);


/**
 * Backend: WPForms image CAPTCHA validation
 * @link https://wpforms.com/developers/how-to-add-coupon-code-field-validation-on-your-forms/
 */
function ai1ic_wpforms_validation_dup($entry, $form_data)
{	

	echo 13; die;
}

function cf7ic_wpforms_check_enabled()
{

    add_action('wpforms_process_before', 'ai1ic_wpforms_validation_dup', 10, 2);
}
add_action('init', 'cf7ic_wpforms_check_enabled');

/**
 * Frontend: WPForms [cf7ic-demo1] shortcode
 */
add_shortcode('cf7ic-demo1', 'call_cf7ic_pro_dup');
function call_cf7ic_pro_dup($tag)
{

    return '';
}
