<?php

namespace com\sellsy\sellsy\controllers;

use com\sellsy\sellsy\models;
use com\sellsy\sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('ShortcodeController')) {
    class ShortcodeController
    {

        public function __construct()
        {
            add_shortcode('ticketSellsy',  array($this, 'ticketSellsy'));
            add_shortcode('contactSellsy', array($this, 'contactSellsy'));
            add_shortcode('testSellsy',    array($this, 'testSellsy'));
        }

        /**
         * Ticket Sellsy
         * @param array|string $atts
         * @return string form
         */
        public function ticketSellsy($atts = '')
        {
            // INIT
            $id         = '';
            $render     = '';
            $error      = array();
            $classError = 'border-error';
            $decode     = array("success"=>false);
            $messageForm = '';
            $form_ticket_support_subject    = '';
            $form_ticket_support_email      = '';
            $form_ticket_support_lastname   = '';
            $form_ticket_support_message    = '';
            $class_ticket_support_email     = '';
            $class_ticket_support_name      = '';
            $class_ticket_support_message   = '';
            extract(shortcode_atts(array('id'=>''), $atts));
            $class_ticket_support_recaptcha = '';

            // MODEL
            $t_ticketForm   = new models\TTicketForm();
            $ticket         = $t_ticketForm->getTicketForm($id);
            $t_setting      = new models\TSetting();
            $setting        = $t_setting->getSetting(1);

            // reCaptcha v2
            if (
                $setting[0]->setting_recaptcha_key_version == 2 &&
                isset($_POST['g-recaptcha-response'])           &&
                $_POST['g-recaptcha-response'] != null
            ) {
                $reCaptchaOkOrNot       = false;
                $reCaptcha['secret']    = $setting[0]->setting_recaptcha_key_secret;
                $reCaptcha['response']  = $_POST['g-recaptcha-response'];
                $reCaptcha['remoteip']  = $_SERVER['REMOTE_ADDR'];

                $api_url = "https://www.google.com/recaptcha/api/siteverify?secret=".$reCaptcha['secret']."&response=".$reCaptcha['response']."&remoteip=".$reCaptcha['remoteip'];
                $decode = json_decode(file_get_contents($api_url), true);
            }
            // ok
            if ($decode['success'] == true) {
                $reCaptchaOkOrNot = true;
            // nok (robot or incorrect code) - https://developers.google.com/recaptcha/docs/verify
            } else {
                $reCaptchaOkOrNot = false;
            }

            if ($setting[0]->setting_recaptcha_key_version == 3) {
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

                        // Source : https://developers.google.com/recaptcha/docs/verify
                        switch ($data->{'error-codes'}[0]) {
                            case 'missing-input-secret':
                                $error[] = $reCaptchaErrorMessageFront." (reT v3-1)";
                                $reCaptchaErrorMessageDetail = "The secret parameter is missing.";
                                break;

                            case 'invalid-input-secret':
                                $error[] = $reCaptchaErrorMessageFront." (reT v3-2)";
                                $reCaptchaErrorMessageDetail = "The secret parameter is invalid or malformed.";
                                break;

                            case 'missing-input-response':
                                $error[] = $reCaptchaErrorMessageFront." (reT v3-3)";
                                $reCaptchaErrorMessageDetail = "The response parameter is missing.";
                                break;

                            case 'invalid-input-response':
                                $error[] = $reCaptchaErrorMessageFront." (reT v3-4)";
                                $reCaptchaErrorMessageDetail = "The response parameter is invalid or malformed.";
                                break;

                            case 'bad-request':
                                $error[] = $reCaptchaErrorMessageFront." (reT v3-5)";
                                $reCaptchaErrorMessageDetail = "The request is invalid or malformed.";
                                break;

                            case 'timeout-or-duplicate':
                                $error[] = $reCaptchaErrorMessageFront." (reT v3-6)";
                                $reCaptchaErrorMessageDetail = "The response is no longer valid: either is too old or has been used previously.";
                                break;

                            default:
                                $error[] = $reCaptchaErrorMessageFront." (reT v3-0)";
                        }

                        // Log error
                        $reCaptchaContentError = new \stdClass;
                        $reCaptchaContentError->status         = "error_recaptcha";
                        $reCaptchaContentError->error->code    = $data->{'error-codes'}[0];
                        $reCaptchaContentError->error->message = $reCaptchaErrorMessageDetail;
                        $reCaptchaContentError->error->more    = 'ticket';
                        $t_error = new models\TError();
                        $t_error->add(array(
                            'categ'     => '',
                            'response'  => $reCaptchaContentError
                        ));

                    }
                }
            }

            // VALIDATE FORM
            if (isset($_POST) && !empty($_POST) && isset($_POST['btn_ticket_support'])) {
                //check_admin_referer('form_nonce_shortcode_ticket_add');

                //if (isset($_POST['form_ticket_support_subject'])) {
                    $form_ticket_support_subject    = sanitize_text_field($_POST['form_ticket_support_subject']);
                //}
                $form_ticket_support_email      = sanitize_email($_POST['form_ticket_support_email']);
                $form_ticket_support_lastname   = sanitize_text_field($_POST['form_ticket_support_lastname']);
                $form_ticket_support_message    = sanitize_textarea_field($_POST['form_ticket_support_message']);

                // REQUIRED
                if (empty($form_ticket_support_email)) {
                    $error[] = __('email', PLUGIN_NOM_LANG);
                    $class_ticket_support_email = $classError;
                }
                if (empty($form_ticket_support_lastname)) {
                    $error[] = __('name', PLUGIN_NOM_LANG);
                    $class_ticket_support_name = $classError;
                }
                if (empty($form_ticket_support_message)) {
                    $error[] = __('message', PLUGIN_NOM_LANG);
                    $class_ticket_support_message = $classError;
                }

                // reCaptcha v2
                if (
                    $reCaptchaOkOrNot === false                         &&
                    $setting[0]->setting_recaptcha_key_status == 0      &&
                    $setting[0]->setting_recaptcha_key_version == 2     &&
                    !empty($setting[0]->setting_recaptcha_key_website)  &&
                    !empty($setting[0]->setting_recaptcha_key_secret)
                ) {
                    $error[] = __('reCAPTCHA', PLUGIN_NOM_LANG);
                    $class_ticket_support_recaptcha = $classError;
                }

                // OK
                if (empty($error)) {
                    // INSERT TO WORDPRESS : table
                    $t_ticket = new models\TTicket();
                    $t_ticket->add(array(
                        'form_ticket_subject'   => $ticket[0]->ticket_form_subject_prefix.' '.$form_ticket_support_subject,
                        'form_ticket_email'     => $form_ticket_support_email,
                        'form_ticket_name'      => $form_ticket_support_lastname,
                        'form_ticket_message'   => "
                            <h2>".__('Consumer', PLUGIN_NOM_LANG)." :</h2>
                            ".$form_ticket_support_lastname."
                            <br>
                            <h2>".__('Message', PLUGIN_NOM_LANG)." :</h2>
                            ".$form_ticket_support_message,
                        'form_ticket_linkedid'  => $ticket[0]->ticket_form_linkedid
                    ));

                    // INSERT TO SELLSY : support
                    $tbl_ticket = array();
                    $tbl_ticket['subject']          = $ticket[0]->ticket_form_subject_prefix.' '.$form_ticket_support_subject;
                    $tbl_ticket['message']          = "
                        <h2>".__('Contact', PLUGIN_NOM_LANG)." :</h2>
                        ".$form_ticket_support_lastname."<br>
                        <h2>".__('Message', PLUGIN_NOM_LANG)." :</h2>
                        ".nl2br($form_ticket_support_message);
                    $tbl_ticket['source']           = 'email';
                    $tbl_ticket['sender']           = get_option('admin_email');
                    $tbl_ticket['requesterEmail']   = $form_ticket_support_email;
                    if ($ticket[0]->ticket_form_linkedid != 0) {
                        $tbl_ticket['staffid']      = $ticket[0]->ticket_form_linkedid;
                    }

                    $request = array(
                        'method' => 'Support.create',
                        'params' => array(
                            'ticket' => $tbl_ticket
                        )
                    );
                    $response = libs\sellsyConnect_curl::load()->requestApi($request);

                    // API : success
                    if ($response->status == 'success') {
                        unset($_POST['form_ticket_support_subject']);
                        unset($_POST['form_ticket_support_email']);
                        unset($_POST['form_ticket_support_lastname']);
                        unset($_POST['form_ticket_support_message']);
                        $form_ticket_support_subject    = '';
                        $form_ticket_support_email      = '';
                        $form_ticket_support_lastname   = '';
                        $form_ticket_support_message    = '';
                        $messageForm = '<div class="sellsy-success-message">'.__('Thank you for your message, it has been sent.', PLUGIN_NOM_LANG).'</div>';

                    // API : error
                    } elseif ($response->status == 'error') {
                        $t_error    = new models\TError();
                        $t_error->add(array(
                            'categ'     => 'ticket',
                            'response'  => $response,
                        ));
                        $messageForm = '<div class="sellsy-success-message">'.__('Error registration.', PLUGIN_NOM_LANG).'</div>';
                    }

                // ERROR : required field(s)
                } else {
                    $render .= '
                    <div id="sellsy-message" class="sellsy-error-message ticket_support ticket_support_'.$ticket[0]->ticket_form_id.'">
                        <strong>';
                            if (sizeof($error) == 1) {
                                $render .= __('A field contains an error.', PLUGIN_NOM_LANG);
                                //$render .= __('Please check and try again', PLUGIN_NOM_LANG);
                            } else {
                                $render .= __('Several fields contain an error.', PLUGIN_NOM_LANG);
                                //$render .= __('Please check and try again', PLUGIN_NOM_LANG);
                            }
                        $render .= '
                        </strong><br>'.implode(', ', $error).'.
                    </div>';
                }
            }

            // FORM (setting = online)
            if ($ticket[0]->ticket_form_status == 0 && !empty($id)) {
                if (!empty($messageForm)) {
                    $render .= $messageForm;
                }

                $render .= '
                <form method="post" action="#sellsy-message" id="form_ticket_support">';

                    // Bloc save page / article with Gutenberg :
                    //$render .= '
                    //'.wp_nonce_field("form_nonce_shortcode_ticket_add", "_wpnonce_sellsy_ticket_add");

                    $render .= '
                    <label>'.__('Subject', PLUGIN_NOM_LANG).'</label>
                    <input type="text" name="form_ticket_support_subject" value="'.$form_ticket_support_subject.'" id="form_ticket_support_subject">  
                    
                    <label>'.__('Email', PLUGIN_NOM_LANG).' *</label>
                    <input type="email" name="form_ticket_support_email" value="'.$form_ticket_support_email.'" id="form_ticket_support_email" class=
                     "'.$class_ticket_support_email.'">
                    
                    <label>'.__('Name', PLUGIN_NOM_LANG).' *</label>
                    <input type="text" name="form_ticket_support_lastname" value="'.$form_ticket_support_lastname.'" id="form_ticket_support_lastname" class=
                     "'.$class_ticket_support_name.'">  
                    
                    <label>'.__('Message', PLUGIN_NOM_LANG).' *</label>
                    <textarea name="form_ticket_support_message" id="form_ticket_support_message" class=
                     "'.$class_ticket_support_message.'">'.$form_ticket_support_message.'</textarea>';

                    // reCaptcha : key site
                    if (
                        $setting[0]->setting_recaptcha_key_status == 0     &&
                        !empty($setting[0]->setting_recaptcha_key_website) &&
                        !empty($setting[0]->setting_recaptcha_key_secret)
                    ) {
                        // v2
                        if ($setting[0]->setting_recaptcha_key_version == 2) {
                            $render .= '
                            <div class="g-recaptcha '.$class_ticket_support_recaptcha.'" data-sitekey="'.$setting[0]->setting_recaptcha_key_website.'" ></div>';

                        // v3
                        } elseif ($setting[0]->setting_recaptcha_key_version == 3) {
                            $render .= '
                            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">';
                        }
                    }

                    $render .= '
                    <input type="submit" id="sellsy_btn_ticket_support" class="btn" name="btn_ticket_support" value="Valider">
                </form>';

                if (
                    $setting[0]->setting_recaptcha_key_status == 0 &&
                    $setting[0]->setting_recaptcha_key_version == 3
                ) {
                    $render .= '
                    <script>
                    jQuery(document).ready(function ($) {
                        // reCaptcha v3
                        grecaptcha.ready(function() {
                            grecaptcha.execute("' . $setting[0]->setting_recaptcha_key_website . '", {action: "submit"}).then(function(token) {
                                document.getElementById("g-recaptcha-response").value = token
                            });
                        });
                    });
                    </script>';
                }
            }
            return $render;
        }




        /**
         * Contact Sellsy
         * @param array $atts
         * @return string form
         */
        public function contactSellsy($atts = '')
        {
            // INIT
            $id         = '';
            $render     = '';
            $error      = array();
            $classError = 'border-error';
            $messageForm = '';
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
            // reCaptcha v2
            $decode['success'] = false;
            // class
            extract(shortcode_atts(array('id'=>''), $atts));

            // MODEL
            $t_contactForm  = new models\TContactForm();
            $contact        = $t_contactForm->getContactForm($id);
            $t_setting      = new models\TSetting();
            $setting        = $t_setting->getSetting(1);
            $required       = array(
                'company' => array(
                    'name'  => false,
                    'siren' => false,
                    'siret' => false,
                    'rcs'   => false
                ),
                'contact' => array(
                    'civility' => false,
                    'lastname' => false,
                    'firstname' => false,
                    'email' => false,
                    'phone_1' => false,
                    'phone_2' => false,
                    'function' => false
                ),
                'address' => array(
                    'street' => false,
                    'zip' => false,
                    'town' => false,
                    'country' => false
                ),
                'website' => false,
                'note' => false
            );

            // Wording
            $wording = json_decode( $contact[0]->contact_form_wording );




            // UPDATE : index.php > add_action(init, dataProcessingContact)




            // FORM (setting = online)
            if ($contact[0]->contact_form_status == 0 && !empty($id)) {
                // A supprimer car j'ai basculer la logique de save dans index.php
                if (!empty($messageForm)) {
                    $render .= $messageForm;
                }

                // post init
	            if (isset($_POST) && !empty($_POST) && is_array($_POST)) {
	                $form_data_post = array_map( 'stripslashes_deep', $_POST );
	            }

	            //$contact_form_company_name  = esc_attr($form_data_post['contact_form_company_name']) ?? '';
	            $contact_form_company_name = "";
	            if (isset($form_data_post['contact_form_company_name']) && !empty($form_data_post['contact_form_company_name'])) {
		            $contact_form_company_name  = esc_attr($form_data_post['contact_form_company_name']);
	            }
	            $contact_form_company_siren = "";
	            if (isset($form_data_post['contact_form_company_siren']) && !empty($form_data_post['contact_form_company_siren'])) {
		            $contact_form_company_siren = esc_attr($form_data_post['contact_form_company_siren']) ?? '';
	            }
	            $contact_form_company_siret = "";
	            if (isset($form_data_post['contact_form_company_siret']) && !empty($form_data_post['contact_form_company_siret'])) {
		            $contact_form_company_siret = esc_attr($form_data_post['contact_form_company_siret']) ?? '';
	            }
	            $contact_form_company_rcs = "";
	            if (isset($form_data_post['contact_form_company_rcs']) && !empty($form_data_post['contact_form_company_rcs'])) {
		            $contact_form_company_rcs   = esc_attr($form_data_post['contact_form_company_rcs']) ?? '';
	            }

                if (isset($contact[0]->contact_form_required_company_name) && !empty($contact[0]->contact_form_required_company_name)) {
                    $required['company']['name'] = '*';
                }
                if (isset($contact[0]->contact_form_required_company_siren) && !empty($contact[0]->contact_form_required_company_siren)) {
                    $required['company']['siren'] = '*';
                }
                if (isset($contact[0]->contact_form_required_company_siret) && !empty($contact[0]->contact_form_required_company_siret)) {
                    $required['company']['siret'] = '*';
                }
                if (isset($contact[0]->contact_form_required_company_rcs) && !empty($contact[0]->contact_form_required_company_rcs)) {
                    $required['company']['rcs'] = '*';
                }

	            $contact_form_contact_civility = "";
	            if (isset($form_data_post['contact_form_contact_civility']) && !empty($form_data_post['contact_form_contact_civility'])) {
		            $contact_form_contact_civility  = esc_attr($form_data_post['contact_form_contact_civility']) ?? '';
	            }
	            $contact_form_contact_lastname = "";
	            if (isset($form_data_post['contact_form_contact_lastname']) && !empty($form_data_post['contact_form_contact_lastname'])) {
		            $contact_form_contact_lastname  = esc_attr($form_data_post['contact_form_contact_lastname']) ?? '';
	            }
	            $contact_form_contact_firstname = "";
	            if (isset($form_data_post['contact_form_contact_firstname']) && !empty($form_data_post['contact_form_contact_firstname'])) {
		            $contact_form_contact_firstname = esc_attr($form_data_post['contact_form_contact_firstname']) ?? '';
	            }
	            $contact_form_contact_email = "";
	            if (isset($form_data_post['contact_form_contact_email']) && !empty($form_data_post['contact_form_contact_email'])) {
		            $contact_form_contact_email     = esc_attr($form_data_post['contact_form_contact_email']) ?? '';
	            }
	            $contact_form_contact_phone_1 = "";
	            if (isset($form_data_post['contact_form_contact_phone_1']) && !empty($form_data_post['contact_form_contact_phone_1'])) {
		            $contact_form_contact_phone_1   = esc_attr($form_data_post['contact_form_contact_phone_1']) ?? '';
	            }
	            $contact_form_contact_phone_2 = "";
	            if (isset($form_data_post['contact_form_contact_phone_2']) && !empty($form_data_post['contact_form_contact_phone_2'])) {
		            $contact_form_contact_phone_2   = esc_attr($form_data_post['contact_form_contact_phone_2']) ?? '';
	            }
	            $contact_form_contact_function = "";
	            if (isset($form_data_post['contact_form_contact_function']) && !empty($form_data_post['contact_form_contact_function'])) {
		            $contact_form_contact_function  = esc_attr($form_data_post['contact_form_contact_function']) ?? '';
	            }

                if (isset($contact[0]->contact_form_required_contact_civility) && !empty($contact[0]->contact_form_required_contact_civility)) {
                    $required['contact']['civility'] = '*';
                }
                if (isset($contact[0]->contact_form_required_contact_firstname) && !empty($contact[0]->contact_form_required_contact_firstname)) {
                    $required['contact']['firstname'] = '*';
                }
                if (isset($contact[0]->contact_form_required_contact_phone_1) && !empty($contact[0]->contact_form_required_contact_phone_1)) {
                    $required['contact']['phone_1'] = '*';
                }
                if (isset($contact[0]->contact_form_required_contact_phone_2) && !empty($contact[0]->contact_form_required_contact_phone_2)) {
                    $required['contact']['phone_2'] = '*';
                }
                if (isset($contact[0]->contact_form_required_contact_function) && !empty($contact[0]->contact_form_required_contact_function)) {
                    $required['contact']['function'] = '*';
                }

	            $contact_form_address_street = "";
	            if (isset($form_data_post['contact_form_address_street']) && !empty($form_data_post['contact_form_address_street'])) {
		            $contact_form_address_street  = esc_attr($form_data_post['contact_form_address_street']);
	            }
	            $contact_form_address_zip = "";
	            if (isset($form_data_post['contact_form_address_zip']) && !empty($form_data_post['contact_form_address_zip'])) {
		            $contact_form_address_zip     = esc_attr($form_data_post['contact_form_address_zip']);
	            }
	            $contact_form_address_town = "";
	            if (isset($form_data_post['contact_form_address_town']) && !empty($form_data_post['contact_form_address_town'])) {
		            $contact_form_address_town    = esc_attr($form_data_post['contact_form_address_town']);
	            }
	            $contact_form_address_country = "";
	            if (isset($form_data_post['contact_form_address_country']) && !empty($form_data_post['contact_form_address_country'])) {
		            $contact_form_address_country = esc_attr($form_data_post['contact_form_address_country']);
	            }

                if (isset($contact[0]->contact_form_required_address_street) && !empty($contact[0]->contact_form_required_address_street)) {
                    $required['address']['street'] = '*';
                }
                if (isset($contact[0]->contact_form_required_address_zip) && !empty($contact[0]->contact_form_required_address_zip)) {
                    $required['address']['zip'] = '*';
                }
                if (isset($contact[0]->contact_form_required_address_town) && !empty($contact[0]->contact_form_required_address_town)) {
                    $required['address']['town'] = '*';
                }
                if (isset($contact[0]->contact_form_required_address_country) && !empty($contact[0]->contact_form_required_address_country)) {
                    $required['address']['country'] = '*';
                }

                if (isset($contact[0]->contact_form_required_website) && !empty($contact[0]->contact_form_required_website)) {
                    $required['website'] = '*';
                }
                if (isset($contact[0]->contact_form_required_note) && !empty($contact[0]->contact_form_required_note)) {
                    $required['note'] = '*';
                }

                if (isset($_SESSION['sellsy']['message']['success'][$id]) && !empty($_SESSION['sellsy']['message']['success'][$id])) {
                    $render .= $_SESSION['sellsy']['message']['success'][$id];
                    unset($_SESSION['sellsy']['message']['success'][$id]);

                } elseif (isset($_SESSION['sellsy']['message']['error'][$id]) && !empty($_SESSION['sellsy']['message']['error'][$id])) {
                    $render .= $_SESSION['sellsy']['message']['error'][$id];
                    unset($_SESSION['sellsy']['message']['error'][$id]);
                }

                /**
                 * FORM VALIDATE
                 */
                $script = "<script>
                    jQuery(document).ready(function($) {

                        function sellsyFormSubmit(id) {                            
                            // Prevent double click for sumbit (eg: click not work):
                            var processing = false;
                            // $('.form_contact_'+id+' #sellsy_btn_contact').on('click dblclick',function(e){
                            $('.form_contact_'+id+' #sellsy_btn_contact').click(function(e){
                                e.stopPropagation();
                                if(!processing){
                                    $(this).attr('disabled', true).html('".__('Loading', PLUGIN_NOM_LANG)." ...');
                                    $('.form_contact_'+id).submit();
                                }
                                processing = true;
                            });
                        }
                        sellsyFormSubmit('".$id."');
                    
                    });
                </script>";
                add_action( 'wp_footer', function() use( $script ){
                    echo $script;
                });

                // onsubmit="document.getElementById('sellsy_btn_contact').disabled=true;
                // document.getElementById('sellsy_btn_contact').value='Submitting, please wait...';
                $render .= '
                <form method="post" action="#sellsy-message" id="form_contact" class="form_contact_'.$id.'">';

                    // Bloc save page / article with Gutenberg :
                    if (!is_admin()) {
                        $render .= '
                        '.wp_nonce_field("form_nonce_shortcode_contact_add", "_wpnonce_sellsy_contact_add", true, false);
                    }

                    $render .= '
                    <noscript>
                        <input name="js_disabled" type="hidden" value="1">
                    </noscript>';

                    $render .= '
                    <input type="hidden" name="contact_form_id" value="'.$id.'">';

                    // COMPANY
                    if ($contact[0]->contact_form_company_name == 0) {
                        $render .= '
                        <label>'.__('Company name', PLUGIN_NOM_LANG).' '.$required['company']['name'].'</label>
                        <input type="text" name="contact_form_company_name" value="'.$contact_form_company_name.'" id="contact_form_company_name">';
                    }
                    if ($contact[0]->contact_form_company_siren == 0) {
                        $render .= '
                        <label>'.__('Siren', PLUGIN_NOM_LANG).' '.$required['company']['siren'].'</label>
                        <input type="text" name="contact_form_company_siren" value="'.$contact_form_company_siren.'" id="contact_form_company_siren">';
                    }
                    if ($contact[0]->contact_form_company_siret == 0) {
                        $render .= '
                        <label>'.__('Siret', PLUGIN_NOM_LANG).' '.$required['company']['siret'].'</label>
                        <input type="text" name="contact_form_company_siret" value="'.$contact_form_company_siret.'" id="contact_form_company_siret">';
                    }
                    if ($contact[0]->contact_form_company_rcs == 0) {
                        $render .= '
                        <label>'.__('RCS', PLUGIN_NOM_LANG).' '.$required['company']['rcs'].'</label>
                        <input type="text" name="contact_form_company_rcs" value="'.$contact_form_company_rcs.'" id="contact_form_company_rcs">';
                    }

                    // CONTACT
                    if ($contact[0]->contact_form_contact_civility == 0) {
                        $checked_civility_1 = "";
                        $checked_civility_2 = "";
                        $checked_civility_3 = "";
                        switch ($contact_form_contact_civility) {
                            case 'man':
                                $checked_civility_1 = "selected";
                                break;

                            case 'woman':
                                $checked_civility_2 = "selected";
                                break;

                            case 'lady':
                                $checked_civility_3 = "selected";
                                break;
                        }
                        $render .= '
                        <label>'.__('Civility', PLUGIN_NOM_LANG).' '.$required['contact']['civility'].'</label>

                        <select name="contact_form_contact_civility" id="contact_form_contact_civility">
                            <option value="man" '.$checked_civility_1.'>'.__('Mr', PLUGIN_NOM_LANG).'</option>
                            <option value="lady" '.$checked_civility_3.'>'.__('Miss', PLUGIN_NOM_LANG).'</option>
                            <option value="woman" '.$checked_civility_2.'>'.__('Mrs', PLUGIN_NOM_LANG).'</option>
                        </select>';
                    }
                    if ($contact[0]->contact_form_contact_lastname == 0) {
                        $render .= '
                        <label>'.__('Lastname', PLUGIN_NOM_LANG).' *</label>
                        <input type="text" name="contact_form_contact_lastname" value="'.$contact_form_contact_lastname.'" id="contact_form_contact_lastname" required>';
                    }
                    if ($contact[0]->contact_form_contact_firstname == 0) {
                        $render .= '
                        <label>'.__('Firstname', PLUGIN_NOM_LANG).' '.$required['contact']['firstname'].'</label>
                        <input type="text" name="contact_form_contact_firstname" value="'.$contact_form_contact_firstname.'" id="contact_form_contact_firstname">';
                    }
                    if ($contact[0]->contact_form_contact_email == 0) {
                        $render .= '
                        <label>'.__('Email', PLUGIN_NOM_LANG).' *</label>
                        <input type="email" name="contact_form_contact_email" value="'.$contact_form_contact_email.'" id="contact_form_contact_email" required>';
                    }
                    if ($contact[0]->contact_form_contact_phone_1 == 0) {
                        if (isset($_POST['contact_form_contact_phone_1_phone_e164']) && !empty($_POST['contact_form_contact_phone_1_phone_e164'])) {
                            $contact_form_contact_phone_1 = $_POST['contact_form_contact_phone_1_phone_e164'];
                        }

                        $render .= '
                        <label>'.__('Phone', PLUGIN_NOM_LANG).' '.$required['contact']['phone_1'].'</label>
                        <input type="text" name="contact_form_contact_phone_1" value="'.$contact_form_contact_phone_1.'" id="contact_form_contact_phone_1">
                        <p class="contact_form_contact_phone_1_error sellsy-error-message sellsy-hidden">
                            '.__('Your phone is not valid.', PLUGIN_NOM_LANG).'
                        </p>';
                    }
                    if ($contact[0]->contact_form_contact_phone_2 == 0) {
                        if (isset($_POST['contact_form_contact_phone_2_phone_e164']) && !empty($_POST['contact_form_contact_phone_2_phone_e164'])) {
                            $contact_form_contact_phone_2 = $_POST['contact_form_contact_phone_2_phone_e164'];
                        }

                        $render .= '
                        <label>'.__('Mobile', PLUGIN_NOM_LANG).' '.$required['contact']['phone_2'].'</label>
                        <input type="text" name="contact_form_contact_phone_2" value="'.$contact_form_contact_phone_2.'" id="contact_form_contact_phone_2">
                        <p class="contact_form_contact_phone_2_error sellsy-error-message sellsy-hidden">
                            '.__('Your mobile is not valid.', PLUGIN_NOM_LANG).'
                        </p>';
                    }
                    if ($contact[0]->contact_form_contact_function == 0) {
                        $render .= '
                        <label>'.__('Function', PLUGIN_NOM_LANG).' '.$required['contact']['function'].'</label>
                        <input type="text" name="contact_form_contact_function" value="'.$contact_form_contact_function.'" id="contact_form_contact_function">';
                    }

                    // OTHER
                    if ($contact[0]->contact_form_address_street == 0) {
                        $render .= '
                        <label>'.__('Address', PLUGIN_NOM_LANG).' '.$required['address']['street'].'</label>
                        <input type="text" name="contact_form_address_street" value="'.$contact_form_address_street.'" id="contact_form_address_street">';
                    }
                    if ($contact[0]->contact_form_address_zip == 0) {
                        $render .= '
                        <label>'.__('Zip', PLUGIN_NOM_LANG).' '.$required['address']['zip'].'</label>
                        <input type="text" name="contact_form_address_zip" value="'.$contact_form_address_zip.'" id="contact_form_address_zip">';
                    }
                    if ($contact[0]->contact_form_address_town == 0) {
                        $render .= '
                        <label>'.__('Town', PLUGIN_NOM_LANG).' '.$required['address']['town'].'</label>
                        <input type="text" name="contact_form_address_town" value="'.$contact_form_address_town.'" id="contact_form_address_town">';
                    }
                    if ($contact[0]->contact_form_address_country == 0) {
                        $render .= '
                        <label>'.__('Country', PLUGIN_NOM_LANG).' '.$required['address']['country'].'</label>
                        <select name="contact_form_address_country" id="contact_form_address_country">
                            <option value="">---- '.__("Select value", PLUGIN_NOM_LANG).' ----</option>';

                            $t_address  = new models\TSellsyAddresses();
                            $countrySmall = $t_address->getCountry('small');
                            $countryAll   = $t_address->getCountry();

                            // Country : small
                            $render .= '<optgroup label="'.__("Selection", PLUGIN_NOM_LANG).'">';
                                foreach($countrySmall as $kCountry=>$vCountry) {
                                    $render .= '
                                    <option value="'.$kCountry.'">'.$vCountry.'</option>';
                                }
                            $render .= '</optgroup>';

                            // Country : all
                            $render .= '<optgroup label="'.__("Country", PLUGIN_NOM_LANG).'">';
                                foreach($countryAll as $kCountry=>$vCountry) {
                                    $selected = '';
                                    if ($contact_form_address_country == $kCountry) {
                                        $selected = "selected";
                                    }

                                    $render .= '
                                    <option value="'.$kCountry.'" '.$selected.'>'.$vCountry.'</option>
                                    ';
                                }
                            $render .= '</optgroup>';

                        $render .= '
                        </select>';
                    }
                    if ($contact[0]->contact_form_website == 0) {
                        if (isset($_POST['contact_form_website']) && !empty($_POST['contact_form_website'])) {
                            $contact_form_website = esc_url($_POST['contact_form_website']);
                        } elseif (isset($_POST['contact_form_website']) && !empty($_POST['contact_form_website'])) {
                            $contact_form_website = esc_url($_POST['contact_form_website']);
                        } else {
                            $contact_form_website = "";
                        }

                        $render .= '
                        <label>'.__('website', PLUGIN_NOM_LANG).' '.$required['website'].'</label>
                        <input type="text" name="contact_form_website" value="'.$contact_form_website.'" id="contact_form_website">';
                    }
                    if ($contact[0]->contact_form_note == 0) {

                        // Message on third + setting "prospect"
                        if (isset($_POST['contact_form_note']) && $contact[0]->contact_form_setting_add_what == 0) {
                            $api_third['stickyNote'] = esc_textarea($_POST['contact_form_note']);
                        }
                        // Message on contact + setting "prospect"
                        if (isset($_POST['contact_form_note']) && $contact[0]->contact_form_setting_add_what == 0) {
                            $api_contact['stickyNote'] = esc_textarea($_POST['contact_form_note']);
                        }
                        // Message on opportunity + setting "prospect & opportunity"
                        if (isset($_POST['contact_form_note']) && $contact[0]->contact_form_setting_add_what == 1) {
                            $api_opportunity['stickyNote'] = esc_textarea($_POST['contact_form_note']);
                        }

                        if (isset($api_third['stickyNote']) && !empty($api_third['stickyNote'])) {
                            $contact_form_note = $api_third['stickyNote'];
                        } elseif (isset($api_contact['stickyNote']) && !empty($api_contact['stickyNote'])) {
                            $contact_form_note = $api_contact['stickyNote'];
                        } elseif (isset($api_opportunity['stickyNote']) && !empty($api_opportunity['stickyNote'])) {
                            $contact_form_note = $api_opportunity['stickyNote'];
                        } else {
                            $contact_form_note = "";
                        }

                        $render .= '
                        <label>'.__('Message', PLUGIN_NOM_LANG).' '.$required['note'].'</label>
                        <textarea name="contact_form_note" id="contact_form_note">'.$contact_form_note.'</textarea>';
                    }

                    // CUSTOM FIELD
                    // models
                    $t_contactForm  = new models\TContactForm();
                    $contact        = $t_contactForm->getContactForm($id);
                    $t_customFields = new models\TSellsyCustomFields();
                    $c_customFields = new SellsyCustomFieldsController();

                    // init
                    $contact_form_custom_fields_value = json_decode($contact[0]->contact_form_custom_fields_value);
                    $cf = '';

                    if (isset($contact_form_custom_fields_value) && !empty($contact_form_custom_fields_value)) {
                        // cf all
                        foreach ($contact_form_custom_fields_value as $k => $v) {
                            $cf = $t_customFields->getOne( array( 'id' => $v ) );
                            if (isset($cf->response->status) && $cf->response->status == 'ok') {
                                $render .= $c_customFields->getGenerator($cf->response);
                            }
                        }
                    }

                    // MARKETING
                    if (
                        isset($contact[0]->contact_form_marketing) &&
                        !empty($contact[0]->contact_form_marketing) &&
                        ToolsController::isJson($contact[0]->contact_form_marketing)
                    ) {
                        $renderMarketing = '';
                        $marketings = json_decode($contact[0]->contact_form_marketing);

                        // All marketing :
                        $marketingsAll = ToolsController::isAllTrueMarketings(array('marketings'=>$marketings));

                        if ($marketingsAll) {
                            $marketingChecked = '';
                            if (isset($_POST['contact_form_marketing_all']) && $_POST['contact_form_marketing_all']) {
                                $marketingChecked = 'checked';
                            }

                            $renderMarketing .= '
                                <label>
                                    <input type="checkbox" name="contact_form_marketing_all" value="1" id="contact_form_marketing_all" '.$marketingChecked.'>
                                    <span>';

                            if (isset($wording->marketing_all) && !empty($wording->marketing_all)) {
                                $renderMarketing .= stripslashes($wording->marketing_all);
                            } else {
                                $renderMarketing .= __('I agree to be contacted.', PLUGIN_NOM_LANG);
                            }

                            $renderMarketing .= '
                                    </span>
                                </label>';

                        } else {

                            if (isset($marketings) && !empty($marketings)) {
                                foreach ($marketings as $kMarketing => $vMarketing) {
                                    if ($vMarketing) {

                                        $marketingChecked = '';
                                        if (isset($_POST['contact_form_marketing_' . $kMarketing]) && $_POST['contact_form_marketing_' . $kMarketing]) {
                                            $marketingChecked = 'checked';
                                        }

                                        $renderMarketing .= '
                                        <label>
                                            <input type="checkbox" name="contact_form_marketing_' . $kMarketing . '" value="1" id="contact_form_marketing_' . $kMarketing . '" ' . $marketingChecked . '>';

                                        switch ($kMarketing) {
                                            case "email":
                                                $renderMarketing .= '<span>';
                                                if (isset($wording->marketing_email) && !empty($wording->marketing_email)) {
                                                    $renderMarketing .= stripslashes($wording->marketing_email);
                                                } else {
                                                    $renderMarketing .= __('I agree to be contacted by email.', PLUGIN_NOM_LANG);
                                                }
                                                $renderMarketing .= '</span>';
                                                break;

                                            case "sms":
                                                $renderMarketing .= '<span>';
                                                if (isset($wording->marketing_sms) && !empty($wording->marketing_sms)) {
                                                    $renderMarketing .= stripslashes($wording->marketing_sms);
                                                } else {
                                                    $renderMarketing .= '<span>' . __('I agree to be contacted by sms.', PLUGIN_NOM_LANG) . '</span>';
                                                }
                                                $renderMarketing .= '</span>';
                                                break;

                                            case "phone":
                                                $renderMarketing .= '<span>';
                                                if (isset($wording->marketing_phone) && !empty($wording->marketing_phone)) {
                                                    $renderMarketing .= stripslashes($wording->marketing_phone);
                                                } else {
                                                    $renderMarketing .= __('I agree to be contacted by phone.', PLUGIN_NOM_LANG);
                                                }
                                                $renderMarketing .= '</span>';
                                                break;

                                            case "mail":
                                                $renderMarketing .= '<span>';
                                                if (isset($wording->marketing_mail) && !empty($wording->marketing_mail)) {
                                                    $renderMarketing .= stripslashes($wording->marketing_mail);
                                                } else {
                                                    $renderMarketing .= __('I agree to be contacted by mail.', PLUGIN_NOM_LANG);
                                                }
                                                $renderMarketing .= '</span>';
                                                break;

                                            case "custom":
                                                $renderMarketing .= '<span>';
                                                if (isset($wording->marketing_customizedmarketing) && !empty($wording->marketing_customizedmarketing)) {
                                                    $renderMarketing .= stripslashes($wording->marketing_customizedmarketing);
                                                } else {
                                                    $renderMarketing .= __('I accept that the data is used to offer me suitable offers.', PLUGIN_NOM_LANG);
                                                }
                                                $renderMarketing .= '</span>';
                                                break;
                                        }

                                        $renderMarketing .= '
                                        </label>';
                                    }
                                }
                            }
                        }// else

                        if (!empty($renderMarketing)) {
                            if (isset($wording->marketing_subscribe) && !empty($wording->marketing_subscribe)) {
                                $render .= '<label>'.stripslashes($wording->marketing_subscribe).'</label>';
                            } else {
                                $render .= '<label>'.__('Subscribe', PLUGIN_NOM_LANG).'</label>';
                            }
                            $render .= $renderMarketing;
                        }
                    }

                    // GDPR
                    if ($contact[0]->contact_form_condition_accept == 0) {
                        $conditionAccept = "";
                        if (isset($_POST['contact_form_condition_accept']) && $_POST['contact_form_condition_accept']) {
                            $conditionAccept = 'checked';
                        }

                        $render .= '
                        <label>
                            <input type="checkbox" name="contact_form_condition_accept" value="1" id="contact_form_condition_accept" ' . $conditionAccept . ' required>
                            <span>';
                                if (isset($wording->conditionLabel) && !empty($wording->conditionLabel)) {
                                    $render .= stripslashes($wording->conditionLabel).' *';
                                } else {
                                    $render .= __('I accept the conditions.', PLUGIN_NOM_LANG).' *';
                                }

                            $render .= '
                            </span>
                        </label>';
                    }

                    // reCaptcha : key site
                    if (
                        $setting[0]->setting_recaptcha_key_status == 0      &&
                        !empty($setting[0]->setting_recaptcha_key_website)  &&
                        !empty($setting[0]->setting_recaptcha_key_secret)
                    ) {
                        // v2
                        if ($setting[0]->setting_recaptcha_key_version == 2) {
                            $render .= '
                            <div class="g-recaptcha" data-sitekey="'.$setting[0]->setting_recaptcha_key_website.'"></div>';

                        // v3
                        } elseif ($setting[0]->setting_recaptcha_key_version == 3) {
                            $render .= '
                            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">';
                        }
                    }

                    if (isset($wording->button) && !empty($wording->button)) {
                        $btnValue = stripslashes($wording->button);
                    } else {
                        $btnValue = __('Validate', PLUGIN_NOM_LANG);
                    }

//                    $render .= '
//                    <input type="submit" name="btn_contact" id="sellsy_btn_contact" value="'.$btnValue.'">';

                    $render .= '
                    <a href="javascript:;" id="sellsy_btn_contact" class="btn">'.$btnValue.'</a>
                </form>';

                if (
                    $setting[0]->setting_recaptcha_key_status == 0 &&
                    $setting[0]->setting_recaptcha_key_version == 3
                ) {
                    $render .= '
                    <script>
                    jQuery(document).ready(function ($) {
                        // reCaptcha v3
                        grecaptcha.ready(function() {
                            grecaptcha.execute("' . $setting[0]->setting_recaptcha_key_website . '", {action: "submit"}).then(function(token) {
                                document.getElementById("g-recaptcha-response").value = token
                            });
                        });
                    });
                    </script>';
                }
            }
            return $render;
        }




        /**
         * Test Sellsy (gutenberg)
         * @param array $atts
         * @return string form
         */
        public function testSellsy($atts = '')
        {
            extract(shortcode_atts(array('id'=>''), $atts));

            $render = "Lorem ipsum (test)";
            $render .= '
            <form method="post" action="#sellsy-message" id="form_contact" class="form_contact_'.$id.'">';

                //$render .= '
                //'.wp_nonce_field("form_nonce_shortcode_test_add", "_wpnonce_sellsy_test_add");

                $render .= '
                <input type="hidden" name="contact_form_id" value="'.$id.'">
                
                <label>'.__('Company name', PLUGIN_NOM_LANG).'</label>
                <input type="text" name="contact_form_company_name" value="xxx" id="contact_form_company_name">
            </form>';

            return $render;
        }
    }//class
}//if
