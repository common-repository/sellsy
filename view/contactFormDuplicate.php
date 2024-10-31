<?php
use com\sellsy\sellsy\controllers;
use com\sellsy\sellsy\models;
use com\sellsy\sellsy\forms;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

// GET : contact_form
$t_contact_form = new models\TContactForm();
$r = $t_contact_form->getContactForm($_GET['contact_form_id']);

// DUPLICATE
$idDuplicate = $t_contact_form->duplicate($r);

// DISPLAY
$tools = new controllers\ToolsController();
$display = $tools->verifMaj($idDuplicate);
echo $display;

// REDIRECT
echo '
<div class="submit">
    <a href="'.SELLSY_URL_BACK_SOUS_MENU_2.'&type=edit&contact_form_id='.$idDuplicate.'" class="button button-primary">'.__('Edit the new form', PLUGIN_NOM_LANG).'</a>
    <a href="/wp-admin/admin.php?page=idSousMenu2" class="button">'.__('Back', PLUGIN_NOM_LANG).'</a>
</div>';
