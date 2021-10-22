<?php

/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

use \Inc\Base\BaseController;
use \Inc\Core\WCShop\WCShopCollation;

class WPCRONHandler extends BaseController
{

    public function register()
    {
        if ( empty( get_option( 'mrkv_ua_marketplaces' ) ) ) {
            return;
        }
        $activation_options_name = get_option( 'mrkv_ua_marketplaces' );

        foreach ( $activation_options_name as $key => $value ) {
            if ( $value ) {

                // Get marketplace name from wp-option 'mrkv_ua_marketplaces'
                if (preg_match('/mrkvuamp_(.*?)_activation/', $key, $match) == 1) {
                    $marketplace = $match[1];
                }

                if ( 'rozetka' == $marketplace ) {
                    $baseController = new BaseController( $marketplace );
                    // Create xml-file name for each active marketplace
                    $xml_name_key = 'plugin_uploads_' . $marketplace . '_xmlname';
                    $xml_fileurl = $baseController->plugin_uploads_dir_url . $baseController->$xml_name_key;

                    // Activate CRON-task for generation xml-прайс
                    if ( file_exists( $baseController->plugin_uploads_dir_path . $baseController->$xml_name_key ) ) {
                        // add_filter( 'cron_schedules', array( $this, 'add_five_minutes_cron_interval' ) ); // For test CRON
                        add_action( 'admin_head', array( $this, 'activate_xml_update' ) );
                        add_action( 'mrkvuamp_update_xml_hook', array( $this, 'update_xml_exec' ) );
                    }
                }
            }
        }
    }

    public function activate_xml_update()
    {
        if( ! wp_next_scheduled( 'mrkvuamp_update_xml_hook' ) ) {
            wp_schedule_event( time(), 'daily', 'mrkvuamp_update_xml_hook' ); // For FREE-version
            // wp_schedule_event( time(), 'five_minutes', 'mrkvuamp_update_xml_hook' ); // For test CRON
        }
    }

    public function update_xml_exec()
    {
        // Create WooCommerce internet-shop Object for Rozetka
        $mrkv_uamrkpl_shop = new WCShopCollation('shop');
        $mrkv_uamrkpl_shop_arr = (array) $mrkv_uamrkpl_shop;

        // Create XML-price for marketplace Rozetka
        $converter = new \Inc\Core\XMLController( 'rozetka' );
        $xml = $converter->array2xml( $mrkv_uamrkpl_shop_arr );
        exit;
    }

    // public function add_five_minutes_cron_interval( $schedules ) { // For test CRON
    //     $schedules['five_minutes'] = array(
    //         'interval' => 300,
    //         'display'  => esc_html__( 'Every Five Minutes' ), );
    //     return $schedules;
    // }

}
