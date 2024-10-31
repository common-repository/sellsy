<?php
namespace com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyStaffs')){
    class TSellsyStaffs extends \WP_Query {
        function __construct() {}
    
        /**
         * Staffs : Get list
         * retourn rows array('linkedid' => 'forename name', ...)
         */
        public function getStaffsList()
        {
            // INIT
            $d = array();

            // GET LIST
            $request = array(
                'method' => 'Staffs.getList',
                'params' => array(
                    'search' => array(
                        'withBlocked' => 'N',
                    )
                )
            );
            $response = libs\sellsyConnect_curl::load()->requestApi($request);
            if (isset($response->response->result) && !empty($response->response->result)) {
                foreach ($response->response->result as $resultStaff) {
	                $d[$resultStaff->linkedid] = ucfirst(strtolower($resultStaff->forename)) . ' ' . strtoupper($resultStaff->name);
                }
            }
            if (empty($d)) { return false; }
            return $d;
        }

        /**
         * Get staff : 100 first "staff"
         * @params array
         * @return rows|false
         */
        public function getList100FirstStaff()
        {
            // INIT
            $params = [
                'pagination' => [
                    'pagenum'   => 1,
                    'nbperpage' => 100,
                ],
                'search' => [
                    'withBlocked' => 'N',
                ],
            ];

            $request = array(
                'method' => 'Staffs.getList',
                'params' => $params
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error = new TError();
                $t_error->add(array(
                    'categ'     => 'staff',
                    'response'  => $response,
                ));
                return false;
            }

            return $response;
        }

    }
}
