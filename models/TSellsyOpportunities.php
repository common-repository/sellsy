<?php
namespace com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyOpportunities')){
    class TSellsyOpportunities extends \WP_Query {
        function __construct() {}

        /**
         * Get funnel pipeline
         * @params array $d id
         * @retourn rows
         */
        public function getFunnels($d = array())
        {
            // INIT
            if (isset($d['id'])) {
                $id = (int)$d['id'];
            }

            $request = array(
                'method' => 'Opportunities.getFunnels',
                'params' => array()
            );
            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if ($response && isset($id) && $id>0) {
                foreach ($response->response as $v) {
                    if ($v->id == $id) {
                        return $v->name;
                    }
                }
            }

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'    => 'opportunities',
                    'response' => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get Steps pipeline
         * @param array $d
         * @return label step|$response|false
         */
        public function getStepsForFunnel($d = array())
        {
            // INIT
            $idPipeline = (int)$d['idPipeline'];
            if (isset($d['idStep'])) {
                $idStep = (int)$d['idStep'];
            }

            if (isset($idPipeline) && !empty($idPipeline)) {
                $request = array(
                    'method' => 'Opportunities.getStepsForFunnel',
                    'params' => array(
                        'funnelid' => $idPipeline
                    )
                );
                $response = libs\sellsyconnect_curl::load()->requestApi($request);

                // return label
                if ($response && isset($idStep) && $idStep>0) {
                    foreach ($response as $vSteps) {
                        foreach ($vSteps as $vStep) {
                            return $vStep->label;
                        }
                    }
                }

                if (is_null($response)) { return false; }

                if ($response->error) {
                    $t_error    = new TError();
                    $t_error->add(array(
                        'categ'     => 'opportunities',
                        'response'  => $response,
                    ));
                    return false;
                }
                return $response;
            }
            return false;
        }

        /**
         * Current opp
         * @param array $d
         * @return false|$response
         */
        public function getCurrentIdent($d=array())
        {
            $request = array(
                'method' => 'Opportunities.getCurrentIdent',
                'params' => array()
            );
            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if (is_null($response)) { return false; }
            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'     => 'opportunities',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Create opportunities
         * @param array $d
         * @return bool|array
         */
        public function create($d = array())
        {
            // INIT
            $linkedtype         = sanitize_key($d['linkedtype']);
            $linkedid           = sanitize_key($d['linkedid']);
            $sourceid           = sanitize_key($d['sourceid']);
            $name               = sanitize_text_field($d['name']);
            $funnelid           = sanitize_key($d['funnelid']);
            $stepid             = sanitize_key($d['stepid']);
            $deadline           = sanitize_key($d['deadline']);
            $potential          = sanitize_text_field($d['potential']);
            $probability        = sanitize_key($d['probability']);
            $stickyNote         = (string)$d['stickyNote'];
            $currentIdent       = $this->getCurrentIdent();
            $staffId            = sanitize_key($d['staffId']);
            //$tags             = $d['contact_form_opportunity_smarttag'];
            $tags               = sanitize_text_field($d['tags']);
            $contactid          = sanitize_text_field($d['api_contact']['id']); // string
            if (isset($d['api_contact']['idLinkedPeopleThird']) && !empty($d['api_contact']['idLinkedPeopleThird'])) {
                $contactid      = sanitize_text_field($d['api_contact']['idLinkedPeopleThird']); // string
            }

            //THIRD
            $thirdName     = ucfirst(strtolower($d['api_third']['name']));

            // CONTACT
            $contactName = ucfirst(strtolower($d['api_contact']['forename'])).' '.strtoupper($d['api_contact']['name']);

            // Prospect
            if (isset($contactName) && !empty($contactName)) {
                $name .= ' - '.$contactName;
            // Company
            } elseif (isset($thirdName) && !empty($thirdName)) {
                $name .= ' - ' . $thirdName;
            }

            if ($probability > 100) {
                $probability = 100;
            }

            $tbl_opp = array(
                'linkedtype'=> $linkedtype,             // @todo : BO custom
                'linkedid'  => $linkedid,
                'ident'     => $currentIdent->response,
                'sourceid'  => $sourceid,
                'dueDate'   => strtotime('+'.$deadline.' day'),
                //'creationDate' => {{creationDate}},
                'name'      => $name,                   // @todo : BO custom
                'funnelid'  => $funnelid,
                'stepid'    => $stepid,
                'potential' => $potential,
                'proba'     => $probability,
                'brief'     => nl2br($stickyNote),      // @todo : BO custom
                //'stickyNote'=> $stickyNote,
                'tags'      => $tags,
                //'staffs'  => array($staffId),
                'contacts'  => $contactid               // get with : Prospects.getOne or Client.getOne
            );

            if (isset($staffId) && !empty($staffId)) {
                $tbl_opp['staffs'] = array($staffId);
            }

            $request = array(
                'method' => 'Opportunities.create',
                'params' => array(
                    'opportunity' => $tbl_opp
                )
            );
            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) {
                return false;
            }
            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'     => 'opportunities',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get sources
         * @param array $d
         * @return false or $response
         */
        public function getSources($d = array())
        {
            $request = array(
                'method' => 'Opportunities.getSources',
                'params' => array()
            );
            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if (is_null($response)) {
                return false;
            }
            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'    => 'opportunities',
                    'response' => $response,
                ));
                return false;
            }
            return $response;
        }

    }
}
