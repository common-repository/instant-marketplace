<?php
/*
Plugin Name: Instant Marketplace for Wordpress
Plugin URI: https://www.pixter-media.com/wordpress
Description: Instant Marketplace, Powered by Pixter, is a free eCommerce plugin that allows you to sell any of your images directly from an instant store we deploy for you with one click.
Built to integrate seamlessly with WordPress, Pixter is the leading Instant eCommerce solution in the world, that gives both store owners and developers complete control.
Author: Pixter Media
Author URI: https://www.pixter.me
Text Domain: pixter-me
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Version: 1.3

Copyright 2016 Pixter Media
*/

defined('ABSPATH') && defined('WPINC') || die;

require_once dirname(__FILE__) . '/shared_variables.php';
require_once dirname(__FILE__) . '/admin.php';
require_once dirname(__FILE__) . '/plugin_functions.php';

function plugins_loaded_pixter_instant_marketplace()
{
    p1xtr_pixter_instant_marketplace_plugin_loaded('pixter_instant_marketplace');
}

add_action('plugins_loaded', 'plugins_loaded_pixter_instant_marketplace', 999999);

function pixter_instant_marketplace_activate()
{
    p1xtr_pixter_instant_marketplace_activate('pixter_instant_marketplace');
}

register_activation_hook(__FILE__, 'pixter_instant_marketplace_activate');

function pixter_instant_marketplace_activation_redirect($plugin)
{
    p1xtr_pixter_instant_marketplace_activation_redirect($plugin , 'pixter_instant_marketplace');
}

add_action('activated_plugin', 'pixter_instant_marketplace_activation_redirect');


//	function pixter_instant_marketplace_init()
//	{
//		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
//	//	wp_enqueue_script( 'pixter-me-global', 'http://ddd.rrr.com/x.js', array(), '0.1', true );
//	}
//	add_action( 'init', 'pixter_instant_marketplace_init');

function show_pixter_instant_marketplace()
{
    return p1xtr_pixter_instant_marketplace_show_plugin('pixter_instant_marketplace');
}

function pixter_instant_marketplace_inline_script()
{
    p1xtr_pixter_instant_marketplace_inline_script('pixter_instant_marketplace');
}

add_action('wp_footer', 'pixter_instant_marketplace_inline_script', 99999);

/***
 * Added By Itay 20/9/2016
 */

function pixter_instant_marketplace_register_notice()
{
    p1xtr_pixter_instant_marketplace_register_notice('pixter_instant_marketplace', 'Pixter.me Instant Marketplace');
}

add_action('admin_notices', 'pixter_instant_marketplace_register_notice');

function pixter_instant_marketplace_toggle_psk_notice()
{
    p1xtr_pixter_instant_marketplace_psk_notice('pixter_instant_marketplace', 'Pixter.me Instant Marketplace');
}

add_action('admin_notices', 'pixter_instant_marketplace_toggle_psk_notice');

function pixter_instant_marketplace_eventStage($url)
{
    $data = array(
        "storename" => get_bloginfo('name'),
        "website" => get_home_url(),
        "lang" => get_bloginfo('language'),
        "uid" => get_option('p1xtr_uid'),
        "plugin_uid" => get_option('pixter_instant_marketplace_uid'),
        "plugin_ver" => get_option('pixter_instant_marketplace_ver'),
        "plugin_db_ver" => get_option('pixter_instant_marketplace_db_ver'),
        "wp_ver" => get_bloginfo('version'),
        "php_ver" => phpversion(),
    );
    $data_string = json_encode($data);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    //execute post
    $result = trim(curl_exec($ch));

    curl_close($ch);

    return $result;

}

function pixter_instant_marketplace_active()
{
    $pixter_instant_marketplace_user = get_option('pixter_instant_marketplace_user');
    global $pixter_instant_marketplace_admin_tools;

    $pixter_instant_marketplace_admin_tools->init_options();

    $apiUrl = P1XTR_API_BASE_URL . "/api/v2/publisher/activate_wp?user=wp&api_key=" . get_option('pixter_instant_marketplace_user') . "&plugin_name=" . "pixter_instant_marketplace";

    pixter_instant_marketplace_eventStage($apiUrl);
    
    if(empty($pixter_instant_marketplace_user)){
        $pixter_instant_marketplace_admin_tools->registerGuestUser();
    }
}

register_activation_hook(__FILE__, 'pixter_instant_marketplace_active');


function pixter_instant_marketplace_deactivation()
{
    $apiUrl = P1XTR_API_BASE_URL . "/api/v2/publisher/deactivate_wp?user=wp&api_key=" . get_option('pixter_instant_marketplace_user') . "&plugin_name=" . "pixter_instant_marketplace";

    pixter_instant_marketplace_eventStage($apiUrl);
}

register_deactivation_hook(__FILE__, 'pixter_instant_marketplace_deactivation');
