<?php
namespace com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyClients')){
    class TSellsyClients extends \WP_Query {
        function __construct() {}

        /**
         * Get clients
         * @params array $d id, ...
         * @return rows
         */
        public function getList($d = array())
        {
            // INIT
            $params = $d;

            $request = array(
                'method' => 'Client.getList',
                'params' => $params
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'     => 'client',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get client
         * @params array $d id
         * @return rows
         */
        public function getOne($d = array())
        {
            // INIT
            $id = (int)$d['id'];

            $request = array(
                'method' => 'Client.getOne',
                'params' => array(
                    'clientid' => $id
                )
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'     => 'client',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get id linked people/client
         * @param $d id (=thirdid), peolpeid
         * @return id link client/contact | false if contact not find
         */
        public function getIsLinkedPeopleClient($d) {
            $clientGetOne = $this->getOne($d);

            $peopleId = (int)$d['peopleid'];

            foreach($clientGetOne->response->contacts as $contact) {
                if ($contact->peopleid == $peopleId) {
                    return $contact->id;
                }
            }

            return false;
        }

    }
}
