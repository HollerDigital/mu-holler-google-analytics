<?php
/*
Plugin Name: Holler Google Analytics
Plugin URI: http://jamesmurgatroyd.com
Description: Drop-In Plugin that adds Google Analytics to a website
Version: 1.0
Author: James Murgatroyd Communications
Author URI: http://jamesmurgatroyd.com/
License: GPLv2
*/

function add_google_analytics() {

$trackingID = get_option('holler_ga_id') ;
  // disabled for Admin users
  if ( !current_user_can( 'manage_options' )  && !empty($trackingID)) {
    ?>
      <!-- Start Google Analytics Script -->
      <!-- Global Site Tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=<? echo $trackingID; ?>"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments)};
        gtag('js', new Date());

        gtag('config', '<?php echo $trackingID; ?>'); // UA property
        // gtag('config', 'G-XXXXX'); // Added Google Analytics 4 ID
      </script>
      <!-- End Google Analytics Script -->
    <?php
  }
}

//register our settings
function register_holler_ga_settings() {
  register_setting( 'holler-ga-settings-group', 'holler_ga_id' );
  register_setting( 'holler-ga-settings-group', 'holler_ga_enabled' );
}
add_action( 'admin_init', 'register_holler_ga_settings' );

// Action Hook to add menu to Settings
add_action("admin_menu", "holler_ga_options_submenu");

// Add Sub Menu to Settings in WP
function holler_ga_options_submenu() {
  add_submenu_page(
        'options-general.php',
        'Holler Google Analytics',
        'Google Analytics',
        'administrator',
        'holler-ga-options',
        'holler_ga_settings_page' );
}
// Settings Page Code
function holler_ga_settings_page()   {
    ob_start(); ?>
  <div class="wrap">
  <h1>Google Analytics</h1>
  
  <form method="post" action="options.php">
      <?php settings_fields( 'holler-ga-settings-group' ); ?>
      <?php do_settings_sections( 'holler-ga-settings-group' ); ?>
      <table class="form-table">
     
          <!-- <tr valign="top">
            <th scope="row">Daily Notifications</th>
            <td> <input type="checkbox" name="advocator_notify" value="1" <?php checked(1, get_option('advocator_notify'), true); ?> /> </td>
          </tr> -->
                 
          <tr valign="top">
            <th scope="row">Tracking ID</th>
            <td><input type="text" name="holler_ga_id" value="<?php echo esc_attr( get_option('holler_ga_id') ); ?>" /></td>
          </tr>
      </table>
      <?php submit_button(); ?>
  </form>
  </div>
<?php
    echo ob_get_clean();
  }

 // Add Google Analytics to site
 add_action('wp_head', 'add_google_analytics');