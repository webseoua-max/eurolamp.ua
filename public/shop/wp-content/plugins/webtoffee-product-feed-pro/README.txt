=== WebToffee WooCommerce Product Feed & Sync Manager(Pro) ===
Contributors: webtoffee
Tags: google shop, facebook feed, facebook shop, google merchant center, instagram shop, facebook sync, facebook product catalog, facebook, woocommerce, products, facebook enhanced catalog, facebook catalog manager
Requires at least: 3.0.1
Tested up to: 6.8.1
Stable tag: 1.2.2
Requires PHP: 5.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

WooCommerce product catalog manager for Google, Facebook, and Instagram shops. Integrate WooCommerce product feeds with Google store and Facebook.

== Description ==

Connect your WooCommerce store with Google Merchant Center, Facebook, and Instagram shops. Generate error-free product feeds for your WooCommerce store and integrate them with popular marketplaces.

Turn your Facebook page or Instagram account into a perfect sales channel for your WooCommerce products. Promote WooCommerce store products on Google shops using product feed through the Google merchant center.

This Facebook catalog plugin allows you to integrate your WooCommerce store with Facebook and Instagram in a few steps by syncing your store’s products or by uploading product feeds.

For Google Shops, the plugin lets you connect your WooCommerce store with the Google merchant center using WooCommerce product feeds.   

== WebToffee WooCommerce Product Feed & Sync Manager Features ==

- **Easy setup** -  It allows you to easily connect your WooCommerce store to Google, Facebook, and Instagram shops using its simple interface. It won’t take more than 2 to 3 minutes to finish the setup.

- **Facebook sync** - You can sync your WooCommerce products with your Facebook business page to set up the Facebook shop.

- **Connect with Google shops** - Easily integrate with Google Merchant Center using WooCommerce product feed.

- **Smart filtering** - It lets you filter product categories and exclude unwanted products from syncing. You can also configure the number of products to perform the sync in batches.

- **Batch sync** - Sync thousands of WooCommerce products with Facebook, Google, or Instagram shops by enabling batch sync.

- **Category mapping** - Quickly map the categories in your store with the Facebook and Google shops categories and save the mapping for future use.

- **Edit mapping** - Allows you to <a href="https://www.webtoffee.com/modify-fb-category-mapping/">edit the existing Facebook category mapping</a>.

- **Product sync log** - View failed product syncs for easy debugging.


== Connect WooCommerce with Popular Sales Channels ==

Generate more sales for your WooCommerce store by connecting to popular sales channels.

= Integrating with Google Shopping and Google Merchant Center =

= ☞ Meet customers where they are already browsing and shopping =

Set up your shop on Google Merchant Center to make your store products appear in Google Shopping. Easily connect with your customers through Google search results. Display your WooCommerce store products on Google shopping through Google product feeds.

= ☞ Design your shop to reflect your brand =

Customize your collections with the product you want to showcase and use eye-catching imagery and design elements that build your brand identity. Setting up Google shops is easy and takes just a few minutes.

= ☞ Help people discover your products at scale =

Let people access your shop from Google searches, shopping ads, or product display pages. You can also run targeted ad campaigns to reach out to people who are browsing and buying from your shop.

= Sync with Facebook catalogs and Instagram product feeds =

= ☞ Make use of Facebook’s social media engagement =

Leverage different social media engagements offered by Facebook and Instagram such as likes, shares, comments, etc., to promote and sell your products to a wider audience including your friends, and family.

= ☞ Drive consideration and sales through conversation =

Let your customers quickly reach out to you through Facebook Messenger, and Instagram Direct, to ask questions, get support, track deliveries, and more.

= ☞ Grow your brand value with social proof =

Within your WooCommerce store, all your customers can do is view products, but on Facebook and Instagram, they can comment on the products, share them with their friends, etc., to give your products the benefit of social proof that will persuade others to try your products as well. 

= ☞ Tag products in Facebook or Instagram posts to boost sales =

Every time you make a post on WordPress you can tag any of your products to make sure that everyone who views your posts is aware of the products you have available in your store.

= ☞ Provide your customers with an easy purchasing experience =

Using Facebook and Instagram stores, your customers can make purchases while chatting with their friends, viewing their favorite feeds, etc. Your shop or products don't interrupt their social media experience in any way.

== Installation and Setup ==

You can visit this <a href="https://www.webtoffee.com/webtoffee-product-feed-user-guide/">Facebook sync plugin</a> setup guide to learn more about the installation, setup, and for adding your WooCommerce products to the Facebook catalog. 

