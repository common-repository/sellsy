<?php
namespace com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyPeoples')) {
    class TSellsyPeoples extends \WP_Query {
        function __construct() {}

        /**
         * Get peoples
         * @params array $d
         * @return rows
         */
        public function getList($d = array())
        {
            // INIT
            $params = $d;

            $request = array(
                'method' => 'Peoples.getList',
                'params' => $params
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error = new TError();
                $t_error->add(array(
                    'categ'     => 'people',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get people
         * @params array $d id
         * @return rows
         */
        /*
        public function getOne($d = array())
        {
            // INIT
            $id = (int)$d['id'];

            $request = array(
                'method' => 'Peoples.getOne',
                'params' => array(
                    'id' => $id
                )
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'     => 'people',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }
        */

        /**
         * Create people
         * @params array $d
         * @return rows
         */
        public function create($d = array())
        {
            // Tag : array
            if (!is_array($d['tags'])) {
                $d['tags'] = explode(',', $d['tags']);
            }

            $request = array(
                'method' => 'Peoples.create',
                'params' => array(
                    'people' => $d
                )
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'     => 'people',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Update people
         * @params array $d
         * @return rows
         */
        public function update($d = array())
        {
            // INIT
            $id = (int)$d['id'];
            unset($d['id']);
            // Tag : array
            if (isset($d['tags']) && !is_array($d['tags'])) {
                $d['tags'] = explode(',', $d['tags']);
            }

            $request = array(
                'method' => 'Peoples.update',
                'params' => array(
                    'id'     => $id,
                    'people' => $d
                )
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if (is_null($response)) {
                return false;
            }

            if ($response->error) {
                $t_error = new TError();
                $t_error->add(array(
                    'categ'    => 'people',
                    'response' => $response,
                ));
                return false;
            }
            return $response;
        }

    }
}
