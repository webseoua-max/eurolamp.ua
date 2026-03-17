


<?php
include_once (get_stylesheet_directory() . '/includes/shortcode.php');
include_once (get_stylesheet_directory() . '/includes/wc-hooks.php');
include_once (get_stylesheet_directory() . '/includes/cumulative-discount.php');
include_once (get_stylesheet_directory() . '/includes/auth.php');
include_once (get_stylesheet_directory() . '/includes/ajax.php');

function eurolamp_scripts() {
  //wp_enqueue_style('eurolamp-fonts',   get_stylesheet_directory_uri() . '/assets/css/fonts.css' );
  //wp_enqueue_style('eurolamp-global',  get_stylesheet_directory_uri() . '/assets/css/global.css' );
  wp_enqueue_script('eurolamp-mask',   get_stylesheet_directory_uri() . '/assets/js/maskinput.js', array('jquery'), false, true);
  wp_enqueue_script('eurolamp-custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), '6.6.28', true);
}
add_action( 'wp_enqueue_scripts', 'eurolamp_scripts' );
// off xml
add_filter('xmlrpc_enabled', '__return_false');
//polylang
add_action('init', function() {
  pll_register_string('Розгорнути', 'Розгорнути');
  pll_register_string('Згорнути', 'Згорнути');
  pll_register_string('Знайти', 'Знайти');
  pll_register_string('Вибране', 'Вибране');
  pll_register_string('ціна', 'ціна');
  pll_register_string('Характеристики', 'Характеристики');
  pll_register_string('Швидке замовлення', 'Швидке замовлення');
  pll_register_string('Передзамовлення', 'Передзамовлення');
  pll_register_string('Програма лояльності', 'Програма лояльності');
  pll_register_string('Безкоштовна доставка від 2999 грн', 'Безкоштовна доставка від 2999 грн');
  pll_register_string('Вам бракує лише $price_shipping грн. щоб її отримати', 'Вам бракує лише $price_shipping грн. щоб її отримати');
  pll_register_string('Увійдіть для нарахування знижки. ', 'Увійдіть для нарахування знижки. ');
  pll_register_string('вхід в акаунт', 'вхід в акаунт');
});
//search
add_filter( 'posts_search', 'genius_product_search_by_sku', 9999, 2 );
  
function genius_product_search_by_sku( $search, $wp_query ) {
   global $wpdb;
   if ( is_admin() || ! is_search() || ! isset( $wp_query->query_vars['s'] ) || ( ! is_array( $wp_query->query_vars['post_type'] ) && $wp_query->query_vars['post_type'] !== "product" ) || ( is_array( $wp_query->query_vars['post_type'] ) && ! in_array( "product", $wp_query->query_vars['post_type'] ) ) ) return $search;   
   $product_id = wc_get_product_id_by_sku( $wp_query->query_vars['s'] );
   if ( ! $product_id ) return $search;
   $product = wc_get_product( $product_id );
   if ( $product->is_type( 'variation' ) ) {
      $product_id = $product->get_parent_id();
   }
   $search = str_replace( 'AND (((', "AND (({$wpdb->posts}.ID IN (" . $product_id . ")) OR ((", $search );   
   return $search;   
}
//add_to_cart_redirect
add_action('add_to_cart_redirect', 'resolve_dupes_add_to_cart_redirect');
function resolve_dupes_add_to_cart_redirect($url = false) {
if(!empty($url)) { return $url; }
return get_bloginfo('wpurl').add_query_arg(array(), remove_query_arg('add-to-cart'));
}
//clasic widget
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );
//include 
add_filter( 'flatsome_lightbox_close_btn_inside', '__return_true' );
//wp-seo
function filter_wpseo_robots( $robotsstr ) {
  if ( is_paged() ) {
      return 'noindex, follow';
  }
  return $robotsstr;
}
add_filter( 'wpseo_robots', 'filter_wpseo_robots' );
//script more
function add_js_functions(){
?>
<script>
    jQuery(document).ready(function($) {
      $(".toggleBtn").click(function() {
        $(".seo__text").toggleClass('active');  
        if ($(".seo__text").hasClass('active')) {
          $(this).text("<?php pll_e('Згорнути'); ?>");
          $('.toggleBtn').addClass('rotate');
        } else {
          $(this).text("<?php pll_e('Розгорнути'); ?>");
          $('.toggleBtn').removeClass('rotate');
        }
      });
    });
</script>
<?php
}
add_action('wp_head','add_js_functions');
//register widget
function true_register_wp_sidebars() {
  register_sidebar(
    array(
      'id' => 'true_side', 
      'name' => 'Боковая панель блог', 
      'description' => 'Перетащите сюда виджеты, чтобы добавить их в сайдбар.', 
      'before_widget' => '<div id="%1$s" class="side widget %2$s">', 
      'after_widget' => '</div>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>'
    )
  );
}
add_action( 'widgets_init', 'true_register_wp_sidebars' );
//register widget
function true_footer1_wp_sidebars() {
  register_sidebar(
    array(
      'id' => 'true_footer_1', 
      'name' => 'Меню футер_1', 
      'description' => 'Перетащите сюда виджеты, чтобы добавить их в сайдбар.', 
      'before_widget' => '<div id="%1$s" class="side widget %2$s">', 
      'after_widget' => '</div>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>'
    )
  );
}
add_action( 'widgets_init', 'true_footer1_wp_sidebars' );
//register widget
function true_footer2_wp_sidebars() {
  register_sidebar(
    array(
      'id' => 'true_footer_2', 
      'name' => 'Меню футер_2', 
      'description' => 'Перетащите сюда виджеты, чтобы добавить их в сайдбар.', 
      'before_widget' => '<div id="%1$s" class="side widget %2$s">', 
      'after_widget' => '</div>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>'
    )
  );
}
add_action( 'widgets_init', 'true_footer2_wp_sidebars' );
//register widget
function true_inner_wp_sidebars() {
  register_sidebar(
    array(
      'id' => 'inner_sidebar', 
      'name' => 'Сайдбар на внутренних', 
      'description' => 'Перетащите сюда виджеты, чтобы добавить их в сайдбар.', 
      'before_widget' => '<div id="%1$s" class="side widget %2$s">', 
      'after_widget' => '</div>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>'
    )
  );
}
add_action( 'widgets_init', 'true_inner_wp_sidebars' );
//remove fields
function eurolamp_add_update_form_billing( $fragments ) {
  $checkout = WC()->checkout();
  parse_str( $_POST['post_data'], $fields_values );
  ob_start();
  echo '<div class="woocommerce-billing-fields__field-wrapper">';
  $fields = $checkout->get_checkout_fields( 'billing' );
  foreach ( $fields as $key => $field ) {
    $value = $checkout->get_value( $key );
    if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
      $field['country'] = $checkout->get_value( $field['country_field'] );
    }
    if ( ! $value && ! empty( $fields_values[ $key ] ) ) {
      $value = $fields_values[ $key ];
    }
    woocommerce_form_field( $key, $field, $value );
  }
  echo '</div>';
  $fragments['.woocommerce-billing-fields__field-wrapper'] = ob_get_clean();
  return $fragments;
}
add_filter( 'woocommerce_update_order_review_fragments', 'eurolamp_add_update_form_billing', 99 );