== Support ==

If you experience any issue with the plugin you can reach the <a href="https://wordpress.org/support/plugin/webtoffee-product-feed/">support</a> to get quick help and resolve your issues as soon as possible. 


== Installation ==

**Pre-requisites**

- PHP Version: 5.6 or later
- WooCommerce version: 2.7 or later
- WordPress version: 3.0 or later

**Automatic Installation**

The automatic installation of the plugin is the easiest way to install the plugin. You can install the plugin without even leaving your browser window and from within your WordPress dashboard.
From your admin dashboard, go to **Plugins > Add New**. From the search box, type "Facebook enhanced catalog" or "Facebook shop catalog" and then search plugins. Click the install button on the WebToffee WooCommerce Product Feed & Sync Manager and then activate the plugin.
Now you are ready to start integrating your WooCommerce store with Google and Facebook Catalogs.

**Manual Installation**

In the manual installation, you will need to download the zip file of the plugin from the plugin page in WordPress.org. You can upload the file directly from your WordPress dashboard, or using an FTP application.

**Plugin Updates**

For every update of the plugin, you will be notified of the installed plugins page. You can directly update the plugin from your dashboard. We recommend that you keep the latest version of the plugin so that you can avail of the new functionalities and security features.

== Frequently Asked Questions ==

= Do I need a business manager account to set up the store? =

Yes, you need a business manager account to connect your WooCommerce product catalog to Facebook.

= Does this have any additional costs? =

No, WebToffee WooCommerce Product Feed & Sync Manager is a free plugin that allows you to set up your store on Facebook, Instagram, and Google Merchant Center using WooCommerce product feeds and sync.

= What currencies do you support? =

We support all currencies.

= Can I send product feed to Google shop directly from my WooCommerce store? =

Yes. The WebToffee WooCommerce Product Feed & Sync Manager plugin lets you automatically send and update WooCommerce product feeds to Google Merchant Center.

= How can I exclude specific products from syncing? =

You can use below code snippet on your active theme functions.php file.

`
add_filter( 'wt_facebook_sync_products', 'modify_wt_facebook_sync_products', 10, 1 );

function modify_wt_facebook_sync_products( $products ) {
	
	$exclude_products	 = array( 2432, 2433, 2434 ); // Enter product IDs to be excluded
	$sync_products		 = array_diff( $products, $exclude_products );
	return $sync_products;

}
`
= How can I set the product long description as default when uploading to facebook catalog? =

You can use below code snippet on your active theme functions.php file.

`
add_filter( 'wt_facebook_product_description_mode', 'wt_modify_product_description_mode', 10, 1 );

function wt_modify_product_description_mode( $mode ) {
	
	return 'long'; // Valid values are 'long' and 'short'

}
`

== Screenshots ==

1. Create new feed - Facebook / Instagram Shop.
2. Attribute mapping - Facebook / Instagram Shop.
3. Category mapping - Facebook / Instagram Shop.
4. Generate feed - Facebook / Instagram Shop.
5. Create new feed - Google Shop.
6. Attribute mapping - Google Shop.
7. Category mapping - Google Shop.
8. Generate feed - Google Shop.
9. Manage Feeds.
10. Menu navigation.
11. Connect to FB catalog
12. Facebook login.
13. Verification before connect.
14. Permission verification before connect.
15. Facebook permissions.
16. Manage connection.
17. Category mapping.
18. Product sync progress window.
19. FB catalog manager.
20. Logs


== Changelog ==

= 1.2.2  2025-08-25 =
*[Fix] - Issue with Stock status and Quantity value for backorders, Attribute option in Fruugo Feed
*[Compatibility] - Tested OK with WooCommerce 10.1.1

= 1.2.1  2025-06-24 =
*[Add] - Criteo channel support
*[Fix] - Sale price end date issue with Facebook catalog sync
*[Fix] – Vulnerability
*[Fix] – Function _load_textdomain_just_in_time issue for plugin transalations
*[Update] - Fruugo XML Feed structure
*[Compatibility] - Polylang compatibility
*[Compatibility] - Tested OK with WooCommerce 9.9.4
*[Compatibility] - Tested OK with WordPress 6.8.1


= 1.2.0  2025-02-24 =
*[Add] - Shopmania channel support
*[Compatibility] - Yaypricing discount rules plugin
*[Compatibility] - Alg_WC_Global_Shop_Discount plugin
*[Compatibility] - YITH WooCommerce Product Brands add-on
*[Compatibility] - Tested OK with WooCommerce 9.6.2

