<?php

namespace com\sellsy\sellsy\helpers;

use com\sellsy\sellsy\controllers\ToolsController;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('FormHelpers')) {
    class FormHelpers
    {
        /**
         * BACK (ADMIN)
         * Render form : custom fields
         * @param $d
         * @return mixed
         */
        public static function getCustomFields($d)
        {
            if (!isset($d['form_name']) /*|| !isset($d['form_value'])*/ || !isset($d['responseCustomFields'])) {
                return false;
            }

            // INIT
            $options                = array();
            $disabled               = 'disabled';
            $selected               = '';
            $isRequired             = '';
            $echo                   = $d['echo'];
            $form_name              = $d['form_name'];
            $form_value             = $d['form_value'];
            if (isset($d['responseCustomFields']->response->result) && !empty($d['responseCustomFields']->response->result)) {
                $resultsCustomFields = $d['responseCustomFields']->response->result;
            }

            // CF ALL
            if (isset($resultsCustomFields) && !empty($resultsCustomFields)) {
                foreach ($resultsCustomFields as $resultCustomFields) {
                    if ($resultCustomFields->status == 'ok') {
                        // only this "custom field" for the moment
                        if (
                            $resultCustomFields->type == 'simpletext' ||
                            $resultCustomFields->type == 'richtext'   ||
                            $resultCustomFields->type == 'select'     ||
                            $resultCustomFields->type == 'numeric'    ||
                            $resultCustomFields->type == 'email'      ||
                            $resultCustomFields->type == 'url'        ||
                            $resultCustomFields->type == 'date'       ||
                            $resultCustomFields->type == 'time'       ||
                            $resultCustomFields->type == 'boolean'    ||
                            $resultCustomFields->type == 'amount'     ||
                            $resultCustomFields->type == 'unit'       ||
                            $resultCustomFields->type == 'radio'      ||
                            $resultCustomFields->type == 'checkbox'   ||
                            $resultCustomFields->type == 'item'       ||
                            $resultCustomFields->type == 'staff'
                        ) {
                            $disabled = '';
                        } else {
                            $disabled = 'disabled';
                        }

                        // required
                        if ($resultCustomFields->isRequired == 'Y') {
                            $isRequired = '*';
                        } else {
                            $isRequired = '';
                        }

                        // selected
                        if ($form_value == $resultCustomFields->cfid) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }

                        $options[] = '
                        <option value="'.$resultCustomFields->cfid.'" '.$disabled.' '.$selected .'>
                            '.$resultCustomFields->name.' ('.$resultCustomFields->type.') '.$isRequired.' 
                            '.($disabled=="disabled" ? "- ".__('Not available on the connector', PLUGIN_NOM_LANG) : "").'
                        </option>';
                    }
                }
            }

            // SELECT
            $r = '
            <select name="'.$form_name.'">
                '.implode("", $options).'
            </select>
            <br>';

            // RENDER
            if ($echo) {
                echo $r;
            } else {
                return $r;
            }
        }

        /**
         * FRONT
         * Render form : custom fields
         * @param $id (cfid)
         * @return mixed
         */
        public static function getCustomFieldsFront($id)
        {
        }

        /**
         * Render form : radio
         * @param $d
         * @return mixed
         */
        public static function radio($d)
        {
            if (!isset($d['form_name']) || !isset($d['form_value'])) {
                return false;
            }

            $echo       = $d['echo'];
            $form_name  = $d['form_name'];
            $on = $off = '';
            if ($d['form_value'] == 0) {
                $on = 'checked="checked"';
            } else {
                $off = 'checked="checked"';
            }

            $r = '
            <input type="radio" id="'.$form_name.'_on" name="'.$form_name.'" value="0" '.$on.' />
            <label for="'.$form_name.'_on">
                <img src="'.SELLSY_PLUGIN_URL.'/images/icones/enabled.gif" />
            </label>
            
            <input type="radio" id="'.$form_name.'_off" name="'.$form_name.'" value="1" '.$off.' />
            <label for="'.$form_name.'_off">
                <img src="'.SELLSY_PLUGIN_URL.'/images/icones/disabled.gif" />
            </label>';

            if ($echo) {
                echo $r;
                return true;
            } else {
                return $r;
            }
        }

        /**
         * Render form : checkbox
         * @param $d $d['form_name']=string, $d['form_data']=array, $d['form_value']=json
         * @return mixed
         */
        public static function checkbox($d)
        {
            if (!isset($d['form_name']) || !isset($d['form_data']) || !isset($d['form_value'])) {
                return false;
            }

            $echo       = $d['echo'];
            $form_name  = $d['form_name'];
            $form_data  = $d['form_data'];
            if (ToolsController::isJson( $d['form_value'] )) {
                $form_value = json_decode($d['form_value']);
            } elseif (is_array($d['form_value'])) {
                $form_value = $d['form_value'];
            } else {
                $form_value = false;
            }
            $optionAll = $d['option']['all'];

            $r = '';
            $checkAll = true;
            if (isset($form_data) && !empty($form_data)) {
                foreach ($form_data as $k => $v) {

                    if (is_object($form_value)) {
                        if ($form_value->$k) {
                            $checked = 'checked="checked"';

                        } else {
                            $checked = '';
                            $checkAll = false;
                        }
                    }else{
                        if ($form_value[$k]) {
                            $checked = 'checked="checked"';

                        } else {
                            $checked = '';
                            $checkAll = false;
                        }

                    }

                    $r .= '
                <label> 
                    <input type="checkbox" id="' . $form_name . '_' . $k . '" name="' . $form_name . '_' . $k . '" value="' . $k . '" ' . $checked . ' />     
                    ' . $v . '
               </label>&nbsp;&nbsp;&nbsp;';
                }
            }

            // ALL
            if ($optionAll) {
                if ($checkAll) {
                    $checked = "checked";
                } else {
                    $checked = "";
                }
                $r = '
                <label> 
                    <input type="checkbox" id="'.$form_name.'_all" name='.$form_name.'_all" value="all" '.$checked.' />
                    '.__('All', PLUGIN_NOM_LANG).'
               </label>&nbsp;&nbsp;&nbsp;'.$r;
            }

            if ($echo) {
                echo $r;
                return true;
            } else {
                return $r;
            }
        }

    }//fin class
}//fin if
