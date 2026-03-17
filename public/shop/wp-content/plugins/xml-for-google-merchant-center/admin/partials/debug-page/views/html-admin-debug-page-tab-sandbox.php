<?php
/**
 * Display the Sandbox tab.
 * 
 * @version    4.0.0 (02-06-2025)
 * @package    XFGMC
 * @subpackage XFGMC/admin/partials/debug_page/
 */
defined( 'ABSPATH' ) || exit; ?>
<div class="postbox">
	<div class="inside">
		<?php
		try {
			xfgmc_run_sandbox();
		} catch (Exception $e) {
			echo 'Exception: ', $e->getMessage(), "\n";
		}
		?>
	</div>
</div>