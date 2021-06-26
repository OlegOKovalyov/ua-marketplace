<?php
/**
 * @link              https://morkva.co.ua/shop-2/woocommerce-rozetka-sync
 * @since             0.0.3
 * @package           MrkvUAMmarketplaces
 *
 * @wordpress-plugin
 * Plugin Name:       UA Marketplaces WooCommerce Plugin
 * Plugin URI:        https://morkva.co.ua/shop-2/woocommerce-rozetka-sync
 * Description:       Забезпечує взаїмодію WooCommerce інетернет-магазину з маркетплейсами Rozetka та PromUA.
 * Version:           1.1.2
 * Author:            MORKVA
 * Author URI:        https://morkva.co.ua
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mrkv-ua-marketplaces
 * Domain Path:       /languages
 */

 // If this file is called directly, abort.
 defined( 'ABSPATH' ) or die();

require_once 'ua-marketplace.php';

if ( ! function_exists( 'mrkv_uamkpl_fs' ) ) {
    // Create a helper function for easy SDK access.
    function mrkv_uamkpl_fs() {
        global $mrkv_uamkpl_fs;

        if ( ! isset( $mrkv_uamkpl_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $mrkv_uamkpl_fs = fs_dynamic_init( array(
                'id'                  => '5140',
                'slug'                => 'ua-marketplace',
                'premium_slug'        => 'nova-poshta-ttn-premium',
                'type'                => 'plugin',
                'public_key'          => 'pk_965fda814e9ffa9cbd3b7f9dbf029',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'mrkv_ua_marketplaces',
                    'account'        => false,
                    'contact'        => false,
                    'support'        => false,
                ),
            ) );
        }

        return $mrkv_uamkpl_fs;
    }

    // Init Freemius.
    mrkv_uamkpl_fs();
    // Signal that SDK was initiated.
    do_action( 'mrkv_uamkpl_fs_loaded' );
}
