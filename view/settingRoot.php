<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (isset($_GET['type'])) {
    if ($_GET['type'] == "edit") {
        include(SELLSY_PLUGIN_DIR . '/view/settingEdit.php');
//    } elseif ($_GET['type'] == "delete") {
//        include(SELLSY_PLUGIN_DIR.'/view/settingDelete.php');
//    } elseif ($_GET['type'] == "add") {
//        include(SELLSY_PLUGIN_DIR . '/view/settingAdd.php');
    }
} else {
    include(SELLSY_PLUGIN_DIR.'/view/settingEdit.php');
}
