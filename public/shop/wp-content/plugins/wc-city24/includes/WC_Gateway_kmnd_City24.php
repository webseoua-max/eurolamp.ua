<?php

/** payment gateway
 *  class WC_Gateway_kmnd_City24
 */

class WC_Gateway_kmnd_City24 extends WC_Payment_Gateway {

    public function __construct() {

            global $woocommerce;
            $this->id = 'city24';
            $this->has_fields = false;
            $this->method_title = 'city24';
            $this->method_description = __('Payment system City24', 'wc-city24');
            $this->init_form_fields();
            $this->connection_status = $this->get_option('connection_status');

            if ($this->get_option('lang') == 'uk/en' && !is_admin()) {
                $this->lang = call_user_func($this->get_option('lang_function'));
                if ($this->lang == 'uk') {
                    $key = 0;
                } else {
                    $key = 1;   
                }

                $array_explode = explode('::', $this->get_option('title'));
                $this->title = $array_explode[$key];
                $array_explode = explode('::', $this->get_option('description'));
                $this->description = $array_explode[$key];
                $array_explode = explode('::', $this->get_option('pay_message'));
                $this->pay_message = $array_explode[$key];

            } else {

                $this->lang = $this->get_option('lang');
                $this->title = $this->get_option('title');
                $this->description = $this->get_option('description');
                $this->pay_message = $this->get_option('pay_message');

            }

            $this->icon = $this->get_option('icon');
            $this->status = $this->get_option('status');
            $this->redirect_page = $this->get_option('redirect_page');
            $this->redirect_page_error = $this->get_option('redirect_page_error');
            $this->button = $this->get_option('button');


            add_action('woocommerce_receipt_city24', array($this, 'receipt_page')); 
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options')); 
            add_action('woocommerce_api_wc_gateway_' . $this->id, array($this, 'check_ipn_response')); 

            if (!$this->is_valid_for_use()) {
                $this->enabled = false;
            }

    }

    public function admin_options() { ?>
        <h3><?php esc_html_e('Payment system City24', 'wc-city24'); ?></h3>
        <?php if(!empty($this->connection_status) && $this->connection_status !='success') : ?>
            <div class="inline error">
                <p class='warning'><?php esc_html_e('Last returned result is city24:', 'wc-city24'); ?> 
                    <?php echo esc_html($this->connection_status);?>
                </p>
            </div>
        <?php endif;
            if ( $this->is_valid_for_use() ) : ?>

        <table class="form-table"><?php $this->generate_settings_html(); ?></table>

        <?php  else : ?>
        <div class="inline error">
            <p>
                <strong><?php esc_html_e('Gateway disabled', 'wc-city24'); ?></strong>:
                <?php esc_html_e('City24 does not support your stores currencies .', 'wc-city24'); ?>
            </p>
        </div>
    <?php endif;

    }

    /** 
     * form_fields 
     * */

