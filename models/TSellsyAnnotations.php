<?php
namespace com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyAnnotations')){
    class TSellsyAnnotations extends \WP_Query {
        function __construct() {}

        /**
         * Create annotations (=comment)
         * @params array $d parentid, relatedtype, relatedid, text, date.
         * @return rows
         */
        public function create($d = array())
        {
            // INIT
            $params = $d;

            $request = array(
                'method' => 'Annotations.create',
                'params' => array(
                    'annotation' => $params
                )
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'     => 'annotations',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

    }
}
