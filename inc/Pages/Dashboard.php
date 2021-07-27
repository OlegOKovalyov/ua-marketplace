<?php
/**
 * @package 	MrkvUAMmarketplaces
 */

namespace Inc\Pages;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;
use \Inc\Api\Callbacks\DashboardCallbacks;

class Dashboard extends BaseController
{
	public $settings;

	public $callbacks;

	public $callbacks_activation;

	public $pages = array();

	public function register()
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->callbacks_activation = new DashboardCallbacks();

		$this->setPages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->register();

		add_action( 'admin_init', array( $this, 'pluginCacheClamesNotice' ) );
	}

	public function setPages()
	{
		$this->pages = array(
			array (
				'page_title'	=> 'UA Marketplaces Plugin',
				'menu_title'	=> 'UA Marketplaces',
				'capability'	=> 'manage_options',
				'menu_slug'		=> 'mrkv_ua_marketplaces',
				'callback'		=> array( $this->callbacks, 'adminDashboard' ),
				'icon_url'		=> 'dashicons-store',
				'position'		=> 65
			)
		);
	}

	public function setSettings()
	{

			$args = array(
				array(
					'option_group'	=> 'mrkv_ua_marketplaces_option_group',
					'option_name'	=> 'mrkv_ua_marketplaces',
					'callback'		=> array( $this->callbacks_activation, 'checkboxActivation' )
				)
			);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id'		=> 'mrkvuamp_activation_section',
				'title'		=> __( 'Маркетплейси', 'mrkv-ua-marketplaces' ),
				'callback'	=> array( $this->callbacks_activation, 'marketplacesActivationSection' ),
				'page'		=> 'mrkv_ua_marketplaces'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array();

		foreach ( $this->activations as $key => $value ) {
			$args[] = array(
				'id'		=> $key,
				'title'		=> $value,
				'callback'	=> array( $this->callbacks_activation, 'checkboxField' ),
				'page'		=> 'mrkv_ua_marketplaces',
				'section'	=> 'mrkvuamp_activation_section',
				'args'		=> array(
					'option_name'	=> 'mrkv_ua_marketplaces',
					'label_for' 	=> $key,
					'class'			=> strtolower( $value ) . '_activation_class'
				)
			);
		}

		$this->settings->setFields( $args );
	}

	public function pluginCacheClamesNotice()
	{
		global $pagenow;
		if ( $pagenow == 'admin.php' ) {
			 echo '<div class="notice notice-warning is-dismissible" style="display:inline-block">
				 <p>Якщо на вашому сайті працює плагін кешування, налаштуйте виключення для xml файлів.</p>
				 <p>Якщо додаєте у прайс більше 200 товарів, збільшіть php execution time до максимально можливого (наприклад, 3600).</p>
				 <p>Якщо товарів на сайті доволі багато, то xml-прайс може створитися і через хвилину після зникнення спінера.</p>
			 </div>';
		}
	}

}
