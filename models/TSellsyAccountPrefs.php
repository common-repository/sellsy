<?php
namespace com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyAccountPrefs')){
    class TSellsyAccountPrefs extends \WP_Query
    {
        public function __construct() {}

        /**
         * Get currencies
         * @return rows
         */
        public function getCurrencies()
        {
            $request = array(
                'method' => 'AccountPrefs.getCurrencies',
                'params' => array()
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'    => 'accountprefs',
                    'response' => $response,
                ));
                return false;
            }
            return $response;
        }
    }
}
