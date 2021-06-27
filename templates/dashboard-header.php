<div class="wrap">

    <h2><?php echo $this->plugin_name['name']; ?></h2>
    <?php settings_errors(); ?>

    <?php
        $default_tab = null;
        $tab = isset($_GET['page']) ? $_GET['page'] : $default_tab;
        $option = get_option('mrkv_ua_marketplaces');
        if ( ! empty( $option )) {
            $rozetka_activated = $option['mrkvuamp_rozetka_activation'];
            $promua_activated = $option['mrkvuamp_promua_activation'];
        }
    ?>

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

    <form id="mrkvuamp-dashboard-form" method="post" action="options.php">
