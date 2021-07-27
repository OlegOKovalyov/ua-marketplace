=== Ua Marketplace ===
Contributors: bandido, olegkovalyov
Tags: woocommerce, розетка, rozetka, ecommerce, xml
Requires at least: 5.0
Tested up to: 5.7
Requires PHP: 7.0
Stable tag: 1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html



Синхронізуйтесь з українськими маркетплейсами швидко та зручно.

== Description ==

Написали "з нуля", враховуючи досвід 30-ти магазинів, що уже використовують наш плагін Woo Rozetka Sync.

Швидкість генерації xml зросла втричі, в порівнянні зі старим кодом.

Підтримуються монобрендові та мультибрендові магазини.

Автоматична ре-генерація xml двічі на добу (wp-cron).

Підтримує маркетплейси Rozetka та Prom (скоро).

Pro-версія:

https://morkva.co.ua/shop-2/ua-marketplaces-woocommerce-plugin


== Інструкція ==

https://youtu.be/CYhov5nuET4


== Що нового? ==

= 1.2.1.0 =
* [new] змінено теку формування прайсу на uploads uamrktpls

= 1.2.0 =
* [fix] виправлено формування тегу name для варіативних товарів
* [fix] виправлено формування тегу description для варіативних товарів
* WooCommerce tested 5.5

= 1.1.11 =
* [new] в xml-тег categoryId кожного offer тепер прописується id товару з магазину
* [deprecated] прибрані поля Rozetka ID Category та ID Category з мета-полів товару
* [fix] в xml-тег name тепер потрапляє значення поля Rozetka Title
* [fix] в xml-тег description тепер потрапляє значення поля Rozetka Description

= 1.1.10 =
* [new] додані параметри варіації в xml-тег name
* [fix] поновлена можливість редагувати кастомні поля вкладки Rozetka
* [fix] відновлена робота Quick Edit

= 1.1.9 =
* [fix] виправлено значення атрибуту id xml-тега <category> у прайсі

= 1.1.8 =
* [new] додана можливість коригувати ціни всіх товарів в прайсі на коефіцієнт (наприклад +15%)

= 1.1.7 =
* [fix] виправлена помилка по обмеженню кількості товарів в xml

= 1.1.6 =
* [fix] виправлені помічені помилки

= 1.1.2 =
* [new] встановлений інструмент аналітики freemius

= 1.1.1 =
* [new] змінена назва головного файлу плагіну

= 1.1 =
* [new] додані поля Title та Category ID до швидкого редагування товарів
* [new] створена зворотня сумісність з PHP 5.6 - 7.0
* [fix] виправлений баг на сторінці варіативного товару

= 1.0.7.1 =
* [fix] змінений алгоритм формування вмісту xml-тегу description

= 1.0.7 =
* [new] доданий функціонал для поля Rozetka Description

= 1.0.6 =
* [new] доданий функціонал для поля Rozetka Variation Image

= 1.0.5 =
* [new] доданий функціонал для вкладки Rozetka в Дані товару

= 1.0.3 =
* [new] додане поле Rozetka Variation Image до варіацій

= 1.0.2 =
* [new] додана вкладка Rozetka до Дані товару

= 1.0.1 =
* [fix] прибрані службові файли

= 1.0 =
* реліз
* WordPress tested 5.7.2
* WooCommerce tested 5.2


== Screenshots ==
1. screenshot-1.jpg
2. screenshot-2.jpg
3. screenshot-3.jpg

== Підтримка ==

Якщо виникла помилка при встановленні чи використанні плагіну, - пишіть на support@morkva.co.ua або в нашу групу в Facebook https://www.facebook.com/groups/morkvasupport/.
