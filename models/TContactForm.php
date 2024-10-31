<?php

namespace com\sellsy\sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('TContactForm')) {
    class TContactForm extends \WP_Query
    {
        private $_table;

        public function __construct()
        {
            $this->_table = SELLSY_PREFIXE_BDD."contact_form";
        }

        /**
         * retourn rows
         */
        public function getContactsForm($req = "", $params = "")
        {
            global $wpdb;
            $sql = $wpdb->prepare("SELECT * FROM ".$this->_table." ".$req, $params);
            $r   = $wpdb->get_results($sql);
            return $r;
        }

        /**
         * retourn row
         * @param int $id
         */
        public function getContactForm($id)
        {
            global $wpdb;
            $sql = $wpdb->prepare("SELECT * FROM ".$this->_table." WHERE contact_form_id=%d", $id);
            $r   = $wpdb->get_results($sql);
            return $r;
        }

        /**
         * update status
         * @param $_POST $p
         */
        public function updateStatus($p)
        {
            global $wpdb;
            $r = $wpdb->update(
                $this->_table,
                // SET (valeur)
                array(
                    'contact_form_status' => $p['status']
                ),
                // WHERE (valeur)
                array(
                    'contact_form_id' => $p['contact_form_id']
                ),
                // SET (type)
                array(
                    '%d'
                ),
                // WHERE (type)
                array(
                    '%d'
                )
            );
            return $r;
        }

