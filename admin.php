<?php
defined('ABSPATH') && defined('WPINC') || die;

require_once dirname(__FILE__) . '/admin-page-class/admin-page-class.php';
require_once dirname(__FILE__) . '/shared_variables.php';
require_once dirname(__FILE__) . '/admin_functions.php';

global $pixter_instant_marketplace_admin_tools;
$pixter_instant_marketplace_admin_tools = new p1xtr_instant_marketplace_admin_tools('pixter_instant_marketplace');
$pixter_instant_marketplace_user = get_option('pixter_instant_marketplace_user');

//if (empty($pixter_instant_marketplace_user)) {
add_action('wp_ajax_register_pixter_instant_marketplace_user', 'register_pixter_instant_marketplace_user');

function register_pixter_instant_marketplace_user()
{
    $isGuest = false;
    global $pixter_instant_marketplace_admin_tools;
    $pixter_instant_marketplace_admin_tools->register_user($isGuest);
    //p1xtr_pixter_instant_marketplace_register_user('pixter_instant_marketplace');
}

function pixter_instant_marketplace_admin_page_register()
{
    p1xtr_pixter_instant_marketplace_admin_page_register('pixter_instant_marketplace', 'Pixter.me Instant Marketplace');
}

add_action('pixter_instant_marketplace_admin_page_class_display_register_page', 'pixter_instant_marketplace_admin_page_register');
//}else{
function pixter_instant_marketplace_admin_before_page()
{
    p1xtr_pixter_instant_marketplace_admin_before_page('pixter_instant_marketplace');
}

add_action('pixter_instant_marketplace_admin_page_class_before_page', 'pixter_instant_marketplace_admin_before_page');
//}
/**
 * configure your options page
 */
$config = array(
    'menu' => array('top' => 'Pixter.me Instant Marketplace' .' settings'),
    'page_title' => 'Pixter.me Instant Marketplace',
    'page_header_text' => 'Here you can find configurations to your Pixter.me buttons, please also check your account on <a target="_blank" href="https://publishers.pixter.me/app/">Pixter.me</a> for more details.',
    'capability' => 'install_plugins',
    'option_group' => 'pixter_instant_marketplace' .'_options',
    'id' => 'pixter_instant_marketplace' .'_plugin',
    'fields' => $p1xtr_pixter_instant_marketplace_fields,
    'icon_url' => plugins_url('admin-icon.png', __FILE__),
    'position' => 82,
    'plugin_name' => 'pixter_instant_marketplace',
);
$options_panel = new BF_Admin_Page_Class($config);
