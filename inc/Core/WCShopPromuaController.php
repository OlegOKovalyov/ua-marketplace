<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core;

// use \Inc\Core\Marketplaces\FactoryAbstractAPI;
// use \Inc\Core\Marketplaces\FactoryAPI;
use \Inc\Core\WCShopController;

class WCShopPromuaController extends WCShopController {

    public $name;

    public $company;

    public $url;

    public $currencies = array();

    public $categories = array();

    public $offers = array();

    public function __construct()
    {

        $this->name = ( null !== \get_option( 'mrkv_uamrkpl_promua_shop_name' ) )
            ? \get_option( 'mrkv_uamrkpl_promua_shop_name' )
            : \get_bloginfo( 'name' );

        $this->company = ( null !== \get_option( 'mrkv_uamrkpl_promua_company' ) )
            ? \get_option( 'mrkv_uamrkpl_promua_company' )
            : \get_bloginfo( 'description' );

        $this->url = \get_bloginfo( 'url' );

        if ( ! \class_exists( 'WooCommerce' ) ) {
            return;
        }

        global $woocommerce, $product;

        $this->currencies[] = \get_option( 'woocommerce_currency' );

        $this->categories[] = $this->get_wc_promua_categories_ids();

        $this->offers = $this->get_wc_offers_ids();

    }

    public function get_wc_offers_ids()
    {
        $cats_slugs = array();
        foreach ( $this->categories as $category ) {
            if ( $term = get_term_by( 'id', $category, 'product_cat' ) ) {
                $cats_slugs[] = $term->slug;
            }
        }

        // Get wc-site all products
        $args = array(
            'limit' => -1,
            'status' => array( 'publish' ),
            'category' => $cats_slugs
        );
        $products = \wc_get_products( $args );

        foreach ( $products as $product ) {
            $offer_ids[] = $product->get_id();
        }

        return $offer_ids;
    }

    public function get_wc_promua_categories_ids()
    {
        $categories_ids = array();
        $args = array(
            'taxonomy'   => "product_cat",
            'orderby'    => 'id',
            'hide_empty' => false,
        );
        $product_categories = get_terms($args);
        foreach( $product_categories as $category ){
            $categories_ids[] = $category->term_id;
        }
        return $categories_ids;
    }

    public static function get_promua_category_name_by_id($id)
    {
        return \get_the_category_by_ID( $id );
    }

    public function get_parent_category_id($id)
    {
        $args = array(
            'taxonomy'   => "product_cat",
            'orderby'    => 'id',
            'hide_empty' => false,
        );
        $product_categories = get_terms($args);
        foreach( $product_categories as $category ) {
            if ( $id == $category->term_id ) {
                return $category->parent;
            }
        }
    }

}
