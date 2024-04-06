<?php
/*
 Plugin Name: Basic Plugin

 Description: This is a very basic plugin to use for development purpose. You can find source code https://github.com/pdilip84/basicplugin

 Author: Dilip Parmar

 Author URI: https://dilip-parmar.in
 
 Version: 1.0.0
*/

/*
You can create a basic custom plugin by boilerplate tool like https://wppb.me/

 Step - 1: Create plugin file, apply security rules like blank index file & ABSPATH check.

 Step - 2: Run activation, deactivation & uninstall hook

 Step - 3: Create a simple short-code to see if it works or not in frontend.

 Step - 4: Create a new table in activation hook, truncate in deactivation hook & delete table on uninstall hook

 Step - 5: How to declare default attributes/parmeters to shortcode and host to assign default value to each

 Step - 6: how to add JS & CSS file to your custom plugin

 Step - 7: how to fetch data from tables using wp_Query class
 
*/

// For security purpose we creted a blank index.php file and we checked if ABSPATH is defined or not. It is defined if your plugin directory is accessed by default WordPress structure. If someone tied to access plugin file directlry through URL, It is not permitted.

/* step 1*/
if (!defined('ABSPATH')) {
    die('No access');
}

/* step 2*/
function basic_activation_fun()
{
    /* Step 4*/
    global $wpdb, $table_prefix;

    $table_name = $table_prefix . 'basic';

    $q = "CREATE TABLE IF NOT EXISTS `$table_name` (`id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(100) NOT NULL , `email` VARCHAR(100) NOT NULL , `status` INT NOT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB;";

    $wpdb->query($q);

    // Now we can use this query to insert the data into table but wordpress insert() method do sanitization on data we passed so let's use default wordpress method to insert the data

    // INSERT INTO `wp_basic` (`id`, `name`, `email`, `status`) VALUES (NULL, 'Janakba', 'janak@gmail.com', '1');

    $data = array(
        'name' => 'Janakba',
        'email' => 'janak@gmail.com'
    );
    $wpdb->insert($table_name, $data);
}
register_activation_hook(__FILE__, 'basic_activation_fun');

function basic_deactivation_fun()
{
    /* Step 4*/

    global $wpdb, $table_prefix;

    $table_name = $table_prefix . 'basic';

    $q = "TRUNCATE TABLE $table_name";

    $wpdb->query($q);
}
register_deactivation_hook(__FILE__, 'basic_deactivation_fun');

/* step 3 */

function basic_plugin_shortcode()
{
    return "<H1 class='basicplugin-title'>This is a basic plugin</H1>";
}
add_shortcode('basic-plugin', 'basic_plugin_shortcode');

/* step 5 */
function basic_plugin_shortcode_parm($atts)
{
    // Declare shortcode on page like [basic-plugin-parm username="Dilip" jobdesc="Website Developer" salary="3lac per anum"]

    // Another shortcode in backend is [basic-plugin-parm username="Janak"] which is not having all attributes so in such case we have to define default value of attributes

    // normalize attribute keys, lowercase
    $atts = array_change_key_case($atts, CASE_LOWER);

    $atts = shortcode_atts(array(
        'username' => 'Not defined',
        'jobdesc' => 'Author',
        'salary' => 'Not declared'
    ), $atts);

    // print_r($atts);
    return "<H1 class='basicplugin-title'>User named $atts[username] is working as $atts[jobdesc] and his/her salary is $atts[salary]</H1>";
}
add_shortcode('basic-plugin-parm', 'basic_plugin_shortcode_parm');

/* step 6 */

// First let's create a css folder with a css file and js folder with js file
// We can't add css/js direct here, we need to add into header hook.
function add_basicplugin_script()
{
    // we can give static verion as number but it creates cache issue so if we want to load these css & js each time we need to add version number as last edited the file so by this way when file is edited, that version number is automatically added to this function and it will always loaded from server rather than cache.

    $version = filemtime(plugin_dir_path(__FILE__) . '/js/main.js');

    // echo $version;
    $js_path = plugins_url('/js/main.js', __FILE__);
    wp_enqueue_script('basic-plugin-js', $js_path, array(), $version, true);

    $css_path = plugins_url('/css/main.css', __FILE__);
    wp_enqueue_style('basic-plugin-style', $css_path, array(), $version);
}
add_action('wp_enqueue_scripts', 'add_basicplugin_script');

/* step 7 */

function display_posts_fun()
{
    // get help from handbook to set argument array https://developer.wordpress.org/reference/classes/wp_query/#post-page-parameters

    // We will use wp_Query class and create a new object and pass arguments. Argements are very important to filter, order the data.

    // ob_start() is function from where we are going to store output into variable & when output is over we use ob_get_clean() method. That is stored as variable, just return that variable. If you do not then your html will display on top of the page.

    $arg = array(
        'post_type' => 'post',
        'orderby' => 'id',
        'order' => 'DESC',
        's' => 'example'
    );
    $this_query = new WP_Query($arg);
    if ($this_query->have_posts()) {
        ob_start();
        while ($this_query->have_posts()) {
            $this_query->the_post();
            echo '<ul>';
            echo "<li>" . get_the_title(), get_the_content() . "</li>";
            echo '</ul>';
        }
        $html = ob_get_clean();
        return $html;
    }
}
add_shortcode('display-posts', 'display_posts_fun');
