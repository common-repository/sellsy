<?php
namespace com\sellsy\sellsy\controllers;

use com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('ContactController')) {
    class ContactController
    {
        /**
         * Data processing contact form
         * @param array $d
         * @return string
         */
        public function dataProcessing($contact, $setting, $d = array()) {
	        $_POST = array_map( 'stripslashes_deep', $_POST );
	        if (isset($_POST['js_disabled']) && !empty($_POST['js_disabled'])) {
	           $jsDisabled = sanitize_key( $_POST['js_disabled'] );
	        }
	        if (isset($_POST['contact_form_company_name']) && !empty($_POST['contact_form_company_name'])) {
		        $contact_form_company_name = sanitize_text_field($_POST['contact_form_company_name']);
	        }
	        if (isset($_POST['contact_form_company_siren']) && !empty($_POST['contact_form_company_siren'])) {
		        $contact_form_company_siren = sanitize_text_field($_POST['contact_form_company_siren']);
	        }
	        if (isset($_POST['contact_form_company_siret']) && !empty($_POST['contact_form_company_siret'])) {
		        $contact_form_company_siret = sanitize_text_field($_POST['contact_form_company_siret']);
	        }
	        if (isset($_POST['contact_form_company_rcs']) && !empty($_POST['contact_form_company_rcs'])) {
		        $contact_form_company_rcs = sanitize_text_field($_POST['contact_form_company_rcs']);
	        }
	        if (isset($_POST['contact_form_contact_civility']) && !empty($_POST['contact_form_contact_civility'])) {
		        $contact_form_contact_civility = sanitize_text_field($_POST['contact_form_contact_civility']);
	        }
	        if (isset($_POST['contact_form_contact_firstname']) && !empty($_POST['contact_form_contact_firstname'])) {
		        $contact_form_contact_firstname = sanitize_text_field($_POST['contact_form_contact_firstname']);
	        }
	        if (isset($_POST['contact_form_contact_lastname']) && !empty($_POST['contact_form_contact_lastname'])) {
		        $contact_form_contact_lastname = sanitize_text_field($_POST['contact_form_contact_lastname']);
	        }
	        if (isset($_POST['contact_form_contact_email']) && !empty($_POST['contact_form_contact_email'])) {
		        $contact_form_contact_email = strtolower(sanitize_email($_POST['contact_form_contact_email']));
	        }
	        if (isset($_POST['contact_form_contact_phone_1']) && !empty($_POST['contact_form_contact_phone_1'])) {
		        $contact_form_contact_phone_1 = sanitize_text_field($_POST['contact_form_contact_phone_1']);
	        }
	        if (isset($_POST['contact_form_contact_phone_2']) && !empty($_POST['contact_form_contact_phone_2'])) {
		        $contact_form_contact_phone_2 = sanitize_text_field($_POST['contact_form_contact_phone_2']);
	        }
	        if (isset($_POST['contact_form_contact_function']) && !empty($_POST['contact_form_contact_function'])) {
		        $contact_form_contact_function = sanitize_text_field($_POST['contact_form_contact_function']);
	        }
	        if (isset($_POST['contact_form_address_street']) && !empty($_POST['contact_form_address_street'])) {
		        $contact_form_address_street = sanitize_text_field($_POST['contact_form_address_street']);
	        }
	        if (isset($_POST['contact_form_address_zip']) && !empty($_POST['contact_form_address_zip'])) {
		        $contact_form_address_zip = sanitize_text_field($_POST['contact_form_address_zip']);
	        }
	        if (isset($_POST['contact_form_address_town']) && !empty($_POST['contact_form_address_town'])) {
		        $contact_form_address_town = sanitize_text_field($_POST['contact_form_address_town']);
	        }
	        if (isset($_POST['contact_form_address_country']) && !empty($_POST['contact_form_address_country'])) {
		        $contact_form_address_country = sanitize_text_field($_POST['contact_form_address_country']);
	        }
	        if (isset($_POST['contact_form_website']) && !empty($_POST['contact_form_website'])) {
		        $contact_form_website = esc_url($_POST['contact_form_website']);
	        }
	        if (isset($_POST['contact_form_note']) && !empty($_POST['contact_form_note'])) {
		        $contact_form_note = esc_textarea($_POST['contact_form_note']);
	        }
	        if (isset($_POST['contact_form_marketing_all']) && !empty($_POST['contact_form_marketing_all'])) {
		        $contact_form_marketing_all = sanitize_text_field($_POST['contact_form_marketing_all']);
	        }
	        if (isset($_POST['contact_form_marketing_email']) && !empty($_POST['contact_form_marketing_email'])) {
		        $contact_form_marketing_email = sanitize_text_field($_POST['contact_form_marketing_email']);
	        }
	        if (isset($_POST['contact_form_marketing_sms']) && !empty($_POST['contact_form_marketing_sms'])) {
		        $contact_form_marketing_sms = sanitize_text_field($_POST['contact_form_marketing_sms']);
	        }
	        if (isset($_POST['contact_form_marketing_phone']) && !empty($_POST['contact_form_marketing_phone'])) {
		        $contact_form_marketing_phone = sanitize_text_field($_POST['contact_form_marketing_phone']);
	        }
	        if (isset($_POST['contact_form_marketing_mail']) && !empty($_POST['contact_form_marketing_mail'])) {
		        $contact_form_marketing_mail = sanitize_text_field($_POST['contact_form_marketing_mail']);
	        }
	        if (isset($_POST['contact_form_marketing_custom']) && !empty($_POST['contact_form_marketing_custom'])) {
		        $contact_form_marketing_custom = sanitize_text_field($_POST['contact_form_marketing_custom']);
	        }
	        if (isset($_POST['contact_form_condition_accept']) && !empty($_POST['contact_form_condition_accept'])) {
		        $contact_form_condition_accept = sanitize_text_field($_POST['contact_form_condition_accept']);
	        }
	        if (isset($_POST['form_cf']) && !empty($_POST['form_cf'])) {
		        $form_cf = $_POST['form_cf'];
	        }
	        if (isset($_POST['_wpnonce_sellsy_contact_add']) && !empty($_POST['_wpnonce_sellsy_contact_add'])) {
		        $_wpnonce_sellsy_contact_add = sanitize_text_field($_POST['_wpnonce_sellsy_contact_add']);
	        }
	        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
		        $g_recaptcha_response = $_POST['g-recaptcha-response'];
	        }

            // Init
            $render     = '';
            $error      = array();
            //$classError  = 'border-error';
            //$messageForm = '';
            // form
            $api_third = array(
                'name'      => '',
                'siren'     => '',
                'siret'     => '',
                'rcs'       => '',
                'web'       => '',
                'stickyNote'=> ''
            );
            $api_contact = array(
                'name'      => '',
                'forename'  => '',
                'email'     => '',
                'tel'       => '',
                'mobile'    => '',
                'position'  => '',
                'stickyNote'=> ''
            );
            $api_opportunity = array(
                'stickyNote' => ''
            );
            $api_address = array(
                'name'        => __("Main address", PLUGIN_NOM_LANG),
                'part1'       => '',
                'zip'         => '',
                'town'        => '',
                'countrycode' => ''
            );

            // reCaptcha enable
            if ($setting[0]->setting_recaptcha_key_status == 0) {

                // reCaptcha v2
                if ($setting[0]->setting_recaptcha_key_version == 2) {
                    // reCaptcha
                    $decode['success'] = false;
                    // class
                    //$tbl_class = '';
                    //$class_ticket_support_recaptcha = '';

                    // reCaptcha
                    if ( isset( $g_recaptcha_response ) && $g_recaptcha_response != null ) {
                        $reCaptchaOkOrNot      = false;
                        $reCaptcha['secret']   = $setting[0]->setting_recaptcha_key_secret;
                        $reCaptcha['response'] = $g_recaptcha_response;
                        $reCaptcha['remoteip'] = $_SERVER['REMOTE_ADDR'];

                        $api_url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $reCaptcha['secret'] . "&response=" . $reCaptcha['response'] . "&remoteip=" . $reCaptcha['remoteip'];
                        $decode  = json_decode( file_get_contents( $api_url ), true );
                    }
                    // ok
                    if ( $decode['success'] == true ) {
                        $reCaptchaOkOrNot = true;

                    // nok (robot or incorrect code) - https://developers.google.com/recaptcha/docs/verify
                    } else {
                        $reCaptchaOkOrNot = false;
                    }

                    if ( $reCaptchaOkOrNot === false &&
                         $setting[0]->setting_recaptcha_key_status == 0 &&
                         ! empty( $setting[0]->setting_recaptcha_key_website ) &&
                         ! empty( $setting[0]->setting_recaptcha_key_secret )
                    ) {
                        $error[] = __( 'You must confirm that you are not a robot (check the box: "I am not a robot")', PLUGIN_NOM_LANG );
                        //$class_ticket_support_recaptcha = $classError;
                    }

                // reCaptcha v3
                } elseif ($setting[0]->setting_recaptcha_key_version == 3) {

                    // On prépare l'URL
                    $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$setting[0]->setting_recaptcha_key_secret."&response={$_POST['g-recaptcha-response']}";

                    // Si curl est dispo
                    if (function_exists('curl_version')) {
                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_HEADER, false);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                        $response = curl_exec($curl);
                    } else {
                        $response = file_get_contents($url);
                    }

                    // On vérifie qu'on a une réponse
                    if (empty($response) || is_null($response)) {
                        $error[] = __('Something went wrong. Please try again. If the case persists, contact support.', PLUGIN_NOM_LANG)." (reC v3-7)";
                    } else {
                        $data = json_decode($response);

                        // reCaptcha ok
                        if ($data->success && $data->score >= 0.7) {
                            // Traiter le formulaire (google success)

                        // Error
                        } elseif (!$data->success && $data->{'error-codes'}[0]) {
                            $reCaptchaErrorMessageFront = __('Something went wrong. Please try again. If the case persists, contact support.', PLUGIN_NOM_LANG);

                            $reCaptchaErrorCode = $data->{'error-codes'}[0];

                            // Source : https://developers.google.com/recaptcha/docs/verify
                            switch ($reCaptchaErrorCode) {
                                case 'missing-input-secret':
                                    $error[] = $reCaptchaErrorMessageFront." (reC v3-1)";
                                    $reCaptchaErrorMessageDetail = "The secret parameter is missing.";
                                    break;

                                case 'invalid-input-secret':
                                    $error[] = $reCaptchaErrorMessageFront." (reC v3-2)";
                                    $reCaptchaErrorMessageDetail = "The secret parameter is invalid or malformed.";
                                    break;

                                case 'missing-input-response':
                                    $error[] = $reCaptchaErrorMessageFront." (reC v3-3)";
                                    $reCaptchaErrorMessageDetail = "The response parameter is missing.";
                                    break;

                                case 'invalid-input-response':
                                    $error[] = $reCaptchaErrorMessageFront." (reC v3-4)";
                                    $reCaptchaErrorMessageDetail = "The response parameter is invalid or malformed.";
                                    break;

                                case 'bad-request':
                                    $error[] = $reCaptchaErrorMessageFront." (reC v3-5)";
                                    $reCaptchaErrorMessageDetail = "The request is invalid or malformed.";
                                    break;

                                case 'timeout-or-duplicate':
                                    $error[] = $reCaptchaErrorMessageFront." (reC v3-6)";
                                    $reCaptchaErrorMessageDetail = "The response is no longer valid: either is too old or has been used previously.";
                                    break;

                                default:
                                    $error[] = $reCaptchaErrorMessageFront." (reC v3-0)";
                                    $reCaptchaErrorMessageDetail = "Default error reCaptcha v3";
                            }

                            // Log error
                            //$reCaptchaContentError = (object)array(
                            //    "status" => "error_recaptcha",
                            //    "error" => array(
                            //        "code"    => $reCaptchaErrorCode,
                            //        "message" => $reCaptchaErrorMessageDetail,
                            //        "more"    => 'contact'
                            //    )
                            //);
                            $reCaptchaContentError = new \stdClass();
                            $reCaptchaContentError->status         = "error_recaptcha";
                            $reCaptchaContentError->error          = new \stdClass();
                            $reCaptchaContentError->error->code    = $reCaptchaErrorCode;
                            $reCaptchaContentError->error->message = $reCaptchaErrorMessageDetail;
                            $reCaptchaContentError->error->more    = 'contact';

                            $t_error = new models\TError();
                            $t_error->add(array(
                                'categ'     => '',
                                'response'  => $reCaptchaContentError
                            ));

                        }
                    }
                }

            }

            // Js disabled
            if (isset($jsDisabled) && $jsDisabled) {
                $error[] = __('You must have JavaScript enabled in your browser to use this form.<br>Please enable JavaScript and then reload this page.', PLUGIN_NOM_LANG);;
            }

            // Required
            if (
				$contact[0]->contact_form_required_company_name && // BO: field required
				empty($contact_form_company_name) &&               // FO: input with value
				$contact[0]->contact_form_company_name == 0        // BO: display this field on FO
            ) {
                $error[] = __('The company field is required', PLUGIN_NOM_LANG);
            }
			if ($contact[0]->contact_form_required_company_siren && empty($contact_form_company_siren) && $contact[0]->contact_form_company_siren == 0
			) {
			    $error[] = __('The siren field is required', PLUGIN_NOM_LANG);
			}
            if ($contact[0]->contact_form_required_company_siret && empty($contact_form_company_siret) && $contact[0]->contact_form_company_siret == 0) {
                $error[] = __('The siret field is required', PLUGIN_NOM_LANG);
            }
            if ($contact[0]->contact_form_required_company_rcs && empty($contact_form_company_rcs) && $contact[0]->contact_form_company_rcs == 0) {
                $error[] = __('The RCS field is required', PLUGIN_NOM_LANG);
            }

            if ($contact[0]->contact_form_required_contact_civility && empty($contact_form_contact_civility) && $contact[0]->contact_form_contact_civility == 0) {
                $error[] = __('The civility field is required', PLUGIN_NOM_LANG);
            }
            if ($contact[0]->contact_form_required_contact_firstname && empty($contact_form_contact_firstname) && $contact[0]->contact_form_contact_firstname == 0) {
                $error[] = __('The firstname field is required', PLUGIN_NOM_LANG);
            }
			if ($contact[0]->contact_form_required_contact_phone_1 && empty($contact_form_contact_phone_1) && $contact[0]->contact_form_contact_phone_1 == 0) {
			    $error[] = __('The phone field is required', PLUGIN_NOM_LANG);
			}
            if ($contact[0]->contact_form_required_contact_phone_2 && empty($contact_form_contact_phone_2) && $contact[0]->contact_form_contact_phone_2 == 0) {
                $error[] = __('The mobile field is required', PLUGIN_NOM_LANG);
            }
            if ($contact[0]->contact_form_required_contact_function && empty($contact_form_contact_function) && $contact[0]->contact_form_contact_function == 0) {
                $error[] = __('The function field is required', PLUGIN_NOM_LANG);
            }

            if ($contact[0]->contact_form_required_address_street && empty($contact_form_address_street) && $contact[0]->contact_form_address_street == 0) {
                $error[] = __('The street field is required', PLUGIN_NOM_LANG);
            }
            if ($contact[0]->contact_form_required_address_zip && empty($contact_form_address_zip) && $contact[0]->contact_form_address_zip == 0) {
                $error[] = __('The zip field is required', PLUGIN_NOM_LANG);
            }
            if ($contact[0]->contact_form_required_address_town && empty($contact_form_address_town) && $contact[0]->contact_form_address_town == 0) {
                $error[] = __('The town field is required', PLUGIN_NOM_LANG);
            }
            if ($contact[0]->contact_form_required_address_country && empty($contact_form_address_country) && $contact[0]->contact_form_address_country == 0) {
                $error[] = __('The country field is required', PLUGIN_NOM_LANG);
            }

            if ($contact[0]->contact_form_required_website && empty($contact_form_website) && $contact[0]->contact_form_website == 0) {
                $error[] = __('The website field is required', PLUGIN_NOM_LANG);
            }
            if ($contact[0]->contact_form_required_note && empty($contact_form_note) && $contact[0]->contact_form_note == 0) {
                $error[] = __('The note field is required', PLUGIN_NOM_LANG);
            }

            //check_admin_referer('form_nonce_shortcode_contact_add');

            // third
            if (isset($contact_form_company_name) && !empty($contact_form_company_name)) {
                $api_third['type'] = 'corporation'; // corporation/person
                $api_third['name'] = $contact_form_company_name;

                if (isset($contact_form_company_siren)) {
                    $api_third['siren'] = $contact_form_company_siren;
                }
                if (isset($contact_form_company_siret)) {
                    $api_third['siret'] = $contact_form_company_siret;
                }
                if (isset($contact_form_company_rcs)) {
                    $api_third['rcs'] = $contact_form_company_rcs;
                }
                if (isset($contact_form_website)) {
                    $api_third['web'] = $contact_form_website;
                }

                // Message on third + setting "prospect"
                if (isset($contact_form_note) && $contact[0]->contact_form_setting_add_what == 0) {
                    $api_third['stickyNote'] = $contact_form_note;
                }
            } else {
                $api_third['type'] = 'person'; // corporation/person

                if (isset($contact_form_contact_lastname)) {
                    $api_third['name'] = $contact_form_contact_lastname;
                }

                // Message on contact + setting "prospect"
                if (isset($contact_form_note) && $contact[0]->contact_form_setting_add_what == 0) {
                    $api_contact['stickyNote'] = $contact_form_note;
                }

                if (isset($contact_form_website)) {
                    $api_contact['web'] = $contact_form_website;
                }
            }




            // marketing
            // N=Subscribe, Y=Unsubscribe
            $api_third['massmailingUnsubscribed']           = 'Y';
            $api_third['massmailingUnsubscribedSMS']        = 'Y';
            $api_third['phoningUnsubscribed']               = 'Y';
            $api_third['massmailingUnsubscribedMail']       = 'Y';
            $api_third['massmailingUnsubscribedCustom']     = 'Y';

            $api_contact['massmailingUnsubscribed']         = 'Y';
            $api_contact['massmailingUnsubscribedSMS']      = 'Y';
            $api_contact['phoningUnsubscribed']             = 'Y';
            $api_contact['massmailingUnsubscribedMail']     = 'Y';
            $api_contact['massmailingUnsubscribedCustom']   = 'Y';

            if (isset($contact_form_marketing_all) && $contact_form_marketing_all) {
                $api_third['massmailingUnsubscribed']   = 'N';
                $api_contact['massmailingUnsubscribed'] = 'N';

                $api_third['massmailingUnsubscribedSMS']    = 'N';
                $api_contact['massmailingUnsubscribedSMS']  = 'N';

                $api_third['phoningUnsubscribed']   = 'N';
                $api_contact['phoningUnsubscribed'] = 'N';

                $api_third['massmailingUnsubscribedMail']   = 'N';
                $api_contact['massmailingUnsubscribedMail'] = 'N';

                $api_third['massmailingUnsubscribedCustom']     = 'N';
                $api_contact['massmailingUnsubscribedCustom']   = 'N';
            } else {
                if (isset($contact_form_marketing_email) && $contact_form_marketing_email) {
                    $api_third['massmailingUnsubscribed'] = 'N';
                    $api_contact['massmailingUnsubscribed'] = 'N';
                }
                if (isset($contact_form_marketing_sms) && $contact_form_marketing_sms) {
                    $api_third['massmailingUnsubscribedSMS'] = 'N';
                    $api_contact['massmailingUnsubscribedSMS'] = 'N';
                }
                if (isset($contact_form_marketing_phone) && $contact_form_marketing_phone) {
                    $api_third['phoningUnsubscribed'] = 'N';
                    $api_contact['phoningUnsubscribed'] = 'N';
                }
                if (isset($contact_form_marketing_mail) && $contact_form_marketing_mail) {
                    $api_third['massmailingUnsubscribedMail'] = 'N';
                    $api_contact['massmailingUnsubscribedMail'] = 'N';
                }
                if (isset($contact_form_marketing_custom) && $contact_form_marketing_custom) {
                    $api_third['massmailingUnsubscribedCustom'] = 'N';
                    $api_contact['massmailingUnsubscribedCustom'] = 'N';
                }
            }




            // SMART-TAG :
            $api_third['tags'] = 'wordpress';
            $api_contact['tags'] = 'wordpress';
            // smart-tag : third
            if (isset($contact[0]->contact_form_company_smarttag) && !empty($contact[0]->contact_form_company_smarttag)) {
                $api_third['tags'] = $contact[0]->contact_form_company_smarttag;
            }
            // smart-tag : contact
            if (isset($contact[0]->contact_form_contact_smarttag) && !empty($contact[0]->contact_form_contact_smarttag)) {
                $api_contact['tags'] = $contact[0]->contact_form_contact_smarttag;
            }

            // Message on opportunity + setting "prospect & opportunity"
            if (isset($contact_form_note) && $contact[0]->contact_form_setting_add_what == 1) {
                $api_opportunity['stickyNote'] = $contact_form_note;
            }

            // contact
            if (isset($contact_form_contact_civility)) {
                $api_contact['civil'] = $contact_form_contact_civility;
            }
            if (isset($contact_form_contact_lastname)) {
                $api_contact['name'] = $contact_form_contact_lastname;
            }
            if (isset($contact_form_contact_firstname)) {
                $api_contact['forename'] = $contact_form_contact_firstname;
            }
            if (isset($contact_form_contact_email)) {
                $api_contact['email'] = $contact_form_contact_email;
                $api_third['email'] = $api_contact['email'];
            }
            if (isset($contact_form_contact_phone_1)) {
                $api_contact['tel'] = $contact_form_contact_phone_1;
            }
            if (isset($contact_form_contact_phone_2)) {
                $api_contact['mobile'] = $contact_form_contact_phone_2;
            }
            if (isset($contact_form_contact_function)) {
                $api_contact['position'] = $contact_form_contact_function;
            }

            // address
            if (isset($contact_form_address_street) && !empty($contact_form_address_street)) {
                $api_address['part1'] = $contact_form_address_street;
            }
            if (isset($contact_form_address_zip) && !empty($contact_form_address_zip)) {
                $api_address['zip'] = $contact_form_address_zip;
            }
            if (isset($contact_form_address_town) && !empty($contact_form_address_town)) {
                $api_address['town'] = $contact_form_address_town;
            }
            if (isset($contact_form_address_country) && !empty($contact_form_address_country)) {
                $api_address['countrycode'] = $contact_form_address_country;
            }

            // REQUIRED (by API v1)
            if (empty($api_contact['name'])) {
                $error[] = __('The lastname field is required', PLUGIN_NOM_LANG);
                //$tbl_class['class_contact_form_contact_lastname'] = $classError;
            }
            if (empty($api_contact['email'])) {
                $error[] = __('The email field is required', PLUGIN_NOM_LANG);
                //$tbl_class['class_contact_form_contact_email'] = $classError;
            }
            if ($contact[0]->contact_form_condition_accept == 0 && empty($contact_form_condition_accept)) {
                $error[] = sprintf(__("The %s field is required", PLUGIN_NOM_LANG), json_decode($contact[0]->contact_form_wording)->conditionLabel);
            }

            // VALIDATE - email != trashmail
            if (isset($api_contact['email']) && !empty($api_contact['email'])) {
                $email = trim($api_contact['email']);

                if (strpos($email, "@") !== false) {
                    $cutEmail = explode("@", $email);

                    if (ToolsController::isTrashmail($cutEmail[1])) {
                        $error[] = __('The email field is invalid (domain not allowed).', PLUGIN_NOM_LANG);
                    }
                }
            }




            // CF
            if (isset($form_cf)) {
                foreach ($form_cf as $k => $v) {
                    // CF : SIMPLETEXT
                    if (isset($v['simpletext'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['simpletext']['name'];
                        $d['api']['default'] = $v['simpletext']['default'];
                        $d['api']['min'] = $v['simpletext']['min'];
                        $d['api']['max'] = $v['simpletext']['max'];
                        $d['api']['useOne_prospect'] = $v['simpletext']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['simpletext']['useOn_opportunity'];
                        $d['api']['required'] = $v['simpletext']['required'];
                        $d['form']['value'] = $v['simpletext']['value'] ?? '';

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkSimpleText($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                            //$tbl_class[$d['api']['id']] = $classError;
                        }

                    // CF : RICHTEXT
                    } elseif (isset($v['richtext'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['richtext']['name'];
                        $d['api']['default'] = $v['richtext']['default'];
                        $d['api']['min'] = $v['richtext']['min'];
                        $d['api']['max'] = $v['richtext']['max'];
                        $d['api']['useOne_prospect'] = $v['richtext']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['richtext']['useOn_opportunity'];
                        $d['api']['required'] = $v['richtext']['required'];
                        $d['form']['value'] = $v['richtext']['value'] ?? '';

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkRichText($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : SELECT
                    } elseif (isset($v['select'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['select']['name'];
                        $d['api']['default'] = $v['select']['default'];
                        $d['api']['useOne_prospect'] = $v['select']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['select']['useOn_opportunity'];
                        $d['api']['required'] = $v['select']['required'];
                        $d['form']['value'] = $v['select']['value'];

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkSelect($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : NUMERIC
                    } elseif (isset($v['numeric'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['numeric']['name'];
                        $d['api']['default'] = $v['numeric']['default'];
                        $d['api']['min'] = $v['numeric']['min'];
                        $d['api']['max'] = $v['numeric']['max'];
                        $d['api']['useOne_prospect'] = $v['numeric']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['numeric']['useOn_opportunity'];
                        $d['api']['required'] = $v['numeric']['required'];
                        $d['form']['value'] = $v['numeric']['value'] ?? '';

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkNumeric($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : EMAIL
                    } elseif (isset($v['email'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['email']['name'];
                        $d['api']['default'] = $v['email']['default'];
                        $d['api']['useOne_prospect'] = $v['email']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['email']['useOn_opportunity'];
                        $d['api']['required'] = $v['email']['required'];
                        $d['form']['value'] = $v['email']['value'];

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkEmail($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : WEB
                    } elseif (isset($v['url'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['url']['name'];
                        $d['api']['default'] = $v['url']['default'];
                        $d['api']['useOne_prospect'] = $v['url']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['url']['useOn_opportunity'];
                        $d['api']['required'] = $v['url']['required'];
                        $d['form']['value'] = $v['url']['value'] ?? '';

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkUrl($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : DATE
                    } elseif (isset($v['date'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['date']['name'];
                        $d['api']['min'] = $v['date']['min'] ?? '';
                        $d['api']['max'] = $v['date']['max'] ?? '';
                        $d['api']['useOne_prospect'] = $v['date']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['date']['useOn_opportunity'];
                        $d['api']['required'] = $v['date']['required'];
                        $d['form']['value'] = $v['date']['value'] ?? '';

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkDate($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : TIME
                    } elseif (isset($v['time'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['time']['name'];
                        $d['api']['min'] = $v['time']['min'];
                        $d['api']['max'] = $v['time']['max'];
                        $d['api']['useOne_prospect'] = $v['time']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['time']['useOn_opportunity'];
                        $d['api']['required'] = $v['time']['required'];
                        $d['form']['value'] = $v['time']['value'] ?? '';

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkTime($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : BOOLEAN
                    } elseif (isset($v['boolean'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['boolean']['name'];
                        $d['api']['default'] = $v['boolean']['default'];
                        $d['api']['useOne_prospect'] = $v['boolean']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['boolean']['useOn_opportunity'];
                        $d['api']['required'] = $v['boolean']['required'];
                        $d['form']['value'] = $v['boolean']['value'] ?? '';

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkBoolean($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : AMOUNT
                    } elseif (isset($v['amount'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['amount']['name'];
                        $d['api']['default'] = $v['amount']['default'];
                        $d['api']['min'] = $v['amount']['min'];
                        $d['api']['max'] = $v['amount']['max'];
                        $d['api']['useOne_prospect'] = $v['amount']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['amount']['useOn_opportunity'];
                        $d['api']['required'] = $v['amount']['required'];
                        $d['form']['value'] = $v['amount']['value'] ?? '';

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkAmount($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : UNIT
                    } elseif (isset($v['unit'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['unit']['name'];
                        $d['api']['default'] = $v['unit']['default'];
                        $d['api']['min'] = $v['unit']['min'];
                        $d['api']['max'] = $v['unit']['max'];
                        $d['api']['useOne_prospect'] = $v['unit']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['unit']['useOn_opportunity'];
                        $d['api']['required'] = $v['unit']['required'];
                        $d['form']['value'] = $v['unit']['value'] ?? '';

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkUnit($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : RADIO
                    } elseif (isset($v['radio'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['radio']['name'];
                        $d['api']['useOne_prospect'] = $v['radio']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['radio']['useOn_opportunity'];
                        $d['api']['required'] = $v['radio']['required'];
                        $d['form']['value'] = $v['radio']['value'] ?? '';

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkRadio($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : CHECKBOX
                    } elseif (isset($v['checkbox'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['checkbox']['name'];
                        $d['api']['min'] = $v['checkbox']['min'];
                        $d['api']['max'] = $v['checkbox']['max'];
                        $d['api']['useOne_prospect'] = $v['checkbox']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['checkbox']['useOn_opportunity'];
                        $d['api']['required'] = $v['checkbox']['required'];
                        $d['form']['value'] = (isset($v['checkbox']['value']) && !empty($v['checkbox']['value'])) ? array_values($v['checkbox']['value']) : [];

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkCheckbox($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : ITEM
                    } elseif (isset($v['item'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['item']['name'];
                        $d['api']['useOne_prospect'] = $v['item']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['item']['useOn_opportunity'];
                        $d['api']['required'] = $v['item']['required'];
                        $d['form']['value'] = $v['item']['value'];

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkItem($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }

                    // CF : STAFF
                    } elseif (isset($v['staff'])) {
                        // DATAS
                        $d['api']['id'] = $k;
                        $d['api']['label'] = $v['staff']['name'];
                        $d['api']['min'] = $v['staff']['min'];
                        $d['api']['max'] = $v['staff']['max'];
                        $d['api']['useOne_prospect'] = $v['staff']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['staff']['useOn_opportunity'];
                        $d['api']['required'] = $v['staff']['required'];
                        $d['form']['value'] = $v['staff']['value'];

                        // PROCESSING
                        $cf_obj     = new SellsyCustomFieldsController();
                        $checkCf    = $cf_obj->checkStaff($d);

                        if (isset($checkCf[0]) && $checkCf[0] == "error") {
                            $error[] = $d['api']['label'].' ('.$checkCf[1].')';
                        }
                    }// elseif
                }//foreach
            }





            // nonce check
            if (
                !isset($_wpnonce_sellsy_contact_add) ||
                !wp_verify_nonce($_wpnonce_sellsy_contact_add, 'form_nonce_shortcode_contact_add')
            ) {
                $error[] = __("Error", PLUGIN_NOM_LANG)." : ".__("The entry time has expired, please try again", PLUGIN_NOM_LANG);
                //print 'Sorry, your nonce did not verify.';
                //exit;
            }

            // OK
            if (empty($error)) {
                // INIT
                $tbl_contact = array();

                /**
                 * BUT :
                 * - Create or get third (client/prospect), and create or get people, and link third/people.
                 *
                 * TEST :
                 *  - OK - client exist + contact exist
                 *  - OK - client exist + contact not exist
                 *  - OK - client not exist + contact exist
                 *  - OK - client not exist + contact not
                 *  - OK - prospect exist + contact exist
                 *  - OK - prospect exist + contact not exist
                 *  - OK - prospect not exist + contact exist
                 *  - OK - prospect not exist + contact not exist
                 *
                 * 1-2/ Check prospect/third exist (Client.getList > search exact "name") :
                 *  - yes : link third
                 *  - no : create third
                 *
                 * 3/ Check people exist (Peoples.getList > search "contains" email) :
                 *  - yes : link people + link third
                 *  - no : create people + link third
                 *
                 * 4/ People find or create
                 * 5/ Third (client/prospect) find or create
                 * 6/ Link third and people
                 */

                // Company name :
                if ($api_third['name']) {
                    $t_prospects = new models\TSellsyProspects();
                    $prospectId = '';

                    // 1.1/ SEARCH prospect (company name) :
                    if (empty($prospectId)) {
                        $responseProspectSearchCompanyName = $t_prospects->getList(array(
                            'search' => array(
                                'name'  => $api_third['name'],
                                'actif' => 'Y',
                            )
                        ));
                        if (isset($responseProspectSearchCompanyName->response->result) &&
                            isset(reset($responseProspectSearchCompanyName->response->result)->thirdid)
                        ) {
                            foreach ($responseProspectSearchCompanyName->response->result as $rpsResult) {
                                // case-insensitive for "name" (ex : Sellsy == SeLLsy == sellSY)
                                if (strcasecmp($rpsResult->name, $api_third['name']) == 0) {
                                    $prospectId = $rpsResult->thirdid;
                                    break;
                                }
                            }

                            // Check exact name (because : API return LIKE %name%) :
                            //if (reset($responseProspectSearchCompanyName->response->result)->name == $api_third['name']) {
                            //    $prospectId = reset($responseProspectSearchCompanyName->response->result)->thirdid;
                            //}
                        }
                    }

                    // 1.2/ SEARCH prospect (company email) :
                    if (empty($prospectId)) {
                        $responseProspectSearchCompanyEmail = $t_prospects->getList(array(
                            'search' => array(
                                'email' => $api_contact['email'],
                                'actif' => 'Y',
                            )
                        ));
                        if (isset($responseProspectSearchCompanyEmail->response->result) &&
                            isset(reset($responseProspectSearchCompanyEmail->response->result)->thirdid)
                        ) {
                            // Check exact name (because : API return LIKE %name%) :
                            if (reset($responseProspectSearchCompanyEmail->response->result)->email == $api_contact['email']) {
                                $prospectId = reset($responseProspectSearchCompanyEmail->response->result)->thirdid;
                            }
                        }
                    }

                    // 2/ SEARCH client :
                    if (empty($prospectId)) {
                        $t_clients = new models\TSellsyClients();
                        $responseClientSearch = $t_clients->getList(array(
                            'search' => array(
                                'name' => $api_third['name'],
                                'actif' => 'Y',
                            )
                        ));
                        $clientId = '';
                        if (isset($responseClientSearch->response->result) &&
                            isset(reset($responseClientSearch->response->result)->thirdid)
                        ) {
                            foreach ($responseClientSearch->response->result as $rcsResult) {
                                // case-insensitive for "name" (ex : Sellsy == SeLLsy == sellSY)
                                if (strcasecmp($rcsResult->name, $api_third['name']) == 0) {
                                    $clientId = $rcsResult->thirdid;
                                    break;
                                }
                            }

                            // Check exact name (because : API return LIKE %name%) :
                            //if (reset($responseClientSearch->response->result)->name == $api_third['name']) {
                            //    $clientId = reset($responseClientSearch->response->result)->thirdid;
                            //}
                        }
                    }
                }

                // 3/ SEARCH people :
                $t_peoples = new models\TSellsyPeoples();
                $responsePeopleSearch = $t_peoples->getList(array(
                    'search' => array(
                        'contains' => $api_contact['email'],
                        'actif' => 'Y',
                    )
                ));

                $peopleId = '';
                if (isset($responsePeopleSearch->response->result) &&
                    isset(reset($responsePeopleSearch->response->result)->id)
                ) {
                    $peopleId = reset($responsePeopleSearch->response->result)->id;
                    $api_contact['name']     = reset($responsePeopleSearch->response->result)->name;
                    $api_contact['forename'] = reset($responsePeopleSearch->response->result)->forename;
                }

                // 4/ People find :
                if (!empty($peopleId) && is_numeric($peopleId)) {
                    // $peopleId used later

                // Create people
                } elseif ($api_third['type'] == "corporation") {
                    $responsePeopleCreate = $t_peoples->create($api_contact);
                    if (isset($responsePeopleCreate->response) && !empty($responsePeopleCreate->response)) {
                        $peopleId = $responsePeopleCreate->response->id;
                    }
                }



                // 5/
                // Check address
                $createAddress = true;
                // form : address not exist, clean (because default name, on init, create a address on API)
                if (empty($api_address['part1'])       &&
                    empty($api_address['zip'])         &&
                    empty($api_address['town'])        &&
                    empty($api_address['countrycode'])
                ) {
                    $api_address   = array();
                    $createAddress = false;
                }




                // Client find : Use $thirdid
                $thirdId = "";
                $linkedtype = "";
                if (!empty($clientId) && is_numeric($clientId)) {
                    $thirdId = $clientId;
                    $linkedtype = 'third';

                // Prospect find : Use $prospectid
                } elseif (!empty($prospectId) && is_numeric($prospectId)) {
                    $thirdId = $prospectId;
                    $linkedtype = 'prospect';

                // create prospect
                } else {
                    $linkedtype = 'prospect';
                    $paramsProspectsCreate = array();

                    // third (client or prospect) + address
                    if ($api_third['type'] == "corporation") {
                        if (isset($api_third)   && !empty($api_third)) {
                            $paramsProspectsCreate['third']   = $api_third;
                        }
                        //if (isset($api_address) && !empty($api_address)) { $paramsProspectsCreate['address'] = $api_address; }
                        $request = array(
                            'method' => 'Prospects.create',
                            'params' => $paramsProspectsCreate
                        );
                        $response = libs\sellsyConnect_curl::load()->requestApi($request);
                        if (isset($response->response) && !empty($response->response)) {
                            $thirdId = $response->response;
                        }

                    // third (client or prospect) + contact + address
                    } elseif ($api_third['type'] == "person") {
                        if (isset($api_third)   && !empty($api_third)) {
                            $paramsProspectsCreate['third']   = $api_third;
                        }
                        if (isset($api_contact) && !empty($api_contact)) {
                            $paramsProspectsCreate['contact'] = $api_contact;
                        }
                        //if (isset($api_address) && !empty($api_address)) { $paramsProspectsCreate['address'] = $api_address; }
                        $request = array(
                            'method' => 'Prospects.create',
                            'params' => $paramsProspectsCreate
                        );
                        $response = libs\sellsyConnect_curl::load()->requestApi($request);
                        if (isset($response->response) && !empty($response->response)) {
                            $thirdId = $response->response;
                        }
                    }
                }




                // 6/ LINK : third (client or prospect) + people
                if ($api_third['type'] == "corporation") {
                    $api_contact['id'] = $peopleId;

                    $api_contact['thirdids'] = array($thirdId);
                    $t_peoples->update(array(
                        'id'       => $peopleId,            // required
                        'name'     => $api_contact['name'], // required
                        'thirdids' => array($thirdId),
                    ));

                    // GET : id linked people/third (client or prospect)
                    if ($linkedtype == "third") {
                        $idLinkedPeopleThird = $t_clients->getIsLinkedPeopleClient(array(
                            'id'       => $thirdId,
                            'peopleid' => $peopleId
                        ));
                    } elseif ($linkedtype == "prospect") {
                        $idLinkedPeopleThird = $t_prospects->getIsLinkedPeopleProspect(array(
                            'id'       => $thirdId,
                            'peopleid' => $peopleId
                        ));
                    }
                    $api_contact['idLinkedPeopleThird'] = $idLinkedPeopleThird;
                }

                if ($api_third['type'] == "corporation" || $api_third['type'] == "person") {
                    // check if address exist on third, 0 address = add new address, 1 address = nothing.
                    $t_address = new models\TSellsyAddresses();
                    $getNbAddress = $t_address->getNbAddress(array(
                        'linkedType' => 'third',
                        'linkedIDs' => array($thirdId)
                    ));

                    // 0 address :
                    if ($getNbAddress === 0 && $createAddress) {
                        // add address
                        $t_address->create(array(
                            'linkedtype'  => 'third',
                            'linkedid'    => $thirdId,
                            'part1'       => $api_address['part1'],
                            'zip'         => $api_address['zip'],
                            'town'        => $api_address['town'],
                            'countrycode' => $api_address['countrycode'],
                        ));

                    // address exist :
                    } else {
                        // nothing, keep existing address
                    }
                }




                $linkedid = $thirdId;

/*
                // INSERT TO SELLSY : prospect
                $request = array(
                    'method' => 'Prospects.create',
                    'params' => array(
                        'third'     => $api_third,
                        'contact'   => $api_contact
                    )
                );
                $response = libs\sellsyConnect_curl::load()->requestApi($request);
                $linkedid = $response->response;
*/

                if (isset($linkedid) && !empty($linkedid)) {
                    $tbl_contact['linkedid'] = $linkedid;

                    // Get prospect
                    $t_prospects = new models\TSellsyProspects();
                    $prospect = $t_prospects->getOne(array('id'=>$linkedid));

                    // Get idContact
                    if (isset($prospect->response->contacts)) {
                        foreach ($prospect->response->contacts as $k => $v) {
                            $api_contact['id'] = $v->id;
                            break;
                        }
                    }
                }

                // EMAIL :
//                $emailSubject = "";
//                if (isset($api_third['name']) && !empty($api_third['name'])) {
//                    if ($api_third['name'] != $api_contact['name']) {
//                        $emailSubject .= $api_third['name'].' - ';
//                    }
//                }
//                $emailSubject .= $api_contact['forename']." ".$api_contact['name'];

                // INSERT TO WORDPRESS : table
                $t_contact = new models\TContact();
                $tbl_contact['contact_dt_create'] = current_time('mysql');
                $tbl_contact['contact_log']       = json_encode($_POST);
                $t_contact->add($tbl_contact);

                // API : success
                if (/*$response->status == 'success'*/ $thirdId) {
                    // INSERT TO SELLSY : CF Prospect
                    $cfSave = new SellsyCustomFieldsController();
                    $cfSave->dataProcessing($_POST, array("id"=>$linkedid,"type"=>"prospect"));

                    // INSERT TO SELLSY : TRACKING
                    $m_setting = new models\TSetting();
                    $isTracking = $m_setting->isTracking();

                    if ($isTracking) {
                        // get
                        $c      = new CookieController(SELLSY_COOKIE_TRACKING);
                        $cDatas = $c->datasForTracking();

                        // save
                        $t = new models\TSellsyTracking();
                        $t->record(array(
                            'thirdid' => $tbl_contact['linkedid'],
                            'datas'   => $cDatas,
                        ));

                        // Remove cookie
                        $obj_cookie = new CookieController(SELLSY_COOKIE_TRACKING);
                        $obj_cookie->delete();
                    }




                    // INSERT TO SELLSY : UTM ($_GET or Cookie) - SOURCE (Sellsy)

                    $cookieUtm = new CookieController(SELLSY_COOKIE_UTM);
                    $utm_last = $cookieUtm->getLast();
                    $utm_source = "";

                    // get current utm_source
                    if (isset($_GET['utm_source']) && !empty($_GET['utm_source'])) {
                        $utm_source = $_GET['utm_source'];

                    // get last utm_source (in cookie SELLSY_COOKIE_UTM)
                    } elseif ($utm_last) {
                        $utm_source = ToolsController::getUtmSourceValue($utm_last['url']);
                        $utm_source = urldecode($utm_source);
                    }

                    // Check if exist in Sellsy source
                    $t_sellsyOpportunities = new models\TSellsyOpportunities();
                    $allSources = $t_sellsyOpportunities->getSources();
                    if ($allSources) {
                        foreach ($allSources->response as $kSource => $vSource) {
                            if (is_numeric($kSource)) {
                                if ($vSource->label == $utm_source && $vSource->status == "ok") {
                                    $sourceid = $vSource->id;

                                    // Remove cookie
                                    $cookieUtm = new CookieController(SELLSY_COOKIE_UTM);
                                    $cookieUtm->delete();
                                }
                            }
                        }
                    }




                    // OPTION SELECTED : prospect and opportunity
                    $emailPipeline = '';
                    $emailStep     = '';
                    if ($contact[0]->contact_form_setting_add_what == 1 && isset($tbl_contact['linkedid'])) {
                        // INSERT TO SELLSY : OPPORTUNITY
                        if (isset($contact[0]->contact_form_setting_smarttag) && !empty($contact[0]->contact_form_setting_smarttag)) {
                            $tagsOpp = $contact[0]->contact_form_setting_smarttag;
                        } else {
                            $tagsOpp = 'wordpress';
                        }

                        // Use : config db source
                        if (!isset($sourceid)) {
                            $sourceid = $contact[0]->contact_form_setting_opportunity_source;
                        }

                        $t_sellsyOpportunities = new models\TSellsyOpportunities();
                        $responseOpp = $t_sellsyOpportunities->create(array(
                            'linkedtype'  => $linkedtype,
                            'linkedid'    => $tbl_contact['linkedid'],
                            'sourceid'    => $sourceid,
                            'name'        => $contact[0]->contact_form_setting_name_opportunity,
                            'funnelid'    => $contact[0]->contact_form_setting_opportunity_pipeline,
                            'stepid'      => $contact[0]->contact_form_setting_opportunity_step,
                            'deadline'    => $contact[0]->contact_form_setting_deadline,
                            'potential'   => $contact[0]->contact_form_setting_potential,
                            'probability' => $contact[0]->contact_form_setting_probability,
                            'staffId'     => $contact[0]->contact_form_setting_linkedid,
                            'tags'        => $tagsOpp,
                            'stickyNote'  => $api_opportunity['stickyNote'],

                            'api_third'   => $api_third,
                            'api_contact' => $api_contact,
                        ));

                        // API : success
                        if ($responseOpp->status == 'success') {
                            // INSERT TO SELLSY : CF Opportunity
                            $cfSave = new SellsyCustomFieldsController();
                            $cfSave->dataProcessing($_POST, array("id"=>$responseOpp->response,"type"=>"opportunity"));

                            // FOR EMAIL
                            $api_opportunity['id'] = $responseOpp->response;

                        // API : error
                        } elseif ($responseOpp->status == 'error') {
                            $t_error = new models\TError();
                            $t_error->add(array(
                                'categ'     => 'opportunities',
                                'response'  => $responseOpp,
                            ));
                            echo __('Error registration.', PLUGIN_NOM_LANG);
                        }

                        // EMAIL :
                        $emailPipeline = $t_sellsyOpportunities->getFunnels([
                            'id' => $contact[0]->contact_form_setting_opportunity_pipeline,
                        ]);

                        $emailStep = $t_sellsyOpportunities->getStepsForFunnel([
                            'idPipeline' => $contact[0]->contact_form_setting_opportunity_pipeline,
                            'idStep' => $contact[0]->contact_form_setting_opportunity_step,
                        ]);
//                        if (!is_object($emailPipeline)) {
//                            $emailSubject = $emailPipeline;
//
//                            if (isset($api_third['name']) && !empty($api_third['name'])) {
//                                if ($api_third['name'] != $api_contact['name']) {
//                                    $emailSubject .= ' - '.$api_third['name'];
//                                }
//                            }
//
//                            if (!empty($api_contact['name'])) {
//                                $emailSubject .= ' - '.$api_contact['name'];
//                            }
//                        }
                    }

                    if (SELLSY_DEBUG) {
                        echo '<pre>contact : '.var_export($contact, true).'</pre>';
                        echo '<pre>api_contact : '.var_export($api_contact, true).'</pre>';
                        echo '<pre>api_third : '.var_export($api_third, true).'</pre>';
                        echo '<pre>api_opportunity : '.var_export($api_opportunity, true).'</pre>';
                        //echo '<pre>emailSubject : '.var_export($emailSubject, true).'</pre>';
                        echo '<pre>emailPipeline : '.var_export($emailPipeline, true).'</pre>';
                        echo '<pre>emailStep : '.var_export($emailStep, true).'</pre>';
                    }

                    // CF : NOTIFICATION EMAIL
                    $formCf = array();
                    if (isset($form_cf) && !empty($form_cf)) {
                        foreach ($form_cf as $formCf01) {
                            // Checkbox : getValueToString
                            if (isset($formCf01['checkbox']) && !empty($formCf01['checkbox'])) {
                                $checkboxValueToString = implode(", ", array_values($formCf01['checkbox']['value']));
                            } else {
                                $checkboxValueToString = "";
                            }

                            foreach ($formCf01 as $formCf02) {
                                $valueForEmailNotif = !empty($checkboxValueToString) ? $checkboxValueToString : $formCf02['value'];
                                $formCf[] = $formCf02['name'] . ' : ' . $valueForEmailNotif;
                            }
                        }
                        $formCf = array_map('stripslashes_deep', $formCf);
                    }

                    // NOTIFICATION EMAIL
                    if ($contact[0]->contact_form_setting_notification_email_enable == 0 &&
                        !empty($contact[0]->contact_form_setting_notification_email)
                    ) {
                        $emailSubjectData = [];
                        if ($contact[0]->contact_form_setting_notification_email_prefix_enable) {
                            // new prefix
                            $newPrefixNb = $contact[0]->contact_form_setting_notification_email_prefix_nb+1;

                            // increment
                            $t_contactForm = new models\TContactForm();
                            $t_contactForm->incrementNotifEmailPrefix($contact[0]->contact_form_id, $newPrefixNb);

                            $emailSubjectData[] = "#".$contact[0]->contact_form_setting_notification_email_prefix_nb;
                        }
                        $emailSubjectData[] = $contact[0]->contact_form_setting_notification_email_prefix_value;
                        $emailSubject = implode(" - ", $emailSubjectData);

                        ToolsController::sendEmailNotification(array(
                            'contact'         => $contact,
                            'api_contact'     => $api_contact,
                            'api_third'       => $api_third,
                            'api_opportunity' => $api_opportunity,
                            'api_address'     => $api_address,
                            'emailSubject'    => $emailSubject,
                            'emailPipeline'   => $emailPipeline,
                            'emailStep'       => $emailStep,
                            'formCf'          => $formCf,
                        ));
                    }




                    // CLEARBIT
                    $t_setting = new models\TSetting();
                    $setting   = $t_setting->getSetting(1);

                    // has token and status contact clearbit
                    if (isset($contact) &&
                        $contact[0]->contact_form_status_clearbit == 0 &&
                        isset($setting) &&
                        !empty($setting[0]->setting_clearbit_token)
                    ) {
                        $clearbit_comment = "";
                        $t_clearbit = new models\TClearbit();

                        // CLEARBIT : Enrichment API
                        $clearbitEnrichment = $t_clearbit->getEnrichment(array(
                            "email" => $api_contact['email']
                        )); // ex: alex@clearbit.com
                        if (ToolsController::isJson($clearbitEnrichment)) {
                            $clearbitEnrichment_json = json_decode($clearbitEnrichment);

                            if (isset($clearbitEnrichment_json->company) && !empty($clearbitEnrichment_json->company)) {
                                // Get comment :
                                $clearbit_comment = "
https://www.crunchbase.com/".$clearbitEnrichment_json->company->crunchbase->handle."

Localisation : ".$clearbitEnrichment_json->company->location."
Secteur : ".$clearbitEnrichment_json->company->category->sector."
Industrie : ".$clearbitEnrichment_json->company->category->industry."
Sous industrie : ".$clearbitEnrichment_json->company->category->subIndustry."
Nombre employe : ".$clearbitEnrichment_json->company->metrics->employees." (".$clearbitEnrichment_json->company->metrics->employeesRange.")
Estimation revenu annuel : ".$clearbitEnrichment_json->company->metrics->estimatedAnnualRevenue."
Technologie : ".implode(", ", $clearbitEnrichment_json->company->tech)."
Tags : ".implode(", ", $clearbitEnrichment_json->company->tags)."

Réseaux sociaux :
- https://www.twitter.com/".$clearbitEnrichment_json->company->twitter->handle."
- https://www.facebook.com/".$clearbitEnrichment_json->company->facebook->handle."
- https://www.linkedin.com/".$clearbitEnrichment_json->company->linkedin->handle."    

Telephone : ".implode(', ', $clearbitEnrichment_json->company->site->phoneNumbers)."
Email : ".implode(', ', $clearbitEnrichment_json->company->site->emailAddresses)."
                                ";
                            }
                        }




//                // CLEARBIT : Reveal API
//                $clearbitReveal = $t_clearbit->getRevealByIp($_SERVER['REMOTE_ADDR']);
//
//                //$clearbitReveal = '{"ip":"80.245.39.76","domain":"sellsy.com","type":"company","fuzzy":true,"company":{"id":"d8ddfb87-95fe-435f-bd22-a52baa6d3828","name":"Sellsy","legalName":null,"domain":"sellsy.com","domainAliases":["sellsy.fr","santethiq.com"],"site":{"phoneNumbers":[],"emailAddresses":[]},"category":{"sector":"Information Technology","industryGroup":"Software \u0026 Services","industry":"Internet Software \u0026 Services","subIndustry":"Internet Software \u0026 Services","sicCode":"48","naicsCode":"51"},"tags":["B2B","SAAS","Technology","Information Technology \u0026 Services"],"description":"Your CRM, sales pipelines, invoices, payment status, and projects (+more) under one roof.","foundedYear":null,"location":"Avenue Jean Monnet, 17000 La Rochelle, France","timeZone":"Europe/Paris","utcOffset":1,"geo":{"streetNumber":null,"streetName":"Avenue Jean Monnet","subPremise":null,"city":"La Rochelle","postalCode":"17000","state":"Nouvelle-Aquitaine","stateCode":null,"country":"France","countryCode":"FR","lat":46.1442561,"lng":-1.1530806},"logo":"https://logo.clearbit.com/sellsy.com","facebook":{"handle":"sellsyusa","likes":568},"linkedin":{"handle":"company/easybill"},"twitter":{"handle":"SellsyEN","id":"2348758315","bio":"Our cloud platform allows you to efficiently manage every facet of your sales business. Join our other 15,000 happy users, and contact us for a free demo!","followers":821,"following":1322,"location":"United Kingdom","site":"https://t.co/2Db7JURNuP","avatar":"https://pbs.twimg.com/profile_images/913788004560506880/IfiBIkOE_normal.jpg"},"crunchbase":{"handle":"organization/sellsy"},"emailProvider":false,"type":"private","ticker":null,"identifiers":{"usEIN":null},"phone":null,"metrics":{"alexaUsRank":null,"alexaGlobalRank":189305,"employees":60,"employeesRange":"51-250","marketCap":null,"raised":10400000,"annualRevenue":null,"estimatedAnnualRevenue":"$10M-$50M","fiscalYearEnd":null},"indexedAt":"2019-01-23T01:32:13.329Z","tech":["google_apps","wistia","sendgrid","recaptcha","mailjet"],"parent":{"domain":null}},"geoIP":{"city":"La Rochelle","state":"Nouvelle-Aquitaine","stateCode":"NAQ","country":"France","countryCode":"FR"}}';
//                $clearbitReveal_json = json_decode($clearbitReveal);
//
//                // Get comment :
//                $clearbit_comment = "
//                https://www.crunchbase.com/".$clearbitReveal_json->company->crunchbase->handle."
//
//                Localisation : ".$clearbitReveal_json->company->geo->city.", ".$clearbitReveal_json->company->geo->country."
//                Secteur : ".$clearbitReveal_json->company->category->sector."
//                Industrie : ".$clearbitReveal_json->company->category->industry."
//                Sous industrie : ".$clearbitReveal_json->company->category->subIndustry."
//                Nombre employe : ".$clearbitReveal_json->company->metrics->employees." (".$clearbitReveal_json->company->metrics->employeesRange.")
//                Estimation revenu annuel : ".$clearbitReveal_json->company->metrics->estimatedAnnualRevenue."
//                Technologie : ".implode(", ", $clearbitReveal_json->company->tech)."
//
//                Réseaux sociaux :
//                - https://www.twitter.com/".$clearbitReveal_json->company->twitter->handle."
//                - https://www.facebook.com/".$clearbitReveal_json->company->facebook->handle."
//                - https://www.linkedin.com/".$clearbitReveal_json->company->linkedin->handle."
//                ";
//
//                /*
//                // Update : third (Social networks)
//                if ($clientProspect == "prospect") {
//
//                    $prospectUpdate = new models\TSellsyProspects();
//                    $prospectUpdate->update(array(
//                        'id' => $thirdId,
//                        'third' =>  array(
//                            'name'     => $dataClean['sellsy_company']['name'], // REQUIRED
//                            'facebook' => '',
//                            'linkedin' => '',
//                            'twitter'  => 'https://www.twitter.com/'.$clearbit_json->company->twitter->handle,
//                        ),
//                    ));
//
//                }elseif($clientProspect == "client") {
//
//                }
//                */




//                // CLEARBIT : Prospector API
//                $clearbitProspector = $t_clearbit->getProspector(array(
//                    "domain" => $clearbitReveal_json->domain,
//                    //"role"   => "marketing",
//                ));
//                $clearbitProspector_json = json_decode($clearbitProspector);
//                $iClearbitProspectorExit = 0;
//                foreach ($clearbitProspector_json as $v) {
//                    if ($v->verified) {
//                        $clearbit_comment .= "
//                        Contact : ".$v->name->fullName.", ".$v->title." (".$v->email.")";
//                    }
//
//                    if ($iClearbitProspectorExit >= 10) {
//                        break;
//                    }
//                    $iClearbitProspectorExit++;
//                }




                        // Add : annotation
                        if (isset($clearbit_comment) && !empty($clearbit_comment)) {
                            $t_sellsyProspect = new models\TSellsyAnnotations();
                            $dataProspectComment = array(
                                "relatedtype"   => 'third',
                                "relatedid"     => $thirdId,
                                "text"          => $clearbit_comment
                            );
                            $t_sellsyProspect->create($dataProspectComment);
                        }
                    }

                    // Reset
                    if (isset($_SESSION['sellsy']['message']) && !empty($_SESSION['sellsy']['message'])) {
                        unset($_SESSION['sellsy']['message']);
                    }
                    unset($_POST);
                    $api_third = array(
                        'name'      => '',
                        'siren'     => '',
                        'siret'     => '',
                        'rcs'       => '',
                        'web'       => '',
                        'stickyNote'=> '',
                    );
                    $api_contact = array(
                        'name'      => '',
                        'forename'  => '',
                        'email'     => '',
                        'tel'       => '',
                        'mobile'    => '',
                        'position'  => '',
                        'stickyNote'=> '',
                    );
                    $api_opportunity = array(
                        'stickyNote'=> '',
                    );

                    // REDIRECTION URL
                    if (isset($contact[0]->contact_form_redirectionurl) && !empty($contact[0]->contact_form_redirectionurl)) {
                        header('Location: '.$contact[0]->contact_form_redirectionurl);
                        exit();
                    }

                    $_SESSION['sellsy']['message']['success'][$contact[0]->contact_form_id] = '<div class="sellsy-success-message">'.__('Thank you for your message, it has been sent.', PLUGIN_NOM_LANG).'</div>';

                // API : error
                } elseif (isset($response->status) && $response->status == 'error') {
                    $t_error = new models\TError();
                    $t_error->add(array(
                        'categ'     => 'contact',
                        'response'  => $response,
                    ));
                    $_SESSION['sellsy']['message']['error'][$contact[0]->contact_form_id] = '<div class="sellsy-error-message">'.__('Error registration.', PLUGIN_NOM_LANG).'</div>';
                }

            // ERROR : required field(s)
            } else {
                $render .= '
                <div id="sellsy-message" class="sellsy-error-message sellsy-contact sellsy-contact-'.$contact[0]->contact_form_id.'">
                    <strong>';
                if (sizeof($error) == 1) {
                    $render .= __('A field contains an error.', PLUGIN_NOM_LANG);
                } else {
                    $render .= __('Several fields contain an error.', PLUGIN_NOM_LANG);
                }
                    $render .= '
                    </strong><br><ul><li>'.implode('</li><li>', $error).'</li></ul>
                </div>';

                $_SESSION['sellsy']['message']['error'][$contact[0]->contact_form_id] = $render;
            }
        }
    }//fin class
}//fin if
