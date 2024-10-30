<?php

require_once dirname(__FILE__) . '/shared_variables.php';


function p1xtr_pixter_instant_marketplace_admin_page_register($pluginName,$pluginTitle)
{

    $siteurl = get_bloginfo('siteurl');
    $admin_email = get_option('admin_email');
    $name = get_option('blogname');
    $password = wp_generate_password(12, false);

    $logo = plugins_url('logo.png', __FILE__);

    $cssTopLevelClassName = 'toplevel_page_' . $pluginName . '_plugin';

    $regBtnTxt = 'register-' . str_replace('_', '-', $pluginName);
    $regLaterBtnId = 'register-' . str_replace('_', '-', $pluginName) . '-later';

    echo "<script> 
pluginName = '$pluginName';

registerBtnText =  '$regBtnTxt'; 
registerLaterBtnId =  '$regLaterBtnId'; 
registerAction = 'register_' + '$pluginName'+'_user';
  
    </script>";
    $pixter_instant_marketplace_is_guest_user = get_option('pixter_instant_marketplace_is_guest_user');

    $hideRegPage = '';
    if(empty($pixter_instant_marketplace_is_guest_user) || $pixter_instant_marketplace_is_guest_user == true ){
        $hideRegPage = 'hidden';
    }

    echo <<<RegisterFirst
<style>
.wp-admin #wpfooter { position: static; }

#admin_page_class { display: none; }
#register-pm
{
	padding: 20px;
	width: calc(100% - 50px);
	position: relative;
}
#registration-form
{
	font-size: 1.2em;
}
#registration-form > div
{
	margin: 20px 40px 20px 0;
	display: inline-block;
	position: relative;
}
#registration-form > div button
{
	padding: 7px 15px;
	border-radius: 7px;
	font-weight: bold;
	color: #0073aa;
	background-color: #FFF;
	border-color: #99A;
}
#registration-form > div button.register-later-btn
{
    margin-left :20px;
    border-color: #ffa201;
    color: #ffa201;
}
/*
#registration-form > div > label:first-child
{
	display: inline-block;
	width: 90px;
}
*/
input#tnc
{
	display: inline-block!important;
}
div.iphone-style[rel=tnc]
{
    display: none;
}
.$cssTopLevelClassName .wp-menu-image img
{
	width: 18px;
}
.err
{
	position: absolute;
	display: none;
	top: 29px;
	left: 20px;
	padding: 5px 12px;
	background-color: #FFF;
	border: 1px solid #D00;
	color: #D00;
	border-radius: 7px;
	box-shadow: 1px 1px 3px #666;
	font-size: 0.9em;
}
</style>
<div id="register-pm" $hideRegPage>
	<h3>Register $siteurl to <a href="http://www.pixter-media.com" target="_blank">Pixter.me</a></h3>
	<div id='registration-form'>
			<div>
				<label for="email">Email:</label>
				<input type="email" name="email" id="email" value="$admin_email">
				<div class="err">Must use a valid email</div>
			</div>
			<div>
				<input id="tnc" type="checkbox" name="tnc">
				<label for="tnc">I agree to the
					<a href="https://publishers.pixter-media.com//publisher/docs/terms_of_service.html" target="_blank">Terms of Service</a>
				</label>
				<div class="err">You must agree to terms</div>
			</div>
			<div>
				<button id="$regBtnTxt">Register my website and create account</button>
                <button id="$regLaterBtnId" class="register-later-btn">Register later >></button>

			</div>
	</div>
</div>
RegisterFirst;
}

function p1xtr_pixter_instant_marketplace_admin_before_page()
{
    $logo = plugins_url('logo.png', __FILE__);
    echo "
			<style>
				.wp-admin #wpfooter { position: static; }
				.conditinal_container {  margin: 0 0 15px 5%; }
			</style>
			<div style='margin: 20px 10px 0'>
				<a href='http://www.pixter-media.com' target='_blank'><img src='$logo'></a>
			</div>";
}


/**
 * configure your fields
 */