= 1.1.9  2025-01-13 =
*[Add] - Rakuten channel support
*[Compatibility] - Translate Multilingual sites – TranslatePress plugin
*[Compatibility] - YITH WooCommerce Dynamic Pricing & Discounts plugin
*[Compatibility] - WC Vendors – WooCommerce Multivendor, WooCommerce Marketplace, Product Vendors plugin
*[Compatibility] - Discount Rules for WooCommerce – Create Smart WooCommerce Coupons & Discounts, Bulk Discount, BOGO Coupons plugin by Flycart
*[Compatibility] - Discount Rules for WooCommerce - PRO plugin by Flycart
*[Compatibility] - Tested OK with WooCommerc 9.5.2

= 1.1.8  2024-11-27 =
*[Rollback] – compatibility with Discount Rules for WooCommerce by Flycart - Rollback for different scenario checking.
*[Enhancement] – checkout_link_template added in Google shop feed.

= 1.1.7  2024-11-19 =
*[Add] – X Shopping Manager(Twitter) channel.
*[Add] – Google manufacturer Center channel.
*[Enhancement] – Pinterest VAT field mapping.
*[Enhancement] – Fruugo feed improvements.
*[Enhancement] – Removed option – Trash product from FB catalog.
*[Fix] - XML header is missing in the generated feed.
*[Compatibility] – Discount Rules for WooCommerce by Flycart
*[Compatibility] – Tested OK with WordPress 6.7
*[Compatibility] - Tested OK with WooCommerce 9.4.1

= 1.1.6  2024-09-30 =
*[Add] – OnBuy channel support.
*[Add] – Missing fields added in Idealo feed.
*[Enhancement] – Feed processing performance improvements.
*[Fix] - Private and draft variations are included in the feed.
*[Compatibility] – Tested OK with WordPress 6.6.2
*[Compatibility] - Tested OK with WooCommerce 9.3.3

= 1.1.5  2024-08-28 =
*[Add] - Vivino product feed.
*[Add] - Yandex product feed.
*[Add] - Option to manage product additional fields.
*[Add] - Default mapping for quantity to sell on Facebook field.
*[Add] - Option to choose highest priced variation and selected variation quantity or sum of all variation quantity.
*[Add] - Convert shortcode for product description.
*[Tweak] - Remove emojis from Fruugo feed product title and description.
*[Compatibility] – Tested OK with WordPress 6.6
*[Compatibility] - Tested OK with WooCommerce 9.2.3

= 1.1.4  2024-06-20 =
*[Add] - Option to duplicate generated feeds.
*[Add] - Fruugo category update option in category add and edit pages.
*[Add] - Dokan vendor compatibility - vendor multi-select option.
*[Add] - Option to map parent_title and parent_description in product attribute list.
*[Tweak] - Type and select box within the attribute mapping drop-down list.
*[Tweak] - Fruugo specific category mapping instead of google categories.
*[Tweak] - Skroutz feed missing columns updated.
*[Update] - Google Product Review feed update. 
*[Compatibility] - Tested OK with WooCommerce 9.0.0

= 1.1.3  2024-05-01 =
*[Fix] - Filename validation improvement.
*[Add] - Support for Pinterest RSS and Heureka channels.
*[Add] - CSV file type support for Google feed.
*[Add] - Shipping label/data update for Bing feed TXT format.
*[Add] - Compatibility with Aelia currency switcher.
*[Update] - TikTok shop rollback due to XLSX template format issue. 
*[Compatibility] - Tested OK with WooCommerce 8.8.3


= 1.1.2  2024-04-01 =
*[Fix] – Feed contains draft/orphaned variations.
*[Add] – Support for Fruugo, Heureka, Pinterest RSS and Google local inventory ads channels.
*[Add] – Option to set custom time interval for feed auto-refresh.
*[Add] - Support for Multi-Currency with Curcy currency switcher plugin and FOX – Currency Switcher Professional for WooCommerce.
*[Add] – Support for popular SEO plugins ( Yoast SEO, Rank Math SEO with AI SEO Tools, All in One SEO – Best WordPress SEO Plugin – Easily Improve SEO Rankings & Increase Traffic).
*[Compatibility] – Tested OK with WooCommerce 8.7.0
*[Compatibility] – Tested OK with WordPress 6.5

