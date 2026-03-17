<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly ?>

<?php if ($this->wp_version_is_compatible()): ?>
	<form class="chaport-form" action='options.php' method='POST'>
		<?php settings_fields('chaport_options') ?>
		<?php do_settings_sections('chaport') ?>
		<p class='submit'>
			<input type='submit' name='submit' id='submit' class='button button-primary' value='<?php echo esc_html__('Save Changes', 'chaport') ?>' />
		</p>
	</form>
<?php else: ?>
	<p>
		<?php
			printf(
				// translators: %s: The minimum required WordPress version.
				esc_html__('Chaport Live Chat Plugin supports Wordpress starting from version %s. Please upgrade.', 'chaport'),
				esc_html(self::WP_MAJOR . '.' . self::WP_MINOR)
			)
		?>
	</p>
<?php endif; ?>
