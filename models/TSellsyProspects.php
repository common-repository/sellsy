<?php
namespace com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyProspects')){
    class TSellsyProspects extends \WP_Query {
        function __construct() {}

        /**
         * Get prospects
         * @params array $d id, ...
         * @return rows
         */
        public function getList($d = array())
        {
            // INIT
            $params = $d;

            $request = array(
                'method' => 'Prospects.getList',
                'params' => $params
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if (isset($response->error) && $response->error) {
                $t_error = new TError();
                $t_error->add(array(
                    'categ'     => 'prospect',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get prospect
         * @params array $d id
         * @return rows
         */
        public function getOne($d = array())
        {
            // INIT
            $id = (int)$d['id'];

            $request = array(
                'method' => 'Prospects.getOne',
                'params' => array(
                    'id' => $id
                )
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if (isset($response->error) && $response->error) {
                $t_error = new TError();
                $t_error->add(array(
                    'categ'     => 'prospect',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get id linked people/prospect
         * @param $d id (=prospectId), peolpeid
         * @return id link prospect/contact | false if contact not find
         */
        public function getIsLinkedPeopleProspect($d) {
            $prospectGetOne = $this->getOne($d);

            $peopleId = (int)$d['peopleid'];

            if (isset($prospectGetOne->response)) {
                foreach($prospectGetOne->response->contacts as $contact) {
                    if ($contact->peopleid == $peopleId) {
                        return $contact->id;
                    }
                }
            }

            return false;
        }

    }
}
