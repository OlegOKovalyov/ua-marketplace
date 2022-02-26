<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core;

use \Inc\Core\Offer;

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

class OfferPromua extends Offer {

    public function set_vendorCode($offer) // XML tag <vendorCode>
    {
        if ( empty( $offer->get_sku() ) ) return ' ';
        return $offer->get_sku();
    }

}
