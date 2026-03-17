<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly ?>

<div class='chaport-status-box <?php echo esc_attr($status_class) ?>'>
	<b><?php echo esc_html__('Status:', 'chaport') ?></b>
	<span><?php echo esc_html($status_message) ?></span>
</div>
<p id="chaport-paste-code">
	<?php if ($status_class != 'chaport-status-ok') {
		printf(
			wp_kses(
				// translators: %s: URL to installation code in Chaport
				__( 'Please fill in the field below. You can find the relevant information at <a href="%s" target="_blank">Settings -> Installation code</a> in Chaport app.', 'chaport' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			),
			esc_url( 'https://app.chaport.com/#/settings/installation_code' )
		);
	} ?>
</p>
