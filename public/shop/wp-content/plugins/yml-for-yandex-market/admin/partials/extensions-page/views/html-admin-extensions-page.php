<?php
/**
 * Print Extensions page.
 * 
 * @version 5.0.23 (15-11-2025)
 * @see     
 * @package 
 */
defined( 'ABSPATH' ) || exit;
?>
<style>
	.notice {
		display: none;
	}

	#yfym_extensions .grid-container {
		display: grid;
		gap: 20px;
		grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
		align-items: start;
		justify-items: center;
		padding: 20px;
		max-width: 1200px;
		margin: 0 auto;
	}

	#yfym_extensions .grid-container .extension-card {
		background-color: #ffffff;
		border-radius: 10px;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		padding: 20px;
		max-width: 400px;
		width: 100%;
	}

	#yfym_extensions .grid-container .extension-card img {
		max-width: 100%;
		height: auto;
		display: block;
		margin: 0 auto;
		object-fit: cover;
	}

	#yfym_extensions .grid-container .extension-card h2 {
		font-size: 24px;
		margin-bottom: 10px;
	}

	#yfym_extensions .grid-container .extension-card p {
		font-size: 16px;
		line-height: 1.5;
		margin-bottom: 10px;
	}

	#yfym_extensions .grid-container .extension-card ul {
		list-style-type: circle;
		padding-left: 20px;
		margin-top: 25px;
		margin-bottom: 20px;
	}

	#yfym_extensions .grid-container p {
		font-size: 18px;
		text-align: justify;
		margin: 20px 0;
	}

	#yfym_extensions .grid-container .description-list {
		list-style-type: none;
		padding: 0;
		margin: 0;
	}

	#yfym_extensions .grid-container .description-list li {
		display: flex;
		align-items: center;
		margin-bottom: 10px;
	}

	#yfym_extensions .grid-container .description-list li::before {
		content: "✔";
		color: green;
		margin-right: 10px;
		font-size: 1.2em;
	}

	#yfym_extensions .grid-container .description-list li.red-cross::before {
		content: "✘";
		color: red;
		margin-right: 10px;
		font-size: 1.2em;
	}

	#yfym_extensions .grid-container .description-list li span {
		font-size: 18px;
		font-weight: bold;
		text-decoration: underline;
	}

	#yfym_extensions .grid-container .button-primary {
		display: inline-block;
		/* Изменение на inline-block */
		background-color: #181a1c !important;
		color: white;
		border: none;
		padding: 10px 20px;
		cursor: pointer;
		border-radius: 5px;
		font-weight: bold;
		font-size: 18px;
		margin: 20px auto;
		text-decoration: none;
		transition: background-color 0.3s ease-in-out;
		max-width: 200px;
		/* Ограничение максимальной ширины */
	}

	#yfym_extensions .grid-container .button-primary:hover {
		background-color: #3d4247 !important;
		border-color: #4b5157 !important;
	}

	/* Обновленный медиа-запрос для экранов меньше 1152px */
	@media (max-width: 1152px) {
		#yfym_extensions .grid-container {
			grid-template-columns: 1fr;
			/* Один столбец для экранов меньше 1152px */
		}
	}