global $p1xtr_pixter_instant_marketplace_fields;
$p1xtr_pixter_instant_marketplace_fields = array(
    array(
        "type" => "cond",
        "name" => __("Turn Pixter.me Instant Marketplace plugin:", 'pixter-me'),
        "desc" => __("When off, <strong>All Pixter's services</strong> will be disabled!", 'pixter-me'),
        "id" => "toggle_psk",
        "default" => array(
            "enabled" => 'on'
        )
    ),
    array(
        "type" => "cond",
        "name" => __("Pixter on-image buttons", 'pixter-me'),
        "desc" => __("When Off, Pixter's on-image buttons will be disabled", 'pixter-me'),
        "id" => "toggle_buttons",
        "default" => array(
            "enabled" => 'on'
        )
    ),
    array(
        "type" => "text",
        "name" => __("Button text", 'pixter-me'),
        "id" => "button_text",
        "desc" => __("The text which will appear on the button which is displayed while hovering an image", 'pixter-me'),
        "default" => __("Get Prints", 'pixter-me')
    ),
    array(
        "type" => "color",
        "name" => __("Button background color", 'pixter-me'),
        "id" => "button_bg_color",
        "desc" => __("Bacground color of the print button.", 'pixter-me'),
        "default" => "#FF7A01"
    ),
    array(
        "type" => "color",
        "name" => __("Button text color", 'pixter-me'),
        "id" => "button_text_color",
        "desc" => __("Text color of the print button.", 'pixter-me'),
        "default" => "#FFFFEE"
    ),
    /*
array(
    "type" => "radio",
    "name" => __("Button position", 'pixter-me'),
    "id" => "button_position",
    "options" => array('top-left' => 'Top Left', 'top-right' => 'Top Right', 'bottom-left' => 'Bottom Left', 'bottom-right' => 'Bottom Right'),
    "desc" => __("The position of the button relatively to the image.", 'pixter-me'),
    "default" => "top-left"
    ),
    */
    array(
        "type" => "text",
        "name" => __("CSS Selector", 'pixter-me'),
        "id" => "selector",
        "desc" => __("Selector for containers including relevant images. Comma separates multiple selectors.", 'pixter-me'),
        "default" => "img"
    ),
    array(
        "type" => "cond",
        "name" => __("Show on pages (shows all if off)", 'pixter-me'),
        "desc" => __("When opened, you must select on which pages you want to show pixter-me", 'pixter-me'),
        "id" => "show_pages",
        "fields" => array(
            array(
                "type" => "posts",
                "name" => __("Show button on the following pages only", 'pixter-me'),
                "id" => "pages",
                "options" => array('args' => array('post_type' => 'page', 'numberposts' => -1), 'type' => 'checkbox_list'),
                'class' => 'no-toggle',
            ),
        ),
    ),
    array(
        "type" => "cond",
        "name" => __("Show on posts by categories (shows all if off)", 'pixter-me'),
        "desc" => __("When opened, you must select on which posts categories you want to show pixter-me", 'pixter-me'),
        "id" => "show_posts",
        "fields" => array(
            array(
                "type" => "taxonomy",
                "name" => __("Show button on the following posts categories", 'pixter-me'),
                "id" => "taxonomies",
                "options" => array('taxonomy' => 'category', 'args' => array('hide_empty' => false), 'type' => 'checkbox_list'),
                'class' => 'no-toggle',
            ),
        ),
    ),
    array(
        "type" => "cond",
        "name" => __("Store Side Tag", 'pixter-me'),
        "desc" => __("When on, Pixter's Side Tag will be displayed", 'pixter-me'),
        "id" => "toggle_teaser",
        "default" => array(
            "enabled" => 'off'
        )
    ),
    array(
        "type" => "cond",
        "name" => __("Top Banner", 'pixter-me'),
        "desc" => __("When on, Pixter's Top Banner will be displayed", 'pixter-me'),
        "id" => "toggle_top_banner",
        "default" => array(
            "enabled" => 'off'
        )
    ),
);


