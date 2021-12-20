<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core;

use \Inc\Base\BaseController;
use \Inc\Core\WCShopController;
use \Inc\Core\WCShopPromuaController;
use \Inc\Core\WCShop\WCShopOffer;
use \Inc\Core\WCShop\WCShopPromua\WCShopPromuaOffer;

require_once ('SimpleXMLElementExtended.php');

class XMLController extends BaseController {

    public $marketplace; // замість хардкоду mrkvuamprozetka.xml (може бути 'rozetka', 'promua')

    public $xml_header;

    public $current_date;

    public $category_name;

    public $xml_rozetka_filepath;
    public $xml_promua_filepath;

    public $plugin_uploads_dir;

    public $plugin_uploads_rozetka_xmlname;
    public $plugin_uploads_promua_xmlname;

    public $plugin_uploads_dir_path;
    public $plugin_uploads_dir_url;

    public $site_total_product_qty;

    public function __construct($marketplace)
    {
        if ( ! \class_exists( 'WooCommerce' ) ) {
            return;
        }
        global $woocommerce, $product;

        $this->marketplace = $marketplace;

        $this->current_date = \date("Y-m-d H:i");

        $this->xml_header = '<yml_catalog date="' . $this->current_date . '"></yml_catalog>';

        $baseController = new BaseController();

        $this->plugin_uploads_dir = $baseController->plugin_uploads_dir;

        $this->plugin_uploads_rozetka_xmlname = $baseController->plugin_uploads_rozetka_xmlname;
        $this->plugin_uploads_promua_xmlname = $baseController->plugin_uploads_promua_xmlname;

        $this->plugin_uploads_dir_path = $baseController->plugin_uploads_dir_path;
        $this->plugin_uploads_dir_url = $baseController->plugin_uploads_dir_url;

        $this->xml_rozetka_filepath = $baseController->plugin_uploads_dir_path . $this->plugin_uploads_rozetka_xmlname;
        $this->xml_promua_filepath = $baseController->plugin_uploads_dir_path . $this->plugin_uploads_promua_xmlname;

        $this->site_total_product_qty = $this->get_total_site_product_quantity();
    }

