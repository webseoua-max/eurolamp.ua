<?php
if (!class_exists('ProductFeed_Feature_Request')) :

    /**
     * Class for catch Feedback on feature request
     */
    class ProductFeed_Feature_Request {

        public function __construct() {
            add_action('admin_footer', array($this, 'feedback_request_scripts'));
            add_action('wp_ajax_productfeed_submit_feature_request', array($this, "send_feedback_request"));
        }

        public function feedback_request_scripts() {

            if (!Webtoffee_Product_Feed_Sync_Pro_Common_Helper::wt_is_screen_allowed()) {
                return;
            }
            ?>
            <div class="productfeed-modal" id="productfeed-productfeed-modal">
                <div class="productfeed-modal-wrap">
                    <div class="productfeed-modal-header" style="text-align: center">
                        <h2 style="margin-bottom:5px;"><?php esc_html_e( 'Missing a feature?', 'webtoffee-product-feed-pro' ); ?></h2>
						<span><?php esc_html_e( 'Drop a message to let us know!', 'webtoffee-product-feed-pro' ); ?></span>
                    </div>
                    <div class="productfeed-modal-body">
						<div class="reasons">
							<label><h4 style="margin-bottom:5px;"><?php esc_html_e( 'Which channel would you like us to add next? ', 'webtoffee-product-feed-pro'); ?></h4></label>
						<input style="width:75%;" type="text" class="input-text" name="channel-suggestion" id="channel-suggestion" placeholder="Eg:- Pinterest" /><br/>
						<label><h4 style="margin-bottom:2px;"><?php _e('What would you like to add as a new feature?', 'webtoffee-product-feed-pro'); ?></h4><span><?php esc_html_e( 'More the details you share, the better.', 'webtoffee-product-feed-pro' ); ?></span><br/></label>					
						<textarea name="suggestion-message" id="suggestion-message" style="margin-top:5px;"  rows="5" cols="55" placeholder="I would like..." spellcheck="false"></textarea>
						<br/><br/>
						<span><i><?php esc_html_e( 'We do not collect any personal data when you submit this form. It\'s your feedback that we value.', 'webtoffee-product-feed-pro'); ?> <a target="_blank" href="https://www.webtoffee.com/privacy-policy/"><?php esc_html_e( 'Privacy policy', 'webtoffee-product-feed-pro' ); ?></a></i></span>
						</div>
                    </div>
                    <div class="productfeed-modal-footer">                        
						<button class="button-secondary productfeed-model-cancel"><?php esc_html_e('Cancel', 'webtoffee-product-feed-pro'); ?></button>						
                        <button class="button-primary productfeed-modal-submit"><?php esc_html_e('Send feature request', 'webtoffee-product-feed-pro'); ?></button>                        
                    </div>
                </div>
            </div>

            <div class="productfeed-modal-success" id="productfeed-productfeed-modal-success">
                <div class="productfeed-modal-wrap-success">
                    <div class="productfeed-modal-header-success" style="text-align: right;cursor: pointer;">                        
						<img class="productfeed-model-cancel" src="<?php echo esc_url(WT_PRODUCT_FEED_PRO_PLUGIN_URL.'/assets/images/feature-close.svg'); ?>" alt="alt"/>
                    </div>
                    <div class="productfeed-modal-body-success">
						<img src="<?php echo esc_url(WT_PRODUCT_FEED_PRO_PLUGIN_URL.'/assets/images/feature-submission.svg'); ?>" alt="alt"/>
						<p></p>
						<div class="feature-success-msg">
						<span><?php esc_html_e( 'Thank you! Your request has been submitted successfully.',  'webtoffee-product-feed-pro' );?></span>
						<p></p>
						<br/><span><i><?php esc_html_e( 'Disclaimer : Features will be implemented based on priority/number of requests. Thanks for understanding!',  'webtoffee-product-feed-pro' );?></i></span>
						</div>
                    </div>
                    <div class="productfeed-modal-footer-success">                        											
                        <button class="button-primary productfeed-model-cancel"><?php esc_html_e( 'Close', 'webtoffee-product-feed-pro'); ?></button>                        
                    </div>
                </div>
            </div>


            <style type="text/css">
                .productfeed-modal {
                    position: fixed;
                    z-index: 99999;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    background: rgba(0,0,0,0.5);
                    display: none;
                }
                .productfeed-modal.modal-active {display: block;}
                .productfeed-modal-wrap {
                    width: 40%;
                    position: relative;
                    margin: 10% auto;
                    background: #fff;
                }
                .productfeed-modal-header {
                    border-bottom: 1px solid #eee;
                    padding: 12px 20px;
                }
                .productfeed-modal-header h3 {
                    line-height: 150%;
                    margin: 0;
                }
                .productfeed-modal-body {padding: 30px;background-color:#F5FAFE;}
                .productfeed-modal-body .input-text,.productfeed-modal-body textarea {width:85%;}
                .productfeed-modal-body .reason-input {
                    margin-top: 5px;
                    margin-left: 20px;
                }
                .productfeed-modal-footer {
                    border-top: 1px solid #eee;
                    padding: 12px 20px;
                    text-align: right;
                }
				
								
				.productfeed-modal-success {
                    position: fixed;
                    z-index: 99999;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    background: rgba(0,0,0,0.5);
                    display: none;
                }
                .productfeed-modal-success.modal-active {display: block;}
                .productfeed-modal-wrap-success {
                    width: 35%;
                    position: relative;
                    margin: 10% auto;
                    background: #fff;
                }
                .productfeed-modal-header-success {
                    padding: 0px;
                }
                .productfeed-modal-header-success h3 {
                    line-height: 150%;
                    margin: 0;
                }
                .productfeed-modal-body-success {padding: 30px;text-align: center;}
                .productfeed-modal-body-success .input-text,.productfeed-modal-body-success textarea {width:75%;}
                .productfeed-modal-body-success .reason-input {
                    margin-top: 5px;
                    margin-left: 20px;
                }
                .productfeed-modal-footer-success {
                    padding: 12px 20px;
                    text-align: center;
                }
				
            </style>
            <script type="text/javascript">
                (function ($) {
                    $(function () {
                        var modal = $('#productfeed-productfeed-modal');
						var successmodal = $('#productfeed-productfeed-modal-success');
                        $('.wt_pf_page_hd').on('click', 'a.productfeed-feature-link', function (e) {
                            e.preventDefault();
                            modal.addClass('modal-active');                         
                        });
                        modal.on('click', '.productfeed-model-cancel', function (e) {
                            e.preventDefault();
                            modal.removeClass('modal-active');
                        });
						
						successmodal.on('click', 'button.productfeed-model-cancel', function (e) {
                            e.preventDefault();
                            successmodal.removeClass('modal-active');
                        });
						successmodal.on('click', '.productfeed-model-cancel', function (e) {
                            e.preventDefault();
                            successmodal.removeClass('modal-active');
                        });


                        modal.on('click', 'button.productfeed-modal-submit', function (e) {
                            e.preventDefault();
                            var button = $(this);
                            if (button.hasClass('disabled')) {
                                return;
                            }
                            var $channelsuggestion = $('#channel-suggestion', modal);
							var $suggestionmessage = $('#suggestion-message', modal);
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'productfeed_submit_feature_request',
                                    reason_id: (0 === $channelsuggestion.length) ? 'none' : $channelsuggestion.val(),
                                    reason_info: (0 !== $suggestionmessage.length) ? $suggestionmessage.val().trim() : ''
                                },
                                beforeSend: function () {
                                    button.addClass('disabled');
                                    button.text(wt_pf_basic_params.msgs.sending_req);
                                },
                                complete: function () {
									
									button.removeClass('disabled');
                                                                        button.text(wt_pf_basic_params.msgs.send_req);
									// Show success popup
									$('#productfeed-productfeed-modal').removeClass('modal-active');
									var modal = $('#productfeed-productfeed-modal-success');
									modal.addClass('modal-active'); 
                                }
                            });
                        });
                    });
                }(jQuery));
            </script>
            <?php
        }

        public function send_feedback_request() {

            global $wpdb;

            if (!isset($_POST['reason_id'])) {
                wp_send_json_error();
            }

            $data = array(
                'reason_id' => sanitize_text_field($_POST['reason_id']),
                'plugin' => "product_feed",
                'auth' => 'productfeed_feature_1234#',
                'date' => gmdate("M d, Y h:i:s A"),
                'url' => '',
                'user_email' => '',
                'msg' => isset($_REQUEST['reason_info']) ? trim(stripslashes($_REQUEST['reason_info'])) : '',
                'software' => $_SERVER['SERVER_SOFTWARE'],
                'php_version' => phpversion(),
                'mysql_version' => $wpdb->db_version(),
                'wp_version' => get_bloginfo('version'),
                'wc_version' => (!defined('WC_VERSION')) ? '' : WC_VERSION,
                'locale' => get_locale(),
                'multisite' => is_multisite() ? 'Yes' : 'No',
                'plugin_version' => WEBTOFFEE_PRODUCT_FEED_PRO_SYNC_VERSION,                
            );
			
            // Write an action/hook here in webtoffe to recieve the data
            $resp = wp_remote_post('https://feedback.webtoffee.com/wp-json/feature-suggestion/v1', array(
                        'method' => 'POST',
                        'timeout' => 45,
                        'redirection' => 5,
                        'httpversion' => '1.0',
                        'blocking' => false,
                        'body' => $data,
                        'cookies' => array(),
                    )
                );

            wp_send_json_success();
        }

    }
    new ProductFeed_Feature_Request();

endif;