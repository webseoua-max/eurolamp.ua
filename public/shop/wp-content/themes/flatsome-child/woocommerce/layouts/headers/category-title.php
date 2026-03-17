<?php
/**
 * Category title.
 *
 * @package          Flatsome/WooCommerce/Templates
 * @flatsome-version 3.16.0
 */

?>
<!--div class="shop-page-title category-page-title page-title <?php flatsome_header_title_classes() ?>">
	<div class="page-title-inner flex-row  medium-flex-wrap container">
	  <div class="flex-col flex-grow medium-text-center">
	  	<?php do_action('flatsome_category_title') ;?>
	  </div>
	  <div class="flex-col medium-text-center">
	  	<?php do_action('flatsome_category_title_alt') ;?>
	  </div>
	</div>
</div-->

<?php
  $filter_taxonomy = get_taxonomy('pa_tip-cokolja'); // Тип Цоколя
  $filter_terms = get_terms([
    'taxonomy'   => 'pa_tip-cokolja',
  ]);
?>

<div id="custom-wc-filter" class="row" style="display: none;">
	<div class="col tabs">
		<div class="tabs__title">
			<button type="button" class="tab__title active"><?php echo $filter_taxonomy->label ?></button>
		</div>
		<div class="tabs__content">
			<div class="tab__content active">
				<?php foreach ($filter_terms as $i => $term): ?>
					<button type="button" class="tab__panel" data-item-key="<?php echo $term->slug; ?>">
            <?php $icon = get_field('icon', $term); ?>
						<img src="<?php echo $icon['url'] ?>" alt="<?php echo $icon['alt'] ?>" class="tab__icon">
						<span class="tab__name"><?php echo $term->name ?></span>
					</button>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>
