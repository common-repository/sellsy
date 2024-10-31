<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (isset($_GET['type'])) {
    if ($_GET['type'] == "edit") {
        include(SELLSY_PLUGIN_DIR . '/view/ticketFormEdit.php');
//  } elseif ($_GET['type'] == "delete") {
//      include(SELLSY_PLUGIN_DIR.'/view/ticketFormDelete.php');
    } elseif ($_GET['type'] == "add") {
        include(SELLSY_PLUGIN_DIR . '/view/ticketFormAdd.php');
    } else {
        include(SELLSY_PLUGIN_DIR.'/view/ticketForm.php');
    }
} else {
    include(SELLSY_PLUGIN_DIR.'/view/ticketForm.php');
}
