<?php
namespace com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyCatalogue')){
    class TSellsyCatalogue extends \WP_Query {
        function __construct() {}

        /**
         * Get catalogue
         * @params array $d type (item, service), ...
         * @return rows
         */
        public function getList($d = array())
        {
            // INIT
            $params = $d;

            $request = array(
                'method' => 'Catalogue.getList',
                'params' => $params
            );
            
            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error = new TError();
                $t_error->add(array(
                    'categ'     => 'catalogue',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get catalogue : 100 first "product", if less 100, complet to "service"
         * @params array $d type (item, service), ...
         * @return rows
         */
        public function getList100FirstProductAndService()
        {
            $typeCatalogues = ['item', 'service'];

            foreach ($typeCatalogues as $typeCatalogue) {
                // INIT
                $params[$typeCatalogue] = [
                    'pagination' => [
                        'pagenum'   => 1,
                        'nbperpage' => 100,
                    ],
                    'type' => $typeCatalogue
                ];

                $request[$typeCatalogue] = array(
                    'method' => 'Catalogue.getList',
                    'params' => $params[$typeCatalogue]
                );

                $response[$typeCatalogue] = libs\sellsyconnect_curl::load()->requestApi($request[$typeCatalogue]);

                if (is_null($response[$typeCatalogue])) { return false; }

                if ($response[$typeCatalogue]->error) {
                    $t_error = new TError();
                    $t_error->add(array(
                        'categ'     => 'catalogue',
                        'response'  => $response[$typeCatalogue],
                    ));
                    return false;
                }
            }
            return $response;
        }

    }
}
