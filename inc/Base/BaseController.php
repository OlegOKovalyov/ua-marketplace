<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

class BaseController
{
    public $plugin_path;

    public $plugin_url;

	public $plugin;

    public $plugin_name;

    public $plugin_ver;

    public $activations = array();

    public $slug_activations = array();

    public $plugin_uploads_dir;
    public $plugin_uploads_rozetka_xmlname;
    public $plugin_uploads_dir_path;
    public $plugin_uploads_dir_url;

	public function __construct() {
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
		$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
		$this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/morkvawrs-plugin.php';
        $this->plugin_name = get_file_data( $this->plugin_path . '/morkvawrs-plugin.php', array( 'name'=>'Plugin Name' ) );
        $this->plugin_ver = get_file_data( $this->plugin_path . '/morkvawrs-plugin.php', array( 'ver'=>'Version' ) );

        $this->activations = array(
            'mrkvuamp_rozetka_activation'   => 'Rozetka',
            'mrkvuamp_promua_activation'    => 'PromUA' // for free version use only Rozetka
        );

        foreach ( $this->activations as $key => $value ) {
            $this->slug_activations[$key] = \strtolower( $value );
        }

        $this->plugin_uploads_dir = 'uamrktpls';
        $this->plugin_uploads_rozetka_xmlname = '/mrkvuamprozetka.xml';
        $this->plugin_uploads_dir_path = $this->create_uploads_dir( $this->plugin_uploads_dir );
        $this->plugin_uploads_dir_url = $this->get_uploads_url( $this->plugin_uploads_dir );
	}

    public function create_uploads_dir($plugin_uploads_dir)
    {
        $upload_dir = wp_upload_dir();
        $uploads_uamrktpls_dir = $upload_dir['basedir'] . '/' . $plugin_uploads_dir;
        if( ! file_exists( $uploads_uamrktpls_dir ) ) wp_mkdir_p( $uploads_uamrktpls_dir );
        return $uploads_uamrktpls_dir;
    }

    public function get_uploads_url($plugin_uploads_dir)
    {
        $upload_dir = wp_get_upload_dir();
        $uploads_uamrktpls_url = $upload_dir['baseurl'] . '/' . $plugin_uploads_dir;
        return $uploads_uamrktpls_url;
    }

    public function activated( string $key )
    {
        $option = get_option('mrkv_ua_marketplaces');

        return isset( $option[$key] ) ? $option[$key] : false;
    }
}
