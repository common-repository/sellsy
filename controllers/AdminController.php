<?php

namespace com\sellsy\sellsy\controllers;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('AdminController')) {
    class AdminController
    {
        public function init()
        {
        }
        
        /**
         * execute lors de l'activation du plugin
         */
        public function sellsy_activate()
        {
            $i = new InstallController();
            $i->install();
        }

        /**
         * execute lors de la désactivation du plugin
         */
        public function sellsy_deactivate()
        {
        }

        /**
         * execute lors de la désinstallation du plugin
         */
        public function sellsy_uninstall()
        {
            $i = new InstallController();
            $i->delete();
        }
        
        /**
         * Gestion des menus du site
         */
        public function sellsy_adminMenu()
        {
            // menu principale
            add_menu_page("Sellsy", "Sellsy", "manage_options", "idSellsy", array($this, "menu"), plugins_url("sellsy/images/icones/logo.png"));
            
            // sous menu dans le menu principale
            add_submenu_page("idSellsy", __('Support ticket', PLUGIN_NOM_LANG), __('Support ticket', PLUGIN_NOM_LANG), "manage_options", "idSousMenu1", array($this, "sousMenu1"));
            add_submenu_page("idSellsy", __('Contact', PLUGIN_NOM_LANG), __('Contact', PLUGIN_NOM_LANG), "manage_options", "idSousMenu2", array($this, "sousMenu2"));
            add_submenu_page("idSellsy", __('Setting', PLUGIN_NOM_LANG), __('Setting', PLUGIN_NOM_LANG), "manage_options", "idSousMenu3", array($this, "sousMenu3"));

            // appel init
            add_action('admin_init', array($this, 'init'));
        }
        
        /**
         * Page : menu principale
         */
        public function menu()
        {
            include(SELLSY_PLUGIN_DIR.'/view/manuel.php');
        }
        public function sousMenu1()
        {
            include(SELLSY_PLUGIN_DIR.'/view/ticketFormRoot.php');
        }
        public function sousMenu2()
        {
            include(SELLSY_PLUGIN_DIR.'/view/contactRoot.php');
        }
        public function sousMenu3()
        {
            include(SELLSY_PLUGIN_DIR.'/view/settingRoot.php');
        }
    }//fin class
}//fin if
