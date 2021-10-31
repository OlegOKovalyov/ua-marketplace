<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop\WCShopPromua;

use \Inc\Core\WCShopPromuaController;
use \Inc\Core\WCShop\WCShopPromua\WCShopPromuaOffer;

class WCShopPromuaOfferSimple extends WCShopPromuaOffer {

    public function set_simple_offer($id, $offers)
    {
        // Checkbox '{Marketplace} xml' custom field
        foreach ( $this->slug_activations as $slug  ) {
            $mrktplc_not_xml = get_post_meta( $id , "mrkvuamp_{$slug}_not_xml", true);
            if ( $mrktplc_not_xml ) return;
        }

        $offer = $this->set_offer_content( $id, $offers ); // XML tag <offer>

            $url = $this->set_url( $id, $offer ); // XML tag <url>

            $price = $this->set_price( $offer ); // XML tag <price>

            $currencyId = $this->set_currency_id( $offer ); // XML tag <currencyId>

            $categoryId = $this->set_category_id( $id, $offer ); // XML tag <categoryId>

            $picture = $this->set_picture( $id, $offer ); // XML tag <picture>

            $name = $this->set_name( $id, $offer ); // XML tag <name>

            $vendor = $this->set_vendor( $id, $offer ); // XML tag <vendor>

            $description = $this->set_description( $id, $offer ); // XML tag <description>

            $param = $this->set_param( $id, $offer ); // XML tag <param>

            $stock_quantity = $this->set_stock_quantity($id, $offer, $offers); // XML tag <stock_quantity>
    }

    public function set_offer_content($id, $offers) // XML tag <offer>
    {
        $offer = $offers->addChild( 'offer' );
        $offer->addAttribute('id', $id);
        $this->_product = \wc_get_product( $id ); // Get product object
        $is_available = $this->_product->is_in_stock() ? 'true' : 'false';
        $offer->addAttribute( 'available', $is_available );
        return $offer;
    }

    public function set_url($id, $offer) // XML tag <url>
    {
        return $offer->addChild( 'url', \get_permalink( $id ) );
    }

    public function set_price($offer) // XML tag <price>
    {
        $price = $this->_product->get_regular_price();
        return $offer->addChild( 'price', $price );
    }

    public function set_currency_id($offer) // XML tag <currencyId>
    {
        return $offer->addChild( 'currencyId', $this->get_wc_currency_id() );
    }

    public function set_category_id($id, $offer) // XML tag <categoryId>
    {
        return $offer->addChild( 'categoryId', $this->get_wc_promua_category_id( $id ) );
    }

    public function set_picture($id, $offer) // XML tag <picture>
    {
        $image_urls = $this->get_product_image_urls( $id );
        if ( \is_array( $image_urls ) ) {
            foreach ( $image_urls as $key => $value ) {
                if ( empty( $value ) ) continue;
                $offer->addChild( 'picture', $value );
            }
        }
    }

    public function set_name($id, $offer) // XML tag <name>
    {
        return $offer->addChild( 'name', $this->get_product_title( $id ) );
    }

    public function set_vendor($id, $offer) // XML tag <vendor>
    {
        $vendor_name = $this->get_product_vendor( $id );
        return $offer->addChild( 'vendor', $vendor_name );
    }

    public function set_description($id, $offer) // XML tag <description>
    {
        return $offer->addChildWithCDATA( 'description', nl2br( $this->get_product_description( $id ) ) );
    }

    public function set_param($id, $offer) // XML tag <param>
    {
        $param_labels = array();
        $param_values = array();
        $params = $this->_product->get_attributes();
        foreach ( $params as $key => $value ) {
            if ( false !== strpos( $key, 'pa_' ) ) {
                $param_labels[] = \wc_attribute_label( $key );
            } else {
                $param_labels[] = $value->get_name();
            }
            $param_values[] = $this->_product->get_attribute( $key );
            continue;
        }

        for ( $i = 0; $i < \sizeof( $param_values ) ; $i++ ) {
            $param = $offer->addChild( 'param', $param_values[$i] );
            $param->addAttribute( 'name', $param_labels[$i] );
        }
    }

    public function set_stock_quantity($id, $offer, $offers) // XML tag <available>
    {
        $stock_status = $this->_product->get_stock_status();
        if ( 'instock' == $stock_status ) {
            $availability = 'true';
        }
        if ( 'outofstock' == $stock_status ) {
            $availability = ' ';
        }
        if ( 'onbackorder' == $stock_status ) {
            $availability = 'false';
        }

        return $offer->addChild( 'available', $availability );
    }

}
