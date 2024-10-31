<?php
/*
Plugin Name: Sellsy
Plugin URI: http://www.sellsy.com
Description: Simple form for : add support ticket to Sellsy, add prospect to Sellsy.
Author: Michael DUMONTET
Author URI: https://marketplace.sellsy.com/fr/application/wordpress/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Version: 2.3.3
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Definitions
 *
 * @since 1.0
 */
global $wpdb;
define('SELLSY_VERSION', '2.3.3');
//define('SELLSY_PLUGIN_DIR', WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__)));
define('SELLSY_PLUGIN_DIR', dirname(__FILE__));
define('SELLSY_PLUGIN_URL', WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)));
define('SELLSY_PREFIXE_BDD', $wpdb->prefix.'sellsy_');
define('PLUGIN_NOM_LANG', 'sellsy');
define('SELLSY_DEBUG', false);
for ($i=1; $i<=3; $i++) {
    define('SELLSY_URL_SOUS_MENU_'.$i, admin_url().'admin.php?page=idSousMenu'.$i);
    define('SELLSY_URL_BACK_SOUS_MENU_'.$i, admin_url().'admin.php?page=idSousMenu'.$i);
}

load_plugin_textdomain(PLUGIN_NOM_LANG, false, dirname(plugin_basename(__FILE__)).'/languages');
define('SELLSY_DEADLINE', 30);
define('SELLSY_PROBABILITY', 10);
// Cookie
define('SELLSY_COOKIE_MAX', 15); // 2 cookies actualy : wp_sellsy and wp_sellsy_utm (4Mo Maxi for all).
define('SELLSY_COOKIE_TRACKING', 'wp_sellsy');
define('SELLSY_COOKIE_UTM', 'wp_sellsy_utm');

/**
 * Required Files
 * @since 1.0
 */
// LIBS
require_once(SELLSY_PLUGIN_DIR . '/libs/sellsy/sellsytools.php');
require_once(SELLSY_PLUGIN_DIR . '/libs/sellsy/sellsyconnect_curl.php');

// MODELS
require_once(SELLSY_PLUGIN_DIR . '/models/TSetting.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TTicket.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TTicketForm.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TContact.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TContactForm.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TError.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TClearbit.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyCatalogue.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyStaffs.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyOpportunities.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyPeoples.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyClients.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyProspects.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyCustomFields.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyTracking.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyAnnotations.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyAddresses.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyAccountPrefs.php');
require_once(SELLSY_PLUGIN_DIR . '/models/TSellsyAccountDatas.php');

// HELPERS
require_once(SELLSY_PLUGIN_DIR . '/helpers/FormHelpers.php');
require_once(SELLSY_PLUGIN_DIR . '/helpers/dbUpdate.php');

// FORMS
require_once(SELLSY_PLUGIN_DIR . '/forms/FSettingEdit.php');
require_once(SELLSY_PLUGIN_DIR . '/forms/FTicketFormEdit.php');
require_once(SELLSY_PLUGIN_DIR . '/forms/FTicketFormAdd.php');
require_once(SELLSY_PLUGIN_DIR . '/forms/FContactFormEdit.php');

// CONTROLLERS
require_once(SELLSY_PLUGIN_DIR . '/controllers/CookieController.php');
require_once(SELLSY_PLUGIN_DIR . '/controllers/AjaxController.php');
require_once(SELLSY_PLUGIN_DIR . '/controllers/InstallController.php');
require_once(SELLSY_PLUGIN_DIR . '/controllers/ToolsController.php');
require_once(SELLSY_PLUGIN_DIR . '/controllers/ShortcodeController.php');
require_once(SELLSY_PLUGIN_DIR . '/controllers/AdminController.php');
require_once(SELLSY_PLUGIN_DIR . '/controllers/SellsyCustomFieldsController.php');
require_once(SELLSY_PLUGIN_DIR . '/controllers/ContactController.php');

// php7 : use com\sellsy\sellsy\{controllers, helpers, models};
use com\sellsy\sellsy\controllers;
use com\sellsy\sellsy\helpers;
use com\sellsy\sellsy\models;
use com\sellsy\sellsy\models\TSetting;

