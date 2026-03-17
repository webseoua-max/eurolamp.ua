<?php
/**
 * Display the Simulation tab.
 * 
 * @version    4.0.3 (17-06-2025)
 * @package    XFGMC
 * @subpackage XFGMC/admin/partials/debug_page/
 * 
 * @param $view_arr['simulated_post_id']
 * @param $view_arr['feed_id']
 * @param $view_arr['simulation_result_report']
 * @param $view_arr['simulation_result']
 */
defined( 'ABSPATH' ) || exit; ?>
<div class="postbox">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="xfgmc_simulated_post_id">Product ID</label></th>
				<td class="overalldesc">
					<input type="number" min="1" name="xfgmc_simulated_post_id"
						value="<?php echo esc_attr( $view_arr['simulated_post_id'] ); ?>">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="xfgmc_feed_id">Feed ID</label></th>
				<td class="overalldesc">
					<select style="width: 100%" name="xfgmc_feed_id" id="xfgmc_feed_id">
						<?php
						if ( is_multisite() ) {
							$cur_blog_id = get_current_blog_id();
						} else {
							$cur_blog_id = '0';
						}
						if ( isset( $_POST['xfgmc_feed_id'] ) ) {
							$cur_feed_id = sanitize_text_field( $_POST['xfgmc_feed_id'] );
						} else {
							$cur_feed_id = '1';
						}
						$xfgmc_settings_arr = univ_option_get( 'xfgmc_settings_arr' );
						$xfgmc_settings_arr_keys_arr = array_keys( $xfgmc_settings_arr );
						for ( $i = 0; $i < count( $xfgmc_settings_arr_keys_arr ); $i++ ) {
							$feed_id = (string) $xfgmc_settings_arr_keys_arr[ $i ];
							if ( $xfgmc_settings_arr[ $feed_id ]['xfgmc_feed_assignment'] === '' ) {
								$feed_assignment = '';
							} else {
								$feed_assignment = sprintf( ' (%s)',
									$xfgmc_settings_arr[ $feed_id ]['xfgmc_feed_assignment']
								);
							}
							printf( '<option value="%s" %s>%s %s: feed-xml-%s.xml%s</option>',
								esc_attr( $feed_id ),
								selected( $cur_feed_id, $feed_id, false ),
								esc_html__( 'Feed', 'xml-for-google-merchant-center' ),
								esc_html( $feed_id ),
								esc_html( $cur_blog_id ),
								esc_html( $feed_assignment )
							);
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row" colspan="2">
					<textarea style="width: 100%;" rows="4"><?php
					echo htmlspecialchars( $view_arr['simulation_result_report'] );
					?></textarea>
				</th>
			</tr>
			<tr>
				<th scope="row" colspan="2">
					<textarea rows="16" style="width: 100%;"><?php
					echo htmlspecialchars( $view_arr['simulation_result'] );
					?></textarea>
				</th>
			</tr>
		</tbody>
	</table>
</div>