<?php

/**
 * Manages the custom taxonomy used by the plugin.
 *
 * @link       https://icopydoc.ru
 * @since      5.2.0
 * @version    5.2.0 (03-02-2026)
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes
 */

/**
 * Manages the custom taxonomy used by the plugin.
 *
 * This class handles the registration and management of the 'yfym_collection' taxonomy,
 * which is used to group products for YML feed generation. It includes functionality for
 * adding, editing, and saving custom metadata fields associated with taxonomy terms.
 * The taxonomy provides a way to organize products into specific collections for export.

 *
 * @package    Y4YM
 * @subpackage Y4YM/includes
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class Y4YM_Taxonomy {

	/**
	 * Add new taxonomy.
	 * 
	 * @return void
	 */
	public function add_new_taxonomies() {

		$labels_arr = [
			'name' => __( 'Сollections for YML feed', 'yml-for-yandex-market' ),
			'singular_name' => 'Сollection',
			'search_items' => __( 'Search collection', 'yml-for-yandex-market' ),
			'popular_items' => null, // __('Популярные категории', 'yml-for-yandex-market'),
			'all_items' => __( 'All collections', 'yml-for-yandex-market' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit collection', 'yml-for-yandex-market' ),
			'update_item' => __( 'Update collection', 'yml-for-yandex-market' ),
			'add_new_item' => __( 'Add new collection', 'yml-for-yandex-market' ),
			'new_item_name' => __( 'New collection', 'yml-for-yandex-market' ),
			'menu_name' => __( 'Сollections for YML', 'yml-for-yandex-market' )
		];
		$args_arr = [
			'hierarchical' => true, // true - по типу рубрик, false - по типу меток (по умолчанию)
			'labels' => $labels_arr,
			'public' => true, // каждый может использовать таксономию, либо только администраторы, по умолчанию - true
			'show_ui' => true, // добавить интерфейс создания и редактирования
			'publicly_queryable' => false, // сделать элементы таксономии доступными для добавления в меню сайта. По умолчанию: значение аргумента public.
			'show_in_nav_menus' => false, // добавить на страницу создания меню
			'show_tagcloud' => false, // нужно ли разрешить облако тегов для этой таксономии
			'update_count_callback' => '_update_post_term_count', // callback-функция для обновления счетчика $object_type
			'query_var' => true, // разрешено ли использование query_var, также можно указать строку, которая будет использоваться в качестве него, по умолчанию - имя таксономии
			'rewrite' => [ // настройки URL пермалинков
				'slug' => 'yfym_collection', // ярлык
				'hierarchical' => false // разрешить вложенность
			]
		];
		register_taxonomy( 'yfym_collection', [ 'product' ], $args_arr );

	}

	/**
	 * Позволяет добавить дополнительные поля на страницу создания элементов таксономии (термина).
	 * Function for `(taxonomy)_add_form_fields` action-hook.
	 * 
	 * @param WP_Term $tag Current taxonomy term object.
	 * @param string $taxonomy Current taxonomy slug.
	 *
	 * @return void
	 */
	public function add_meta_product_cat( $term ) {

		?>
		<div class="form-field term-cat_meta-wrap">
			<label>
				<?php esc_html_e( 'Collection URL', 'yml-for-yandex-market' ); ?>
			</label>
			<input id="y4ym_collection_url" type="text" name="y4ym_cat_meta[yfym_collection_url]" value="" />
			<p>
				<?php esc_html_e( 'URL of the collection page', 'yml-for-yandex-market' ); ?>.
			</p>
		</div>
		<div class="form-field term-cat_meta-wrap">
			<label>
				<?php esc_html_e( 'Main picture URL', 'yml-for-yandex-market' ); ?>
			</label>
			<input id="y4ym_collection_picture" type="text" name="y4ym_cat_meta[yfym_collection_picture]" value="" />
			<p>
				<?php esc_html_e( 'For example', 'yml-for-yandex-market' ); ?>: <code>https://site.ru/picture-1.jpg</code>.
				<?php esc_html_e( 'URL of the main picture of the collection', 'yml-for-yandex-market' ); ?>.
			</p>
		</div>
		<div class="form-field term-cat_meta-wrap">
			<label>
				<?php esc_html_e( 'Add the main photos of products to the collection', 'yml-for-yandex-market' ); ?>
			</label>
			<input id="y4ym_collection_num_product_picture" type="number" step="1" min="0" max="20"
				name="y4ym_cat_meta[yfym_collection_num_product_picture]" value="" />
			<p>
				<?php esc_html_e( 'Indicate the number from 0 to 20', 'yml-for-yandex-market' ); ?>.
			</p>
		</div>
		<?php

	}

	/**
	 * Позволяет добавить дополнительные поля на страницу редактирования элементов таксономии (термина).
	 * Function for `(taxonomy)_edit_form_fields` action-hook.
	 * 
	 * @param WP_Term $tag Current taxonomy term object.
	 * @param string $taxonomy Current taxonomy slug.
	 *
	 * @return void
	 */
	public function edit_meta_product_cat( $term ) {

		global $post; ?>
		<tr class="form-field term-parent-wrap">
			<th scope="row" valign="top">
				<label>
					<?php esc_html_e( 'Collection URL', 'yml-for-yandex-market' ); ?>
				</label>
			</th>
			<td>
				<input id="y4ym_collection_url" type="text" name="y4ym_cat_meta[yfym_collection_url]"
					value="<?php echo esc_attr( get_term_meta( $term->term_id, 'yfym_collection_url', true ) ) ?>" />
				<p class="description">
					<?php esc_html_e( 'URL of the collection page', 'yml-for-yandex-market' ); ?>.
				</p>
			</td>
		</tr>
		<tr class="form-field term-parent-wrap">
			<th scope="row" valign="top">
				<label>
					<?php esc_html_e( 'Main picture URL', 'yml-for-yandex-market' ); ?>
				</label>
			</th>
			<td>
				<input id="y4ym_collection_picture" type="text" name="y4ym_cat_meta[yfym_collection_picture]"
					value="<?php echo esc_attr( get_term_meta( $term->term_id, 'yfym_collection_picture', true ) ) ?>" />
				<p>
					<?php esc_html_e( 'For example', 'yml-for-yandex-market' ); ?>: <code>https://site.ru/picture-1.jpg</code>.
					<?php esc_html_e( 'URL of the main picture of the collection', 'yml-for-yandex-market' ); ?>.
				</p>
			</td>
		</tr>
		<tr class="form-field term-parent-wrap">
			<th scope="row" valign="top">
				<label>
					<?php esc_html_e( 'Add the main photos of products to the collection', 'yml-for-yandex-market' ); ?>
				</label>
			</th>
			<td>
				<input id="y4ym_collection_num_product_picture" type="number" step="1" min="0" max="20"
					name="y4ym_cat_meta[yfym_collection_num_product_picture]"
					value="<?php echo esc_attr( get_term_meta( $term->term_id, 'yfym_collection_num_product_picture', true ) ) ?>" />
				<p class="description">
					<?php esc_html_e( 'Indicate the number from 0 to 20', 'yml-for-yandex-market' ); ?>.
				</p>
			</td>
		</tr>
		<?php

	}

	/**
	 * Сохранение данных в БД. Function for `create_(taxonomy)` and `edited_(taxonomy)` action-hooks.
	 * 
	 * @param int $term_id
	 * 
	 * @return void
	 */
	public function save_meta_product_cat( $term_id ) {

		if ( ! isset( $_POST['y4ym_cat_meta'] ) ) {
			return;
		}
		$y4ym_cat_meta = array_map( 'sanitize_text_field', $_POST['y4ym_cat_meta'] );
		foreach ( $y4ym_cat_meta as $key => $value ) {
			if ( empty( $value ) ) {
				delete_term_meta( $term_id, $key );
				continue;
			}
			update_term_meta( $term_id, $key, $value );
		}
		return;

	}

}