//preloader
function eurolamp_add_script_update_shipping_method() {

  if ( is_checkout() ) {
    ?>
    <style>
      #billing_country_field {display: none !important;}
    </style>
    <script>
        jQuery( document ).ready( function( $ ) {
          $( document.body ).on( 'updated_checkout updated_shipping_method', function( event, xhr, data ) {
            $( 'input[name^="shipping_method"]' ).on( 'change', function() {
              $( '.woocommerce-billing-fields__field-wrapper' ).block( {
                message: null,
                overlayCSS: {
                  background: '#fff',
                  'z-index': 1000000,
                  opacity: 0.3
                }
              } );
            } );
          } );
        } );
    </script>
    <?php
  }
}
add_action( 'wp_footer', 'eurolamp_add_script_update_shipping_method' );
//custom login
function alter_login_headerurl() {
  return '/'; 
  }
  add_action('login_headerurl','alter_login_headerurl');

function my_login_logo() { ?>
<style type="text/css">
  .login form, .login #login_error, .login .message, .login .success {
    border-radius: 0px;
    font-family: "Adrianna Lt", sans-serif;
  }
  #loginform input[type=text] {
    font-size: 12px;
  }
  #loginform #wp-submit {
     background: #8cbe22;
     border: none;
     width: 100%;
     margin-top: 10px;
     border-radius: 5px;
     height: 45px;
  }
  .login #backtoblog a, .login #nav a {
    color: #fff!important;
  }
  #nav, #backtoblog, .privacy-policy-page-link {
      font-family: "Adrianna Lt", sans-serif;
  }
  body.login div#login h1 a {
    background-image: url(/wp-content/uploads/logo.png) !important;
    background-size: contain;
    width: 240px;}
  body {
    background: #444444!important;
  }
  .login #backtoblog a, .login #nav a {
    padding: 2px;
    border-radius: 5px;
  }</style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

