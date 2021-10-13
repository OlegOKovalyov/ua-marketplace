<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Core\WCShop\EditProduct;

use \Inc\Base\BaseController;

class ExtraVariationSettings {

    public $activations = array();

    public function register()
    {
        if ( empty( get_option( 'mrkv_ua_marketplaces' ) ) ) {
            return;
        }

        $base_controller = new BaseController();
        $marketplaces = $base_controller->activations;
        $activated_marketplaces = get_option( 'mrkv_ua_marketplaces' );

        foreach ( $activated_marketplaces as $key => $value ) {
            if ( $value ) {
                $this->activations[] = $marketplaces[$key];
            }
        }

        add_action( 'woocommerce_variation_options_pricing', array( $this, 'add_product_variation_id_field' ), 10, 3 );
        add_action( 'woocommerce_save_product_variation', array( $this, 'save_product_variation_id_field' ), 10, 2 );
        
        add_action( 'woocommerce_variation_options_pricing', array( $this, 'add_image_field' ), 10, 3 );
        add_action( 'woocommerce_save_product_variation', array( $this, 'save_image_field' ), 10, 2 );
    }

    public function add_image_field( $loop, $variation_data, $variation ) // '{Marketplace} Variation Image URL' field
    {
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            woocommerce_wp_text_input(
                array(
                    'id' => "mrkvuamp_{$slug}_variation_image[" . $loop . "]",
                    'name' => "mrkvuamp_{$slug}_variation_image[" . $loop . "]",
                    'class' => 'short mrkvuamp-full-width',
                    'label' => __( "{$activation} Variation Image URL", 'mrkv-ua-marketplaces' ),
                    'value' => get_post_meta( $variation->ID, "mrkvuamp_{$slug}_variation_image", true ),
                    'type' => 'text',
                    'data_type' => 'url',
                    'desc_tip' => true,
                    'description' => __( 'Якщо ввести URL потрібного фото, саме це фото потрапить в xml замість того, що на сторінці товару.', 'mrkv-ua-marketplaces' )
                )
            );
        }
    }

    public function add_product_variation_id_field( $loop, $variation_data, $variation ) // '{Marketplace} Variation ID' field
    {
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            if ( 'rozetka' == $slug ) {
                woocommerce_wp_text_input (
                    array(
                        'id' => "mrkvuamp_{$slug}_product_variation_id[" . $loop . "]",
                        'name' => "mrkvuamp_{$slug}_product_variation_id[" . $loop . "]",
                        'wrapper_class' => 'mrkvuamp-short-width',
                        'label' => __( "{$activation} Variation ID", 'mrkv-ua-marketplaces' ),
                        'value' => get_post_meta( $variation->ID, "mrkvuamp_{$slug}_product_variation_id", true ),
                        'type' => 'text',
                        'desc_tip' => true,
                        'description' => __( 'Якщо ввести значення, саме воно потрапить в xml замість id варіації, який встановлений на сайті.', 'mrkv-ua-marketplaces' ),
                    )
                );
            }
        }
    }

    public function save_image_field($variation_id, $i)
    {
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );
            $image_field = $_POST["mrkvuamp_{$slug}_variation_image"][$i];

            if ( isset( $image_field ) ) { // Save '{Marketplace} Variation Image URL'
                update_post_meta( $variation_id, "mrkvuamp_{$slug}_variation_image", esc_attr( $image_field ) );
            }
        }
    }

    public function save_product_variation_id_field($variation_id, $i)
    {
        foreach ( $this->activations as $activation  ) {
            $slug =  \strtolower( $activation );

            if ( 'rozetka' == $slug ) {
                $variation_id_field = $_POST["mrkvuamp_{$slug}_product_variation_id"][$i];

                if ( isset( $variation_id_field ) ) { // Save '{Marketplace} Variation ID'
                    update_post_meta( $variation_id, "mrkvuamp_{$slug}_product_variation_id", esc_attr( $variation_id_field ) );
                }
            }
        }
    }

}
