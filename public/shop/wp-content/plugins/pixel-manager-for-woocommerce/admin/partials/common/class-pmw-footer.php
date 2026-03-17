<?php
/**
 * @since      1.0.0
 * Description: Footer
 */
if ( ! class_exists( 'PMW_Footer' ) ) {
	class PMW_Footer {	
		public function __construct( ){
			add_action('pmw_footer',array($this, 'before_end_footer'));
		}	
		public function before_end_footer(){ 
							if(isset($_GET['page']) && sanitize_text_field($_GET['page'] != "pixel-manager-documentation") ){
								?>
								<div class="pmw_rate_us_footer">
									<a class="pmw-rate-us" href="<?php echo esc_url_raw("https://wordpress.org/support/plugin/pixel-manager-for-woocommerce/reviews/"); ?>" target="_blank"><?php echo esc_attr__('Happy to use it! Share your experience with this tool.', 'pixel-manager-for-woocommerce'); ?><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/rate-us.png"); ?>" alt="rate-us" /></a>
								</div>
							<?php } ?>
							</div>							
						</div>
					</section>
				</main>
				<div id="pmw_form_message" class="toaster-bottom"></div>
			</div>
			<?php
		}
	}
}
new PMW_Footer();