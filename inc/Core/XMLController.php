<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core;

use \Inc\Core\WCShopController;

class XMLController {

    public $marketplace; // замість хардкоду mrkvuamprozetka.xml (може бути 'rozetka', 'promua')

    public $xml_header;

    public $current_date;

    public $category_name;

    public $xml_filepath;

    public function __construct($marketplace)
    {
        $this->marketplace = $marketplace;

        $this->current_date = \date("Y-m-d H:i");

        $this->xml_header = '<yml_catalog date="' . $this->current_date . '"></yml_catalog>';

        $this->xml_filepath = WP_CONTENT_DIR . '/uploads/mrkvuamp' . $this->marketplace . '.xml';

    }

    public function array2xml($array, $xml = null)
    {
        if ( $xml === null ) {
            $xml = new \SimpleXMLElement( "<?xml version='1.0' encoding='UTF-8'?>
                <!DOCTYPE yml_catalog SYSTEM 'shops.dtd'>" . $this->xml_header );
        }
        $shop = $xml->addChild('shop');
        foreach( $array as $key => $value ){
            if ( is_array( $value ) ) {

                if ( 'currencies' == $key ) { // XML tag <currencies>
                    $currencies = $shop->addChild( 'currencies' );
                    $currency = $currencies->addChild( 'currency' );
                    $currency->addAttribute('id', $value[0]);
                    $currency->addAttribute('rate', "1");

                } else if ( 'categories' == $key ) { // XML tag <categories>
                    $categories = $shop->addChild( 'categories' );
                    foreach ($value as $k => $v) {
                        if ( $v ) {
                            $category = $categories->addChild( 'category', WCShopController::get_collation_category_name_by_id($v) );
                            $category->addAttribute('id', $v);
                            $category->addAttribute('rz_id', $v);
                        }
                    }

                } else {
                    $this->array2xml( $value, $shop->addChild( $key ) );
                }
            } else {
                if ( ! \is_numeric($key) ) {
                    $shop->addChild( $key, $value );
                }
            }
        }
        $xml->saveXML();
        return $xml->asXML( WP_CONTENT_DIR . "/uploads/mrkvuamp" . $this->marketplace . ".xml" );
    }

    public function last_xml_file_date()
    {
        header('Clear-Site-Data: "cache"'); // Clear browser cache for read last xml file
        if ( ! \file_exists( $this->xml_filepath ) ) {
            return;
        }
        if ( isset($_POST["mrkvuamp_submit_collation"] ) ) :
            echo '<span>( ' . date( " d.m.Y H:i:s" ) . ' UTC )</span>';
        else :
            echo '<span>( ' . clearstatcache() . date( " d.m.Y H:i:s", filemtime( $this->xml_filepath ) ) . ' UTC )</span>';
        endif;
    }

}
