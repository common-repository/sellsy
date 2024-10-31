<?php

namespace com\sellsy\sellsy\helpers;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('DbUpdate')) {
    class DbUpdate
    {
        /**
         * Get data version
         * @return float
         */
        public function getVersion()
        {
            global $wpdb;

            // Table not in database. Create new table
            $table_name = SELLSY_PREFIXE_BDD."version";
            if ($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
                return false;

            // Table in db
            } else {
                $result = $wpdb->get_results('SELECT * FROM '.SELLSY_PREFIXE_BDD.'version WHERE version_id=1', OBJECT);
                return $result[0]->version_value;
            }
        }

        /**
         * DB UPDATE
         * use during : update plugin AND refresh BO.
         * @return current version updated
         */
        public function updateDb($dbVersion)
        {
            global $wpdb;
            //add_option('sellsy_version', SELLSY_VERSION);

            if (version_compare($dbVersion, '1.0', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.0";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '1.1', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.1";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '1.2', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.2";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '1.3', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.3";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '1.4', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.4";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '1.5', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.5";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '1.6', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.6";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '1.7', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.7";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '1.8', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.8";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '1.9', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.9";
                $this->updateVersion($dbVersion);

                // db update
                $table_name = SELLSY_PREFIXE_BDD . 'version';
                $sql = "ALTER TABLE `".$table_name."` CHANGE `version_value` `version_value` VARCHAR(255) NOT NULL;";
                $wpdb->query($sql);
            }
            if (version_compare($dbVersion, '1.9.1', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.9.1";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '1.9.2', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.9.2";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '1.9.3', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="1.9.3";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.0.0', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.0.0";
                $this->updateVersion($dbVersion);

                // db update
                $table_name = SELLSY_PREFIXE_BDD . 'setting';
                $sql = "ALTER TABLE `".$table_name."` ADD `setting_clearbit_token` VARCHAR(255) NOT NULL AFTER `setting_utilisateur_secret`;";
                $wpdb->query($sql);

                // db update
                $table_name = SELLSY_PREFIXE_BDD . 'contact_form';
                $sql = "ALTER TABLE `".$table_name."` ADD `contact_form_status_clearbit` BOOLEAN NOT NULL AFTER `contact_form_status`;";
                $wpdb->query($sql);
            }
            if (version_compare($dbVersion, '2.1.0', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.0";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.1', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.1";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.2', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.2";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.3', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.3";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.4', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.4";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.5', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.5";
                $this->updateVersion($dbVersion);

                // db update
                $table_name = SELLSY_PREFIXE_BDD . 'contact_form';
                $sql = "ALTER TABLE `".$table_name."` ADD `contact_form_setting_potential` FLOAT NOT NULL AFTER `contact_form_setting_deadline`;";
                $wpdb->query($sql);
            }
            if (version_compare($dbVersion, '2.1.6', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.6";
                $this->updateVersion($dbVersion);

                // db update
                $table_name = SELLSY_PREFIXE_BDD . 'contact_form';
                $sql  = "
                ALTER TABLE `".$table_name."` 
                ADD `contact_form_address_street` int(11) NOT NULL DEFAULT '1' AFTER `contact_form_contact_function`,
                ADD `contact_form_address_zip` int(11) NOT NULL DEFAULT '1' AFTER `contact_form_address_street`,
                ADD `contact_form_address_town` int(11) NOT NULL DEFAULT '1' AFTER `contact_form_address_zip`,
                ADD `contact_form_address_country` int(11) NOT NULL DEFAULT '1' AFTER `contact_form_address_town`;
                ";
                $wpdb->query($sql);
            }
            if (version_compare($dbVersion, '2.1.7', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.7";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.8', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.8";
                $this->updateVersion($dbVersion);

                // db update
                $table_name = SELLSY_PREFIXE_BDD . 'contact_form';
                $sql  = "
                ALTER TABLE `".$table_name."` ADD `contact_form_condition_accept` INT(11) NOT NULL DEFAULT '1' AFTER `contact_form_note`;";
                $wpdb->query($sql);
            }
            if (version_compare($dbVersion, '2.1.9', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.9";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.10', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.10";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.11', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.11";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.12', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.12";
                $this->updateVersion($dbVersion);

                // db update
                $table_name = SELLSY_PREFIXE_BDD . 'contact_form';
                $sql  = "
                ALTER TABLE `".$table_name."` 
                ADD `contact_form_required_company_name` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_note`,
                ADD `contact_form_required_company_siren` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_company_name`,
                ADD `contact_form_required_company_siret` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_company_siren`,
                ADD `contact_form_required_company_rcs` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_company_siret`,
                ADD `contact_form_required_contact_civility` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_company_rcs`,
                ADD `contact_form_required_contact_lastname` INT(11) NOT NULL DEFAULT '1' AFTER `contact_form_required_contact_civility`,
                ADD `contact_form_required_contact_firstname` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_contact_lastname`,
                ADD `contact_form_required_contact_email` INT(11) NOT NULL DEFAULT '1' AFTER `contact_form_required_contact_firstname`,
                ADD `contact_form_required_contact_phone_1` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_contact_email`,
                ADD `contact_form_required_contact_phone_2` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_contact_phone_1`,
                ADD `contact_form_required_contact_function` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_contact_phone_2`,
                ADD `contact_form_required_address_street` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_contact_function`,
                ADD `contact_form_required_address_zip` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_address_street`,
                ADD `contact_form_required_address_town` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_address_zip`,
                ADD `contact_form_required_address_country` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_address_town`,
                ADD `contact_form_required_website` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_address_country`,
                ADD `contact_form_required_note` INT(11) NOT NULL DEFAULT '0' AFTER `contact_form_required_website`;
                ";
                $wpdb->query($sql);
            }
            if (version_compare($dbVersion, '2.1.13', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.13";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.14', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.14";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.15', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.15";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.16', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.16";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.17', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.17";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.18', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.18";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.19', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.19";
                $this->updateVersion($dbVersion);

                // db update
                $table_name = SELLSY_PREFIXE_BDD . 'contact_form';
                $sql  = "
                ALTER TABLE `".$table_name."` 
                ADD `contact_form_setting_notification_email_enable` BOOLEAN NOT NULL AFTER `contact_form_setting_notification_email`, 
                ADD `contact_form_setting_notification_email_prefix_enable` BOOLEAN NOT NULL AFTER `contact_form_setting_notification_email_enable`,
                ADD `contact_form_setting_notification_email_prefix_nb` INT(11) NOT NULL DEFAULT '1' AFTER `contact_form_setting_notification_email_prefix_enable`,
                ADD `contact_form_setting_notification_email_prefix_value` VARCHAR(255) NOT NULL AFTER `contact_form_setting_notification_email_prefix_nb`;
                ";

                $wpdb->query($sql);
            }
            if (version_compare($dbVersion, '2.1.20', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.20";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.21', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.21";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.22', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.22";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.23', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.23";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.24', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.24";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.25', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.25";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.26', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.26";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.27', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.27";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.28', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.28";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.29', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.29";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.30', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.30";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.31', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.31";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.1.32', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.1.32";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.2', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.2";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.2.1', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.2.1";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.2.2', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.2.2";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.2.3', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.2.3";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.2.4', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.2.4";
                $this->updateVersion($dbVersion);

                // db update
                $table_name = SELLSY_PREFIXE_BDD . 'setting';
                $sql  = "
                ALTER TABLE `".$table_name."`
                ADD `setting_recaptcha_key_version` ENUM('2','3') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '2' AFTER `setting_recaptcha_key_status`;
                ";

                $wpdb->query($sql);
            }
            if (version_compare($dbVersion, '2.2.5', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.2.5";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.2.6', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.2.6";
                $this->updateVersion($dbVersion);
            }
            if (version_compare($dbVersion, '2.2.7', '<')) {
                if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
                $dbVersion="2.2.7";
                $this->updateVersion($dbVersion);
            }
	        if (version_compare($dbVersion, '2.2.8', '<')) {
		        if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
		        $dbVersion="2.2.8";
		        $this->updateVersion($dbVersion);
	        }
	        if (version_compare($dbVersion, '2.2.9', '<')) {
		        if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
		        $dbVersion="2.2.9";
		        $this->updateVersion($dbVersion);
	        }
	        if (version_compare($dbVersion, '2.3.0', '<')) {
		        if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
		        $dbVersion="2.3.0";
		        $this->updateVersion($dbVersion);
	        }
			if (version_compare($dbVersion, '2.3.1', '<')) {
		        if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
		        $dbVersion="2.3.1";
		        $this->updateVersion($dbVersion);
	        }
	        if (version_compare($dbVersion, '2.3.2', '<')) {
		        if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
		        $dbVersion="2.3.2";
		        $this->updateVersion($dbVersion);
	        }
	        if (version_compare($dbVersion, '2.3.3', '<')) {
		        if ($dbVersion==SELLSY_VERSION) { return SELLSY_VERSION; }
		        $dbVersion="2.3.3";
		        $this->updateVersion($dbVersion);
	        }
        }

        /**
         * Update version
         * @param $version
         */
        public function updateVersion($version)
        {
            global $wpdb;
            $wpdb->update(
                SELLSY_PREFIXE_BDD."version",
                array("version_value"=>$version),
                array("version_id"=>1),
                array("%s"),
                array("%d")
            );
        }

    }//fin class
}//fin if
