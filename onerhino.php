<?php

  /**
   * Plugin Name: OneRhino
   * Description: Plugin created to developer test for One Rhino
   * Author:      Joao Magalhaes
   */

  
  if(isset($_POST['new_item'])) {

    if(get_option('onerhino_items')) {

      $items = maybe_unserialize(get_option('onerhino_items'));

      $copy = $items;

      ksort($copy);

      $last_item = (int)substr(array_key_last($copy), -1);

      $items['item_' . ($last_item + 1) ] = $_POST['new_item'];

      update_option('onerhino_items', maybe_serialize($items));

    } else {

      add_option('onerhino_items', maybe_serialize(array('item_0' => $_POST['new_item'])));

    }

  };

  
  function onerhino_options_page_html() {
    ?>
    <div class="wrap">
      <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

      <?php 

          $items = maybe_unserialize(get_option('onerhino_items'));
          
          ?><ul id="items_list"><?php
          foreach($items as $key => $item) {
            ?>
              <li id="<?= $key ?>" class=" option ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?= $item ?> <span class="ui-icon ui-icon-trash delete"></li>
            <?php
          }
          ?></ul><?php

        ?>
      
      <form action="<?php menu_page_url( 'wporg' ) ?>" method="post">
        
        <label for="new_item">Item value</label>
        <input type="text" name="new_item" required>
        
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
    wp_enqueue_style('onerhino_css', plugins_url( '', __FILE__ ).'/_assets/css/onerhino.css');

    wp_register_script('onerhino_js', plugins_url( '', __FILE__ ).'/_assets/js/onerhino.js', array('jquery'),'1.0', true);
    wp_enqueue_script('onerhino_js');

  }



  //This function send email message
  add_action( 'wp_ajax_reorder_list',  'reorder_list');
  add_action( 'wp_ajax_nopriv_reorder_list','reorder_list');

  function reorder_list() {

    $order_key = $_POST['data'];

    $items = maybe_unserialize(get_option('onerhino_items'));

    $reorder_items = array_merge(array_flip($order_key), $items );

    update_option('onerhino_items', maybe_serialize($reorder_items ));

  }

  //This function send email message
  add_action( 'wp_ajax_remove_item',  'remove_item');
  add_action( 'wp_ajax_nopriv_remove_item','remove_item');

  function remove_item() {

    $order_key = $_POST['data'];

    $items = maybe_unserialize(get_option('onerhino_items'));

    $new_list = array_intersect_key($items, array_flip($order_key) );

    update_option('onerhino_items', maybe_serialize($new_list ));

  }


  add_shortcode('listofitems','listofitemsFunction');
  function listofitemsFunction($atts){

    $items = maybe_unserialize(get_option('onerhino_items'));

    if($items) {

      ob_start(); 
      
      ?><ul><?php

        if( $atts['limit'] ) {

          $limit = (int)$atts['limit'];

          $items = array_slice($items, 0, $limit, true);
     
        }

        foreach($items as $key => $item) {
          ?>
            <li id="<?= $key ?>" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?= $item ?><span class="ui-icon ui-icon-trash delete"></li>
          <?php
        }

      ?></ul><?php

      return ob_get_clean();
    
    } else {

      return 'Sorry, no items to show';

    }

  }
