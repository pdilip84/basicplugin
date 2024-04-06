<?php
/*
 Plugin Name: Basic Plugin

 Description: This is a very basic plugin to use for development purpose. You can find source code https://github.com/pdilip84/basicplugin

 Author: Dilip Parmar

 Author URI: https://dilip-parmar.in
 
 Version: 1.0.0
*/

/*
 Step - 1: Create plugin file, apply security rules like blank index file & ABSPATH check.

 Step - 2: Run activation, deactivation & uninstall hook

 Step - 3: Create a simple short-code to see if it works or not in frontend.
*/
// For security purpose we creted a blank index.php file and we checked if ABSPATH is defined or not. It is defined if your plugin directory is accessed by default WordPress structure. If someone tied to access plugin file directlry through URL, It is not permitted.

/* step 1*/
if (!defined('ABSPATH')) {
    die('No access');
}

/* step 2*/
function basic_activation_fun()
{
}
register_activation_hook(__FILE__, 'basic_activation_fun');

function basic_deactivation_fun()
{
}
register_deactivation_hook(__FILE__, 'basic_deactivation_fun');

/* step 3 */

function basic_plugin_shortcode()
{
    return "<H1>This is a basic plugin</H1>";
}
add_shortcode('basic-plugin', 'basic_plugin_shortcode');