class p1xtr_instant_marketplace_admin_tools{
    private $pluginName;
    private $plugin_db_ver = 1;
    private $plugin_ver = '1.3';


    public function __construct($pluginName)
    {
        $this->pluginName = $pluginName;
        add_action('plugins_loaded',array($this, 'maybe_update'));
    }


    public function register_user($isGuestUser)
    {
        $email = "";
        if ($isGuestUser == false) { // Registering a normal user - Must contain an email address
            if (empty($_POST['email'])) {
                wp_die('MissingData');
            } else {
                $email = $_POST['email'];
            }
        }

        $currentApiKey = get_option($this->pluginName . '_user');
        if (empty($currentApiKey)) {
            $currentApiKey = '';
        }

        $apiUrl = P1XTR_API_BASE_URL . "/api/v2/publisher/register_wp?user=wp&email=" . $email . "&plugin_name=" . $this->pluginName . "&api_key=" . $currentApiKey;

        $ch = curl_init();


        $data = array(
            "storename" => get_bloginfo('name'),
            "email" => $email,
            "fullname" => $email,
            "website" => get_home_url(),
            "lang" => get_bloginfo('language'),
            "is_guest" => $isGuestUser,
            "uid" => get_option('p1xtr_uid'),
            "plugin_uid" => get_option($this->pluginName . '_uid'),
            "plugin_ver" => get_option($this->pluginName . '_ver'),
            "plugin_db_ver" => get_option($this->pluginName . '_db_ver'),
            "wp_ver" => get_bloginfo('version'),
            "php_ver" => phpversion(),
        );
        $data_string = json_encode($data);

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
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        //execute post
        $result = trim(curl_exec($ch));
        if (empty($result)) {
            $isSuccess = false;
            $result = '{"success":false,"message":"' . curl_error($ch) . '"}';
        } else {
            update_option($this->pluginName . '_is_guest_user', $isGuestUser, true);
            $values = json_decode($result);
            $v3User = array();
            $api_key = $values->api_key;
            $token = $values->token;
            $store_id = $values->store_id;
            $isGuest = $values->guest;
            $pluginUid = $values->plugin_uid;
            if (isset($api_key)) {
                update_option($this->pluginName . '_user', $api_key, true);
                $v3User['old_api_key'] = $api_key;
            }
            if (isset($token)) {
                $v3User['token'] = $token;
            }
            if (isset($store_id)) {
                $v3User['store_id'] = $store_id;
            }
            if (isset($isGuest)) {
                $v3User['is_guest'] = $isGuest;
            }
            if (isset($pluginUid)) {
                update_option($this->pluginName . '_uid', $pluginUid, true);
            }
            unset($values->api_key);
            unset($values->token);
            unset($values->store_id);
            unset($values->guest);
            update_option($this->pluginName . '_v3_user', $v3User, true);
            $result = json_encode($values);
            $isSuccess = $values->success;
        }
        curl_close($ch);
        if ($isGuestUser == false) { // Return answer only when registering an actual user
            wp_die($result); // this is required to terminate immediately and return a proper response
        } else {
            return $isSuccess;
        }
    }

    public function register_default_guest_user()
    {
        $api_key = 'P1XWPGST';
        $store_id = '3444568374294A';
        $token = '27075b2053f5f566ba292b6f0d5b908701089b75';
        $isGuest = true;

        update_option($this->pluginName . '_is_guest_user', $isGuest, true);
        update_option($this->pluginName . '_user', $api_key, true);

        $v3User = array();
        $v3User['old_api_key'] = $api_key;
        $v3User['token'] = $token;
        $v3User['store_id'] = $store_id;
        $v3User['is_guest'] = $isGuest;
        update_option($this->pluginName . '_v3_user', $v3User, true);
    }

    public function maybe_update() {

        // bail if this plugin data doesn't need updating
        $plugin_option_db_ver =  get_option( $this->pluginName . '_db_ver' );
        if ( !empty($plugin_option_db_ver) && $plugin_option_db_ver >= $this->plugin_db_ver ) {
            return;
        }

        $this->update_plugin();
    }

