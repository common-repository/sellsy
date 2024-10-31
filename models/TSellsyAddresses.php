<?php
namespace com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyAddresses')){
    class TSellsyAddresses extends \WP_Query {
        function __construct() {}

        /**
         * Get address
         * @params array $d id, ...
         * @return rows
         */
        public function getList($d = array())
        {
            // INIT
            $params = $d;

            $request = array(
                'method' => 'Addresses.getList',
                'params' => $params
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'    => 'addresses',
                    'response' => $response,
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
        /*
        public function getOne($d = array())
        {
            // INIT
            $id = (int)$d['id'];

            $request = array(
                'method' => 'Addresses.getOne',
                'params' => array(
                    'id' => $id
                )
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'     => 'addresses',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }
        */

        /**
         * Create address
         * @params array $d $d['linkedtype'] $d['linkedid'] $d['part1'] $d['zip'] $d['town'] $d['countrycode']
         * @return rows
         */
        public function create($d = array())
        {
            // INIT
            if (!isset($d['name'])) {
                $d['name'] = __("Main address", PLUGIN_NOM_LANG);
            }
            $params = $d;

            $request = array(
                'method' => 'Addresses.create',
                'params' => $params
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if (is_null($response)) { return false; }

            if ($response->error) {
                $t_error    = new TError();
                $t_error->add(array(
                    'categ'     => 'addresses',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Country list (format ISO 3166-1)
         * @return array
         */
        public function getCountry($qty='')
        {
            if ($qty == 'small') {
                $country = [
                    "BE" => "Belgium",
                    "CA" => "Canada",
                    "CH" => "Switzerland",
                    "DE" => "Germany",
                    "ES" => "Spain",
                    "FR" => "France",
                    "GB" => "United Kingdom",
                    "IT" => "Italy",
                    "LU" => "Luxembourg",
                    "PT" => "Portugal",
                    "US" => "United States",
                ];

            } else {
                $country = [
                    "AD" => "Andorra",
                    "AE" => "United Arab Emirates",
                    "AF" => "Afghanistan",
                    "AG" => "Antigua and barbuda",
                    "AI" => "Anguilla",
                    "AL" => "Albania",
                    "AM" => "Armenia",
                    "AN" => "Netherlands antilles",
                    "AO" => "Angola",
                    "AQ" => "Antarctica",
                    "AR" => "Argentina",
                    "AS" => "American samoa",
                    "AT" => "Austria",
                    "AU" => "Australia",
                    "AW" => "Aruba",
                    "AZ" => "Azerbaijan",
                    "BA" => "Bosnia and herzegovina",
                    "BB" => "Barbados",
                    "BD" => "Bangladesh",
                    "BE" => "Belgium",
                    "BF" => "Burkina faso",
                    "BG" => "Bulgaria",
                    "BH" => "Bahrain",
                    "BI" => "Burundi",
                    "BJ" => "Benin",
                    "BL" => "Saint barthélemy",
                    "BM" => "Bermuda",
                    "BN" => "Brunei darussalam",
                    "BO" => "Bolivia, plurinational state of",
                    "BR" => "Brazil",
                    "BS" => "Bahamas",
                    "BT" => "Bhutan",
                    "BV" => "Bouvet island",
                    "BW" => "Botswana",
                    "BY" => "Belarus",
                    "BZ" => "Belize",
                    "CA" => "Canada",
                    "CC" => "Cocos (keeling) islands",
                    "CD" => "Congo, the democratic republic of the",
                    "CF" => "Central african republic",
                    "CG" => "Congo",
                    "CH" => "Switzerland",
                    "CI" => "Côte d'ivoire",
                    "CK" => "Cook islands",
                    "CL" => "Chile",
                    "CM" => "Cameroon",
                    "CN" => "China",
                    "CO" => "Colombia",
                    "CR" => "Costa rica",
                    "CU" => "Cuba",
                    "CV" => "Cape verde",
                    "CX" => "Christmas island",
                    "CY" => "Cyprus",
                    "CZ" => "Czech republic",
                    "DE" => "Germany",
                    "DJ" => "Djibouti",
                    "DK" => "Denmark",
                    "DM" => "Dominica",
                    "DO" => "Dominican republic",
                    "DZ" => "Algeria",
                    "EC" => "Ecuador",
                    "EE" => "Estonia",
                    "EG" => "Egypt",
                    "EH" => "Western sahara",
                    "ER" => "Eritrea",
                    "ES" => "Spain",
                    "ET" => "Ethiopia",
                    "FI" => "Finland",
                    "FJ" => "Fiji",
                    "FK" => "Falkland islands (malvinas)",
                    "FM" => "Micronesia, federated states of",
                    "FO" => "Faroe islands",
                    "FR" => "France",
                    "GA" => "Gabon",
                    "GB" => "United Kingdom",
                    "GD" => "Grenada",
                    "GE" => "Georgia",
                    "GF" => "French guiana",
                    "GG" => "Guernsey",
                    "GH" => "Ghana",
                    "GI" => "Gibraltar",
                    "GL" => "Greenland",
                    "GM" => "Gambia",
                    "GN" => "Guinea",
                    "GP" => "Guadeloupe",
                    "GQ" => "Equatorial guinea",
                    "GR" => "Greece",
                    "GS" => "South georgia and the south sandwich islands",
                    "GT" => "Guatemala",
                    "GU" => "Guam",
                    "GW" => "Guinea-Bissau",
                    "GY" => "Guyana",
                    "HK" => "Hong Kong",
                    "HM" => "Heard island and mcdonald islands",
                    "HN" => "Honduras",
                    "HR" => "Croatia",
                    "HT" => "Haiti",
                    "HU" => "Hungary",
                    "ID" => "Indonesia",
                    "IE" => "Ireland",
                    "IL" => "Israel",
                    "IM" => "Isle of man",
                    "IN" => "India",
                    "IO" => "British indian ocean territory",
                    "IQ" => "Iraq",
                    "IR" => "Iran, islamic republic of",
                    "IS" => "Iceland",
                    "IT" => "Italy",
                    "JE" => "Jersey",
                    "JM" => "Jamaica",
                    "JO" => "Jordan",
                    "JP" => "Japan",
                    "KE" => "Kenya",
                    "KG" => "Kyrgyzstan",
                    "KH" => "Cambodia",
                    "KI" => "Kiribati",
                    "KM" => "Comoros",
                    "KN" => "Saint kitts and nevis",
                    "KP" => "Democratic people's republic of Korea",
                    "KR" => "Republic of Korea",
                    "KW" => "Kuwait",
                    "KY" => "Cayman islands",
                    "KZ" => "Kazakhstan",
                    "LA" => "Lao people's democratic republic",
                    "LB" => "Lebanon",
                    "LC" => "Saint lucia",
                    "LI" => "Liechtenstein",
                    "LK" => "Sri lanka",
                    "LR" => "Liberia",
                    "LS" => "Lesotho",
                    "LT" => "Lithuania",
                    "LU" => "Luxembourg",
                    "LV" => "Latvia",
                    "LY" => "Libyan arab jamahiriya",
                    "MA" => "Morocco",
                    "MC" => "Monaco",
                    "MD" => "Moldova, republic of",
                    "ME" => "Montenegro",
                    "MF" => "Saint martin (FR)",
                    "MG" => "Madagascar",
                    "MH" => "Marshall islands",
                    "MK" => "Macedonia, the former yugoslav republic of",
                    "ML" => "Mali",
                    "MM" => "Myanmar",
                    "MN" => "Mongolia",
                    "MO" => "Macao",
                    "MP" => "Northern mariana islands",
                    "MQ" => "Martinique",
                    "MR" => "Mauritania",
                    "MS" => "Montserrat",
                    "MT" => "Malta",
                    "MU" => "Mauritius",
                    "MV" => "Maldives",
                    "MW" => "Malawi",
                    "MX" => "Mexico",
                    "MY" => "Malaysia",
                    "MZ" => "Mozambique",
                    "NA" => "Namibia",
                    "NC" => "New caledonia",
                    "NE" => "Niger",
                    "NF" => "Norfolk island",
                    "NG" => "Nigeria",
                    "NI" => "Nicaragua",
                    "NL" => "Netherlands",
                    "NO" => "Norway",
                    "NP" => "Nepal",
                    "NR" => "Nauru",
                    "NU" => "Niue",
                    "NZ" => "New zealand",
                    "OM" => "Oman",
                    "PA" => "Panama",
                    "PE" => "Peru",
                    "PF" => "French polynesia",
                    "PG" => "Papua new guinea",
                    "PH" => "Philippines",
                    "PK" => "Pakistan",
                    "PL" => "Poland",
                    "PM" => "Saint pierre and miquelon",
                    "PN" => "Pitcairn",
                    "PR" => "Puerto rico",
                    "PS" => "Palestinian territory, occupied",
                    "PT" => "Portugal",
                    "PW" => "Palau",
                    "PY" => "Paraguay",
                    "QA" => "Qatar",
                    "RE" => "Réunion",
                    "RO" => "Romania",
                    "RS" => "Serbia",
                    "RU" => "Russian federation",
                    "RW" => "Rwanda",
                    "SA" => "Saudi arabia",
                    "SB" => "Solomon islands",
                    "SC" => "Seychelles",
                    "SD" => "Sudan",
                    "SE" => "Sweden",
                    "SG" => "Singapore",
                    "SH" => "Saint helena, ascension and tristan da cunha",
                    "SI" => "Slovenia",
                    "SJ" => "Svalbard and jan mayen",
                    "SK" => "Slovakia",
                    "SL" => "Sierra leone",
                    "SM" => "San marino",
                    "SN" => "Senegal",
                    "SO" => "Somalia",
                    "SR" => "Suriname",
                    "ST" => "Sao tome and principe",
                    "SV" => "El salvador",
                    "SX" => "Sint Maarten",
                    "SY" => "Syrian arab republic",
                    "SZ" => "Swaziland",
                    "TC" => "Turks and caicos islands",
                    "TD" => "Chad",
                    "TF" => "French southern territories",
                    "TG" => "Togo",
                    "TH" => "Thailand",
                    "TJ" => "Tajikistan",
                    "TK" => "Tokelau",
                    "TL" => "Timor-Leste",
                    "TM" => "Turkmenistan",
                    "TN" => "Tunisia",
                    "TO" => "Tonga",
                    "TR" => "Turkey",
                    "TT" => "Trinidad and tobago",
                    "TV" => "Tuvalu",
                    "TW" => "Taiwan, Republic of China",
                    "TZ" => "Tanzania, united republic of",
                    "UA" => "Ukraine",
                    "UG" => "Uganda",
                    "UM" => "United states minor outlying islands",
                    "US" => "United States",
                    "UY" => "Uruguay",
                    "UZ" => "Uzbekistan",
                    "VA" => "Holy see (vatican city state)",
                    "VC" => "Saint vincent and the grenadines",
                    "VE" => "Venezuela, bolivarian republic of",
                    "VG" => "Virgin islands, british",
                    "VI" => "Virgin islands, u.s.",
                    "VN" => "Viet nam",
                    "VU" => "Vanuatu",
                    "WF" => "Wallis and futuna",
                    "WS" => "Samoa",
                    "XK" => "Kosovo",
                    "YE" => "Yemen",
                    "YT" => "Mayotte",
                    "ZA" => "South africa",
                    "ZM" => "Zambia",
                    "ZW" => "Zimbabwe",
                ];
            }
            asort($country);
            return $country;
        }

        /**
         * Count nb address on 1 object
         *
         * @params array $d
         * @return nb address on 1 object
         */
        public function getNbAddress($d) {
            $count      = '';
            $linkedType = $d['linkedType'];
            $linkedIDs  = $d['linkedIDs'];

            $response = $this->getList(array(
                'search' => array(
                    'linkedType' => $linkedType,
                    'linkedIDs'  => $linkedIDs
                )
            ));

            if (isset($response->response) && $response->response) {
                if (is_array($response->response->result)) {
                    $count = count($response->response->result);
                }
                if (is_object($response->response->result)) {
                    $count = count(get_object_vars($response->response->result));
                }
            }
            return $count;
        }

    }
}