    public function init_form_fields() {

        $this->form_fields = array(
                'enabled'     => array(
                    'title'   => __('Turn on/Switch off', 'wc-city24'),
                    'type'    => 'checkbox',
                    'label'   => __('Turn on', 'wc-city24'),
                    'default' => 'yes',
                ),

                'title'       => array(
                    'title'       => __('Heading', 'wc-city24'),
                    'type'        => 'textarea',
                    'description' => __('Title that appears on the checkout page', 'wc-city24'),
                    'default'     => __('city24'),
                    'desc_tip'    => true,
                ),

                'description' => array(
                    'title'       => __('Description', 'wc-city24'),
                    'type'        => 'textarea',
                    'description' => __('Description that appears on the checkout page', 'wc-city24'),
                    'default'     => __('Pay using the payment system city24::Pay with city24 payment system', 'wc-city24'),
                    'desc_tip'    => true,
                ),

                'pay_message' => array(
                    'title'       => __('Message before payment', 'wc-city24'),
                    'type'        => 'textarea',
                    'description' => __('Message before payment', 'wc-city24'),
                    'default'     => __('Thank you for your order, click the button below to continue::Thank you for your order, click the button'),
                    'desc_tip'    => true,
                ),

                'public_key'  => array(
                    'title'       => __('Public key', 'wc-city24'),
                    'type'        => 'text',
                    'description' => __('Public key city24. Required parameter', 'wc-city24'),
                    'desc_tip'    => true,
                ),

                'private_key' => array(
                    'title'       => __('Private key', 'wc-city24'),
                    'type'        => 'text',
                    'description' => __('Private key city24. Required parameter', 'wc-city24'),
                    'desc_tip'    => true,
                ),

                'lang' => array(
                    'title'       => __('Language', 'wc-city24'),
                    'type'        => 'select',
                    'default'     => 'uk',
                    'options'     => array('uk'=> __('uk'), 'en'=> __('en')),
                    'description' => __('Interface language (For uk + en install multi-language plugin. Separating languages ​​with :: .)', 'wc-city24'),
                    'desc_tip'    => true,
                ),

                'lang_function'     => array(
                    'title'       => __('Language detection function', 'wc-city24'),
                    'type'        => 'text',
                    'default'     => 'pll_current_language',
                    'description' => __('The function of determining the language of your plugin', 'wc-city24'),
                    'desc_tip'    => true,

                ),

                'icon'     => array(
                    'title'       => __('Logotype', 'wc-city24'),
                    'type'        => 'text',
                    'default'     =>  WC_CITY24_DIR.'assets/images/logo_city24.svg',
                    'description' => __('Full path to the logo, located on the order page', 'wc-city24'),
                    'desc_tip'    => true,
                ),

                'button'     => array(
                    'title'       => __('Button', 'wc-city24'),
                    'type'        => 'text',
                    'default'     => '',
                    'description' => __('Full path to the image of the button to go to city24', 'wc-city24'),
                    'desc_tip'    => true,
                ),

                'status'     => array(
                    'title'       => __('Order status', 'wc-city24'),
                    'type'        => 'text',
                    'default'     => 'processing',
                    'description' => __('Order status after successful payment', 'wc-city24'),
                    'desc_tip'    => true,
                ),

                'sandbox'     => array(
                    'title'       => __('Test mode', 'wc-city24'),
                    'label'       => __('Turn on', 'wc-city24'),
                    'type'        => 'checkbox',
                    'description' => __('This mode will help to test the payment without withdrawing funds from the cards', 'wc-city24'),
                    'desc_tip'    => true,
                ),

                'redirect_page'     => array(
                    'title'       => __('Redirect page URL', 'wc-city24'),
                    'type'        => 'url',
                    'default'     => '',
                    'description' => __('URL page to go to after gateway city24', 'wc-city24'),
                    'desc_tip'    => true,
                ),

                'redirect_page_error'     => array(
                    'title'       => __('URL error Payment page', 'wc-city24'),
                    'type'        => 'url',
                    'default'     => '',
                    'description' => __('URL page to go to after gateway city24', 'wc-city24'),
                    'desc_tip'    => true,
                ),
        );

    }

    function is_valid_for_use() {

        if (!in_array(get_option('woocommerce_currency'), array('UAH'))) {
            return false;
        }
        return true;
    }
    
    function process_payment($order_id) {
        $order = new WC_Order($order_id);
        return array(
            'result'   => 'success',
            'redirect' => add_query_arg('order-pay', $order->id, add_query_arg('key', $order->order_key, $order->get_checkout_payment_url(true)))
        );

    }

    public function receipt_page($order) {

        echo '<p>' . esc_html($this->pay_message) . '</p><br/>';
        echo $this->generate_form($order);

    }

