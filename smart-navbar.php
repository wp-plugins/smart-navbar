<?php
/*
Plugin Name: Smart-Navbar
Plugin URI: http://www.loudlever.com/wordpress-plugins/smart-navbar/
Description: Give readers the ability to favorite and bookmark POSTs with this simply styled navigation bar.
Author: Loudlever
Author URI: http://www.loudlever.com
Version: 0.0.4

  Copyright 2014-2015 Loudlever (wordpress@loudlever.com)

  Permission is hereby granted, free of charge, to any person
  obtaining a copy of this software and associated documentation
  files (the "Software"), to deal in the Software without
  restriction, including without limitation the rights to use,
  copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the
  Software is furnished to do so, subject to the following
  conditions:

  The above copyright notice and this permission notice shall be
  included in all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
  EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
  OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
  NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
  HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
  WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
  FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
  OTHER DEALINGS IN THE SOFTWARE.

*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
 echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
 exit;
}

/*
---------------------------------------------------------------------------------
  OPTION SETTINGS
---------------------------------------------------------------------------------
*/  
define('SNB_PLUGIN_VERSION', '0.0.1');
define('SNB_INCLUDE_URL', plugins_url('includes',__FILE__));
define('SNB_ADMIN_PAGE','smart-navbar');
define('SNB_ADMIN_PAGE_NONCE','_snb-save-options');
define('SNB_PLUGIN_FILE',plugin_basename(__FILE__));

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

// Load the class files and associated scoped functionality
load_template(dirname(__FILE__) . '/includes/classes/SmartNavbar.class.php');
$snb = new SmartNavbar();

// This callback does not handle class functions, thus we wrap it....
function smart_navbar_settings_page() {
  global $snb;
  $snb->configuration_screen();
}

if (class_exists("SmartNavbar")) {
  // enable our link to the settings
  add_filter($snb->plugin_filter(), array(&$snb,'plugin_link'), 10, 2 );
  // Enable the Admin Menu and Contextual Help
  add_action('admin_menu', array(&$snb,'register_admin_page')); 
  add_filter('contextual_help', array(&$snb,'configuration_screen_help'), 10, 3);
  add_filter('loop_start', array(&$snb,'header_bar'));
  add_action('wp_enqueue_scripts', array(&$snb,'plugin_init'));
  add_action('init', array(&$snb,'write_cookies'));
  // add_action('wp_head', array(&$snb,'read_cookies'));
  // AJAX Handlers for priviledged and non-priviledged users
  add_action( 'wp_ajax_snb_ajax_handler', array(&$snb,'ajax_handler' ));
  add_action( 'wp_ajax_nopriv_snb_ajax_handler', array(&$snb,'ajax_handler' ));
  add_shortcode($snb->page_shortcode, array(&$snb,'parse_shortcode' ) );
}

register_activation_hook( __FILE__, array(&$snb,'activate_plugin'));
register_deactivation_hook( __FILE__, array(&$snb,'deactivate_plugin'));

?>
