<?php

use \Inc\Core\XMLController;

if ( ! $this->activated( 'mrkvuamp_promua_activation' ) ) return;

$xml = new XMLController( 'promua' ); // Get PromUA xml-file URL and plugin uploads dir path
$xml_fileurl = $xml->plugin_uploads_dir_url . $xml->plugin_uploads_promua_xmlname;
$plugin_uploads_dir_path = $xml->plugin_uploads_dir_path;
$site_total_product_qty = $xml->site_total_product_qty;

?>

 <div class="wrap">

     <h2><?php echo $this->plugin_name['name']; ?></h2>
     <?php settings_errors(); ?>

     <?php
         $default_tab = null;
         $tab = isset($_GET['page']) ? $_GET['page'] : $default_tab;
         $option = get_option('mrkv_ua_marketplaces');
         $rozetka_activated = isset( $option['mrkvuamp_rozetka_activation'] ) ? $option['mrkvuamp_rozetka_activation'] : false;
         $promua_activated = isset( $option['mrkvuamp_promua_activation'] ) ? $option['mrkvuamp_promua_activation'] : false;
     ?>

     <?php // Tabs ?>
     <nav class="nav-tab-wrapper">
       <a href="?page=mrkv_ua_marketplaces" class="nav-tab <?php if( 'mrkv_ua_marketplaces' == $tab ):?>nav-tab-active<?php endif; ?>">Dashboard</a>
       <?php
           if ( $rozetka_activated ) { ?>
               <a href="?page=mrkv_ua_marketplaces_rozetka" class="nav-tab <?php if( 'mrkv_ua_marketplaces_rozetka' == $tab ):?>nav-tab-active<?php endif; ?>">Rozetka</a>
           <?php }
           if ( $promua_activated ) { ?>
               <a href="?page=mrkv_ua_marketplaces_promua" class="nav-tab <?php if( 'mrkv_ua_marketplaces_promua' == $tab ):?>nav-tab-active<?php endif; ?>">PromUA</a>
           <?php }
        ?>
       <a href="?page=mrkv_ua_marketplaces_support" class="nav-tab <?php if( 'mrkv_ua_marketplaces_support' == $tab ):?>nav-tab-active<?php endif; ?>">Підтримка</a>
     </nav>

     <?php // Links ?>
     <ul class="mrkvuamp-nav-links">
         <li class="active"><a href="#mrkvuamp_main-configuration">Загальні налаштування</a></li>
         <!-- <li><a href="#category-matching">Співставлення категорій</a></li>
         <li><a href="#my-orders">Мої замовлення</a></li> -->
     </ul>
     <div class="mrkvuamp-nav-links-content">

         <?php // Загальні налаштування link ?>
         <div id="mrkvuamp_main-configuration" class="link-pane active">

             <?php // Last xml-file link ?>
             <div class="mrkvuamp_promua_xml_link hidden" >
                 <form action="">
                     <p>Посилання на
                         <a  class="mrkvuamp_xml_link" target="_blank" href="<?php clearstatcache(); echo esc_url( $xml_fileurl ); clearstatcache(); ?>">останній згенерований xml</a>
                             <?php $xml->last_promuaxml_file_date();
                                 $xml_file_path = $plugin_uploads_dir_path . $xml->plugin_uploads_promua_xmlname;
                                 $xml_file_size = ( file_exists( $xml_file_path ) ) ? filesize( $xml_file_path ) : '';
                                 $progBarCoef = ( $site_total_product_qty < 100 ) ? 1.2 : 3.7;
                             ?>
                             <br><?php // PromUA xml-file processing progress bar ?>
                             <progress id="mrkvuamp-progress-xml-upload-promua" max="<?php echo \round( $site_total_product_qty * $progBarCoef ); ?>" value="0" style="width: 98%;"></progress>
                             <div class="hidden" id="mrkvuamp_progbar_hidden_msg" style="padding-left: 10px;"></div>
                             <input type="hidden" name="mrkvuamp_xml_file_path" value="<?php echo sanitize_text_field( $xml_file_path ); ?>" />
                             <input type="hidden" name="mrkvuamp_xml_file_size" value="<?php echo sanitize_text_field( $xml_file_size ); ?>" />
                             <input type="hidden" name="mrkvuamp_site_total_product_qty" value="<?php echo sanitize_text_field( $site_total_product_qty ); ?>" />
                     </p>
                 </form>
             </div>

             <form method="post" action="options.php">

                <?php settings_fields( 'mrkv_ua_promua_option_group' ); ?>
                <?php do_settings_sections( 'mrkv_ua_marketplaces_promua' ); ?>

                <?php submit_button(); ?>

            </form>

            <form id="mrkv_uamrkpl_promuaxml_form" class="mrkv_uamrkpl_promuaxml_form" method="post" action="">
                <input type="hidden" name="action" value="mrkvuamp_promuaxml_action">
                <?php wp_nonce_field( 'mrkv_uamrkpl_promuaxml_form_nonce' ); ?>
                <?php submit_button( __( 'Створити xml', 'mrkv-ua-marketplaces'), 'primary', 'mrkvuamp_submit_promuaxml', false ); ?>
                <span style="display:inline;" id="mrkvuamp_loader"> </span>
            </form>

        </div>

        <!-- <div id="category-matching" class="link-pane">
            <h2>PromUA Співставлення категорій та генерація XML</h2>
        </div>

        <div id="my-orders" class="link-pane">
            <h2>PromUA Мої замовлення</h2>
        </div> -->

    </div>

</div><!-- /.wrap -->