    public function generate_form($order_id) {

        global $woocommerce;
        $order = new WC_Order($order_id);
        $result_url = add_query_arg('wc-api', 'wc_gateway_' . $this->id, home_url('/'));
        $currency= get_woocommerce_currency();

        if ($this->sandbox == 'yes') {
                $sandbox = 1;
        } else {
                $sandbox = 0;
        }

        if (trim($this->redirect_page) == '') {
                $redirect_page_url = $order->get_checkout_order_received_url();
        } else {
                $redirect_page_url = trim($this->redirect_page) . '?wc_order_id=' .$order_id;
        }
        
        $key = file_get_contents($_SERVER['DOCUMENT_ROOT'].'shop/wp-content/plugins/wc-city24/cert/111118.pem');
		
		$pkeyid = openssl_pkey_get_private($key,'1111');
		
		$payId = 0;
		
		$pdata = [
	    	'notificationUrl' => esc_attr($result_url).'&oid='.$order_id,
	    	'redirectUrl' => 'https://eurolamp.ua/shop/checkout/order-received/'.$order_id.'/',
	    	
	        'details' => [
	        	[
	            'serviceId' => '63486',
	            'amount' => esc_attr($this->get_order_total()),
				'account' => "acc_number=".$order_id.';additional_parameter1='.$order->get_billing_email().';',
	            ]
	        ],
        ];
        
        
        openssl_sign(json_encode($pdata, JSON_UNESCAPED_SLASHES), $signature, $pkeyid);
	    openssl_free_key($pkeyid);
	    
	    
	    $sign = base64_encode(pack('H*',bin2hex($signature)));
	    		
		$headers = [
		    'Content-Type: application/json',
             'KioskId: 111118',
             'Sign: '.$sign,
		];
		
				
		$myCurl = curl_init();
		curl_setopt_array($myCurl, [
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_SSL_VERIFYHOST => false,
		    CURLOPT_HTTPHEADER => $headers,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_URL => 'https://api.platezhka.com.ua/ext/v1.1/Frame/Session',
		    CURLOPT_POST => true,
		    CURLOPT_POSTFIELDS => json_encode($pdata, JSON_UNESCAPED_SLASHES)
		]);
		
		//print_r('<pre>');print_r([$headers, $pdata]);print_r('</pre>');

		
		$response = curl_exec($myCurl);
		
		//print_r('<pre>');print_r($response);print_r('</pre>');

		
		curl_close($myCurl);
		
		$resp = json_decode($response, true);
		
		$html = '';
		
		if(isset($resp['payId']) && $resp['payId'] && isset($resp['url']) && $resp['url']){
			$template = '<div 
	            class="load_window_liqpay" 
	            style="position: fixed;
	                top: 0;
	                left: 0;
	                width: 100vw;
	                height: 100vh;
	                background-color: #f9f9f9;
	                opacity: 1;
	                z-index: 99999999999;
	                display: -webkit-box;
	                display: -ms-flexbox;
	                display: flex;
	                -webkit-box-align: center;
	                -ms-flex-align: center;
	                align-items: center;
	                -webkit-box-pack: center;
	                -ms-flex-pack: center;
	                justify-content: center;
	            "> <p style="color:#000;">Loading...</p></div>
	            <form method="GET" action="'.$resp['url'].'" id="'.$this->id.'_payment_form" accept-charset="utf-8">
	            	<button type="submit" style="width: 160px">Сплатити</button>
	            </form>';
	
	        $skip_script ='<script type="text/javascript">
	              jQuery(function() {
	                jQuery("#' . $this->id . '_payment_form").submit(); 
	              })
	            </script>';
	
	        $html = $template . PHP_EOL . $skip_script;
		}
		
        return $html;

    }
    
    function check_ipn_response() {

        global $woocommerce;

        if (isset($_GET['payId']) && $_GET['payId'] && isset($_GET['oid']) && $_GET['oid']) {
	        
	        $key = file_get_contents($_SERVER['DOCUMENT_ROOT'].'shop/wp-content/plugins/wc-city24/cert/111118.pem');
			
			$pkeyid = openssl_pkey_get_private($key,'1111');
			
			$payId = 0;
			
			$id = $_GET['oid'];
			$payId = $_GET['payId'];
	        
            $order = new WC_Order($order_id);
            
            $order_status  = $order->get_status();
            
            if($order_status != 'pending'){
            	wp_die('Success');
            }
	        
	        $orderemail = $order->get_billing_email();


	        $psdata = ["payId" => (int)$payId];
	        
			openssl_sign(json_encode($psdata, JSON_UNESCAPED_SLASHES), $signature_n, $pkeyid);

		    $sign = base64_encode(pack('H*',bin2hex($signature_n)));
		    		
			$headers = [
			    'Content-Type: application/json',
	             'KioskId: 111118',
	             'Sign: '.$sign,
			];
				
			$myCurl = curl_init();
			curl_setopt_array($myCurl, [
			    CURLOPT_SSL_VERIFYPEER => false,
			    CURLOPT_SSL_VERIFYHOST => false,
			    CURLOPT_HTTPHEADER => $headers,
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_URL => 'https://api.platezhka.com.ua/ext/v1.0/frame/status',
			    CURLOPT_POST => true,
			    CURLOPT_POSTFIELDS => json_encode($psdata, JSON_UNESCAPED_SLASHES)
			]);
			
			$response = curl_exec($myCurl);
			
			curl_close($myCurl);
			
			$resp = json_decode($response, true);
			
			$status = 0;

			if(isset($resp['code'])){
				$status = $resp['code'];
			}

            $this->update_option( 'connection_status', $status );

            if ($status == '8') {
                $order->update_status($this->status, esc_html__('Order has been paid (payment received)', 'wc-city24'));
                $order->add_order_note(esc_html__('The client paid for his order', 'wc-city24'));
                $woocommerce->cart->empty_cart();
            } else {
                $order->update_status('failed', esc_html__('Payment has not been received', 'wc-city24'));
                wp_redirect($order->get_cancel_order_url());
                exit;
            }

        } else {
                wp_die('IPN Request Failure');
        }
    }
}
