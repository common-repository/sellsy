<?php
use com\sellsy\sellsy\controllers;
use com\sellsy\sellsy\models;
use com\sellsy\sellsy\forms;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

global $wpdb;
?>

<div class="wrap" >
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Contact', PLUGIN_NOM_LANG); ?> <?php /*<a href="<?php echo $_SERVER["REQUEST_URI"]."&type=add"; ?>" class="add-new-h2"><?php _e('Add', PLUGIN_NOM_LANG); ?></a>*/ ?></h2>
</div>

<?php
// UPDATE : status
if (isset($_GET['type']) && $_GET['type'] == "status") {
    $t_contact_form = new models\TContactForm();
    $r = $t_contact_form->updateStatus($_GET);

    $tools = new controllers\ToolsController();
    $display = $tools->verifMaj( $r );
    echo $display;
}//if

// REQ
$reqSuite   = " WHERE contact_form_id > %d ";    // just for notice wordpress wpdb::prepare
$reqSuite   .=" ORDER BY contact_form_id DESC";
$tbl_params = array('0');                       // just for notice wordpress wpdb::prepare

// req
$t_contact  = new models\TContactForm();
$r          = $t_contact->getContactsForm( $reqSuite, $tbl_params );

// display
$render = "
<p>
    ".__('Here is the list of all contact forms available to you.<br>You can configure your forms by editing them.<br>They will allow you to create a prospect and a contact in Sellsy, when submitting the form of your site.<br>The shortcode is to copy / paste on a page / article to display the form.', PLUGIN_NOM_LANG)."
</p>
<table class='sellsy-table'>
	<tr>
		<th>
			".__('id', PLUGIN_NOM_LANG)."
		</th>
		<th>
			".__('Name', PLUGIN_NOM_LANG)."
		</th>
        <th>
			".__('Shortcode', PLUGIN_NOM_LANG)."
		</th>
        <th>
			".__('State', PLUGIN_NOM_LANG)."
		</th>
		<th>
			".__('Edit', PLUGIN_NOM_LANG)."
		</th>
		<th>
			".__('Duplicate', PLUGIN_NOM_LANG)."
		</th>
	</tr>";

    if(isset($r) && !empty($r)) {
        foreach ( $r as $v ) {
            $render .= "
                <tr>
                    <td class='c'>
                        " . $v->contact_form_id . "
                    </td>
                    <td>
                        " . $v->contact_form_setting_name . "
                    </td>
                    <td class='c'>
                        [contactSellsy id=" . $v->contact_form_id . "]
                    </td>
                    <td class='c'>";

            if ( $v->contact_form_status ) {
                $render .= "
                <a href='" . SELLSY_URL_SOUS_MENU_2 . "&type=status&status=0&contact_form_id=" . $v->contact_form_id . "'>
                    <img src='" . SELLSY_PLUGIN_URL . "/images/icones/disabled.gif' />
                </a>";
            } else {
                $render .= "
                <a href='" . SELLSY_URL_SOUS_MENU_2 . "&type=status&status=1&contact_form_id=" . $v->contact_form_id . "'>
                    <img src='" . SELLSY_PLUGIN_URL . "/images/icones/enabled.gif' />
                </a>";
            }

            $render .= "
                    </td>
                    <td class='c'>
                        <a href='" . SELLSY_URL_SOUS_MENU_2 . "&type=edit&contact_form_id=" . $v->contact_form_id . "'>" . __( 'Edit',
                    PLUGIN_NOM_LANG ) . "</a>
                    </td>
                    <td class='c'>
                        <a href='" . SELLSY_URL_SOUS_MENU_2 . "&type=duplicate&contact_form_id=" . $v->contact_form_id . "'>" . __( 'Duplicate',
                    PLUGIN_NOM_LANG ) . "</a>
                    </td>
                </tr>";
        }//fin foreach
    }

$render .= "
</table>";
echo $render;