        /**
         * insert
         * @param $_POST $p
         */
        public function duplicate( $p )
        {
            global $wpdb;

            // INIT
            $contact_form_setting_smarttag              = $p[0]->contact_form_setting_smarttag;
            $contact_form_setting_name                  = $p[0]->contact_form_setting_name;
            $contact_form_setting_add_what              = (int)$p[0]->contact_form_setting_add_what;
            $contact_form_setting_name_opportunity      = $p[0]->contact_form_setting_name_opportunity;

            $contact_form_setting_opportunity_source    = (int)$p[0]->contact_form_setting_opportunity_source;
            $contact_form_setting_opportunity_pipeline  = (int)$p[0]->contact_form_setting_opportunity_pipeline;
            $contact_form_setting_opportunity_step      = (int)$p[0]->contact_form_setting_opportunity_step;

            $contact_form_setting_notification_email    = $p[0]->contact_form_setting_notification_email;
            $contact_form_setting_notification_email_enable = (int)$p[0]->contact_form_setting_notification_email_enable;
            $contact_form_setting_notification_email_prefix_enable = (int)$p[0]->contact_form_setting_notification_email_prefix_enable;
            $contact_form_setting_notification_email_prefix_value = $p[0]->contact_form_setting_notification_email_prefix_value;

            $contact_form_setting_deadline              = (int)$p[0]->contact_form_setting_deadline;
            $contact_form_setting_potential             = $p[0]->contact_form_setting_potential;
            $contact_form_setting_probability           = (int)$p[0]->contact_form_setting_probability;
            $contact_form_setting_linkedid              = (int)$p[0]->contact_form_setting_linkedid;

            $contact_form_company_smarttag              = $p[0]->contact_form_company_smarttag;
            $contact_form_company_name                  = (int)$p[0]->contact_form_company_name;
            $contact_form_company_siren                 = (int)$p[0]->contact_form_company_siren;
            $contact_form_company_siret                 = (int)$p[0]->contact_form_company_siret;
            $contact_form_company_rcs                   = (int)$p[0]->contact_form_company_rcs;

            $contact_form_contact_smarttag              = $p[0]->contact_form_contact_smarttag;
            $contact_form_contact_civility              = (int)$p[0]->contact_form_contact_civility;
            $contact_form_contact_lastname              = (int)$p[0]->contact_form_contact_lastname;
            $contact_form_contact_firstname             = (int)$p[0]->contact_form_contact_firstname;
            $contact_form_contact_email                 = (int)$p[0]->contact_form_contact_email;
            $contact_form_contact_phone_1               = (int)$p[0]->contact_form_contact_phone_1;
            $contact_form_contact_phone_2               = (int)$p[0]->contact_form_contact_phone_2;
            $contact_form_contact_function              = (int)$p[0]->contact_form_contact_function;

            $contact_form_address_street                = (int)$p[0]->contact_form_address_street;
            $contact_form_address_zip                   = (int)$p[0]->contact_form_address_zip;
            $contact_form_address_town                  = (int)$p[0]->contact_form_address_town;
            $contact_form_address_country               = (int)$p[0]->contact_form_address_country;

            $contact_form_website                       = (int)$p[0]->contact_form_website;
            $contact_form_note                          = (int)$p[0]->contact_form_note;
            $contact_form_condition_accept              = (int)$p[0]->contact_form_condition_accept;
            $contact_form_marketing                     = $p[0]->contact_form_marketing;
            $contact_form_redirectionurl                = $p[0]->contact_form_redirectionurl;

            $contact_form_status                        = (int)$p[0]->contact_form_status;
            $contact_form_status_clearbit               = (int)$p[0]->contact_form_status_clearbit;
            $contact_form_custom_fields_quantity        = (int)$p[0]->contact_form_custom_fields_quantity;

            $contact_form_custom_fields_value           = $p[0]->contact_form_custom_fields_value;
            $contact_form_wording                       = $p[0]->contact_form_wording;

            $r = $wpdb->insert(
                $this->_table,
                array(
                    'contact_form_dt_create' => current_time('mysql'),
                    'contact_form_dt_update' => current_time('mysql'),

                    'contact_form_setting_smarttag' => $contact_form_setting_smarttag,
                    'contact_form_setting_name' => $contact_form_setting_name,
                    'contact_form_setting_add_what' => $contact_form_setting_add_what,
                    'contact_form_setting_name_opportunity' => $contact_form_setting_name_opportunity,

                    'contact_form_setting_opportunity_source' => $contact_form_setting_opportunity_source,
                    'contact_form_setting_opportunity_pipeline' => $contact_form_setting_opportunity_pipeline,
                    'contact_form_setting_opportunity_step' => $contact_form_setting_opportunity_step,

                    'contact_form_setting_notification_email' => $contact_form_setting_notification_email,
                    'contact_form_setting_notification_email_enable' => $contact_form_setting_notification_email_enable,
                    'contact_form_setting_notification_email_prefix_enable' => $contact_form_setting_notification_email_prefix_enable,
                    'contact_form_setting_notification_email_prefix_value' => $contact_form_setting_notification_email_prefix_value,

                    'contact_form_setting_deadline'       => $contact_form_setting_deadline,
                    'contact_form_setting_potential'      => $contact_form_setting_potential,
                    'contact_form_setting_probability'    => $contact_form_setting_probability,
                    'contact_form_setting_linkedid'       => $contact_form_setting_linkedid,

                    'contact_form_company_smarttag'       => $contact_form_company_smarttag,
                    'contact_form_company_name'           => $contact_form_company_name,
                    'contact_form_company_siren'          => $contact_form_company_siren,
                    'contact_form_company_siret'          => $contact_form_company_siret,
                    'contact_form_company_rcs'            => $contact_form_company_rcs,

                    'contact_form_contact_smarttag'       => $contact_form_contact_smarttag,
                    'contact_form_contact_civility'       => $contact_form_contact_civility,
                    'contact_form_contact_lastname'       => $contact_form_contact_lastname,
                    'contact_form_contact_firstname'      => $contact_form_contact_firstname,
                    'contact_form_contact_email'          => $contact_form_contact_email,
                    'contact_form_contact_phone_1'        => $contact_form_contact_phone_1,
                    'contact_form_contact_phone_2'        => $contact_form_contact_phone_2,
                    'contact_form_contact_function'       => $contact_form_contact_function,

                    'contact_form_address_street'         => $contact_form_address_street,
                    'contact_form_address_zip'            => $contact_form_address_zip,
                    'contact_form_address_town'           => $contact_form_address_town,
                    'contact_form_address_country'        => $contact_form_address_country,

                    'contact_form_website'                => $contact_form_website,
                    'contact_form_note'                   => $contact_form_note,
                    'contact_form_condition_accept'       => $contact_form_condition_accept,
                    'contact_form_marketing'              => $contact_form_marketing,
                    'contact_form_redirectionurl'         => $contact_form_redirectionurl,

                    'contact_form_status'                 => $contact_form_status,
                    'contact_form_status_clearbit'        => $contact_form_status_clearbit,
                    'contact_form_custom_fields_quantity' => $contact_form_custom_fields_quantity,

                    'contact_form_custom_fields_value'    => $contact_form_custom_fields_value,
                    'contact_form_wording'                => $contact_form_wording,
                ),
                array(
                    '%s',
                    '%s',

                    '%s',
                    '%s',
                    '%d',   // contact_form_setting_add_what
                    '%s',

                    '%d',
                    '%d',
                    '%d',

                    '%s',   // contact_form_setting_notification_email
                    '%d',
                    '%d',
                    '%s',

                    '%d',
                    '%f',   // contact_form_setting_potential
                    '%d',
                    '%d',

                    '%s',   // contact_form_company_smarttag
                    '%d',
                    '%d',
                    '%d',
                    '%d',

                    '%s',   // contact_form_contact_smarttag
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',

                    '%s',   // Addresses
                    '%s',
                    '%s',
                    '%s',

                    '%s',
                    '%s',
                    '%s',
                    '%s',   // contact_form_marketing
                    '%s',

                    '%d',
                    '%d',
                    '%d',

                    '%s',
                    '%s',
                )
            );

            return $wpdb->insert_id; // last id
        }

