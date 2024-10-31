<?php
namespace com\sellsy\sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TClearbit')){
    class TClearbit extends \WP_Query {

        private $clearbit_token;

        function __construct()
        {
            $t_setting = new TSetting();
            $setting = $t_setting->getSetting(1);
            $this->clearbit_token = $setting[0]->setting_clearbit_token;
        }

        /**
         * ENRICHMENT API
         * @param array d (email, ip)
         * retourn json
         */
        public function getEnrichment( $d )
        {
            // INIT
            if (isset($d['email']) && !empty($d['email'])) {
                $data[] = "?email=".$d['email'];
            } else {
                return false;
            }

            if (isset($d['ip']) && !empty($d['ip'])) {
                $data[] = "ip_address=".$d['ip'];
            }

            $url = implode("&", $data);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL            => "https://person-stream.clearbit.com/v2/combined/find".$url,
                CURLOPT_USERPWD        => $this->clearbit_token,
            ));

            $r = curl_exec($curl);
            if(!$r){
                $t_error = new TError();
                $t_error->add(array(
                    'categ'    => 'clearbit',
                    'response' => 'Error: "' . curl_error($curl) . '" ---- Code: ' . curl_errno($curl),
                ));
                //die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            }
            curl_close($curl);

            return $r;
        }

        /**
         * REVEAL API
         * @param int $ip
         * retourn json
         */
        public function getRevealByIp( $ip )
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL            => "https://reveal.clearbit.com/v1/companies/find?ip=".$ip,
                CURLOPT_USERPWD        => $this->clearbit_token,
            ));

            $r = curl_exec($curl);
            if(!$r){
                $t_error = new TError();
                $t_error->add(array(
                    'categ'    => 'clearbit',
                    'response' => 'Error: "' . curl_error($curl) . '" ---- Code: ' . curl_errno($curl),
                ));
                //die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            }
            curl_close($curl);

            return $r;
        }

        /**
         * PROSPECTOR API
         * @param array $d (domain, role)
         * retourn json
         */
        public function getProspector( $d )
        {
            // INIT
            $domain = "";
            $role   = "";
            if (isset($d['domain']) && !empty($d['domain'])) {
                $domain = $d['domain'];
            }
            if (isset($d['role']) && !empty($d['role'])) {
                $role = $d['role'];
            }
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL            => "https://prospector.clearbit.com/v1/people/search?domain=".$domain."&role=".$role,
                CURLOPT_USERPWD        => $this->clearbit_token,
            ));

            $r = curl_exec($curl);
            if(!$r){
                $t_error = new TError();
                $t_error->add(array(
                    'categ'    => 'clearbit',
                    'response' => 'Error: "' . curl_error($curl) . '" ---- Code: ' . curl_errno($curl),
                ));
                //die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            }
            curl_close($curl);

            return $r;
        }

    }
}