/**
 * Required CSS / JS
 *
 * @since 1.0
 */
function ajouterScriptsSellsy()
{
    $t_setting = new TSetting();
    $setting = $t_setting->getSetting(1);

    // CSS
    wp_enqueue_style(PLUGIN_NOM_LANG.'-libs-intl-tel-input', plugins_url('sellsy/libs/intl-tel-input/build/css/intlTelInput.css'));
    wp_enqueue_style(PLUGIN_NOM_LANG.'-style', plugins_url('sellsy/css/style.css'));

    // JS
    wp_enqueue_script(PLUGIN_NOM_LANG.'-libs-intl-tel-input-js', plugins_url('sellsy/libs/intl-tel-input/build/js/intlTelInput.js'), array('jquery'), '', true);
    // reCaptcha v3
    if (!is_admin() && $setting[0]->setting_recaptcha_key_version == 3 && $setting[0]->setting_recaptcha_key_status == 0) {
        wp_enqueue_script(PLUGIN_NOM_LANG.'-recaptcha-js', 'https://www.google.com/recaptcha/api.js?render='.$setting[0]->setting_recaptcha_key_website, '', '', true);
    // reCaptcha v2
    } elseif (!is_admin() && $setting[0]->setting_recaptcha_key_version == 2 && $setting[0]->setting_recaptcha_key_status == 0) {
        wp_enqueue_script(PLUGIN_NOM_LANG.'-recaptcha-js', 'https://www.google.com/recaptcha/api.js', '', '', true);
    }

    // ONLY PAGE : contact edit
    if (isset($_GET['contact_form_id'])) {
        $contact_form_id = (int)$_GET['contact_form_id'];
        if (isset($contact_form_id) && $contact_form_id) {
            $tbl_data['ajax_url']           =  admin_url('admin-ajax.php');
            $tbl_data['contact_form_id']    =  $contact_form_id;

            // JS
            wp_enqueue_script(PLUGIN_NOM_LANG.'-ajax-script-js', plugins_url('sellsy/js/main.js'), array('jquery'), '', true);
            // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
            wp_localize_script(PLUGIN_NOM_LANG.'-ajax-script-js', 'ajax_object', $tbl_data);
        }
    }

    wp_enqueue_script(PLUGIN_NOM_LANG.'-js', plugins_url('sellsy/js/front.js'), array('jquery'), '', true);
    wp_localize_script('script', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('init', 'ajouterScriptsSellsy');

/**
 * Instantiate Class
 * @since 1.0
 */
$adminController = new controllers\AdminController();

/**
 * Wordpress Activate/Deactivate
 *
 * @uses register_activation_hook()
 * @uses register_deactivation_hook()
 * @uses register_uninstall_hook()
 * @since 1.0
 */
//register_activation_hook( __FILE__, 'install_table' );
//register_activation_hook( __FILE__, 'install_data' );
register_activation_hook(__FILE__, array($adminController, 'sellsy_activate'));
register_deactivation_hook(__FILE__, array($adminController, 'sellsy_deactivate'));
//register_uninstall_hook(__FILE__, array( $adminController, 'sellsy_uninstall')); // use methode 2 with "uninstall.php"

$s = new controllers\ShortcodeController();
$a = new controllers\AjaxController();

/**
 * Tracking
 */
$isTracking = false;
if (!is_admin()) {
    $m_setting = new models\TSetting();
    $isTracking = $m_setting->isTracking();
}


//function vv_process_profile_forms() {
//    session_start();
//
//    if ( isset( $_POST['_wpnonce'] ) && !empty($_POST['_wpnonce']) ) {
//
//        if ( ! isset( $_SESSION['form-submit'] ) ) {
//
//            // form processing code here
//
//            $_SESSION['form-submit'] = 1;
//
//            $redirect_page = get_permalink( 586 );
//            wp_redirect( $redirect_page );
//
//            exit;
//
//        } else {
//
//            unset( $_SESSION['form-submit'] );
//        }
//    }
//}
//add_action( 'init','vv_process_profile_forms' );


/*
// Test ajax with Wordpress :
add_action( 'wp_ajax_mon_action', 'mon_action' );
add_action( 'wp_ajax_nopriv_mon_action', 'mon_action' );
function mon_action() {
    echo json_encode(array(
        'aaa' => var_export($_POST, true),
        'contact_form_contact_phone_1' => $_POST['contact_form_contact_phone_1'],
        //'contact_form_contact_phone_1_phone_e164' => $_POST['contact_form_contact_phone_1_phone_e164'],
    ));

    die();
}
*/

/**
 * Cookie
 * @since 1.0
 */
if (!is_admin() && $isTracking) {
    $cookieController = new controllers\CookieController(SELLSY_COOKIE_TRACKING);
    $cookieController->exec();
}

/**
 * UTM
 * @since 1.2
 */
if (!is_admin() && isset($_GET) && !empty($_GET)) {
    // Check utm_ in url
    $cookieUtm = false;
    foreach ($_GET as $k => $v) {
        if (strpos($k, 'utm_') !== false) {
            $cookieUtm = true;
            break;
        }
    }
    // Add / Update cookie UTM
    if ($cookieUtm) {
        $cookieController = new controllers\CookieController(SELLSY_COOKIE_UTM);
        $cookieController->exec();
    }
}

//// get last utm
//$cookieUtm = new controllers\CookieController(SELLSY_COOKIE_UTM);
//$utm_last = $cookieUtm->getLast();
//
//// Remove cookie
////$cookieUtm->delete();
//
//$utm_source_value = controllers\ToolsController::getUtmSourceValue($utm_last['url']);

/**
 * Required action filters
 *
 * @uses add_action()
 * @since 1.0
 */
add_action('admin_menu', array($adminController, 'sellsy_adminMenu'));

/**
 * UPDATE DB
 * @since 1.0
 */
if (is_admin()) {
    $dbUpdate = new helpers\DbUpdate();
    $dbVersion = $dbUpdate->getVersion();
    if (version_compare($dbVersion, SELLSY_VERSION, '<') && $dbVersion !== false) {
        $dbUpdate->updateDb($dbVersion);
    }
}

/**
 * DATA PROCESSING : contact form
 * @since 1.1
 */
function dataProcessingContact()
{
    // GET ID (shortcode > form)
    if (isset($_POST['contact_form_id']) && !empty($_POST['contact_form_id'])) {
        $id = (int)$_POST['contact_form_id'];

        // MODEL
        $t_contactForm  = new models\TContactForm();
        $contact        = $t_contactForm->getContactForm($id);
        $t_setting      = new models\TSetting();
        $setting        = $t_setting->getSetting(1);

//        global $wpdb;
//        $table_setting = SELLSY_PREFIXE_BDD."setting";
//        if($wpdb->get_var("SHOW TABLES LIKE '".$table_setting."'") == $table_setting) {
//            $t_setting  = new models\TSetting();
//            $setting    = $t_setting->getSetting(1);
//            //echo '<pre>'; var_dump($setting); echo '</pre>';
//        }

        if (
            isset($_POST)  &&
            !empty($_POST) &&
            //isset($_POST['btn_contact']) &&
            ($contact[0]->contact_form_status == 0 || $contact[0]->contact_form_status == 1) &&
            !empty($id)
        ) {
            $d = array();

            $dpc = new controllers\ContactController();
            $dpc->dataProcessing($contact, $setting, $d);
        }
    }
}
add_action('init', 'dataProcessingContact');

function is_js_disabled() { ?>
    <noscript>
        <h3>
            You must have JavaScript enabled in order to use this order form.<br>
            Please enable JavaScript and then reload this page in order to continue.
        </h3>
    </noscript>
    <?php
    // <meta HTTP-EQUIV="refresh" content=0;url="/">
}
//add_action('init', 'is_js_disabled');

/**
 * Debug
 * Print session, post, get, files
 * @since 1.0
 */
if (SELLSY_DEBUG && !is_admin()) {
    echo "<pre>";
    var_dump($_SESSION, $_POST, $_GET, $_FILES);
    echo "</pre>";
}
