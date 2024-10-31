<?php

namespace com\sellsy\sellsy\controllers;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class InstallController
{
    /**
     * install
     */
    public function install()
    {
        global $wpdb;

        $sql    = "";
        $data   = "";

        //add_option( "sellsy_version", SELLSY_VERSION );

        $charset_collate = $wpdb->get_charset_collate();

        // BO - VERSION
        $sql .= "CREATE TABLE ".SELLSY_PREFIXE_BDD."version (
            version_id int(11) NOT NULL AUTO_INCREMENT,
            version_value varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            PRIMARY KEY  (version_id)
        ) $charset_collate;
        ";
        
        // BO - SETTING
        $sql .= "CREATE TABLE ".SELLSY_PREFIXE_BDD."setting (
            setting_id int(11) NOT NULL AUTO_INCREMENT,
            setting_dt_create datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            setting_dt_update datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            setting_consumer_token varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            setting_consumer_secret varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            setting_utilisateur_token varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            setting_utilisateur_secret varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            setting_clearbit_token varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            setting_tracking tinyint(1) NOT NULL,
            setting_recaptcha_key_status tinyint(1) NOT NULL,
            setting_recaptcha_key_version ENUM('2','3') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '2',
            setting_recaptcha_key_website varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            setting_recaptcha_key_secret varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            PRIMARY KEY  (setting_id)
        ) $charset_collate;
        ";

        // FO - TICKET : stock data form ticket
        $sql .= "CREATE TABLE ".SELLSY_PREFIXE_BDD."ticket (
            ticket_id int(11) NOT NULL AUTO_INCREMENT,
            ticket_dt_create datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            ticket_email varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            ticket_name varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            ticket_subject varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            ticket_message text NOT NULL,
            ticket_form_linkedid int(11) NOT NULL,
            PRIMARY KEY  (ticket_id)
        ) $charset_collate;
        ";

        // BO - TICKET : form
        $sql .= "CREATE TABLE ".SELLSY_PREFIXE_BDD."ticket_form (
            ticket_form_id int(11) NOT NULL AUTO_INCREMENT,
            ticket_form_dt_create datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            ticket_form_dt_update datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            ticket_form_name varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            ticket_form_subject_prefix varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            ticket_form_linkedid int(11) NOT NULL,
            ticket_form_status tinyint(1) NOT NULL,
            PRIMARY KEY  (ticket_form_id)
        ) $charset_collate;
            ";

        // FO - CONTACT : stock data form contact
        $sql .= "CREATE TABLE ".SELLSY_PREFIXE_BDD."contact (
            contact_id int(11) NOT NULL AUTO_INCREMENT,
            contact_dt_create datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            contact_log text NOT NULL,
            contact_linkedid int(11) NOT NULL,
            PRIMARY KEY  (contact_id)
        ) $charset_collate;
        ";

        // BO - CONTACT : form
        $sql .= "CREATE TABLE ".SELLSY_PREFIXE_BDD."contact_form (
            contact_form_id int(11) NOT NULL AUTO_INCREMENT,
            contact_form_dt_create datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            contact_form_dt_update datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            
            contact_form_setting_smarttag varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            contact_form_setting_name varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            contact_form_setting_add_what int(11) NOT NULL,
            contact_form_setting_name_opportunity varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            contact_form_setting_opportunity_source int(11) NOT NULL,
            contact_form_setting_opportunity_pipeline int(11) NOT NULL,
            contact_form_setting_opportunity_step int(11) NOT NULL,
            contact_form_setting_notification_email varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            contact_form_setting_notification_email_enable tinyint(1) NOT NULL,
            contact_form_setting_notification_email_prefix_enable tinyint(1) NOT NULL,
            contact_form_setting_notification_email_prefix_nb int(11) NOT NULL DEFAULT '1',
            contact_form_setting_notification_email_prefix_value varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            contact_form_setting_deadline int(11) NOT NULL DEFAULT '".SELLSY_DEADLINE."',
            contact_form_setting_potential float NOT NULL,
            contact_form_setting_probability int(11) NOT NULL DEFAULT '".SELLSY_PROBABILITY."',              
            contact_form_setting_linkedid int(11) NOT NULL,
            
            contact_form_company_smarttag varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            contact_form_company_name int(11) NOT NULL,
            contact_form_company_siren int(11) NOT NULL,
            contact_form_company_siret int(11) NOT NULL,
            contact_form_company_rcs int(11) NOT NULL,
            
            contact_form_contact_smarttag varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            contact_form_contact_civility int(11) NOT NULL,
            contact_form_contact_lastname int(11) NOT NULL,
            contact_form_contact_firstname int(11) NOT NULL,
            contact_form_contact_email int(11) NOT NULL,
            contact_form_contact_phone_1 int(11) NOT NULL,
            contact_form_contact_phone_2 int(11) NOT NULL,
            contact_form_contact_function int(11) NOT NULL,
                        
            contact_form_address_street int(11) NOT NULL DEFAULT '1',
            contact_form_address_zip int(11) NOT NULL DEFAULT '1',
            contact_form_address_town int(11) NOT NULL DEFAULT '1',
            contact_form_address_country int(11) NOT NULL DEFAULT '1',
            
            contact_form_website int(11) NOT NULL,
            contact_form_note int(11) NOT NULL,

            contact_form_required_company_name int(11) NOT NULL DEFAULT '0',
            contact_form_required_company_siren int(11) NOT NULL DEFAULT '0',
            contact_form_required_company_siret int(11) NOT NULL DEFAULT '0',
            contact_form_required_company_rcs int(11) NOT NULL DEFAULT '0',
            
            contact_form_required_contact_civility int(11) NOT NULL DEFAULT '0',
            contact_form_required_contact_lastname int(11) NOT NULL DEFAULT '1',
            contact_form_required_contact_firstname int(11) NOT NULL DEFAULT '0',
            contact_form_required_contact_email int(11) NOT NULL DEFAULT '1',
            contact_form_required_contact_phone_1 int(11) NOT NULL DEFAULT '0',
            contact_form_required_contact_phone_2 int(11) NOT NULL DEFAULT '0',
            contact_form_required_contact_function int(11) NOT NULL DEFAULT '0',
            
            contact_form_required_address_street int(11) NOT NULL DEFAULT '0',
            contact_form_required_address_zip int(11) NOT NULL DEFAULT '0',
            contact_form_required_address_town int(11) NOT NULL DEFAULT '0',
            contact_form_required_address_country int(11) NOT NULL DEFAULT '0',
            
            contact_form_required_website int(11) NOT NULL DEFAULT '0',
            contact_form_required_note int(11) NOT NULL DEFAULT '0',

            contact_form_condition_accept int(11) NOT NULL DEFAULT '1',
            contact_form_marketing varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            contact_form_redirectionurl varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,

            contact_form_status tinyint(1) NOT NULL,
            contact_form_status_clearbit tinyint(1) NOT NULL,

            contact_form_custom_fields_quantity int(11) NOT NULL,
            contact_form_custom_fields_value text NOT NULL,
            contact_form_wording text NOT NULL,
            PRIMARY KEY  (contact_form_id)
        ) $charset_collate;
        ";

        // FO - TICKET : error form
        $sql .= "CREATE TABLE ".SELLSY_PREFIXE_BDD."error (
            error_id int(11) NOT NULL AUTO_INCREMENT,
            error_dt_create datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            error_categ enum('ticket','contact','opportunities') NOT NULL,
            error_status varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            error_code varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            error_message varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            error_more varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            error_inerro varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            PRIMARY KEY  (error_id)
        ) $charset_collate;
        ";

        $data .= "INSERT INTO ".SELLSY_PREFIXE_BDD."version VALUES (1, '".SELLSY_VERSION."');
        ";

        $data .= "INSERT INTO ".SELLSY_PREFIXE_BDD."setting VALUES (1, '".current_time('mysql')."', '".current_time('mysql')."', '', '', '', '', '', '1', '1', '2', '', '');
        ";

        $data .= "INSERT INTO ".SELLSY_PREFIXE_BDD."ticket_form VALUES (1, '".current_time('mysql')."', '".current_time('mysql')."', 'Ticket support', '[TICKET SUPPORT]', '0', '0');
        ";

        $i = 1;
        $data .= "INSERT INTO " . SELLSY_PREFIXE_BDD . "contact_form VALUES (
        ".$i.",
        '" . current_time('mysql') . "',
        '" . current_time('mysql') . "',
         
        '',
        'Contact ".$i."',
        '0',
        'Website form ".$i."',
        '0',
        '0',
        '0',
        '" . get_bloginfo('admin_email') . "',
        '0',
        '0',
        '1',
        '0',
        ".SELLSY_DEADLINE.",
        0,
        ".SELLSY_PROBABILITY.",
        '0',
        
        '',
        '1',
        '1',
        '1',
        '1',
        
        '',
        '1',
        '0',
        '0',
        '0',
        '1',
        '1',
        '1',
         
        '1',
        '1',
        '1',
        '1',
        
        '0',
        '0',
        
        '0',
        '0',
        '0',
        '0',
        
        '0',
        '1',
        '0',
        '1',
        '0',
        '0',
        '0',
        
        '0',
        '0',
        '0',
        '0',
        
        '0',
        '0',
        
        '1',
        '',
        '',
         
        '0',
        '0',
         
        '0',
        '',
        ''
        );
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        dbDelta( $data );
    }

    /**
     * delete
     * @deprecated use uninstall.php
     */
    public function delete()
    {
//        $sql = "
//        DROP TABLE  ".SELLSY_PREFIXE_BDD."version
//        DROP TABLE  ".SELLSY_PREFIXE_BDD."setting
//        DROP TABLE  ".SELLSY_PREFIXE_BDD."ticket
//        DROP TABLE  ".SELLSY_PREFIXE_BDD."ticket_form
//        DROP TABLE  ".SELLSY_PREFIXE_BDD."contact
//        DROP TABLE  ".SELLSY_PREFIXE_BDD."contact_form
//        DROP TABLE  ".SELLSY_PREFIXE_BDD."error
//        ";
//        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//        dbDelta( $sql );

        //-----------
//
//        global $wpdb;
//        $sql[] = "DROP TABLE  `".SELLSY_PREFIXE_BDD."version`";
//        $sql[] = "DROP TABLE  `".SELLSY_PREFIXE_BDD."setting`";
//        $sql[] = "DROP TABLE  `".SELLSY_PREFIXE_BDD."ticket`";
//        $sql[] = "DROP TABLE  `".SELLSY_PREFIXE_BDD."ticket_form`";
//        $sql[] = "DROP TABLE  `".SELLSY_PREFIXE_BDD."contact`";
//        $sql[] = "DROP TABLE  `".SELLSY_PREFIXE_BDD."contact_form`";
//        $sql[] = "DROP TABLE  `".SELLSY_PREFIXE_BDD."error`";
//        if (isset($sql) && !empty($sql)) {
//            foreach ($sql as $v) {
//                $wpdb->query($v);
//            }
//        }
    }
}//class