</style>
<div id="yfym_extensions" class="wrap">
	<div>
		<h1 style="font-size: 32px; text-align: center;"><?php esc_html_e( 'Upgrade the', 'yml-for-yandex-market' ); ?>
			YML for Yandex Market</h1>
		<hr />
	</div>
	<div class="grid-container">
		<div class="extension-card">
			<a href="https://icopydoc.ru/product/yml-for-yandex-market-pro/?utm_source=yml-for-yandex-market&utm_medium=purchase&utm_campaign=basic_version&utm_content=extensions-page&utm_term=product-image-yml-for-yandex-market-pro"
				target="_blank">
				<img style="max-width: 100%; display: block; margin: 0 auto;"
					src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ); ?>/imgs/yml-for-yandex-market-pro-350x350.jpg"
					alt="img">
			</a>
			<h2 style="text-align: center;">YML for Yandex Market PRO</h2>
			<ul class="description-list">
				<li><span><?php esc_html_e( 'All features of the free version', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li><span><?php esc_html_e( 'Automatic mark-up on products', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li><span><?php esc_html_e( 'Filter products by price', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( 'Product filter based on stock availability', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li><span><?php esc_html_e( 'Filter products by categories and tags', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li><span><?php esc_html_e( 'Product filter by brand', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( 'Product filter by ID', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( 'Product filter by "checkmark"', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( 'Support for UTM tags', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( 'Support for RS tags', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( 'Multiple photos instead of one', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( 'Constructor params', 'yml-for-yandex-market' ); ?></span></li>
				<li class="red-cross"><span><?php esc_html_e( 'SET format', 'yml-for-yandex-market' ); ?>:
						<?php esc_html_e( 'Service providers', 'yml-for-yandex-market' ); ?></span></li>
				<li class="red-cross"><span><?php esc_html_e( 'SET format', 'yml-for-yandex-market' ); ?>:
						<?php esc_html_e( 'Vehicles', 'yml-for-yandex-market' ); ?></span></li>
				<li class="red-cross"><span><?php esc_html_e( 'SET format', 'yml-for-yandex-market' ); ?>:
						<?php esc_html_e( 'Education', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( '1 year of technical support', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( 'Unlimited updates throughout the year', 'yml-for-yandex-market' ); ?></span>
				</li>
			</ul>
			<p style="text-align: center;">
				<a class="button-primary"
					href="https://icopydoc.ru/product/yml-for-yandex-market-pro/?utm_source=yml-for-yandex-market&utm_medium=purchase&utm_campaign=basic_version&utm_content=extensions-page&utm_term=poluchit-yml-pro"
					target="_blank">
					<?php esc_html_e( 'Get Now', 'yml-for-yandex-market' ); ?>
				</a>
			</p>
		</div>
		<div class="extension-card">
			<a href="https://icopydoc.ru/product/plagin-yml-for-yandex-market-sets/?utm_source=yml-for-yandex-market&utm_medium=purchase&utm_campaign=basic_version&utm_content=extensions-page&utm_term=product-image-yml-for-yandex-market-sets"
				target="_blank">
				<img style="max-width: 100%; display: block; margin: 0 auto;"
					src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ); ?>/imgs/yml-for-yandex-market-sets-350x350.jpg"
					alt="img">
			</a>
			<h2 style="text-align: center;">YML for Yandex Market SETS</h2>
			<ul class="description-list">
				<li><span><?php esc_html_e( 'All features of the free version', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li class="red-cross">
					<span><?php esc_html_e( 'Automatic mark-up on products', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li class="red-cross">
					<span><?php esc_html_e( 'Filter products by price', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li class="red-cross">
					<span><?php esc_html_e( 'Product filter based on stock availability', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li class="red-cross">
					<span><?php esc_html_e( 'Filter products by categories and tags', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li class="red-cross">
					<span><?php esc_html_e( 'Product filter by brand', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li class="red-cross">
					<span><?php esc_html_e( 'Product filter by ID', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li class="red-cross">
					<span><?php esc_html_e( 'Product filter by "checkmark"', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li class="red-cross">
					<span><?php esc_html_e( 'Support for UTM tags', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li class="red-cross">
					<span><?php esc_html_e( 'Support for RS tags', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li class="red-cross">
					<span><?php esc_html_e( 'Multiple photos instead of one', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li class="red-cross"><span><?php esc_html_e( 'Constructor params', 'yml-for-yandex-market' ); ?></span>
				</li>
				<li><span><?php esc_html_e( 'SET format', 'yml-for-yandex-market' ); ?>:
						<?php esc_html_e( 'Service providers', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( 'SET format', 'yml-for-yandex-market' ); ?>:
						<?php esc_html_e( 'Vehicles', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( 'SET format', 'yml-for-yandex-market' ); ?>:
						<?php esc_html_e( 'Education', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( '1 year of technical support', 'yml-for-yandex-market' ); ?></span></li>
				<li><span><?php esc_html_e( 'Unlimited updates throughout the year', 'yml-for-yandex-market' ); ?></span>
				</li>
			</ul>
			<p style="text-align: center;">
				<a class="button-primary"
					href="https://icopydoc.ru/product/plagin-yml-for-yandex-market-sets/?utm_source=yml-for-yandex-market&utm_medium=purchase&utm_campaign=basic_version&utm_content=extensions-page&utm_term=poluchit-yml-sets"
					target="_blank">
					<?php esc_html_e( 'Get Now', 'yml-for-yandex-market' ); ?>
				</a>
			</p>
		</div>
	</div>
	<div>
		<hr />
		<p>* <?php esc_html_e( 'Both versions can work together with each other', 'yml-for-yandex-market' ); ?>.</p>
	</div>
</div>