= 1.1.1  2024-02-16 =
*[Fix] - FileType restrictions for channels.
*[Fix] - Price Limited to two decimal places.
*[Add] - Added support for TikTok Shop channel(via Product upload accelerator method) .
*[Add] - Compute option in the mapping screen to make dynamic changes for fields like price, stock while generating the feed.
*[Add] - Support for XLSX file format.
*[Update] - Google Product Category List.
*[Compatibility] - Tested OK with WooCommerce 8.5.2
*[Compatibility] - Tested OK with WordPress 6.4.3

= 1.1.0  2024-01-12 =
*[Add] - Filter Products by their Tags while generating the feed.
*[Add] - Integration of additional variations while syncing with Facebook.
*[Add] - WooCommerce product block editor support for simple products and product variations ( Beta ).
*[Fix] - Issue with WooCommerce Multi Currency( WCML ).
*[Fix] - Issue with WPML for product type and product URL columns.
*[Tweak] - Added filter to add default variation as simple product to the feed.
*[Compatibility] - Tested OK with WooCommerce 8.4.0

= 1.0.9  2023-12-07 =
*[Add] - Create custom product feed
*[Add] - New channel - Leguide Shop ( KelkooGroup )
*[Tweak] - Display the Facebook catalog sync log message
*[Compatibility] - Tested OK with PHP 8.2
*[Compatibility] - Tested OK with WooCommerce 8.3.1
*[Compatibility] - Tested OK with WordPress 6.4.2

= 1.0.8  2023-11-08 =
*[Fix] - Product Tags contain HTML links.
*[Fix] - 'Refresh Feed' button does not regenerate the feed file.
*[Add] - 'Additional Variant Attributes' options for Facebook feed.
*[Add] - Multiple options to filter product variations.
*[Add] - 'Global Attributes' and 'Local Attributes' in the mapping screen.
*[Add] - New channel - Leguide Shop ( Kelkoogroup )
*[Compatibility] - Dokan multivendor plugin.
*[Compatibility] - Tested OK with WooCommerce 8.2.1
*[Compatibility] - Tested OK with WordPress 6.4

= 1.0.7  2023-10-03 =
*[Fix] - Excluded parent product while generating feed and Facebook sync.
*[Add] - New channels - Shopzilla, Bizrate, Become & Price grabber shops.
*[Add] - Advanced product filtering options while generating the feed.
*[Enhancement] - Optimized the mapping process between the store category and Google/Facebook categories.
*[Compatibility] - Tested OK with WooCommerce 8.1.1

= 1.0.6  2023-08-28 =
*[Add] - New channels - PriceRunner, PriceSpy, Bing, Idealo & Skroutz shops
*[Compatibility] - Tested OK with WooCommerce 8.0.2 
*[Compatibility] - Tested OK with WordPress 6.3

= 1.0.5  2023-07-24 =
*[Fix] - Additional data missing when syncing with Facebook catalog sync
*[Fix] - Added the parent description when the child variation description is missing.
*[Add] - Category filter-based category mapping.
*[Add] - Shipping data fix for UK
*[Add] - Convert the category mapping selection mode to 'type and search'
*[Compatibility] - Tested OK with Facebook Graph API version 17.0.
*[Compatibility] - Tested OK with WooCommerce 7.9.

= 1.0.4  2023-06-26 =
*[Add] - Added Pinterest, TikTok, and Google Product Reviews.
*[Add] - Delete products from Facebook when trashed in WooCommerce.
*[Fix] - If the child variation description is missing add the parent description.
*[Tweak] - Combined Facebook & Google fields in the product edit page into a single tab (WebToffee Product Feed).
*[Compatibility] - Tested OK with WooCommerce 7.8.

= 1.0.3  2023-04-27 =
* [Add] Option to set static value for the product fields on the mapping screen
* [Add] Facebook catalog scheduled sync
* [Add] Buy on Google feed
* [Add] Google Promotions feed
* [Add] Custom auto refresh scheduled interval for Feed
* [Fix] Google Product category missing warning when uploading to Facebook catalog
* [Fix] Exclude category filter
* [Compatibility] Compatibility with Perfect Brands for WooCommerce plugin

= 1.0.2  2023-02-10 =
* [Add] XML format added to product feed types
* [Fix] Price with tax not showing in the feed
* [Fix] Product feed stock availability value corrected.
* [Fix] Delete scheduled action entry when deleting feed

= 1.0.1  2023-01-19 =
* update: Export only published products.
* add: Google local product inventory.

= 1.0.0 =
* Initial commit.

== Upgrade Notice ==

= 1.2.2 =
*[Fix] - Issue with Stock status and Quantity value for backorders, Attribute option in Fruugo Feed
*[Compatibility] - Tested OK with WooCommerce 10.1.1
