<?php
namespace com\sellsy\sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TTicket')){
    class TTicket extends \WP_Query {

        private $_table;

        function __construct()
        {
            $this->_table = SELLSY_PREFIXE_BDD."ticket";
        }

        /**
         * retourn rows
         */
        /*
        public function getTickets( $req="", $params="" )
        {
            global $wpdb;
            $sql    = $wpdb->prepare("SELECT * FROM ".$this->_table." ".$req, $params);
            $r      = $wpdb->get_results($sql);
            return $r;
        }
        */

        /**
         * retourn row
         * @param int $id
         */
        /*
        public function getTicket( $id )
        {
            global $wpdb;
            $sql    = $wpdb->prepare("SELECT * FROM ".$this->_table." WHERE ticket_id=%d", $id);
            $r      = $wpdb->get_results($sql);
            return $r;
        }
        */

        /**
         * insert
         * @param $_POST $p
         */
        public function add( $p )
        {
            global $wpdb;
            $r = $wpdb->insert(
                $this->_table,
                array(
                    'ticket_dt_create'     => current_time('mysql'),
                    'ticket_email'         => sanitize_email($p['form_ticket_email']),
                    'ticket_name'          => sanitize_text_field($p['form_ticket_name']),
                    'ticket_subject'       => sanitize_text_field($p['form_ticket_subject']),
                    'ticket_message'       => sanitize_textarea_field($p['form_ticket_message']),
                    'ticket_form_linkedid' => sanitize_key($p['form_ticket_linkedid'])
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d'
                )
            );
            return $r;
        }

    }
}