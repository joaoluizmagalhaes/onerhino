<?php

  /**
   * Plugin Name: OneRhino
   * Description: Plugin created to developer test for One Rhino
   * Author:      Joao Magalhaes
   */

  
  if(isset($_POST['new_item'])) {

    if(get_option('onerhino_items')) {

      $items = maybe_unserialize(get_option('onerhino_items'));
      
      array_push($items, $_POST['new_item']);

      update_option('onerhino_items', maybe_serialize($items));

    } else {

      add_option('onerhino_items', maybe_serialize(array($_POST['new_item'])));

    }

  };

  
  function onerhino_options_page_html() {
    ?>
    <div class="wrap">
      <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
      <form action="<?php menu_page_url( 'wporg' ) ?>" method="post">

        <?php 

          $items = maybe_unserialize(get_option('onerhino_items'));
          
          ?><ul id="items_list"><?php
          foreach($items as $key => $item) {
            ?>
              <li id="<?= $key ?>" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?= $item ?></li>
            <?php
          }
          ?></ul><?php

        ?>
        
        <label for="new_item">Item value</label>
        <input type="text" name="new_item">
        
        <?php
          submit_button( __( 'Save Settings', 'textdomain' ) );
        ?>
      </form>
    </div>
    <?php
  }

  add_action( 'admin_menu', 'onerhino_options_page' );
  function onerhino_options_page() {
      add_menu_page(
          'One Rhino',
          'One Rhino Options',
          'manage_options',
          'onerhino',
          'onerhino_options_page_html',
          'dashicons-editor-ol',
          4
      );
  }
  


  add_action('admin_enqueue_scripts','onerhino_scripts',7);
  function onerhino_scripts(){

    wp_register_script('jquery_ui_js', plugins_url( '', __FILE__ ).'/_assets/vendor/jquery-ui/jquery-ui.min.js', array('jquery'),'1.0', true);
    wp_enqueue_script('jquery_ui_js');

    wp_enqueue_style('jquery_ui_css', plugins_url( '', __FILE__ ).'/_assets/vendor/jquery-ui/jquery-ui.min.css');

    wp_register_script('onerhino_js', plugins_url( '', __FILE__ ).'/_assets/js/onerhino.js', array('jquery'),'1.0', true);
    wp_enqueue_script('onerhino_js');

  }