add_filter( 'login_display_language_dropdown', '__return_false' );
//order form
add_action( 'woocommerce_after_add_to_cart_button', 'wpbl_exmaple_hook', 20);
function wpbl_exmaple_hook(){
  echo '<p class="order__one">
    <span class="order__popover">'.pll__('Швидке замовлення').'</span>
    <a href="#order__form"></a>
  <p>';
} 
//remove
function eurolamp_override_checkout_fields( $fields ) {
  $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
  if ( false !== strpos( $chosen_methods[0], 'local_pickup' ) ) {
    unset(
      $fields['billing']['billing_company'],
      $fields['billing']['billing_address_1'],
      $fields['billing']['billing_address_2'],
      $fields['billing']['billing_city'],
      $fields['billing']['billing_postcode'],
      $fields['billing']['billing_state'],
    );
  }
  if ( false !== strpos( $chosen_methods[0], 'nova_poshta_shipping' ) ) {
    unset(
      $fields['billing']['billing_company'],
    );
  }
  if ( false !== strpos( $chosen_methods[0], 'flat_rate' ) ) {
    unset(
      $fields['billing']['billing_city'],
      $fields['billing']['billing_company'],
      $fields['billing']['billing_postcode'],
      $fields['billing']['billing_state'],
    );
  }
  return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'eurolamp_override_checkout_fields' );

add_filter( 'woocommerce_product_tabs', 'info_product_tab' );
function info_product_tab( $tabs ) {
  if( have_rows('tech_info') ){
    $tabs['info_tab'] = array(
      'title'   => __( pll__('Технічна документація'), 'woocommerce' ),
      'priority'  => 20,
      'callback'  => 'info_product'
    ); 
  } else {
    unset ($tabs['info_tab']);    
  }
  return $tabs;
}
function info_product() {
  echo do_shortcode( '[wbcr_snippet id="4240"]');
};
// cat custom
function display_categories_with_hidden_subcategories() {
    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'parent'   => 0, 
        // 'hide_empty' => true,
    ]);

    if (!empty($categories)) {
      echo '<div class="categories-container">';

      echo '<ul class="item main-categories">';
      foreach ($categories as $category) {
        if(get_field('hidden_custom_menu', $category)) continue;

        $subcategories = get_terms([
          'taxonomy' => 'product_cat',
          'parent'   => $category->term_id,
          'hide_empty' => true,
        ]);

        $category_class = !empty($subcategories) ? 'category-item sub' : 'category-item';
        $icon = get_field('icon', $category);
        ?>
        <li class="<?php echo esc_attr($category_class); ?>" data-category-id="<?php echo esc_attr($category->term_id); ?>">
          <a href="<?php echo esc_url(get_term_link($category)); ?>">
            <?php if ($icon): ?>
              <img src="<?php echo $icon['url'] ?>" alt="<?php echo $icon['alt'] ?>" class="icon">
            <?php endif ?>
            <?php echo esc_html($category->name); ?>
          </a>
        </li>

        <?php
      }
      echo '</ul>';

      echo '<div class="item subcategories-container">';
      foreach ($categories as $category) {
        $subcategories = get_terms([
          'taxonomy' => 'product_cat',
          'parent'   => $category->term_id,
          'hide_empty' => true,
        ]);

        if (!empty($subcategories)) {
          echo '<div class="subcategory-block" id="subcategories-' . esc_attr($category->term_id) . '" style="display: none;">';
          foreach ($subcategories as $subcategory) {
        		if(get_field('hidden_custom_menu', $subcategory)) continue;

            $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
            $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'thumbnail');

            echo '<div class="subcategory-item">';
            if ($thumbnail_url) {
                echo '<a href="' . esc_url(get_term_link($subcategory)) . '"><img src="' . esc_url($thumbnail_url[0]) . '" alt="' . esc_attr($subcategory->name) . '"></a>';
            } else {
                echo '<a href="' . esc_url(get_term_link($subcategory)) . '"><img src="/shop/wp-content/uploads/woocommerce-placeholder.png" alt="Placeholder"></a>';
            }
            echo '<a href="' . esc_url(get_term_link($subcategory)) . '">' . esc_html($subcategory->name) . '</a>';
            echo '</div>';
          }
          echo '</div>';
        }
      }
      echo '</div>'; // subcategories-container

      echo '</div>'; // categories-container
    }
}
add_shortcode('display_categories_with_hidden_subcategories', 'display_categories_with_hidden_subcategories');
//
add_action( 'woocommerce_after_add_to_cart_form', 'lamp_exmaple_hook', 20);
function lamp_exmaple_hook(){
  echo do_shortcode( '[wbcr_snippet id="7333"]');   
} 
//
add_action('woocommerce_single_product_summary', 'price_example_hook', 10);

function price_example_hook() {
  $text = get_field('dop_price_info');
  if ($text) {    
    echo '<p class="after__price">*' . pll__('ціна') . ' за ' . esc_html($text) . '</p>';
  } else {
    echo '<p class="after__price">&nbsp;</p>';
  }
}

////////////////////////////////////////////
add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_catalog_ordering_args' );

function custom_catalog_ordering_args( $args ) {
  $args['meta_key'] = '_stock_status';
  $args['orderby'] = array( 'meta_value' => 'ASC');
  return $args;
}
// noindex for add-to-cart URLs
function add_noindex_for_add_to_cart() {
  if ( isset( $_GET['add-to-cart'] ) ) {
    echo '<meta name="robots" content="noindex, follow">' . "\n";
  }
}
add_action( 'wp_head', 'add_noindex_for_add_to_cart' );