    public function array2xml($array, $xml = null)
    {
        if ( $xml === null ) {
            $xml = new SimpleXMLElementExtended( "<?xml version='1.0' encoding='UTF-8'?>
                <!DOCTYPE yml_catalog SYSTEM 'shops.dtd'>" . $this->xml_header );
        }
        $wcShopController = new WCShopController();
        $wcShopOffer = new WCShopOffer();

        $shop = $xml->addChild('shop'); // XML tag <shop>

        foreach( $array as $key => $value ){
            if ( is_array( $value ) ) {

                if ( 'currencies' == $key ) { // XML tag <currencies>
                    $currencies = $shop->addChild( 'currencies' );
                    $currency = $currencies->addChild( 'currency' );
                    $currency->addAttribute( 'id', $value[0] );
                    $currency->addAttribute( 'rate', "1" );

                } else if ( 'categories' == $key ) { // XML tag <categories>
                    $categories = $shop->addChild( 'categories' );
                    foreach ($value as $k => $v) {
                        if ( $v ) {
                            $category = $categories->addChild( 'category',
                                $wcShopController->get_collation_category_name_by_id( $v ) );
                            $category->addAttribute( 'id', $k );
                            $category->addAttribute( 'rz_id', $v );
                        }
                    }
                } else if ( 'offers' == $key ) { // XML tag <offers>
                    $offers = $shop->addChild( 'offers' );
                    foreach ($value as $k => $v) {
                        if ( $v ) {
                            $offer = $wcShopOffer->set_offer( $v, $offers );
                        }
                    }
                } else {
                    $this->array2xml( $value, $shop->addChild( $key ) );
                }
            } else {
                if ( ! \is_numeric( $key ) ) {
                    $shop->addChild( $key, $value );
                }
            }
        }

        // Before create new xml remove old xml
        if ( \file_exists( $this->xml_rozetka_filepath ) && \is_file( $this->xml_rozetka_filepath ) ) {
            \chmod( $this->xml_rozetka_filepath, 0777 );
            if ( ! \unlink( $this->xml_rozetka_filepath ) ) {
                //\error_log( "xml-file cannot be deleted due to an error" );
            }
            else {
                //\error_log( "xml-file has been deleted" );
            }
        }

        // Create XML-file
        header('Clear-Site-Data: "cache"');
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type: application/xml; charset=utf-8");
        clearstatcache();

        // Save the output to a variable
        $content = $xml->asXML();

        // Now open a file to write to
        $handle = fopen( $this->xml_rozetka_filepath, "w" );

        // Write the contents to the file
        fwrite( $handle, $content );

        //Close the file
        fclose( $handle );

        return $xml->asXML( $this->xml_rozetka_filepath );
    }

    public function last_xml_file_date()
    {
        // For remove xml link on 'Rozetka' tab when xml-file is not exists yet
        if ( 'mrkv_ua_marketplaces_rozetka' == $_GET['page'] ) {
            // header('Clear-Site-Data: "cache"'); // Clear browser cache for read last xml file
        }

        if ( ! \file_exists( $this->xml_rozetka_filepath ) ) { // This if may be only here!
            return;
        }

        // Add date and time after xml-link
        if ( isset($_POST["mrkvuamp_submit_collation"] ) ) :
            echo '<span>( ' . date( " d.m.Y H:i:s" ) . ' UTC )</span>';
        else :
            echo '<span>( ' . clearstatcache() . date( " d.m.Y H:i:s", filemtime( $this->xml_rozetka_filepath ) ) . ' UTC )</span>';
        endif;
    }

    public function last_promuaxml_file_date()
    {
        // For remove xml link on 'PromUA' tab when xml-file is not exists yet
        if ( 'mrkv_ua_marketplaces_promua' == $_GET['page'] ) {
            // header('Clear-Site-Data: "cache"'); // Clear browser cache for read last xml file
        }

        if ( ! \file_exists( $this->xml_promua_filepath ) ) { // This if may be only here!
            return;
        }

        // Add date and time after xml-link
        if ( isset($_POST["mrkvuamp_submit_promuaxml"] ) ) :
            echo '<span>( ' . date( " d.m.Y H:i:s" ) . ' UTC )</span>';
        else :
            echo '<span>( ' . clearstatcache() . date( " d.m.Y H:i:s", filemtime( $this->xml_promua_filepath ) ) . ' UTC )</span>';
        endif;
    }

    public function get_total_site_product_quantity()
    {
        // Get total product quantity on the site
        $args = array(
            'limit' => -1,
            'status' => array( 'publish' )
        );
        return count( wc_get_products( $args ) );
    }

    public function array2promuaxml($array, $xml = null)
    {
        if ( $xml === null ) {
            $xml = new SimpleXMLElementExtended( "<?xml version='1.0' encoding='UTF-8'?>" . $this->xml_header );
        }
        $wcShopPromuaController = new WCShopPromuaController();
        $wcShopOffer = new WCShopPromuaOffer();

        $shop = $xml->addChild('shop'); // XML tag <shop>

        foreach( $array as $key => $value ){
            if ( is_array( $value ) ) {

                if ( 'currencies' == $key ) { // XML tag <currencies>
                    $currencies = $shop->addChild( 'currencies' );
                    $currency = $currencies->addChild( 'currency' );
                    $currency->addAttribute( 'id', $value[0] );
                    $currency->addAttribute( 'rate', "1" );

                } else if ( 'categories' == $key ) { // XML tag <categories>
                    $categories = $shop->addChild( 'categories' );
                    foreach ($value[0] as $k => $v) {
                        if ( $v ) {
                            $category = $categories->addChild( 'category',
                                $wcShopPromuaController->get_promua_category_name_by_id( $v ) );
                            $category->addAttribute( 'id', $v );
                                $parentCategoryID = $wcShopPromuaController->get_parent_category_id($v);
                            if ( $parentCategoryID ) {
                                $category->addAttribute( 'parentId', $parentCategoryID );
                            }
                        }
                    }
                } else if ( 'offers' == $key ) { // XML tag <offers>
                    $offers = $shop->addChild( 'offers' );
                    foreach ($value as $k => $v) {
                        if ( $v ) {
                            $offer = $wcShopOffer->set_offer( $v, $offers );
                        }
                    }
                } else {
                    $this->array2promuaxml( $value, $shop->addChild( $key ) );
                }
            } else {
                if ( ! \is_numeric( $key ) ) {
                    $shop->addChild( $key, $value );
                }
            }
        }

        // Before create new xml remove old xml
        if ( \file_exists( $this->xml_promua_filepath ) && \is_file( $this->xml_promua_filepath ) ) {
            \chmod( $this->xml_promua_filepath, 0777 );
            if ( ! \unlink( $this->xml_promua_filepath ) ) {
                //\error_log( "xml-file cannot be deleted due to an error" );
            }
            else {
                //\error_log( "xml-file has been deleted" );
            }
        }

        // Create XML-file
        // header('Clear-Site-Data: "cache"');
        // header("Cache-Control: no-cache, must-revalidate");
        // header("Content-Type: application/xml; charset=utf-8");
        clearstatcache();

        // Save the output to a variable
        $content = $xml->asXML();

        // Now open a file to write to
        $handle = fopen( $this->xml_promua_filepath, "w" );

        // Write the contents to the file
        fwrite( $handle, $content );

        //Close the file
        fclose( $handle );

        return $xml->asXML( $this->xml_promua_filepath );
    }

}