        /**
         * update
         * @param $_POST $p
         */
        public function update($p)
        {
            global $wpdb;

            // INIT
            $contact_form_custom_fields_quantity        = 0;
            $contact_form_custom_fields_value_json      = "";

            $contact_form_setting_opportunity_source = "";
            if (isset($p['contact_form_setting_opportunity_source']) && !empty($p['contact_form_setting_opportunity_source'])) {
                $contact_form_setting_opportunity_source = sanitize_key($p['contact_form_setting_opportunity_source']);
            } else {
                $p['contact_form_setting_opportunity_source'] = "";
            }

            $contact_form_setting_opportunity_pipeline = "";
            if (isset($p['contact_form_setting_opportunity_pipeline']) && !empty($p['contact_form_setting_opportunity_pipeline'])) {
                $contact_form_setting_opportunity_pipeline = sanitize_key($p['contact_form_setting_opportunity_pipeline']);
            } else {
                $p['contact_form_setting_opportunity_pipeline'] = "";
            }

            $contact_form_setting_opportunity_step = "";
            if (isset($p['contact_form_setting_opportunity_step']) && !empty($p['contact_form_setting_opportunity_step'])) {
                $contact_form_setting_opportunity_step = sanitize_key($p['contact_form_setting_opportunity_step']);
            } else {
                $p['contact_form_setting_opportunity_step'] = "";
            }

            if (isset($p['contact_form_custom_fields_quantity']) && !empty($p['contact_form_custom_fields_quantity'])) {
                $contact_form_custom_fields_quantity = (int)$p['contact_form_custom_fields_quantity'];
                $contact_form_custom_fields_value = array();
                for ($i=0; $i<=$contact_form_custom_fields_quantity; $i++) {
                    if (isset($p['contact_form_custom_fields_value_'.$i])) {
                        $contact_form_custom_fields_value[] = sanitize_key($p['contact_form_custom_fields_value_'.$i]);
                    }
                }
                $contact_form_custom_fields_value_json = json_encode($contact_form_custom_fields_value, JSON_FORCE_OBJECT);
            }

            // Marketing : email, sms, phone, mail, custom
            $marketing = array();
            $marketingData = array('email', 'sms', 'phone', 'mail', 'custom');
            if (isset($marketingData) && !empty($marketingData)) {
                foreach ($marketingData as $vData) {
                    if (isset($p['contact_form_marketing_' . $vData]) && !empty($p['contact_form_marketing_' . $vData])) {
                        $marketing[$vData] = true;
                    } else {
                        $marketing[$vData] = false;
                    }
                }
            }
            $marketing = json_encode($marketing);

            if ($p['contact_form_setting_probability'] > 100) {
                $p['contact_form_setting_probability'] = 100;
            }

            // Wording
            $wording = array(
                'marketing_subscribe'           => $p['contact_form_wording_marketing_subscribe'],
                'marketing_all'                 => $p['contact_form_wording_marketing_all'],
                'marketing_email'               => $p['contact_form_wording_marketing_email'],
                'marketing_sms'                 => $p['contact_form_wording_marketing_sms'],
                'marketing_phone'               => $p['contact_form_wording_marketing_phone'],
                'marketing_mail'                => $p['contact_form_wording_marketing_mail'],
                'marketing_customizedmarketing' => $p['contact_form_wording_marketing_customizedmarketing'],
                'button'                        => $p['contact_form_wording_button'],
                'conditionLabel'                => $p['contact_form_wording_condition_label'],
            );
            $wording_json = json_encode($wording);

            // Required
            $required = array(
                'contact_form_required_company_name' => false,
                'contact_form_required_company_siren' => false,
                'contact_form_required_company_siret' => false,
                'contact_form_required_company_rcs' => false,
                'contact_form_required_contact_civility' => false,
                'contact_form_required_contact_firstname' => false,
                'contact_form_required_contact_phone_1' => false,
                'contact_form_required_contact_phone_2' => false,
                'contact_form_required_contact_function' => false,
                'contact_form_required_address_street' => false,
                'contact_form_required_address_zip' => false,
                'contact_form_required_address_town' => false,
                'contact_form_required_address_country' => false,
                'contact_form_required_website' => false,
                'contact_form_required_note' => false
            );
            if (isset($p['contact_form_required_company_name']) && !empty($p['contact_form_required_company_name'])) {
                $required['contact_form_required_company_name'] = true;
            }
            if (isset($p['contact_form_required_company_siren']) && !empty($p['contact_form_required_company_siren'])) {
                $required['contact_form_required_company_siren'] = true;
            }
            if (isset($p['contact_form_required_company_siret']) && !empty($p['contact_form_required_company_siret'])) {
                $required['contact_form_required_company_siret'] = true;
            }
            if (isset($p['contact_form_required_company_rcs']) && !empty($p['contact_form_required_company_rcs'])) {
                $required['contact_form_required_company_rcs'] = true;
            }
            if (isset($p['contact_form_required_contact_civility']) && !empty($p['contact_form_required_contact_civility'])) {
                $required['contact_form_required_contact_civility'] = true;
            }
            if (isset($p['contact_form_required_contact_firstname']) && !empty($p['contact_form_required_contact_firstname'])) {
                $required['contact_form_required_contact_firstname'] = true;
            }
            if (isset($p['contact_form_required_contact_phone_1']) && !empty($p['contact_form_required_contact_phone_1'])) {
                $required['contact_form_required_contact_phone_1'] = true;
            }
            if (isset($p['contact_form_required_contact_phone_2']) && !empty($p['contact_form_required_contact_phone_2'])) {
                $required['contact_form_required_contact_phone_2'] = true;
            }
            if (isset($p['contact_form_required_contact_function']) && !empty($p['contact_form_required_contact_function'])) {
                $required['contact_form_required_contact_function'] = true;
            }
            if (isset($p['contact_form_required_address_street']) && !empty($p['contact_form_required_address_street'])) {
                $required['contact_form_required_address_street'] = true;
            }
            if (isset($p['contact_form_required_address_zip']) && !empty($p['contact_form_required_address_zip'])) {
                $required['contact_form_required_address_zip'] = true;
            }
            if (isset($p['contact_form_required_address_town']) && !empty($p['contact_form_required_address_town'])) {
                $required['contact_form_required_address_town'] = true;
            }
            if (isset($p['contact_form_required_address_country']) && !empty($p['contact_form_required_address_country'])) {
                $required['contact_form_required_address_country'] = true;
            }
            if (isset($p['contact_form_required_website']) && !empty($p['contact_form_required_website'])) {
                $required['contact_form_required_website'] = true;
            }
            if (isset($p['contact_form_required_note']) && !empty($p['contact_form_required_note'])) {
                $required['contact_form_required_note'] = true;
            }

            $r = $wpdb->update(
                $this->_table,
                // SET (valeur)
                array(
                    'contact_form_dt_update'                    => current_time('mysql'),

                    'contact_form_setting_smarttag'             => sanitize_text_field($p['contact_form_setting_smarttag']), // string
                    'contact_form_setting_name'                 => sanitize_text_field($p['contact_form_setting_name']),
                    'contact_form_setting_add_what'             => sanitize_key($p['contact_form_setting_add_what']),
                    'contact_form_setting_name_opportunity'     => sanitize_text_field($p['contact_form_setting_name_opportunity']),
                    'contact_form_setting_opportunity_source'   => sanitize_key($contact_form_setting_opportunity_source),
                    'contact_form_setting_opportunity_pipeline' => sanitize_key($contact_form_setting_opportunity_pipeline),
                    'contact_form_setting_opportunity_step'     => sanitize_key($contact_form_setting_opportunity_step),
                    'contact_form_setting_notification_email'   => sanitize_text_field($p['contact_form_setting_notification_email']),
                    'contact_form_setting_notification_email_enable'        => sanitize_key($p['contact_form_setting_notification_email_enable']),
                    'contact_form_setting_notification_email_prefix_enable' => sanitize_key($p['contact_form_setting_notification_email_prefix_enable']),
                    'contact_form_setting_notification_email_prefix_value'  => sanitize_text_field($p['contact_form_setting_notification_email_prefix_value']),

                    'contact_form_setting_deadline'             => sanitize_key($p['contact_form_setting_deadline']),
                    'contact_form_setting_linkedid'             => sanitize_key($p['contact_form_setting_linkedid']),
                    'contact_form_setting_potential'            => sanitize_text_field($p['contact_form_setting_potential']),
                    'contact_form_setting_probability'          => sanitize_text_field($p['contact_form_setting_probability']),

                    'contact_form_company_smarttag'             => sanitize_text_field($p['contact_form_company_smarttag']), // string
                    'contact_form_company_name'                 => sanitize_key($p['contact_form_company_name']),
                    'contact_form_company_siren'                => sanitize_key($p['contact_form_company_siren']),
                    'contact_form_company_siret'                => sanitize_key($p['contact_form_company_siret']),
                    'contact_form_company_rcs'                  => sanitize_key($p['contact_form_company_rcs']),
                    'contact_form_required_company_name'        => $required['contact_form_required_company_name'],
                    'contact_form_required_company_siren'       => $required['contact_form_required_company_siren'],
                    'contact_form_required_company_siret'       => $required['contact_form_required_company_siret'],
                    'contact_form_required_company_rcs'         => $required['contact_form_required_company_rcs'],

                    'contact_form_contact_smarttag'             => sanitize_text_field($p['contact_form_contact_smarttag']), // string
                    'contact_form_contact_civility'             => sanitize_key($p['contact_form_contact_civility']),
                    'contact_form_contact_lastname'             => sanitize_key($p['contact_form_contact_lastname']),
                    'contact_form_contact_firstname'            => sanitize_key($p['contact_form_contact_firstname']),
                    'contact_form_contact_email'                => sanitize_email($p['contact_form_contact_email']),    // email
                    'contact_form_contact_phone_1'              => sanitize_key($p['contact_form_contact_phone_1']),
                    'contact_form_contact_phone_2'              => sanitize_key($p['contact_form_contact_phone_2']),
                    'contact_form_contact_function'             => sanitize_key($p['contact_form_contact_function']),
                    'contact_form_required_contact_civility'    => $required['contact_form_required_contact_civility'],
                    //'contact_form_required_contact_lastname'  => $required['contact_form_required_contact_lastname'],
                    'contact_form_required_contact_firstname'   => $required['contact_form_required_contact_firstname'],
                    //'contact_form_required_contact_email'     => $required['contact_form_required_contact_email'],
                    'contact_form_required_contact_phone_1'     => $required['contact_form_required_contact_phone_1'],
                    'contact_form_required_contact_phone_2'     => $required['contact_form_required_contact_phone_2'],
                    'contact_form_required_contact_function'    => $required['contact_form_required_contact_function'],

                    'contact_form_address_street'               => sanitize_key($p['contact_form_address_street']),
                    'contact_form_address_zip'                  => sanitize_key($p['contact_form_address_zip']),
                    'contact_form_address_town'                 => sanitize_key($p['contact_form_address_town']),
                    'contact_form_address_country'              => sanitize_key($p['contact_form_address_country']),
                    'contact_form_required_address_street'      => $required['contact_form_required_address_street'],
                    'contact_form_required_address_zip'         => $required['contact_form_required_address_zip'],
                    'contact_form_required_address_town'        => $required['contact_form_required_address_town'],
                    'contact_form_required_address_country'     => $required['contact_form_required_address_country'],

                    'contact_form_website'                      => sanitize_key($p['contact_form_website']),
                    'contact_form_note'                         => sanitize_key($p['contact_form_note']),
                    'contact_form_required_website'             => $required['contact_form_required_website'],
                    'contact_form_required_note'                => $required['contact_form_required_note'],

                    'contact_form_condition_accept'             => sanitize_key($p['contact_form_condition_accept']),
                    'contact_form_marketing'                    => $marketing, // json
                    'contact_form_redirectionurl'               => esc_url($p['contact_form_redirectionurl']), // url
                    'contact_form_status'                       => sanitize_key($p['form_status']),
                    'contact_form_status_clearbit'              => sanitize_key($p['form_status_clearbit']),

                    'contact_form_custom_fields_quantity'       => sanitize_key($contact_form_custom_fields_quantity),
                    'contact_form_custom_fields_value'          => $contact_form_custom_fields_value_json,  // json

                    'contact_form_wording'                      => $wording_json,   // json
                ),
                // WHERE (valeur)
                array(
                    'contact_form_id'                           => sanitize_key($p['form_id'])
                ),
                // SET (type)
                array(
                    '%s',

                    '%s',
                    '%s',
                    '%d',
                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%s',
                    '%d',
                    '%d',
                    '%s',

                    '%d',
                    '%d',
                    '%f',
                    '%d',

                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',

                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',

                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',

                    '%d',
                    '%d',
                    '%d',
                    '%d',

                    '%d',
                    '%s',
                    '%s',
                    '%d',
                    '%d',

                    '%d',
                    '%s',

                    '%s'
                ),
                // WHERE (type)
                array(
                    '%d'
                )
            );
            return $r;
        }

        /**
         * update value prefix (for notification)
         * @param $_POST $p
         */
        public function incrementNotifEmailPrefix($idContactForm, $newPrefixNb)
        {
            global $wpdb;

            // INIT
            $idContactForm = (int)$idContactForm;

            $r = $wpdb->update(
                $this->_table,
                // SET (valeur)
                array(
                    'contact_form_setting_notification_email_prefix_nb'  => sanitize_key($newPrefixNb)
                ),
                // WHERE (valeur)
                array(
                    'contact_form_id' => sanitize_key($idContactForm)
                ),
                // SET (type)
                array(
                    '%d'
                ),
                // WHERE (type)
                array(
                    '%d'
                )
            );
            return $r;
        }
    }
}
