<?php

if (!defined('WPINC')) {
    exit;
}


$post_columns = array(
    'id' => 'Product Id[id]',
    'brand' => 'Brand[brand]',
    'title' => 'Product Title[title]',
    'gtin' => 'GTIN[gtin]',
    'mpn' => 'MPN[mpn]',
    'description' => 'Product Description[description]',
    'disclosure_date' => 'Disclosure date[disclosure_date]',
    'release_date' => 'release_date[release_date]',
    'suggested_retail_price' => 'suggested_retail_price[suggested_retail_price]',
    'product_name' => 'product_name[product_name]',
    'product_line' => 'product_line[product_line]',
    'product_type' => 'product_type[product_type]',
    'item_group_id' => 'item_group_id[item_group_id]',
    'color' => 'Color[color]',
    'material' => 'material[material]',
    'size' => 'size[size]',
    'size_type' => 'size_type[size_type]',
    'size_system' => 'size_system[size_system]',
    'gender' => 'gender[gender]',
    'age_group' => 'age_group[age_group]',
    'product_highlight' => 'product_highlight[product_highlight]',
    'product_detail' => 'product_detail[product_detail]',
    'feature_description' => 'feature_description[feature_description]',
    'image_link' => 'Main Image[image_link]',
    'product_type' => 'Product Categories[product_type] ',
    
    'wtimages_1' => 'Additional Image 1 [additional_image_link]',
    'wtimages_2' => 'Additional Image 2 [additional_image_link]',
    'wtimages_3' => 'Additional Image 3 [additional_image_link]',
    'wtimages_4' => 'Additional Image 4 [additional_image_link]',
    'wtimages_5' => 'Additional Image 5 [additional_image_link]',
    'wtimages_6' => 'Additional Image 6 [additional_image_link]',
    'wtimages_7' => 'Additional Image 7 [additional_image_link]',
    'wtimages_8' => 'Additional Image 8 [additional_image_link]',
    'wtimages_9' => 'Additional Image 9 [additional_image_link]',
    'wtimages_10' => 'Additional Image 10 [additional_image_link]',
    'video_link' => 'video_link[video_link]',
    'product_page_url' => 'Product URL[link]',
);

return apply_filters('wt_pf_googlemfc_product_post_columns',$post_columns);


