<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @package    Pixel_Manager_For_Woocommerce/admin/partials
 * Pixel Tag Manager For Woocommerce
 */

if(!defined('ABSPATH')){
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_PixelsFreeVsPro')){
  class PMW_PixelsFreeVsPro extends PMW_AdminHelper{
    public function __construct( ) {           
      //$this->req_int();
      $this->load_html();
    }
    public function req_int(){
    }
    protected function load_html(){
      $this->page_html();
      $this->page_js();
    }

    /**
     * Page HTML
     **/
    protected function page_html(){
      ?>
      <div class="pmw_form-wrapper pmw_form-wrapper_freevsprow pmw_form-row">
        <div class="pmw_form-group pmw_freevsprow">
          <div class="pmw-pricing-plan mb-5">
            <h1><?php esc_attr_e("Upgrad to our Pro Plan, starting at just $9.","pixel-manager-for-woocommerce"); ?></h1>
            <div class="pmw-christmas-offer">
              <span class="pmw-christmas-offer__badge"><?php esc_attr_e('Christmas Special','pixel-manager-for-woocommerce'); ?></span>
              <h3><?php esc_attr_e('Business yearly plan now $39 (down from $49).','pixel-manager-for-woocommerce'); ?></h3>
              <p><?php esc_attr_e('Enjoy an extra 20%-35% Christmas discount—automatically applied to the Business yearly plan.','pixel-manager-for-woocommerce'); ?></p>
              <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=172&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+BUSINESS+Yearly+Christmas&m_campaign=Christmas+Offer"); ?>" class="pmw_btn pmw-btn-christmas">
                <?php esc_attr_e('Claim the Christmas deal','pixel-manager-for-woocommerce'); ?>
              </a>
            </div>

            <br>
            <div class="switch-wrapper">
              <input id="monthly" value="monthly" type="radio" name="plans_type">
              <input id="yearly" value="yearly" type="radio" name="plans_type" checked="">
              <label for="monthly"><?php esc_attr_e('Monthly','pixel-manager-for-woocommerce'); ?></label>
              <label for="yearly"><?php esc_attr_e('Yearly','pixel-manager-for-woocommerce'); ?></label>
                            
              <span class="highlighter"></span>
            </div>
            <div class="pmw_row text-center mb-5 yearly">
              <!-- Pricing Table-->
              <div class="pmw_col-4">
                <div class="bg-white p-2 rounded-lg shadow">
                  <?php /*<div class="pmw-one-month-free">
                    <span style="background-color:#2271b1; color:#fff; margin-top:5px; padding:3px 12px;"><?php esc_attr_e('ONE MONTH FREE','pixel-manager-for-woocommerce'); ?></span><br>
                    <span class="text-small font-weight-bold"><?php esc_attr_e('No payment required','pixel-manager-for-woocommerce'); ?></span>
                  </div> */ ?>
                  <h2 class="h6 text-uppercase font-weight-bold mb-4"><?php esc_attr_e('BUSINESS','pixel-manager-for-woocommerce'); ?></h2>
                  <span class="pmw-christmas-offer__badge">Christmas Special Offer</span>
                  <h3><del>$49</del><strong> $39/ year</strong></h3>
                  <div class="price_allow_site"><?php esc_attr_e('1 active website','pixel-manager-for-woocommerce'); ?></div>
                  <h2 class="h2 font-weight-bold"><?php esc_attr_e('$4.1','pixel-manager-for-woocommerce'); ?><span class="text-small font-weight-normal"> /<?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></h2>
                  <span><?php esc_attr_e('billed annually ($49 per year)','pixel-manager-for-woocommerce'); ?></span><br>
                  <span class="h5 per-site"><?php esc_attr_e('$4.1/month per website','pixel-manager-for-woocommerce'); ?></span>
                  <div class="custom-separator my-3 mx-auto bg-primary"></div>
                  <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=172&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+BUSINESS+Yearly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="pmw_btn pmw_btn-fill shadow rounded-pill pay-subscription"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                  <ul>     
                    <li><?php esc_attr_e('Use on 1 Site','pixel-manager-for-woocommerce'); ?></li>
                    <?php echo $this->get_plan_features_html(); ?>
                    </ul>
                    <a class="show-all-features" href="#"><?php esc_attr_e('See all features…','pixel-manager-for-woocommerce'); ?></a>
                  </div>
              </div>
              <!-- END -->
              <div class="pmw_col-4">
                <div class="bg-white p-2 rounded-lg shadow">
                  <h2 class="h6 text-uppercase font-weight-bold mb-4"><?php esc_attr_e('ENTERPRISE','pixel-manager-for-woocommerce'); ?></h2>
                  <span class="pmw-christmas-offer__badge">Christmas Special Offer</span>
                  <h3><del>$149</del><strong> $99/ year</strong></h3>
                  <div class="price_allow_site"><?php esc_attr_e('5 active  websites','pixel-manager-for-woocommerce'); ?></div>
                  <h2 class="h2 font-weight-bold"><?php esc_attr_e('$12.4','pixel-manager-for-woocommerce'); ?><span class="text-small font-weight-normal"> /<?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></h2>
                  <span><?php esc_attr_e('billed annually ($149 per year)','pixel-manager-for-woocommerce'); ?></span><br>
                  <span class="h5 per-site"><?php esc_attr_e('$2.5/month per website','pixel-manager-for-woocommerce'); ?></span>
                  <div class="custom-separator my-3 mx-auto bg-primary"></div>
                  <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=174&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+ENTERPRISE+Yearly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="pmw_btn pmw_btn-fill shadow rounded-pill pay-subscription"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                  <ul>
                    <li><?php esc_attr_e('Use on 5 Sites','pixel-manager-for-woocommerce'); ?></li>
                    <?php echo $this->get_plan_features_html(); ?>
                    </ul>
                  <a class="show-all-features" href="#"><?php esc_attr_e('See all features…','pixel-manager-for-woocommerce'); ?></a>
                </div>
              </div>
              <!-- END -->
              <div class="pmw_col-4">
                <div class="bg-white p-2 rounded-lg shadow">
                  <h2 class="h6 text-uppercase font-weight-bold mb-6"><?php esc_attr_e('ENTERPRISE PLUS','pixel-manager-for-woocommerce'); ?></h2>
                  <h3><strong> $239/ year</strong></h3>
                  <div class="price_allow_site"><?php esc_attr_e('20 active  websites','pixel-manager-for-woocommerce'); ?></div>
                  <h2 class="h2 font-weight-bold"><?php esc_attr_e('$19.9','pixel-manager-for-woocommerce'); ?><span class="text-small font-weight-normal"> /<?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></h2>
                  <span><?php esc_attr_e('billed annually ($239 per year)','pixel-manager-for-woocommerce'); ?></span><br>
                  <span class="h5 per-site"><?php esc_attr_e('$1.0/month per website','pixel-manager-for-woocommerce'); ?></span>
                  <div class="custom-separator my-3 mx-auto bg-primary"></div>
                  <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=183&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+ENTERPRISEPLUS+Yearly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="pmw_btn pmw_btn-fill shadow rounded-pill pay-subscription"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                  <ul>
                    <li><?php esc_attr_e('Use on 20 Sites','pixel-manager-for-woocommerce'); ?></li>
                    <?php echo $this->get_plan_features_html(); ?>
                    </ul>
                  <a class="show-all-features" href="#"><?php esc_attr_e('See all features…','pixel-manager-for-woocommerce'); ?></a>
                </div>
              </div>
              <!-- END -->
            </div>
            <div class="pmw_row text-center monthly">
              <div class="pmw_col-4">
                <div class="bg-white p-2 rounded-lg shadow">
                  <h2 class="h6 text-uppercase font-weight-bold mb-4"><?php esc_attr_e('BUSINESS','pixel-manager-for-woocommerce'); ?></h2>
                  <div class="price_allow_site"><?php esc_attr_e('1 active website','pixel-manager-for-woocommerce'); ?></div>
                  <h2 class="h2 font-weight-bold"><?php esc_attr_e('$9','pixel-manager-for-woocommerce'); ?><span class="text-small font-weight-normal"> /<?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></h2>
                  <span class="h5 per-site"><?php esc_attr_e('$9/month per website','pixel-manager-for-woocommerce'); ?></span>
                  <div class="custom-separator my-3 mx-auto bg-primary"></div>
                  <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=95&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+BUSINESS+Monthly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="pmw_btn pmw_btn-fill shadow rounded-pill pay-subscription"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                  <ul>
                    <li><?php esc_attr_e('Use on 1 Site','pixel-manager-for-woocommerce'); ?></li>
                    <?php echo $this->get_plan_features_html(); ?>
                    </ul>
                  <a class="show-all-features" href="#"><?php esc_attr_e('See all features…','pixel-manager-for-woocommerce'); ?></a>
                </div>
              </div>
              <!-- END -->
              <div class="pmw_col-4">
                <div class="bg-white p-2 rounded-lg shadow">
                  <h2 class="h6 text-uppercase font-weight-bold mb-4"><?php esc_attr_e('ENTERPRISE','pixel-manager-for-woocommerce'); ?></h2>
                  <div class="price_allow_site"><?php esc_attr_e('5 active  websites','pixel-manager-for-woocommerce'); ?></div>
                  <h2 class="h2 font-weight-bold"><?php esc_attr_e('$25','pixel-manager-for-woocommerce'); ?><span class="text-small font-weight-normal"> /<?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></h2>
                  <span class="h5 per-site"><?php esc_attr_e('$5/month per website','pixel-manager-for-woocommerce'); ?></span>
                  <div class="custom-separator my-3 mx-auto bg-primary"></div>
                  <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=96&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+ENTERPRISE+Monthly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="pmw_btn pmw_btn-fill shadow rounded-pill pay-subscription"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                  <ul>
                    <li><?php esc_attr_e('Use on 5 Sites','pixel-manager-for-woocommerce'); ?></li>
                    <?php echo $this->get_plan_features_html(); ?>
                  </ul>
                  <a class="show-all-features" href="#"><?php esc_attr_e('See all features…','pixel-manager-for-woocommerce'); ?></a>
                </div>
              </div>
                <!-- END -->
              <div class="pmw_col-4">
                <div class="bg-white p-2 rounded-lg shadow">
                  <h2 class="h6 text-uppercase font-weight-bold mb-4"><?php esc_attr_e('ENTERPRISE PLUS','pixel-manager-for-woocommerce'); ?></h2>
                  <div class="price_allow_site"><?php esc_attr_e('20 active  websites','pixel-manager-for-woocommerce'); ?></div>
                  <h2 class="h2 font-weight-bold"><?php esc_attr_e('$40','pixel-manager-for-woocommerce'); ?><span class="text-small font-weight-normal"> /<?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></h2>
                  <span class="h5 per-site"><?php esc_attr_e('$2/month per website','pixel-manager-for-woocommerce'); ?></span>
                  <div class="custom-separator my-3 mx-auto bg-primary"></div>
                  <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=185&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+ENTERPRISEPLUS+Monthly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="pmw_btn pmw_btn-fill shadow rounded-pill pay-subscription"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                  <ul>
                    <li><?php esc_attr_e('Use on 20 Sites','pixel-manager-for-woocommerce'); ?></li>
                    <?php echo $this->get_plan_features_html(); ?>
                  </ul>
                  <a class="show-all-features" href="#"><?php esc_attr_e('See all features…','pixel-manager-for-woocommerce'); ?></a>
                </div>
              </div>
              <!-- END -->
            </div>
          </div>
          <div class=""><?php esc_attr_e('Reach out to us for any questions about pricings and support ','pixel-manager-for-woocommerce'); ?><a target="_blank" href="<?php echo esc_url_raw($this->get_support_page_link()); ?>">Click here.</a></div>
          <header class="mb-6 text-white">
            <div class="row">
              <div class="col-lg-10 p-2 mx-auto pmw_site-color-bg">
                <h4><?php esc_attr_e('15 Days 100% No-Risk Money Back Guarantee!','pixel-manager-for-woocommerce'); ?></h4>
                <p><?php esc_attr_e('You are fully protected by our 100% No-Risk-Double-Guarantee. If you don’t like over the next 15 days, then we will happily refund 100% of your money. No questions asked.','pixel-manager-for-woocommerce'); ?></p>
              </div>
            </div>
          </header>
          <div id="show-all-features" class="mb-5 pmw_price-table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>
                    <div>
                      <?php esc_attr_e('Select your plan','pixel-manager-for-woocommerce'); ?>
                      <div class="svg-wrapper">
                        <svg viewBox="0 0 24 24">
                          <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm1 17v-4h-8v-2h8v-4l6 5-6 5z"></path>
                        </svg>
                      </div>
                    </div>
                  </th>
                  <th>
                    <div class="heading"><?php esc_attr_e('FREE','pixel-manager-for-woocommerce'); ?></div>
                    <div class="info">
                      <div class="price monthly">
                        <div class="amount"><span></span></div>
                      </div>
                      <div class="price yearly">
                        <div class="amount"><span></span></div>
                        <div class="billing-msg"></div>
                      </div>
                      <div class="price_allow_site"><?php esc_attr_e('1 active website','pixel-manager-for-woocommerce'); ?></div>
                      <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=85&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+FREE&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="btn btn-pay-list"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                    </div>
                  </th>
                  <th>
                    <div class="heading"><?php esc_attr_e('BUSINESS','pixel-manager-for-woocommerce'); ?></div>
                    <div class="info">
                      <div class="popular"><?php esc_attr_e('Popular','pixel-manager-for-woocommerce'); ?></div>
                      <div class="price monthly">
                        <div class="amount"><?php esc_attr_e('$9','pixel-manager-for-woocommerce'); ?><span><?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></div>
                      </div>
                      <div class="price yearly">
                        <div class="amount"><?php esc_attr_e('$4.1','pixel-manager-for-woocommerce'); ?><span><?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></div>
                        <div class="billing-msg"><?php esc_attr_e('billed annually','pixel-manager-for-woocommerce'); ?></div>
                      </div>
                      <div class="price_allow_site"><?php esc_attr_e('1 active website','pixel-manager-for-woocommerce'); ?></div>
                      <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=95&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+BUSINESS+Monthly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="monthly btn btn-pay-list"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                      <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=172&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+BUSINESS+Yearly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="yearly btn btn-pay-list"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                    </div>
                  </th>
                  <th>
                    <div class="heading"><?php esc_attr_e('ENTERPRISE','pixel-manager-for-woocommerce'); ?></div>
                    <div class="info">
                      <div class="price monthly">
                        <div class="amount"><?php esc_attr_e('$25','pixel-manager-for-woocommerce'); ?><span><?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></div>
                      </div>
                      <div class="price yearly">
                        <div class="amount"><?php esc_attr_e('$12.4','pixel-manager-for-woocommerce'); ?><span><?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></div>
                        <div class="billing-msg"><?php esc_attr_e('billed annually','pixel-manager-for-woocommerce'); ?></div>
                      </div>
                      <div class="price_allow_site"><?php esc_attr_e('5 active  websites','pixel-manager-for-woocommerce'); ?></div>
                      <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=96&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+ENTERPRISE+Monthly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="monthly btn btn-pay-list"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                      <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=174&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+ENTERPRISE+Yearly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="yearly btn btn-pay-list"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                    </div>
                  </th>
                  <th>
                    <div class="heading"><?php esc_attr_e('ENTERPRISE PLUS','pixel-manager-for-woocommerce'); ?></div>
                    <div class="info">
                      <div class="price monthly">
                        <div class="amount"><?php esc_attr_e('$40','pixel-manager-for-woocommerce'); ?><span><?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></div>
                      </div>
                      <div class="price yearly">
                        <div class="amount"><?php esc_attr_e('$19.9','pixel-manager-for-woocommerce'); ?><span><?php esc_attr_e('month','pixel-manager-for-woocommerce'); ?></span></div>
                        <div class="billing-msg"><?php esc_attr_e('billed annually','pixel-manager-for-woocommerce'); ?></div>
                      </div>
                      <div class="price_allow_site"><?php esc_attr_e('20 active  websites','pixel-manager-for-woocommerce'); ?></div>
                      <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=185&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+ENTERPRISEPLUS+Monthly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="monthly btn btn-pay-list"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                      <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=183&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+ENTERPRISEPLUSPLUS+Yearly&m_campaign=Upsell+at+PixelTagManager+Plugin"); ?>" class="yearly btn btn-pay-list"><?php esc_attr_e('Get Started','pixel-manager-for-woocommerce'); ?></a>
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php esc_attr_e('Use on website','pixel-manager-for-woocommerce'); ?></td>
                  <td>1</td>
                  <td>1</td>
                  <td>5</td>
                  <td>20</td>
                </tr>
                <tr>
                  <td class="ptm-full-width" colspan=5><strong><?php esc_attr_e('GrowInsights360 GA4 Dashboard','pixel-manager-for-woocommerce'); ?></strong></td>                 
                </tr>
                <tr>
                  <td><?php esc_attr_e('Google Analytics Dashboard Overview','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_allhtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('General Reports - provides a high-level summary','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_allhtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Product Performance Reports - with Multi-Dimensional Analysis','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_allhtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Purchase Journey Reports','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_allhtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Ecommerce & User Metrics','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_allhtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Download CSV & PDF','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_allhtml(); ?>
                </tr>
                <tr>
                  <td class="ptm-full-width" colspan=5><strong><?php esc_attr_e('Pixels Tracking','pixel-manager-for-woocommerce'); ?></strong></td>
                </tr>
                <tr>
                  <td><?php esc_attr_e('GTM base tracking','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Complet eCommerce tracking','pixel-manager-for-woocommerce'); ?></td>
                  <td><span class="free plan-no"></span></td>
                  <td><span class="paid1-plan-yes"></span></td>
                  <td><span class="paid2-plan-yes"></span></td>
                  <td><span class="paid3-plan-yes"></span></td>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Google Consent Mode v2 with Axeptio Integration','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Google Analytics 4 Tracking','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Form Submission Tracking','pixel-manager-for-woocommerce'); ?></td>
                  <td><span class="free plan-yes"></span></td>
                  <td><span class="paid1-plan-yes"></span></td>
                  <td><span class="paid2-plan-yes"></span></td>
                  <td><span class="paid3-plan-yes"></span></td>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Google Ads Conversion Tracking','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Google Ads Enhanced Conversion Tracking','pixel-manager-for-woocommerce'); ?></td>
                  <td><span class="free plan-yes"></span></td>
                  <td><span class="paid1-plan-yes"></span></td>
                  <td><span class="paid2-plan-yes"></span></td>
                  <td><span class="paid3-plan-yes"></span></td>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Google Ads Dynamic Remarketing Tracking','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Facebook Pixel Tracking','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Facebook Conversion API','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>                
                <tr>
                  <td><?php esc_attr_e('Multiple Facebook Pixel ID(s) Tracking','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('TikTok Ads Pixel Tracking','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('TikTok Conversion API','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Pinterest Pixel Tracking','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Pinterest Conversion API','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Microsoft Ads Pixel Tracking','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Remarketing and Dynamic remarketing tracking for Microsoft Ads','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Snapchat Pixel Tracking','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Twitter Ads Pixel tracking','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Send Advanced Matching data is now sent to Facebook, TikTok, Twitter, Bing, and Pinterest','pixel-manager-for-woocommerce'); ?></td>
                  <?php echo $this->get_plan_features_limited_detailshtml(); ?>
                </tr>
                <tr>
                  <td class="ptm-full-width" colspan=5><strong><?php esc_attr_e('Support','pixel-manager-for-woocommerce'); ?></strong></td>
                </tr>
                <tr>
                  <td><?php esc_attr_e('Priority Support (24*5)','pixel-manager-for-woocommerce'); ?></td>
                  <td><span class="free plan-no"></span></td>
                  <td><span class="paid1-plan-yes"></span></td>
                  <td><span class="paid2-plan-yes"></span></td>
                  <td><span class="paid3-plan-yes"></span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php
    }
    /**
     * Page JS
     **/
    protected function page_js(){
      ?>
      <script type="text/javascript">
        (function($) {
            jQuery(document).ready(function () {
              jQuery(".show-all-features").on('click', function(e){
                e.preventDefault();
                //$(".tvc-price-table-features").slideDown(); 
                 jQuery('html, body').animate({
                    scrollTop: jQuery("#show-all-features").offset().top-130
                }, 500);
              });
            });
          })( jQuery );
        var plans_type = jQuery("input[name='plans_type']:checked").val();
        price_show_hide(plans_type);
        jQuery("input[name='plans_type']").on('change', function(){
          plans_type = jQuery("input[name='plans_type']:checked").val();
          price_show_hide(plans_type);
        });
        function price_show_hide(plans_type){
          if(plans_type == "monthly"){
            jQuery(".yearly").addClass("pmw-hide");
            jQuery(".monthly").removeClass("pmw-hide");
          }else{
            jQuery(".yearly").removeClass("pmw-hide");
            jQuery(".monthly").addClass("pmw-hide");
          }
        }
      </script>
      <?php
    }
  }
}