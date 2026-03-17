<?php
/**
 * Custom Languages dropdown.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

$current_lang = 'Languages';
$flag         = null;
$languages    = null;

// Polylang elseif WMPL.
if ( function_exists( 'pll_the_languages' ) ) {
	$languages = pll_the_languages( array( 'raw' => 1 ) );
	foreach ( $languages as $lang ) {
		if ( $lang['current_lang'] ) {
			$flag         = '<i class="image-icon"><img src="' . $lang['flag'] . '" alt="' . $lang['name'] . '"/></i>';
			$current_lang = $lang['name'];
		}
	}
} elseif ( function_exists( 'icl_get_languages' ) ) {
	$languages = icl_get_languages();
	foreach ( $languages as $lang ) {
		if ( $lang['active'] ) {
			$flag         = '<i class="image-icon"><img src="' . $lang['country_flag_url'] . '" alt="' . $lang['native_name'] . '"/></i>';
			$current_lang = $lang['native_name'];
		}
	}
}
?>
<ul class="lang" id="top-lang">
  <li class="pll-parent-menu-item">
    <ul class="sub-menu">
     <?php if(function_exists('pll_the_languages')){ 
        pll_the_languages(array('display_names_as' => 'name', 'hide_current' => 1, 'dropdown' => 0 )); 
     } ?> 
    </ul>
  </li>
</ul>