<?php
/**
 * Wishlist element.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

// Exit if class does not exist.
if(!class_exists( 'YITH_WCWL' )) return;

$icon = flatsome_option('wishlist_icon');
$icon_style = flatsome_option('wishlist_icon_style');

?>
<li class="header-wishlist-icon">
  <?php if($icon_style) { ?><div class="header-button"><?php } ?>
  <a href="<?php echo YITH_WCWL()->get_wishlist_url(); ?>" class="wishlist-link <?php echo get_flatsome_icon_class($icon_style, 'small'); ?>">

    <?php if($icon){ ?>
      <i class="wishlist-icon icon-<?php echo $icon; ?>"
        <?php if(YITH_WCWL()->count_products() > 0){ ?>data-icon-label="<?php echo YITH_WCWL()->count_products() ; ?>" <?php } ?>>
				<img src="/shop/wp-content/uploads/3.svg" alt="wishlist">
      </i>
    <?php } ?>
		<?php if(flatsome_option('wishlist_title')) { ?>
    <p class="hide-for-medium header-wishlist-title">
  	  <?php pll_e('Вибране'); ?>
  	</p>
    <?php } ?>
  </a>
  <?php if($icon_style) { ?> </div> <?php } ?>
</li>
