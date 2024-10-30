<?php

require_once dirname(__FILE__) . '/shared_variables.php';


function p1xtr_pixter_instant_marketplace_plugin_loaded($pluginName){
    if (is_admin()) {
        // embed the javascript file that makes the AJAX request
        wp_enqueue_script('pixter-me-script', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), false, true);
        wp_localize_script('pixter-me-script', 'pixterAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
    load_plugin_textdomain('pixter-me', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

function p1xtr_pixter_instant_marketplace_activate($pluginName){
    global $p1xtr_pixter_instant_marketplace_fields;
    $default = array();
    foreach ($p1xtr_pixter_instant_marketplace_fields as $conf)
        if (!empty($conf['default']) && !empty($conf['id']))
            $default[$conf['id']] = $conf['default'];

    update_option($pluginName.'_options', $default);
}

function p1xtr_pixter_instant_marketplace_activation_redirect($plugin, $pluginName)
{
    if ($plugin == plugin_basename(__FILE__))
        exit(wp_redirect(admin_url('admin.php?page='.$pluginName.'_plugin')));
}

function p1xtr_pixter_instant_marketplace_show_plugin($pluginName)
{
    global $post;

    $options = (object)get_option($pluginName.'_options');
    $post_type = get_post_type();
    switch ($post_type) {
        case 'page':
            if (!empty($options->show_pages) && $options->show_pages['enabled'] == 'on') {
                $pages = $options->show_pages['pages'];
                if (empty($pages) || !is_array($pages) || !in_array($post->ID, $pages))
                    return false;
            }
            break;
        case 'post':
            if (!empty($options->show_posts) && $options->show_posts['enabled'] == 'on') {
                $taxonomies = $options->show_posts['taxonomies'];
                if (empty($taxonomies))
                    return false;

                $categories = wp_get_post_categories($post->ID, array('fields' => 'slugs'));
                foreach ($categories as $cat)
                    if (in_array($cat, $taxonomies))
                        return true;

                return false;
            }
            break;
        default:
            break;
    }
    return true;
}

function p1xtr_pixter_instant_marketplace_inline_script($pluginName){
    $apiKey = get_option( $pluginName . '_user');
    if (!empty($apiKey) && !wp_script_is($pluginName.'_inline', 'done') && p1xtr_pixter_instant_marketplace_show_plugin($pluginName)) {
        $options = (object)get_option($pluginName.'_options');

        $button_text = $options->button_text;
        $button_bg_color = $options->button_bg_color;
        $button_text_color = $options->button_text_color;

        if(!function_exists('getBoolString')){
            function getBoolString($inputArrayContainingEnabled){
                if(isset($inputArrayContainingEnabled['enabled']) && $inputArrayContainingEnabled['enabled'] == 'on'){
                    return 'true';
                }
                return 'false';
            }
        }

        $toggle_psk = getBoolString($options->toggle_psk);
        $toggle_buttons = getBoolString($options->toggle_buttons);
        $toggle_teaser = getBoolString($options->toggle_teaser);
        $toggle_top_banner = getBoolString($options->toggle_top_banner);

        $v3_user = (object)get_option($pluginName.'_v3_user');
        $token = $v3_user -> token;
        $storeId = $v3_user -> store_id;
        $isGuest = ($v3_user -> is_guest) ? 'true' : 'false';

        /*
        $button_position = $options->button_position;
        switch ($button_position)
        {
            case 'top-left':		$cssPos = "left: 5px; top: 5px;";	break;
            case 'top-right':		$cssPos = "left: auto!important; right: 5px; top: 5px;";	break;
            case 'bottom-left':		$cssPos = "left: 5px; top: auto!important; bottom: 5px;";	break;
            case 'bottom-right':	$cssPos = "left: auto!important; right: 5px; top: auto!important; bottom: 5px;";	break;
        }

        */
        $selector = $options->selector;

        $random = rand();
        echo <<<InlineScript

<script src="https://pixter-loader-assets.s3.amazonaws.com/Loader/v3Loader.js?cbr=$random" onload="pLoader.initStore('$token','$storeId',function(){console.log('READY');},
    {
        isGuest: $isGuest,
        platform : 'wp',
        pluginName: '$pluginName'
    },  
    {autoInit:  $toggle_psk,
    itemsToInit:
        [
        {name:'smartLaunchButton',
        autoInit: $toggle_buttons,
        parameters:{
            text: '$button_text',
            selectors: '$selector',
            textColor: '$button_text_color',
            buttonColor: '$button_bg_color'
            }
        },
        {name:'smartPopup', autoInit : $toggle_teaser},
        {name:'smartSideTag', autoInit : $toggle_teaser},
        {name:'smartTopBanner', autoInit : $toggle_top_banner},
        ]
    }
);"></script>
InlineScript;
        global $wp_scripts;
        $wp_scripts->done[] = $pluginName.'_inline';
    }

}


function p1xtr_pixter_instant_marketplace_register_notice($pluginName, $pluginDisplayName){
    if (get_option($pluginName.'_is_guest_user') == true) {
        $class = 'notice notice-warning p1xtr-reg-notice';
        $message = __('<strong>IMPORTANT</strong>: It is highly recommended to <a href="admin.php?page='.$pluginName.'_plugin&isReg=true">register</a> your <strong>' . $pluginDisplayName . '</strong> plugin. Registration will enable you to customize your store and to earn revenues from this plugin.', 'pixter-register-notification');

        printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
    }
}

function p1xtr_pixter_instant_marketplace_psk_notice($pluginName, $pluginDisplayName){
    $options = (object)get_option($pluginName.'_options');
    $toggle_psk = $options->toggle_psk;
    global $pixter_instant_marketplace_admin_tools;
    if ($pixter_instant_marketplace_admin_tools->getToggleBoolValue($toggle_psk) === false) {
        $class = 'notice notice-warning p1xtr-psk-notice';
        $message = __('<strong>IMPORTANT</strong>: Please enable the ' . $pluginDisplayName . ' plugin in the plugin settings', 'pixter-register-notification');

        printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
    }
}