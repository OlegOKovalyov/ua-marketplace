<?php

/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Base;

use \Inc\Base\BaseController;
use \Inc\Core\XMLController;

class Enqueue extends BaseController
{

	public function register()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 99 );
	}

	public function enqueue() {
		// enqueue all our scripts
		// wp_deregister_script( 'jquery' );
		wp_enqueue_style( 'morkvauamarketplacestyle', $this->plugin_url . 'assets/mrkvmpstyle.min.css', array(), $this->plugin_ver['ver'] );

		// wp_register_style( 'Sweetalert2-style', '//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css' );
    	// wp_enqueue_style('Sweetalert2-style');

		// wp_enqueue_script( 'morkvauamarketplacescript', $this->plugin_url . 'assets/mrkvmpscript.min.js' );
		wp_add_inline_script( 'jquery-migrate', 'jQuery.migrateMute = true;', 'before' ); // Deactivate logging for JQMIGRATE
		// wp_register_script( 'wpvue_vuejs', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js');
		// wp_enqueue_script('wpvue_vuejs');
		// wp_enqueue_script('vue', '//cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js', [], '2.5.17');
		// wp_enqueue_script('vuejs2', 'https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js', [], '2.5.17');
		// wp_enqueue_script('mrkvvuejs', 'https://cdn.jsdelivr.net/npm/vue@2.6.12', [], '2.6.12');
		// wp_enqueue_script('mrkvvuejs', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.12/vue.min.js', [], '2.6.12');
		// wp_enqueue_script('mrkvvuejs', '//unpkg.com/vue', __FILE__, '2.6.12', false ); // WORKSING!!!
		// wp_enqueue_script('mrkvvuejs', $this->plugin_url . 'node_modules/vue/dist/vue.js' );
		// wp_enqueue_script( 'clipboard');
		wp_enqueue_script( 'morkvauamarketplacescript', $this->plugin_url . 'assets/mrkvmpscript.min.js', array('jquery'), $this->plugin_ver['ver'], true );

		$xml = new XMLController( 'rozetka' );
		$xml_fileurl = $xml->plugin_uploads_dir_url . $xml->plugin_uploads_rozetka_xmlname;
		// Add php variables for using in js-script
		wp_localize_script(
		    'morkvauamarketplacescript', // the handle of the 'morkvauamarketplacescript' script we enqueued above
		    'mrkvuamp_script_vars', // object name to access our PHP variables from in js-script
		    array( // register an array of variables we would like to use in js-script
		        'rozetka_xml_path' => $xml_fileurl
		    )
		);

		wp_register_script( 'Sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@10', null, null, true );
		wp_enqueue_script('Sweetalert2');

// wp_enqueue_media();

		// pass Ajax Url to script.js
    	// wp_localize_script('morkvauamarketplacescript', 'ajaxurl', admin_url('admin-ajax.php'));
		// wp_deregister_script( 'jquery' );

		// if ( 'mrkv_ua_marketplaces' == $_GET['page'] ) {
			// wp_deregister_script( 'jquery' );
			// wp_enqueue_script('vuejs2', 'https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js', [], '2.5.17');
			// wp_enqueue_script('mrkvvuejs', 'https://cdn.jsdelivr.net/npm/vue@2.6.12', [], '2.6.12');
			// wp_enqueue_script('mrkvvuejs', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.12/vue.min.js', [], '2.6.12');
			// wp_enqueue_script('mrkvvuejs', '//unpkg.com/vue', __FILE__, '2.6.12', false );
			// wp_enqueue_script('mrkvvuejs', $this->plugin_url . 'node_modules/vue/dist/vue.js' );
			// wp_enqueue_script('wpvue_vuejs');
		// }
	}

}