    public function init_options() {
        update_option( $this->pluginName . '_ver', $this->plugin_ver ); // Always set the version number
        add_option( $this->pluginName . '_db_ver', $this->plugin_db_ver ); // Set the db version number only if not set

        $p1xtr_uid = get_option('p1xtr_uid');
        $plugin_uid = get_option($this->pluginName . '_uid');
        if(empty($p1xtr_uid)){
            update_option('p1xtr_uid', md5(uniqid(mt_rand(), true)), true);
        }
        if(empty($plugin_uid)){
            update_option($this->pluginName . '_uid', md5(uniqid(mt_rand(), true)), true);
        }
    }



    /**
     * Run the incremental updates one by one.
     *
     * For example, if the current DB version is 3, and the target DB version is 6,
     * this function will execute update routines if they exist:
     *  - update_routine_4()
     *  - update_routine_5()
     *  - update_routine_6()
     */
    public function update_plugin()
    {
        // no PHP timeout for running updates
        set_time_limit(0);

        // this is the current database schema version number
        $current_db_ver = get_option($this->pluginName . '_db_ver');
        if(empty($current_db_ver)){
            $current_db_ver = 0;
        }

        // this is the target version that we need to reach
        $target_db_ver = $this->plugin_db_ver;


        $method = "update_routine_{$current_db_ver}";
        if (method_exists($this, $method)) {
            call_user_func(array( $this, $method), $this->pluginName);
        }

        // run update routines one by one until the current version number
        // reaches the target version number
        while ($current_db_ver < $target_db_ver) {
            // increment the current db_ver by one
            $current_db_ver++;

            // each db version will require a separate update function
            // for example, for db_ver 3, the function name should be solis_update_routine_3
            $method = "update_routine_{$current_db_ver}";
            if (method_exists($this, $method)) {
                call_user_func(array( $this, $method), $this->pluginName);
            }

            // update the option in the database, so that this process can always
            // pick up where it left off
            update_option($this->pluginName . '_db_ver', $current_db_ver);
        }
    }

    private function update_routine_1(){ // db update #1, Version 1.10 -> 1.11
        $this->init_options();

        $currentApiKey = get_option($this->pluginName . '_user');

        if($currentApiKey == false){ // User is not registered
            $this->registerGuestUser();

        }else{ // User is registered
            $v3User = array();
            $v3User['old_api_key'] = $currentApiKey;

            /**
             * Get the token and store id
             */
            $apiUrl = P1XTR_API_BASE_URL . "/api/v2/store/get_token?api_key=" . get_option($this->pluginName . '_user') ;
            $data = array(
                "storename" => get_bloginfo('name'),
                "website" => get_home_url(),
                "lang" => get_bloginfo('language'),
                "uid" => get_option('p1xtr_uid'),
                "plugin_uid" => get_option($this->pluginName . '_uid'),
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
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            //execute post
            $result = trim(curl_exec($ch));

            curl_close($ch);
            $values = json_decode($result);
            /**
             * If there is no token/store_id than register the default guest user
             */
            if (isset($values->token) && isset($values->store_id)) {
                $v3User['token'] = $values->token;
                $v3User['store_id'] = $values->store_id;
                update_option($this->pluginName . '_v3_user', $v3User, true);
                $isGuest = false;
                $v3User['is_guest'] = $isGuest;
                update_option($this->pluginName . '_is_guest_user', $isGuest, true);
            }else{
                $this->registerGuestUser();
            }


        }

    }

    public function registerGuestUser(){
        $isGuest = true;
        $isSuccess = $this->register_user($isGuest);
        if(!$isSuccess){
            $this->register_default_guest_user();
        }
    }

    public function getToggleBoolValue($inputArrayContainingEnabled){
        if(isset($inputArrayContainingEnabled['enabled']) && $inputArrayContainingEnabled['enabled'] == 'on'){
            return true;
        }
        return false;
    }
    
}