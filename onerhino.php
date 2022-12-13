<?php

  /**
   * Plugin Name: OneRhino
   * Description: Plugin created to developer test for One Rhino
   * Author:      Joao Magalhaes
   */


  
  function onerhino_options_page_html() {
    ?>
    <div class="wrap">
      <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
      <form action="options.php" method="post">
        
        <label for="item">Item value</label>
        <input type="text" name="item">
        
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
          20
      );
  }