<?php

namespace com\sellsy\sellsy\forms;

use com\sellsy\sellsy\helpers;
use com\sellsy\sellsy\libs;
use com\sellsy\sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('Form_ContactFormEdit')) {
    class Form_ContactFormEdit extends \WP_Query
    {
        private $_action;
        private $_returnUrl;

        public function __construct()
        {
            $this->_action      = $_SERVER["REQUEST_URI"];
            $this->_returnUrl   = SELLSY_URL_BACK_SOUS_MENU_2;
        }

        /**
         * retourn form
         * @param array $r
         */
        public function contactFormEdit($r)
        {
            // INIT
            $contact_form_status = '';
            if (isset($r[0]->contact_form_status)) {
                $contact_form_status = $r[0]->contact_form_status;
            }
            $contact_form_status_clearbit = '';
            if (isset($r[0]->contact_form_status_clearbit)) {
                $contact_form_status_clearbit = $r[0]->contact_form_status_clearbit;
            }
            // DATA
            $t_contactForm = new models\TContactForm();
            $contactForm = $t_contactForm->getContactForm($r[0]->contact_form_id);

            // Wording
            $wording = json_decode( $r[0]->contact_form_wording );
            ?>

            <form method="post" action="<?php echo $this->_action; ?>">
                <?php wp_nonce_field('form_nonce_contact_edit'); ?>
                <input type='hidden' name='form_id' value='<?php echo $r[0]->contact_form_id; ?>' />

                <div class="submit">
                    <input type="submit" name="update" value="<?php _e('Save', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
                    <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
                </div>

                <div class="postbox " id="postexcerpt">
                    <h3 class="hndle"><span>
                        <?php _e('Edit', PLUGIN_NOM_LANG); ?></span>
                        <a href="/wp-admin/admin.php?page=idSellsy"><?php _e('Help', PLUGIN_NOM_LANG); ?></a>
                    </h3>
                    <div class="inside">

                        <table class='table1'>

                            <?php
                            //------------------------------------------------------------------------------------------
                            // SETTING
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Setting', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Shortcode', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php echo '[contactSellsy id="'.$r[0]->contact_form_id.'"]'; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Name', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_setting_name" value="<?php echo $r[0]->contact_form_setting_name; ?>" />
                                    <p class="description"><?php _e('Name for your back-office', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Add on Sellsy', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $contact_form_setting_add_what_selected_0 = $contact_form_setting_add_what_selected_1 = '';
                                    if ($r[0]->contact_form_setting_add_what == 0) {
                                        $contact_form_setting_add_what_selected_0 = 'selected';
                                    } else {
                                        $contact_form_setting_add_what_selected_1 = 'selected';
                                    }
                                    echo '
                                    <select name="contact_form_setting_add_what">
                                        <option value="0" '.$contact_form_setting_add_what_selected_0.'>'.__('Prospect', PLUGIN_NOM_LANG).'</option>
                                        <option value="1" '.$contact_form_setting_add_what_selected_1.'>'.__('Prospect and opportunity', PLUGIN_NOM_LANG).'</option>
                                    </select>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Status', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $status_on = $status_off = '';
                                    if( $contact_form_status == 0 ){ $status_on  = "checked";
                                    }else{                           $status_off = "checked";
                                    } ?>

                                    <input type="radio" id="status_on" name="form_status" value="0" <?php echo $status_on; ?> />
                                    <label for="status_on">
                                        <img src='<?php echo SELLSY_PLUGIN_URL; ?>/images/icones/enabled.gif' />
                                    </label>

                                    <input type="radio" id="status_off" name="form_status" value="1" <?php echo $status_off; ?> />
                                    <label for="status_off">
                                        <img src='<?php echo SELLSY_PLUGIN_URL; ?>/images/icones/disabled.gif' />
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Clearbit', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $status_clearbit_on = $status_clearbit_off = '';
                                    if( $contact_form_status_clearbit == 0 ){ $status_clearbit_on  = "checked";
                                    }else{                                    $status_clearbit_off = "checked";
                                    } ?>

                                    <input type="radio" id="status_clearbit_on" name="form_status_clearbit" value="0" <?php echo $status_clearbit_on; ?> />
                                    <label for="status_clearbit_on">
                                        <img src='<?php echo SELLSY_PLUGIN_URL; ?>/images/icones/enabled.gif' />
                                    </label>

                                    <input type="radio" id="status_clearbit_off" name="form_status_clearbit" value="1" <?php echo $status_clearbit_off; ?> />
                                    <label for="status_clearbit_off">
                                        <img src='<?php echo SELLSY_PLUGIN_URL; ?>/images/icones/disabled.gif' />
                                    </label>
                                </td>
                            </tr>

                            <?php
                            //------------------------------------------------------------------------------------------
                            // OPPORTUNITY INFORMATION
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php
                                        echo __('Opportunity information', PLUGIN_NOM_LANG)." (".__('Only if you use the option "add prospect and opportunity"', PLUGIN_NOM_LANG).")";
                                    ?></div>
                                </th>
                            </tr>

                            <tr>
                                <th>
                                    <?php _e('Name of opportunity', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_setting_name_opportunity" value="<?php echo $r[0]->contact_form_setting_name_opportunity; ?>" placeholder="<?php _e('Website', PLUGIN_NOM_LANG); ?>" />
                                    <p class="description"><?php
                                        _e('+ firstname lastname', PLUGIN_NOM_LANG);                                    ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Opportunity source', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // SOURCE
                                    $optionOppSources   = '';
                                    $t_opportunities    = new models\TSellsyOpportunities();
                                    $responseOppSource  = $t_opportunities->getSources();
                                    if (isset($responseOppSource->response) && !empty($responseOppSource->response)) {
                                        foreach ( $responseOppSource->response as $vOppSources ) {
                                            if ( isset( $vOppSources->status ) && $vOppSources->status == 'ok' ) {
                                                $selected = '';
                                                if ( $vOppSources->id == $contactForm[0]->contact_form_setting_opportunity_source ) {
                                                    $selected = 'selected';
                                                }
                                                $optionOppSources .= '<option value="' . $vOppSources->id . '" ' . $selected . '>' . $vOppSources->label . '</option>';
                                            }
                                        }
                                    }

                                    // DISPLAY
                                    echo '
                                    <select name="contact_form_setting_opportunity_source" id="contact_form_setting_opportunity_source">
                                        <option value="0">' . __( '---- selection ----', PLUGIN_NOM_LANG ) . '</option>
                                        ' . $optionOppSources . '
                                    </select>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Pipeline', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // FUNNELS
                                    $optionOppFun       = '';
                                    $t_opportunities    = new models\TSellsyOpportunities();
                                    $responseOppFun     = $t_opportunities->getFunnels();
                                    if (isset($responseOppFun->response) && !empty($responseOppFun->response)) {
                                        foreach ($responseOppFun->response as $vOppFun) {
                                            if (isset($vOppFun->status) && $vOppFun->status == 'ok') {
                                                $selected = '';
                                                if ($vOppFun->id == $contactForm[0]->contact_form_setting_opportunity_pipeline) {
                                                    $selected = 'selected';
                                                }
                                                $optionOppFun .= '<option value="' . $vOppFun->id . '" ' . $selected . '>' . $vOppFun->name . '</option>';
                                            }
                                        }
                                    }

                                    // STEPS
                                    $optionOppStep       = '';
                                    if (isset($contactForm[0]->contact_form_setting_opportunity_pipeline) && !empty($contactForm[0]->contact_form_setting_opportunity_pipeline)) {
                                        $t_opportunities    = new models\TSellsyOpportunities();
                                        $responseOppStep     = $t_opportunities->getStepsForFunnel(array(
                                            'idPipeline' => $contactForm[0]->contact_form_setting_opportunity_pipeline
                                        ));
                                        if (isset($responseOppStep->response) && !empty($responseOppStep->response)) {
                                            foreach ($responseOppStep->response as $vOppStep) {
                                                if (isset($vOppStep->status) && $vOppStep->status == 'ok' ) {
                                                    $selected = '';
                                                    if ($vOppStep->id == $contactForm[0]->contact_form_setting_opportunity_step) {
                                                        $selected = 'selected';
                                                    }
                                                    $optionOppStep .= '<option value="' . $vOppStep->id . '" ' . $selected . '>' . $vOppStep->label . '</option>';
                                                }
                                            }
                                        }
                                    }

                                    // FUNNELS
                                    echo '
                                    <select name="contact_form_setting_opportunity_pipeline" id="contact_form_setting_opportunity_pipeline">
                                        <option value="0">'.__('---- selection ----', PLUGIN_NOM_LANG).'</option>
                                        '.$optionOppFun.'
                                    </select>';

                                    // STEPS
                                    echo '
                                    <select name="contact_form_setting_opportunity_step" id="contact_form_setting_opportunity_step">
                                        <option value="0">'.__('---- selection ----', PLUGIN_NOM_LANG).'</option>
                                        '.$optionOppStep.'
                                    </select>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Deadline (in days)', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // DEADLINE
                                    $deadline = SELLSY_DEADLINE;
                                    if (isset($contactForm[0]->contact_form_setting_deadline) && !empty($contactForm[0]->contact_form_setting_deadline)) {
                                        $deadline = $contactForm[0]->contact_form_setting_deadline;
                                    }

                                    echo '
                                    <input name="contact_form_setting_deadline" id="contact_form_setting_deadline" value="'.$deadline.'">';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Potential', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // POTENTIAL
                                    $potential = 0;
                                    if (isset($contactForm[0]->contact_form_setting_potential) && !empty($contactForm[0]->contact_form_setting_potential)) {
                                        $potential = $contactForm[0]->contact_form_setting_potential;
                                    }

                                    echo '
                                    <input name="contact_form_setting_potential" id="contact_form_setting_potential" value="'.$potential.'">';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Probability', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // PROBABILITY
                                    $probability = 0;
                                    if (isset($contactForm[0]->contact_form_setting_probability) && !empty($contactForm[0]->contact_form_setting_probability)) {
                                        $probability = $contactForm[0]->contact_form_setting_probability;
                                    }

                                    echo '
                                    <input name="contact_form_setting_probability" id="contact_form_setting_probability" value="'.$probability.'">';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Assigned to', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $t_sellsyStaffs = new models\TSellsyStaffs();
                                    $staffsList = $t_sellsyStaffs->getStaffsList();
                                    if ($staffsList) {
                                        echo '
                                        <select name="contact_form_setting_linkedid">
                                            <option value="0">---- '.__('Nobody', PLUGIN_NOM_LANG).' ----</option>';
                                            if (isset($staffsList) && !empty($staffsList)) {
                                                foreach ($staffsList as $k => $v) {
                                                    $selected = '';
                                                    if ($k == $r[0]->contact_form_setting_linkedid) {
                                                        $selected = 'selected';
                                                    }
                                                    echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
                                                }
                                            }
                                        echo '
                                        </select>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Smart-tag', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_setting_smarttag" value="<?php echo $r[0]->contact_form_setting_smarttag; ?>" />
                                </td>
                            </tr>

                            <?php
                            //------------------------------------------------------------------------------------------
                            // COMPANY INFORMATION
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Company information', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Name', PLUGIN_NOM_LANG); // raison sociale ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_company_name',
                                        'form_value'=> $r[0]->contact_form_company_name,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                        helpers\FormHelpers::checkbox(array(
                                            'echo'       => true,
                                            'form_name'  => 'contact_form_required_company',
                                            'form_data'  => array(
                                                'name'  => __('Required', PLUGIN_NOM_LANG)
                                            ),
                                            'form_value' => '{"name":'.$r[0]->contact_form_required_company_name.'}',
                                            'option'     => array(
                                                'all' => false
                                            )
                                        ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Siren', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_company_siren',
                                        'form_value'=> $r[0]->contact_form_company_siren,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                        helpers\FormHelpers::checkbox(array(
                                            'echo'       => true,
                                            'form_name'  => 'contact_form_required_company',
                                            'form_data'  => array(
                                                'siren'  => __('Required', PLUGIN_NOM_LANG)
                                            ),
                                            'form_value' => '{"siren":'.$r[0]->contact_form_required_company_siren.'}',
                                            'option'     => array(
                                                'all' => false
                                            )
                                        ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Siret', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_company_siret',
                                        'form_value'=> $r[0]->contact_form_company_siret,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                        helpers\FormHelpers::checkbox(array(
                                            'echo'       => true,
                                            'form_name'  => 'contact_form_required_company',
                                            'form_data'  => array(
                                                'siret'  => __('Required', PLUGIN_NOM_LANG)
                                            ),
                                            'form_value' => '{"siret":'.$r[0]->contact_form_required_company_siret.'}',
                                            'option'     => array(
                                                'all' => false
                                            )
                                        ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('RCS', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_company_rcs',
                                        'form_value'=> $r[0]->contact_form_company_rcs,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                        helpers\FormHelpers::checkbox(array(
                                            'echo'       => true,
                                            'form_name'  => 'contact_form_required_company',
                                            'form_data'  => array(
                                                'rcs'  => __('Required', PLUGIN_NOM_LANG)
                                            ),
                                            'form_value' => '{"rcs":'.$r[0]->contact_form_required_company_rcs.'}',
                                            'option'     => array(
                                                'all' => false
                                            )
                                        ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Smart-tag', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_company_smarttag" value="<?php echo $r[0]->contact_form_company_smarttag; ?>" />
                                </td>
                            </tr>

                            <?php
                            //------------------------------------------------------------------------------------------
                            // CONTACT INFORMATION
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Contact information', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Civility', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_contact_civility',
                                        'form_value'=> $r[0]->contact_form_contact_civility,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                        helpers\FormHelpers::checkbox(array(
                                            'echo'       => true,
                                            'form_name'  => 'contact_form_required_contact',
                                            'form_data'  => array(
                                                'civility'  => __('Required', PLUGIN_NOM_LANG)
                                            ),
                                            'form_value' => '{"civility":'.$r[0]->contact_form_required_contact_civility.'}',
                                            'option'     => array(
                                                'all' => false
                                            )
                                        ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Lastname', PLUGIN_NOM_LANG); ?> <span class="sellsy-required">*</span> :
                                </th>
                                <td>
                                    <input type="radio" id="contact_form_contact_lastname" name="contact_form_contact_lastname" value="0" checked="checked">
                                    <label for="contact_form_contact_lastname">
                                        <img src="<?php echo SELLSY_PLUGIN_URL; ?>/images/icones/enabled.gif" />
                                    </label>
                                    <?php
                                    //                                    // ENABLE / DISABLE
                                    //                                    helpers\FormHelpers::radio(array(
                                    //                                        'echo'      => true,
                                    //                                        'form_name' => 'contact_form_contact_lastname',
                                    //                                        'form_value'=> $r[0]->contact_form_contact_lastname,
                                    //                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Firstname', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_contact_firstname',
                                        'form_value'=> $r[0]->contact_form_contact_firstname,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                        helpers\FormHelpers::checkbox(array(
                                            'echo'       => true,
                                            'form_name'  => 'contact_form_required_contact',
                                            'form_data'  => array(
                                                'firstname'  => __('Required', PLUGIN_NOM_LANG)
                                            ),
                                            'form_value' => '{"firstname":'.$r[0]->contact_form_required_contact_firstname.'}',
                                            'option'     => array(
                                                'all' => false
                                            )
                                        ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Email', PLUGIN_NOM_LANG); ?> <span class="sellsy-required">*</span> :
                                </th>
                                <td>
                                    <input type="radio" id="contact_form_contact_email" name="contact_form_contact_email" value="0" checked="checked">
                                    <label for="contact_form_contact_email">
                                        <img src="<?php echo SELLSY_PLUGIN_URL; ?>/images/icones/enabled.gif" />
                                    </label>
                                    <?php
//                                  // ENABLE / DISABLE
//                                  helpers\FormHelpers::radio(array(
//                                      'echo'      => true,
//                                      'form_name' => 'contact_form_contact_email',
//                                      'form_value'=> $r[0]->contact_form_contact_email,
//                                  ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Phone', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_contact_phone_1',
                                        'form_value'=> $r[0]->contact_form_contact_phone_1,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                        helpers\FormHelpers::checkbox(array(
                                            'echo'       => true,
                                            'form_name'  => 'contact_form_required_contact',
                                            'form_data'  => array(
                                                'phone_1'  => __('Required', PLUGIN_NOM_LANG)
                                            ),
                                            'form_value' => '{"phone_1":'.$r[0]->contact_form_required_contact_phone_1.'}',
                                            'option'     => array(
                                                'all' => false
                                            )
                                        ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Mobile', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_contact_phone_2',
                                        'form_value'=> $r[0]->contact_form_contact_phone_2,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                        helpers\FormHelpers::checkbox(array(
                                            'echo'       => true,
                                            'form_name'  => 'contact_form_required_contact',
                                            'form_data'  => array(
                                                'phone_2'  => __('Required', PLUGIN_NOM_LANG)
                                            ),
                                            'form_value' => '{"phone_2":'.$r[0]->contact_form_required_contact_phone_2.'}',
                                            'option'     => array(
                                                'all' => false
                                            )
                                        ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Function', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_contact_function',
                                        'form_value'=> $r[0]->contact_form_contact_function,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                        helpers\FormHelpers::checkbox(array(
                                            'echo'       => true,
                                            'form_name'  => 'contact_form_required_contact',
                                            'form_data'  => array(
                                                'function'  => __('Required', PLUGIN_NOM_LANG)
                                            ),
                                            'form_value' => '{"function":'.$r[0]->contact_form_required_contact_function.'}',
                                            'option'     => array(
                                                'all' => false
                                            )
                                        ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Smart-tag', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_contact_smarttag" value="<?php echo $r[0]->contact_form_contact_smarttag; ?>" />
                                </td>
                            </tr>

                            <?php
                            //------------------------------------------------------------------------------------------
                            // MARKETING
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Marketing', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Marketing', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <div id="contact_form_marketing">
                                        <?php
                                        // ENABLE / DISABLE
                                        helpers\FormHelpers::checkbox(array(
                                            'echo'       => true,
                                            'form_name'  => 'contact_form_marketing',
                                            'form_data'  => array(
                                                'email'     => __('Email', PLUGIN_NOM_LANG),
                                                'sms'       => __('Sms', PLUGIN_NOM_LANG),
                                                'phone'     => __('Phone', PLUGIN_NOM_LANG),
                                                'mail'      => __('Mail', PLUGIN_NOM_LANG),
                                                'custom'    => __('Customized marketing', PLUGIN_NOM_LANG)
                                            ),
                                            'form_value' => $r[0]->contact_form_marketing,
                                            'option'     => array(
                                                'all' => true
                                            )
                                        ));
                                        ?>
                                    </div>
                                    <br>
                                </td>
                            </tr>

                            <?php
                            //------------------------------------------------------------------------------------------
                            // Wording
                            //------------------------------------------------------------------------------------------
                            // INIT
                            $marketingSubscribe     = "";
                            $marketingAll           = "";
                            $marketingEmail         = "";
                            $marketingSms           = "";
                            $marketingPhone         = "";
                            $marketingMail          = "";
                            $marketingCustomized    = "";
                            if (isset($wording->marketing_subscribe)) { $marketingSubscribe = stripslashes($wording->marketing_subscribe); }
                            if (isset($wording->marketing_all)) { $marketingAll = stripslashes($wording->marketing_all); }
                            if (isset($wording->marketing_email)) { $marketingEmail = stripslashes($wording->marketing_email); }
                            if (isset($wording->marketing_sms)) { $marketingSms = stripslashes($wording->marketing_sms); }
                            if (isset($wording->marketing_phone)) { $marketingPhone = stripslashes($wording->marketing_phone); }
                            if (isset($wording->marketing_mail)) { $marketingMail = stripslashes($wording->marketing_mail); }
                            if (isset($wording->marketing_customizedmarketing)) { $marketingCustomized = stripslashes($wording->marketing_customizedmarketing); }
                            ?>
                            <tr>
                                <th>
                                    <?php _e('Subscribe', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_wording_marketing_subscribe" value="<?php echo $marketingSubscribe; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('I agree to be contacted.', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_wording_marketing_all" value="<?php echo $marketingAll; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('I agree to be contacted by email.', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_wording_marketing_email" value="<?php echo $marketingEmail; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('I agree to be contacted by sms.', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_wording_marketing_sms" value="<?php echo $marketingSms; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('I agree to be contacted by phone.', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_wording_marketing_phone" value="<?php echo $marketingPhone; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('I agree to be contacted by mail.', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_wording_marketing_mail" value="<?php echo $marketingMail; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('I accept that the data is used to offer me suitable offers.', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_wording_marketing_customizedmarketing" value="<?php echo $marketingCustomized; ?>" />
                                </td>
                            </tr>

                            <?php
                            //------------------------------------------------------------------------------------------
                            // OTHER
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Other', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>

                            <tr>
                                <th>
                                    <?php _e('Address', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_address_street',
                                        'form_value'=> $r[0]->contact_form_address_street,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                        helpers\FormHelpers::checkbox(array(
                                            'echo'       => true,
                                            'form_name'  => 'contact_form_required_address',
                                            'form_data'  => array(
                                                'street'  => __('Required', PLUGIN_NOM_LANG)
                                            ),
                                            'form_value' => '{"street":'.$r[0]->contact_form_required_address_street.'}',
                                            'option'     => array(
                                                'all' => false
                                            )
                                        ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Zip', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_address_zip',
                                        'form_value'=> $r[0]->contact_form_address_zip,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                    helpers\FormHelpers::checkbox(array(
                                        'echo'       => true,
                                        'form_name'  => 'contact_form_required_address',
                                        'form_data'  => array(
                                            'zip'  => __('Required', PLUGIN_NOM_LANG)
                                        ),
                                        'form_value' => '{"zip":'.$r[0]->contact_form_required_address_zip.'}',
                                        'option'     => array(
                                            'all' => false
                                        )
                                    ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Town', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_address_town',
                                        'form_value'=> $r[0]->contact_form_address_town,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                    helpers\FormHelpers::checkbox(array(
                                        'echo'       => true,
                                        'form_name'  => 'contact_form_required_address',
                                        'form_data'  => array(
                                            'town'  => __('Required', PLUGIN_NOM_LANG)
                                        ),
                                        'form_value' => '{"town":'.$r[0]->contact_form_required_address_town.'}',
                                        'option'     => array(
                                            'all' => false
                                        )
                                    ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Country', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_address_country',
                                        'form_value'=> $r[0]->contact_form_address_country,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                    helpers\FormHelpers::checkbox(array(
                                        'echo'       => true,
                                        'form_name'  => 'contact_form_required_address',
                                        'form_data'  => array(
                                            'country'  => __('Required', PLUGIN_NOM_LANG)
                                        ),
                                        'form_value' => '{"country":'.$r[0]->contact_form_required_address_country.'}',
                                        'option'     => array(
                                            'all' => false
                                        )
                                    ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <?php _e('Website', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_website',
                                        'form_value'=> $r[0]->contact_form_website,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                    helpers\FormHelpers::checkbox(array(
                                        'echo'       => true,
                                        'form_name'  => 'contact_form_required',
                                        'form_data'  => array(
                                            'website'  => __('Required', PLUGIN_NOM_LANG)
                                        ),
                                        'form_value' => '{"website":'.$r[0]->contact_form_required_website.'}',
                                        'option'     => array(
                                            'all' => false
                                        )
                                    ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Note', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_note',
                                        'form_value'=> $r[0]->contact_form_note,
                                    ));

                                    // REQUIRED
                                    echo '<span class="ml20">';
                                    helpers\FormHelpers::checkbox(array(
                                        'echo'       => true,
                                        'form_name'  => 'contact_form_required',
                                        'form_data'  => array(
                                            'note'  => __('Required', PLUGIN_NOM_LANG)
                                        ),
                                        'form_value' => '{"note":'.$r[0]->contact_form_required_note.'}',
                                        'option'     => array(
                                            'all' => false
                                        )
                                    ));
                                    echo '</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Url redirection', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="url" name="contact_form_redirectionurl" value="<?php echo $r[0]->contact_form_redirectionurl; ?>" />
                                    <p class="description"><?php
                                        _e('Redirection URL following the validation of the form (ex : https://www.your_website.com/validation_form).', PLUGIN_NOM_LANG);
                                    ?></p>
                                </td>
                            </tr>






                            <?php
                            //------------------------------------------------------------------------------------------
                            // FORM NOTIFICATION
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Form notification', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>

                            <tr>
                                <th>
                                    <?php _e('Activate email notification', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_setting_notification_email_enable',
                                        'form_value'=> $r[0]->contact_form_setting_notification_email_enable,
                                    ));
                                    ?>
                                    <p class="description"><?php
                                        _e('Receive an email with the information from the validated form on your website.', PLUGIN_NOM_LANG);
                                    ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Email recipient', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_setting_notification_email" value="<?php echo $r[0]->contact_form_setting_notification_email; ?>" placeholder="<?php _e('your email', PLUGIN_NOM_LANG); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Email subject', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $contact_form_setting_notification_email_selected_0 = $contact_form_setting_notification_email_selected_1 = '';
                                    if ($r[0]->contact_form_setting_notification_email_prefix_enable == 0) {
                                        $contact_form_setting_notification_email_selected_0 = 'selected';
                                    } else {
                                        $contact_form_setting_notification_email_selected_1 = 'selected';
                                    }
                                    echo '
                                    <select name="contact_form_setting_notification_email_prefix_enable">
                                        <option value="0" '.$contact_form_setting_notification_email_selected_0.'>'.__('No prefix', PLUGIN_NOM_LANG).'</option>
                                        <option value="1" '.$contact_form_setting_notification_email_selected_1.'>'.__('Count [#123]', PLUGIN_NOM_LANG).'</option>
                                    </select>';
                                    ?>

                                    <input type="text" name="contact_form_setting_notification_email_prefix_value" value="<?php echo $r[0]->contact_form_setting_notification_email_prefix_value; ?>" placeholder="<?php _e('your subject', PLUGIN_NOM_LANG); ?>" />
                                    <p class="description"><?php
                                        _e('The counter will automatically increment with each new request.', PLUGIN_NOM_LANG);
                                    ?></p>
                                </td>
                            </tr>






                            <?php
                            //------------------------------------------------------------------------------------------
                            // SUBMIT FORM
                            //------------------------------------------------------------------------------------------
                            $marketingButton = "";
                            $conditionLabel  = "";
                            if (isset($wording->button)) { $marketingButton = stripslashes($wording->button); }
                            if (isset($wording->conditionLabel)) {
                                $conditionLabel = stripslashes($wording->conditionLabel);
                                $conditionLabel = str_replace('"', '\'', $conditionLabel);
                            }
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Submit form', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>

                            <tr>
                                <th>
                                    <?php _e('Accept the conditions', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    // ENABLE / DISABLE
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_condition_accept',
                                        'form_value'=> $r[0]->contact_form_condition_accept,
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Condition label', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_wording_condition_label" value="<?php echo $conditionLabel; ?>" />

                                    <p class="description"><?php
                                        _e('Explanatory text of the check box for the submission of the form (TOS, GDPR, ...).', PLUGIN_NOM_LANG);
                                    ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Label of the submission button', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_wording_button" value="<?php echo $marketingButton; ?>" placeholder="<?php _e('Send request', PLUGIN_NOM_LANG); ?>" />
                                </td>
                            </tr>

                            <?php
                            //------------------------------------------------------------------------------------------
                            // CUSTOM FIELDS
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Custom fields', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Add custom fields', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $qtyCf = 0;
                                    if (isset($r[0]->contact_form_custom_fields_quantity) && !empty($r[0]->contact_form_custom_fields_quantity)) {
                                        $qtyCf = $r[0]->contact_form_custom_fields_quantity;
                                    }
//                                    echo '
//                                    <input type="text" name="contact_form_custom_fields_quantity" value="'.$qtyCf.'" disabled>';
                                    echo '
                                    <input type="hidden" name="contact_form_custom_fields_quantity" value="'.$qtyCf.'">
                                    <a href="javascript:void(0);" class="sellsy-addCf"><img src="'.SELLSY_PLUGIN_URL.'/images/icones/icone_add.png" /></a>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>

                                </th>
                                <td>
                                    <?php
                                    // Model : get cf
                                    $t_customFields       = new models\TSellsyCustomFields();
                                    $responseCustomFields = $t_customFields->getCustomFields();

                                    // Exist (cf in db)
                                    if (isset($r[0]->contact_form_custom_fields_value) && !empty($r[0]->contact_form_custom_fields_value)) {
                                        $cfVal = json_decode($r[0]->contact_form_custom_fields_value); // cfid
                                    } else {
                                        $cfVal = '';
                                    }

                                    $i=0;
                                    if (isset($cfVal) && !empty($cfVal)) {

                                        // CF Value
                                        foreach ( $cfVal as $k => $v ) {
                                            $tbl_value[ $k ] = $v;
                                        }

                                        // Form : select
                                        for ( $i = 0; $i < $qtyCf; $i ++ ) {
                                            echo '
                                            <div class="cf_'.$i.'">
                                                <table><tr><td>';

                                                    if (isset($tbl_value[ $i ]) && !empty($tbl_value[ $i ])){
                                                        helpers\FormHelpers::getCustomFields( array(
                                                            'echo'                 => true,
                                                            'form_name'            => 'contact_form_custom_fields_value_' . $i,
                                                            'form_value'           => $tbl_value[ $i ],
                                                            // cf all
                                                            'responseCustomFields' => $responseCustomFields,
                                                            // use for display cf all
                                                            'useOn_x'              => $r[0]->contact_form_setting_add_what,
                                                        ) );
                                                    }

                                                echo '
                                                </td><td>
                                                    <a href="javascript:void(0);" class="sellsy-deleteCf" data-id="'.$i.'">
                                                        <img src="'.SELLSY_PLUGIN_URL.'/images/icones/icone_remove.png" />
                                                    </a>
                                                </td></tr></table> 
                                            </div>';
                                        }

                                    }

                                    // Form : select structure (hidden)
                                    echo '
                                    <div class="cf_structure">
                                        <table>
                                            <tr>
                                                <td>';
                                                    helpers\FormHelpers::getCustomFields( array(
                                                        'echo'                 => true,
                                                        'form_name'            => 'contact_form_custom_fields_value_structure',
                                                        'form_value'           => '',
                                                        // cf all
                                                        'responseCustomFields' => $responseCustomFields,
                                                        // use for display cf all
                                                        'useOn_x'              => $r[0]->contact_form_setting_add_what,
                                                    ) );

                                                echo '
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" class="sellsy-deleteCf" data-id="'.$i.'">
                                                        <img src="'.SELLSY_PLUGIN_URL.'/images/icones/icone_remove.png" />
                                                    </a>
                                                </td>
                                            </tr>
                                        </table> 
                                    </div>';

                                    echo '
                                    <div id="cf_new" data-count="'.$qtyCf.'">
                                    </div>';

                                    // Wording + list : Required field
                                    $requiredCF = $t_customFields->countTotalRequiredField(array(
                                        "response" => $responseCustomFields,
                                        "cfByName" => true,
                                    ));
                                    if ($requiredCF) {
                                        if (sizeof($requiredCF)>1) {
                                            echo _e('Required field', PLUGIN_NOM_LANG);
                                        } else {
                                            echo _e('Required fields', PLUGIN_NOM_LANG);
                                        }
                                        echo " : ".implode(", ", $requiredCF);
                                    }
                                    ?>
                                </td>
                            </tr>

                        </table>

                        <p>* : <?php _e('required', PLUGIN_NOM_LANG); ?></p>

                    </div>
                </div><?php //postbox ?>

                <div class="submit">
                    <input type="submit" name="update" value="<?php _e('Save', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
                    <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
                </div>

            </form>
            <?php
        }
    }
}
