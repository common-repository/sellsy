<?php
namespace com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyAccountDatas')){
    class TSellsyAccountDatas extends \WP_Query
    {
        public function __construct() {}

        /**
         * Get unit
         * @params array $d id
         * @return rows
         */
        public function getUnit($d = array())
        {
            // INIT
            $params = $d;

            $request = array(
                'method' => 'Accountdatas.getUnit',
                'params' => $params
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'    => 'accountdatas',
                    'response' => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get units
         * @return rows
         */
        public function getUnits()
        {
            $request = array(
                'method' => 'Accountdatas.getUnits',
                'params' => array()
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'    => 'accountdatas',
                    'response' => $response,
                ));
                return false;
            }
            return $response;
        }
    }
}
