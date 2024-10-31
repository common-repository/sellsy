<?php

namespace com\sellsy\sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('TError')) {
    class TError extends \WP_Query
    {
        private $_table;

        public function __construct()
        {
            $this->_table = SELLSY_PREFIXE_BDD."error";
        }

        /**
         * retourn rows
         */
        /*
        public function getErrors($req = "", $params = "")
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
        /*  public function getError ($id)
        {
            global $wpdb;
            $sql    = $wpdb->prepare("SELECT * FROM ".$this->_table." WHERE error_id=%d", $id);
            $r      = $wpdb->get_results($sql);
            return $r;
        }
        */

        /**
         * insert
         * @param $_POST $p
         */
        public function add ($p)
        {
            global $wpdb;

            // init
            $code = "";
            $message = "";
            $more = "";
            $inerror = "";
            if (isset($p['response']->error->code))    { $code = $p['response']->error->code; }
            if (isset($p['response']->error->message)) { $message = $p['response']->error->message; }
            if (isset($p['response']->error->more))    { $more = $p['response']->error->more; }
            if (isset($p['response']->error->inerror)) { $inerror = $p['response']->error->inerror; }

            $r = $wpdb->insert(
                $this->_table,
                array(
                    'error_dt_create'    => current_time('mysql'),
                    'error_categ'        => sanitize_text_field($p['categ']),
                    'error_status'       => sanitize_text_field($p['response']->status),
                    'error_code'         => sanitize_text_field($code),
                    'error_message'      => sanitize_text_field($message),
                    'error_more'         => sanitize_text_field($more),
                    'error_inerro'       => sanitize_text_field($inerror)
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                )
            );
            return $r;
        }
    }
}