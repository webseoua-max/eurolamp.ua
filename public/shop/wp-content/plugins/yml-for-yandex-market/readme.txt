=== YML for Yandex Market ===
Contributors: icopydoc
Donate link: https://pay.cloudtips.ru/p/45d8ff3f
Tags: yml, yandex, market, export, woocommerce
Requires at least: 5.0
Tested up to: 6.9.1
Stable tag: 5.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates a YML-feed to upload to Yandex Market and not only.

== Description ==

=== Purpose of the plugin ===

Creates a YML-feed to upload to Yandex Market and not only. In addition to Yandex Market, the plugin is also used for:

* СберМегаМаркет
* Yandex Turbo-pages
* Yandex delivery
* GOODS.ru
* boo.ua
* 2gis.com
* AliExpress.com
* Маркетплейс Маркета (BERU)
* CDEK (partial support)
* OZON (partial support)
* ВКонтакте (vk.com) (partial support)
* EBay (partial support)
* Flowwow (flowwow.com) (partial support)
* Youla (youla.ru) (partial support)
and not only...

The plugin Woocommerce is required!

PRO version: [https://icopydoc.ru/product/yml-for-yandex-market-pro/](https://icopydoc.ru/product/yml-for-yandex-market-pro/?utm_source=wp-repository&utm_medium=content&utm_campaign=yml-for-yandex-market&utm_content=readme&utm_term=pro-version)

---
*If there is an appropriate supplement. See Extensions page.

= Format and method requirements for product data feeds =

For a better understanding of the principles of YML feed - read this: 
[https://yandex.ru/support/market-tech-requirements/index.html](https://yandex.ru/support/market-tech-requirements/index.html)  

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the entire `yml-for-yandex-market` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Export Yandex Market-->Settings screen to configure the plugin

== Frequently Asked Questions ==

= How to connect my store to Yandex market? =

Read this:
[https://yandex.ru/support/partnermarket/registration/how-to-register.html](https://yandex.ru/support/partnermarket/registration/how-to-register.html)
[https://yandex.ru/support/webmaster/goods-prices/connecting-shop.xml](https://yandex.ru/support/webmaster/goods-prices/connecting-shop.xml)
[https://yandex.ru/adv/edu/market-exp/vtoroy-magazin](https://yandex.ru/adv/edu/market-exp/vtoroy-magazin)

= What plugin online store supported by your plugin? =

Only Woocommerce.

= How to create a YML feed? =

Detailed instructions with screenshots [here](https://icopydoc.ru/kak-sozdat-woocommerce-yml-instruktsiya/?utm_source=wp-repository&utm_medium=content&utm_campaign=yml-for-yandex-market&utm_content=readme&utm_term=documentation)

Go to Yandex Market-->Settings. In the box called "Automatic file creation" select another menu entry (which differs from "off"). You can also change values in other boxes if necessary, then press "Save".
After 1-7 minutes (depending on the number of products), the feed will be generated and a link will appear instead of this message.

== Screenshots ==

1. screenshot-1.png

== Changelog ==

= 5.2.0 =
*Release Date 03-02-2026*

* Fixed interface bugs.
* Improved plugin performance.
* Added compatibility with the plugin: `ACF`.
* Code refactoring.

= 5.1.0 =
*Release Date 27-01-2026*

* Improved support VAT 22%.
* Added compatibility with plugins: `Perfect Woocommerce Brands`, `Saphali Custom Brands Pro`, `Premmerce Brands for WooCommerce`, `Woocomerce Brands Pro`, `YITH WooCommerce Brands Add-On`.
* Code refactoring.

= 5.0.26 =
*Release Date 24-12-2025*

* Added support VAT 22%.
* Improved the mechanism for generating tags `price` and `oldprice'.
* Fixed a security bug.

= 5.0.25 =
*Release Date 15-12-2025*

* Fixed interface bugs.
* The mechanism for automatically deleting products from the feed for Yandex Direct rules has been changed.

= 5.0.24 =
*Release Date 27-11-2025*

* Fixed interface bugs.
* Fixed the bug of double serialization of arrays.

= 5.0.23 =
*Release Date 15-11-2025*

* Added following tags: `certificate`, `service_life_days`, `comment_life_days`, `comment_validity_days`, `comment_warranty`, `keywords`.
* Fixed interface bugs.
* Fixed a weight tag to the rule for marketplace Flowwow.
* Fixed bugs with tags: `delivery-options`, `pickup-options`.

= 5.0.22 =
*Release Date 15-10-2025*

* Fixed an bug where the most recent product was not included in the feed.
* Added a weight tag to the rule for marketplace Flowwow.
* Added support for marketplace Youla.

= 5.0.21 =
*Release Date 18-09-2025*

* Fixed interface bugs.
* Added feed integrity check.

= 5.0.20 =
*Release Date 10-09-2025*

* Fixed compatibility with the plugin FOX - Currency Switcher Professional for WooCommerce.
* Improved mechanism for recording temporary files.

= 5.0.19 =
*Release Date 26-08-2025*

* Fixed interface bugs.

= 5.0.18 =
*Release Date 31-07-2025*

* Fixed bugs.
* Added the `okpd2` tag to the `AliExpress` rules.

= 5.0.17 =
*Release Date 30-07-2025*

* Updated plugin libraries.
* Fixed minor bugs.
* The option to choose the abbreviation `RUR` or `RUB` has been added for the Russian ruble.
* The mechanism of counting products in the feed has been changed.

= 5.0.16 =
*Release Date 23-07-2025*

* Added the new rule 'Яндекс.Товары'.
* Added compatibility with the plugin FOX - Currency Switcher Professional for WooCommerce.
* Fixed interface bugs.
* Fixed bugs in the following tags: `market_category`, `market_category_id`.

= 5.0.15 =
*Release Date 09-07-2025*

* Fixed interface bugs.
* Fixed bugs in the following tags: `collection_id`, `market_category`, `market_category_id`, `pickup_options`, `quality`, `reason`.

= 5.0.14 =
*Release Date 17-06-2025*

* Fixed interface bugs.
* Updated plugin libraries.

= 5.0.13 =
*Release Date 09-06-2025*

* Fixed minor bugs.

= 5.0.11 =
*Release Date 05-06-2025*

* Fixed minor bugs.

= 5.0.10 =
*Release Date 03-06-2025*

* Fixed minor bugs.

= 5.0.9 =
*Release Date 20-05-2025*

* Fixed bug with the `currency` tag.
* Fixed bug with pictures.
* Added the `oldprice` tag to the `Flowwow` rules.

= 5.0.8 =
*Release Date 26-04-2025*

* Fixed bug with the `currency` tag.


= 5.0.7 =
*Release Date 15-04-2025*

* Fixed bugs with the `outlet` tag.

= 5.0.6 =
*Release Date 15-04-2025*

* Fixed minor bugs.

= 5.0.5 =
*Release Date 07-04-2025*

* Fixed bugs with currencies.

= 5.0.4 =
*Release Date 05-04-2025*

* Fixed bugs with collections.

= 5.0.3 =
*Release Date 03-04-2025*

* Fixed minor bugs.
* Expanded the list of sources for product descriptions.

= 5.0.2 =
*Release Date 02-04-2025*

* Fixed bug with pictures.
* Now you can create feeds for Aliexpress.

= 5.0.1 =
*Release Date 31-03-2025*

* Fixed bug with `wp_admin_notice()`.

= 5.0.0 =
*Release Date 30-03-2025*

* New plugin core

== Upgrade Notice ==

= 5.2.0 =
*Release Date 03-02-2026*

* Fixed interface bugs.
* Improved plugin performance.
* Added compatibility with the plugin: `ACF`.
* Code refactoring.