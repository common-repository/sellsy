<?php

namespace com\sellsy\sellsy\controllers;

use com\sellsy\sellsy\models;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SellsyCustomFieldsController')) {
    class SellsyCustomFieldsController
    {
        /**
         * Data Processing for post (form)
         * @param array $post only $_POST for CustomField
         * @param array[(int)id, (string)type] $cfType. Type = prospect or opportunity
         * @return array|bool $error
         */
        public function dataProcessing($post, $cfType)
        {
            // INIT
            $error      = array();
            $success    = array();
            $linkedid   = (int)$cfType['id'];
            $linkedtype = $cfType['type'];

            if (isset($post['form_cf'])) {
                foreach ($post['form_cf'] as $k => $v) {
                    // CF : SIMPLETEXT
                    if (isset($v['simpletext'])) {
                        // function pour traiter le $_POST
                        //echo "traitement : simpletext.<br>";
                        //echo 'linkedid : '.$linkedid.'<br>';
                        //echo 'linkedtype : '.$linkedtype.'<br>';

                        // DATA
                        $d                              = array();
                        $d['api']['id']                 = $k;
                        $d['api']['label']              = $v['simpletext']['name'];
                        $d['api']['default']            = $v['simpletext']['default'];
                        $d['api']['min']                = $v['simpletext']['min'];
                        $d['api']['max']                = $v['simpletext']['max'];
                        $d['api']['useOne_prospect']    = $v['simpletext']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['simpletext']['useOn_opportunity'];
                        $d['api']['required']           = $v['simpletext']['required'];
                        $d['form']['value']             = ToolsController::replaceEmptyStringWithSpace($v['simpletext']['value']);

                        // PROCESSING
                        $check = $this->checkSimpleText($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => stripslashes($d['form']['value'])
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => stripslashes($d['form']['value'])
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[$d['api']['id']] = $d['form']['value'];
                        }

                    // CF : RICHTEXT
                    } elseif (isset($v['richtext'])) {
                        // DATA
                        $d                              = array();
                        $d['api']['id']                 = $k;
                        $d['api']['label']              = $v['richtext']['name'];
                        $d['api']['default']            = $v['richtext']['default'];
                        $d['api']['min']                = $v['richtext']['min'];
                        $d['api']['max']                = $v['richtext']['max'];
                        $d['api']['useOne_prospect']    = $v['richtext']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['richtext']['useOn_opportunity'];
                        $d['api']['required']           = $v['richtext']['required'];
                        $d['form']['value']             = ToolsController::replaceEmptyStringWithSpace($v['richtext']['value']);

                        // PROCESSING
                        $check = $this->checkRichText($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => nl2br(stripslashes($d['form']['value']))
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => nl2br(stripslashes($d['form']['value']))
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[$d['api']['id']] = $d['form']['value'];
                        }

                    // CF : SELECT
                    } elseif (isset($v['select'])) {
                        // DATA
                        $d                              = array();
                        $d['api']['id']                 = $k;
                        $d['api']['label']              = $v['select']['name'];
                        $d['api']['default']            = $v['select']['default'];
                        $d['api']['useOne_prospect']    = $v['select']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['select']['useOn_opportunity'];
                        $d['api']['required']           = $v['select']['required'];
                        $d['form']['value']             = $v['select']['value'];

                        // PROCESSING
                        $check = $this->checkSelect($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[$d['api']['id']] = $d['form']['value'];
                        }

                    // CF : NUMERIC
                    } elseif (isset($v['numeric'])) {
                        // DATA
                        $d = [
                            'api'  => [
                                'id'                 => $k,
                                'label'              => $v['numeric']['name'],
                                'default'            => $v['numeric']['default'],
                                'min'                => $v['numeric']['min'],
                                'max'                => $v['numeric']['max'],
                                'useOne_prospect'    => $v['numeric']['useOn_prospect'],
                                'useOne_opportunity' => $v['numeric']['useOn_opportunity'],
                                'required'           => $v['numeric']['required'],
                            ],
                            'form' => [
                                'value' => $v['numeric']['value'],
                            ]
                        ];

                        // PROCESSING
                        $check = $this->checkNumeric($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[ $d['api']['id'] ] = $d['form']['value'];
                        }
                    } elseif (isset($v['email'])) {
                        // DATA
                        $d = [
                            'api'  => [
                                'id'                 => $k,
                                'label'              => $v['email']['name'],
                                'default'            => $v['email']['default'],
                                'useOne_prospect'    => $v['email']['useOn_prospect'],
                                'useOne_opportunity' => $v['email']['useOn_opportunity'],
                                'required'           => $v['email']['required'],
                            ],
                            'form' => [
                                'value' => $v['email']['value'],
                            ]
                        ];

                        // PROCESSING
                        $check = $this->checkEmail($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[ $d['api']['id'] ] = $d['form']['value'];
                        }
                    } elseif (isset($v['url'])) {
                        // DATA
                        $d = [
                            'api'  => [
                                'id'                 => $k,
                                'label'              => $v['url']['name'],
                                'default'            => $v['url']['default'],
                                'useOne_prospect'    => $v['url']['useOn_prospect'],
                                'useOne_opportunity' => $v['url']['useOn_opportunity'],
                                'required'           => $v['url']['required'],
                            ],
                            'form' => [
                                'value' => $v['url']['value'],
                            ]
                        ];

                        // PROCESSING
                        $check = $this->checkUrl($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[ $d['api']['id'] ] = $d['form']['value'];
                        }
                    } elseif (isset($v['date'])) {
                        // DATA
                        $d = [
                            'api'  => [
                                'id'                 => $k,
                                'label'              => $v['date']['name'],
                                'min'                => $v['date']['min'],
                                'max'                => $v['date']['max'],
                                'useOne_prospect'    => $v['date']['useOn_prospect'],
                                'useOne_opportunity' => $v['date']['useOn_opportunity'],
                                'required'           => $v['date']['required'],
                            ],
                            'form' => [
                                'value' => $v['date']['value'],
                            ]
                        ];

                        // PROCESSING
                        $check = $this->checkDate($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => ToolsController::convertDateToTimestamp($d['form']['value'])
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => ToolsController::convertDateToTimestamp($d['form']['value'])
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[ $d['api']['id'] ] = $d['form']['value'];
                        }
                    } elseif (isset($v['time'])) {
                        // DATA
                        $d = [
                            'api'  => [
                                'id'                 => $k,
                                'label'              => $v['time']['name'],
                                'min'                => $v['time']['min'],
                                'max'                => $v['time']['max'],
                                'useOne_prospect'    => $v['time']['useOn_prospect'],
                                'useOne_opportunity' => $v['time']['useOn_opportunity'],
                                'required'           => $v['time']['required'],
                            ],
                            'form' => [
                                'value' => $v['time']['value'],
                            ]
                        ];

                        // PROCESSING
                        $check = $this->checkTime($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => ToolsController::convertTimeToSecond($d['form']['value'])
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => ToolsController::convertTimeToSecond($d['form']['value'])
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[ $d['api']['id'] ] = $d['form']['value'];
                        }
                    } elseif (isset($v['boolean'])) {
                        // DATA
                        $d = [
                            'api'  => [
                                'id'                 => $k,
                                'label'              => $v['boolean']['name'],
                                'default'            => $v['boolean']['default'],
                                'useOne_prospect'    => $v['boolean']['useOn_prospect'],
                                'useOne_opportunity' => $v['boolean']['useOn_opportunity'],
                                'required'           => $v['boolean']['required'],
                            ],
                            'form' => [
                                'value' => $v['boolean']['value'],
                            ]
                        ];

                        // PROCESSING
                        $check = $this->checkBoolean($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[ $d['api']['id'] ] = $d['form']['value'];
                        }
                    } elseif (isset($v['amount'])) {
                        $currency = $v['amount']['defaultCurrency'];
                        if (isset($v['amount']['currencyid']) && !empty($v['amount']['currencyid'])) {
                            $currency = $v['amount']['currencyid'];
                        }

                        // DATA
                        $d = [
                            'api'  => [
                                'id'                 => $k,
                                'label'              => $v['amount']['name'],
                                'default'            => $v['amount']['default'],
                                'defaultCurrency'    => $v['amount']['defaultCurrency'],
                                'min'                => $v['amount']['min'],
                                'max'                => $v['amount']['max'],
                                'useOne_prospect'    => $v['amount']['useOn_prospect'],
                                'useOne_opportunity' => $v['amount']['useOn_opportunity'],
                                'required'           => $v['amount']['required'],
                            ],
                            'form' => [
                                'value'      => $v['amount']['value'],
                                'currencyid' => $currency
                            ]
                        ];

                        // PROCESSING
                        $check = $this->checkAmount($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'     => $d['api']['id'],
                                            'value'    => $d['form']['value'],
                                            'currencyid' => $d['form']['currencyid']
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'     => $d['api']['id'],
                                            'value'    => $d['form']['value'],
                                            'currencyid' => $d['form']['currencyid']
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[ $d['api']['id'] ] = $d['form']['value'];
                        }
                    } elseif (isset($v['unit'])) {
                        // DATA
                        $d = [
                            'api'  => [
                                'id'                 => $k,
                                'label'              => $v['unit']['name'],
                                'default'            => $v['unit']['default'],
                                'min'                => $v['unit']['min'],
                                'max'                => $v['unit']['max'],
                                'useOne_prospect'    => $v['unit']['useOn_prospect'],
                                'useOne_opportunity' => $v['unit']['useOn_opportunity'],
                                'required'           => $v['unit']['required'],
                            ],
                            'form' => [
                                'value'  => $v['unit']['value'],
                                'unitid' => $v['unit']['unitid']
                            ]
                        ];

                        // PROCESSING
                        $check = $this->checkUnit($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'   => $d['api']['id'],
                                            'value'  => $d['form']['value'],
                                            'unitid' => $d['form']['unitid']
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'   => $d['api']['id'],
                                            'value'  => $d['form']['value'],
                                            'unitid' => $d['form']['unitid']
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[ $d['api']['id'] ] = $d['form']['value'];
                        }
                    } elseif (isset($v['radio'])) {
                        // DATA
                        $d = [
                            'api'  => [
                                'id'                 => $k,
                                'label'              => $v['radio']['name'],
                                'useOne_prospect'    => $v['radio']['useOn_prospect'],
                                'useOne_opportunity' => $v['radio']['useOn_opportunity'],
                                'required'           => $v['radio']['required'],
                            ],
                            'form' => [
                                'value' => $v['radio']['value']
                            ]
                        ];

                        // PROCESSING
                        $check = $this->checkRadio($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => stripslashes($d['form']['value'])
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => stripslashes($d['form']['value'])
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[ $d['api']['id'] ] = $d['form']['value'];
                        }
                    } elseif (isset($v['checkbox'])) {

                        // Clean
                        if (isset($v['checkbox']['value'])) {
                            foreach ($v['checkbox']['value'] as $keyCF => $valueCF) {
                                $v['checkbox']['value'][$keyCF] = stripslashes($valueCF);
                            }
                        }

                        // DATA
                        $d = [
                            'api'  => [
                                'id'                 => $k,
                                'label'              => $v['checkbox']['name'],
                                'min'                => $v['checkbox']['min'],
                                'max'                => $v['checkbox']['max'],
                                'useOne_prospect'    => $v['checkbox']['useOn_prospect'],
                                'useOne_opportunity' => $v['checkbox']['useOn_opportunity'],
                                'required'           => $v['checkbox']['required'],
                            ],
                            'form' => [
                                'value' => $v['checkbox']['value']
                            ]
                        ];

                        // PROCESSING
                        $check = $this->checkCheckbox($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[ $d['api']['id'] ] = $d['form']['value'];
                        }

                    // CF : ITEM
                    } elseif (isset($v['item'])) {
                        // DATA
                        $d                              = array();
                        $d['api']['id']                 = $k;
                        $d['api']['label']              = $v['item']['name'];
                        $d['api']['useOne_prospect']    = $v['item']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['item']['useOn_opportunity'];
                        $d['api']['required']           = $v['item']['required'];
                        $d['form']['value']             = $v['item']['value'];

                        // PROCESSING
                        $check = $this->checkItem($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => [$d['form']['value']]
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => [$d['form']['value']]
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[$d['api']['id']] = $d['form']['value'];
                        }

                    // CF : STAFF
                    } elseif (isset($v['staff'])) {
                        // DATA
                        $d                              = array();
                        $d['api']['id']                 = $k;
                        $d['api']['label']              = $v['staff']['name'];
                        $d['api']['min']                = $v['staff']['min'];
                        $d['api']['max']                = $v['staff']['max'];
                        $d['api']['useOne_prospect']    = $v['staff']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['staff']['useOn_opportunity'];
                        $d['api']['required']           = $v['staff']['required'];
                        $d['form']['value']             = str_replace("staff_", "", $v['staff']['value']);

                        // PROCESSING
                        $check = $this->checkStaff($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {
                            // STOCK : opportunity or prospect
                            switch ($linkedtype) {
                                case 'prospect':
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => [$d['form']['value']]
                                        );
                                    }
                                    break;

                                case 'opportunity':
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => [$d['form']['value']]
                                        );
                                    }
                                    break;
                            }
                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[$d['api']['id']] = $d['form']['value'];
                        }

                    }//elseif
                }//foreach

                if (isset($error) && !empty($error)) {
                    return $error;
                } elseif (isset($success) && !empty($success)) {

                    // SAVE
                    $tCf = new models\TSellsyCustomFields();
                    $tCf->recordValues(array(
                        "linkedtype"    => $linkedtype,
                        "linkedid"      => $linkedid,
                        "datas"         => $success
                    ));

                    return true;
                }
            }
            return false;
        }

        /**
         * Generator input, select, radio, checkbox, ...
         * @param $d object
         * @param $tbl_class array (DON'T USE)
         * @return bool|string input field
         */
        public function getGenerator($d, $tbl_class = "")
        {
            $type = $d->type;
            
            // Dispatcher
            switch ($type) {
                case 'simpletext':
                    $res = $this->getTypeSimpleText($d, $tbl_class);
                    break;

                case 'richtext':
                    $res = $this->getTypeRichText($d, $tbl_class);
                    break;

                case 'select':
                    $res = $this->getTypeSelect($d, $tbl_class);
                    break;

                case 'numeric':
                    $res = $this->getTypeNumeric($d, $tbl_class);
                    break;

                case 'email':
                    $res = $this->getTypeEmail($d, $tbl_class);
                    break;

                case 'url':
                    $res = $this->getTypeUrl($d, $tbl_class);
                    break;

                case 'date':
                    $res = $this->getTypeDate($d, $tbl_class);
                    break;

                case 'time':
                    $res = $this->getTypeTime($d, $tbl_class);
                    break;

                case 'boolean':
                    $res = $this->getTypeBoolean($d, $tbl_class);
                    break;

                case 'amount':
                    $res = $this->getTypeAmount($d, $tbl_class);
                    break;

                case 'unit':
                    $res = $this->getTypeUnit($d, $tbl_class);
                    break;

                case 'radio':
                    $res = $this->getTypeRadio($d, $tbl_class);
                    break;

                case 'checkbox':
                    $res = $this->getTypeCheckbox($d, $tbl_class);
                    break;

                case 'item':
                    $res = $this->getTypeItem($d, $tbl_class);
                    break;

                case 'staff':
                    $res = $this->getTypeStaff($d, $tbl_class);
                    break;

                default:
                    return false;
            }

            return $res;
        }




        //---------------------------------------------------------------------
        // SELECT
        //---------------------------------------------------------------------

        /**
         * select
         * Note : string with min/max
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeSelect($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['select']['value']) &&
                !empty($_POST['form_cf'][$d->id]['select']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['select']['value'];
            } elseif (isset($d->preferences->defaultValue) && !empty($d->preferences->defaultValue)) {
                $valueField = $d->preferences->defaultValue;
            }
            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            $selectOption = "";
            foreach ($d->preferencesList as $v) {
                $selected = "";
                if (isset($valueField) && !empty($valueField) && $valueField == $v->id) {
                    $selected = "selected";
                } elseif ($v->isDefault == 'Y') {
                    $selected = "selected";
                }
                $selectOption .= "<option value='".$v->value."' ".$selected.">".$v->value."</option>";
            }




            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>

            <select name="form_cf['.$d->id.'][select][value]" class="'.$class.'">
                <option value="">---- '.__("Select value", PLUGIN_NOM_LANG).' ----</option>
                '.$selectOption.'
            </select>

            <input type="hidden" name="form_cf['.$d->id.'][select][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][select][default]" value="'.sanitize_text_field($d->preferences->defaultValue).'">
            <input type="hidden" name="form_cf['.$d->id.'][select][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][select][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][select][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * select : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkSelect($d)
        {
            // INIT : default by api
            //$name     = $d['api']['label'];       // name field
            //$default  = $d['api']['default'];     // default value
            $required   = $d['api']['required'];    // required

            // INIT : form value
            $f_value    = $d['form']['value'];      // form value

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (!empty($f_value)) {
                return array("success", $f_value);
            }
            return false;
        }




        //---------------------------------------------------------------------
        // SIMPLE TEXT
        //---------------------------------------------------------------------

        /**
         * simpletext
         * Note : string with min/max
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeSimpleText($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['simpletext']['value']) &&
                !empty($_POST['form_cf'][$d->id]['simpletext']['value'])
            ) {
                $valueField = esc_textarea(stripslashes_deep($_POST['form_cf'][$d->id]['simpletext']['value']));
            } elseif (isset($d->preferences->defaultValue) && !empty($d->preferences->defaultValue)) {
                $valueField = esc_textarea($d->preferences->defaultValue);
            }

            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>
            <input type="text" name="form_cf['.$d->id.'][simpletext][value]" value="'.esc_attr($valueField).'" class="'.$class.'" '.$isRequiredField.' pattern="{'.$d->preferences->min.','.$d->preferences->max.'}">
            
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][default]" value="'.sanitize_text_field($d->preferences->defaultValue).'">
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][min]" value="'.$d->preferences->min.'">
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][max]" value="'.$d->preferences->max.'">
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * simpletext : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkSimpleText($d)
        {
            // INIT : default by api
            $name       = $d['api']['label'];       // name field
            $default    = $d['api']['default'];     // default value
            $min        = (int)$d['api']['min'];    // min
            $max        = (int)$d['api']['max'];    // max
            $required   = $d['api']['required'];    // required

            // INIT : form value
            $f_value        = $d['form']['value'];  // form value
            $f_value_size   = strlen($f_value);     // form value size

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (empty($min) && empty($max)) {
                if (empty($f_value)) {
                    return array("success", $default);
                } else {
                    return array("success", $f_value);
                }
            } elseif ($min > 0 && $max > 0) {
                if ($f_value_size >= $min && $f_value_size <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value_size <= $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$min);
                } elseif ($f_value_size >= $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$max);
                }
            } elseif ($min > 0) {
                if ($f_value_size >= $min) {
                    return array("success", $f_value);
                } elseif ($f_value_size < $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$min);
                }
            } elseif ($max > 0) {
                if ($f_value_size <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value_size > $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$max);
                }
            }
            return false;
        }




        //---------------------------------------------------------------------
        // RICH TEXT
        //---------------------------------------------------------------------

        /**
         * richtext
         * Note : string with min/max
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeRichText($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                $class = 'required ';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class .= 'border-error ';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['richtext']['value']) && !empty($_POST['form_cf'][$d->id]['richtext']['value'])) {
                $valueField = esc_textarea(stripslashes_deep($_POST['form_cf'][$d->id]['richtext']['value']));
            } elseif (isset($d->preferences->defaultValue) && !empty($d->preferences->defaultValue)) {
                $valueField = esc_textarea($d->preferences->defaultValue);
            }

            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class .= $tbl_class[$d->id];
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>';

            ob_start();
            $args = array(
                'teeny' => true,

                'wpautop' => true,              // adding paragraphe
                'media_buttons' => false,       // require : don't use btn add media (img, ...)
                'editor_class' => $class,       // class
                'textarea_name' => 'form_cf['.$d->id.'][richtext][value]', // name
                'textarea_rows' => 4,
                'tabindex' => 1,
                'quicktags' => false,
                //'quicktags' => array(
                //    'buttons' => 'strong,em,del,ul,ol,li,close'
                //), // note that spaces in this list seem to cause an issue
            );
            // Source : https://codex.wordpress.org/Function_Reference/wp_editor
            wp_editor($valueField, "richtext-".$d->id, $args);
            $field .= ob_get_contents();
            ob_end_clean();

            //<textarea name="form_cf['.$d->id.'][richtext][value]" class="'.$class.'" '.$isRequiredField.'
            // pattern="{'.$d->preferences->min.','.$d->preferences->max.'}">'.$valueField.'</textarea>

            $field .= '
            <input type="hidden" name="form_cf['.$d->id.'][richtext][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][richtext][default]" value="'.sanitize_textarea_field($d->preferences->defaultValue).'">
            <input type="hidden" name="form_cf['.$d->id.'][richtext][min]" value="'.$d->preferences->min.'">
            <input type="hidden" name="form_cf['.$d->id.'][richtext][max]" value="'.$d->preferences->max.'">
            <input type="hidden" name="form_cf['.$d->id.'][richtext][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][richtext][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][richtext][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * richtext : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkRichText($d)
        {
            // INIT : default by api
            $name       = $d['api']['label'];       // name field
            $default    = $d['api']['default'];     // default value
            $min        = (int)$d['api']['min'];    // min
            $max        = (int)$d['api']['max'];    // max
            $required   = $d['api']['required'];    // required

            // INIT : form value
            $f_value        = $d['form']['value'];  // form value
            $f_value_size   = strlen($f_value);     // form value size

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (empty($min) && empty($max)) {
                if (empty($f_value)) {
                    return array("success", $default);
                } else {
                    return array("success", $f_value);
                }
            } elseif ($min > 0 && $max > 0) {
                if ($f_value_size >= $min && $f_value_size <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value_size <= $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$min);
                } elseif ($f_value_size >= $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$max);
                }
            } elseif ($min > 0) {
                if ($f_value_size >= $min) {
                    return array("success", $f_value);
                } elseif ($f_value_size < $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$min);
                }
            } elseif ($max > 0) {
                if ($f_value_size <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value_size > $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$max);
                }
            }
            return false;
        }



        //---------------------------------------------------------------------
        // NUMERIC
        //---------------------------------------------------------------------

        /**
         * numeric
         * Note : int with min/max
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeNumeric($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['numeric']['value']) &&
                !empty($_POST['form_cf'][$d->id]['numeric']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['numeric']['value'];
            } elseif (isset($d->preferences->defaultValue) && !empty($d->preferences->defaultValue)) {
                $valueField = $d->preferences->defaultValue;
            }
            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>
            <input type="number" name="form_cf['.$d->id.'][numeric][value]" value="'.$valueField.'" class="'.$class.'" '.$isRequiredField.' pattern="{'.$d->preferences->min.','.$d->preferences->max.'}" min="'.$d->preferences->min.'" max="'.$d->preferences->max.'">
            
            <input type="hidden" name="form_cf['.$d->id.'][numeric][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][numeric][default]" value="'.sanitize_text_field($d->preferences->defaultValue).'">
            <input type="hidden" name="form_cf['.$d->id.'][numeric][min]" value="'.(int)$d->preferences->min.'">
            <input type="hidden" name="form_cf['.$d->id.'][numeric][max]" value="'.(int)$d->preferences->max.'">
            <input type="hidden" name="form_cf['.$d->id.'][numeric][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][numeric][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][numeric][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * numeric : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkNumeric($d)
        {
            // INIT : default by api
            $name       = $d['api']['label'];       // name field
            $default    = $d['api']['default'];     // default value
            $min        = (int)$d['api']['min'];    // min
            $max        = (int)$d['api']['max'];    // max
            $required   = $d['api']['required'];    // required

            // INIT : form value
            $f_value        = $d['form']['value'];  // form value

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (empty($min) && empty($max)) {
                if (empty($f_value)) {
                    return array("success", $default);
                } else {
                    return array("success", $f_value);
                }
            } elseif ($min > 0 && $max > 0) {
                if ($f_value >= $min && $f_value <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value <= $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$min);
                } elseif ($f_value >= $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$max);
                }
            } elseif ($min > 0) {
                if ($f_value >= $min) {
                    return array("success", $f_value);
                } elseif ($f_value < $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$min);
                }
            } elseif ($max > 0) {
                if ($f_value <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value > $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$max);
                }
            }
            return false;
        }




        //---------------------------------------------------------------------
        // EMAIL
        //---------------------------------------------------------------------

        /**
         * email
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeEmail($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['email']['value']) &&
                !empty($_POST['form_cf'][$d->id]['email']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['email']['value'];
            } elseif (isset($d->preferences->defaultValue) && !empty($d->preferences->defaultValue)) {
                $valueField = $d->preferences->defaultValue;
            }

            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>
            <input type="email" name="form_cf['.$d->id.'][email][value]" value="'.$valueField.'" class="'.$class.'" '.$isRequiredField.'>
            
            <input type="hidden" name="form_cf['.$d->id.'][email][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][email][default]" value="'.sanitize_text_field($d->preferences->defaultValue).'">
            <input type="hidden" name="form_cf['.$d->id.'][email][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][email][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][email][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * email : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkEmail($d)
        {
            // INIT : default by api
            $name     = $d['api']['label'];       // name field
            $default  = $d['api']['default'];     // default value
            $required = $d['api']['required'];    // required

            // INIT : form value
            $f_value  = $d['form']['value'];      // form value

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (!empty($f_value) && !is_email($f_value)) {
                return array("error", __("Field is not a email", PLUGIN_NOM_LANG));
            } elseif (empty($f_value) && isset($default) && !empty($default)) {
                return array("success", $default);
            } else {
                return array("success", $f_value);
            }
        }




        //---------------------------------------------------------------------
        // URL
        //---------------------------------------------------------------------

        /**
         * url
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeUrl($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['url']['value']) &&
                !empty($_POST['form_cf'][$d->id]['url']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['url']['value'];
            } elseif (isset($d->preferences->defaultValue) && !empty($d->preferences->defaultValue)) {
                $valueField = $d->preferences->defaultValue;
            }

            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>
            <input type="url" name="form_cf['.$d->id.'][url][value]" value="'.$valueField.'" class="'.$class.'" '.$isRequiredField.'>
            
            <input type="hidden" name="form_cf['.$d->id.'][url][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][url][default]" value="'.sanitize_text_field($d->preferences->defaultValue).'">
            <input type="hidden" name="form_cf['.$d->id.'][url][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][url][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][url][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * url : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkUrl($d)
        {
            // INIT : default by api
            $name     = $d['api']['label'];       // name field
            $default  = $d['api']['default'];     // default value
            $required = $d['api']['required'];    // required

            // INIT : form value
            $f_value  = $d['form']['value'];      // form value

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (!empty($f_value) && !ToolsController::isUrl($f_value)) {
                return array("error", __("Field is not a url", PLUGIN_NOM_LANG));
            } elseif (empty($f_value)) {
                return array("success", $default);
            } else {
                return array("success", $f_value);
            }
            return false;
        }



        //---------------------------------------------------------------------
        // DATE
        //---------------------------------------------------------------------

        /**
         * date
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeDate($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['date']['value']) &&
                !empty($_POST['form_cf'][$d->id]['date']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['date']['value'];
            }

            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>
            <input type="date" name="form_cf['.$d->id.'][date][value]" min="'.ToolsController::convertTimestampToDate($d->preferences->min).'" max="'.ToolsController::convertTimestampToDate($d->preferences->max).'" value="'.$valueField.'" class="'.$class.'" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" '.$isRequiredField.'>

            <input type="hidden" name="form_cf['.$d->id.'][date][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][date][min]" value="'.$d->preferences->min.'">
            <input type="hidden" name="form_cf['.$d->id.'][date][max]" value="'.$d->preferences->max.'">
            <input type="hidden" name="form_cf['.$d->id.'][date][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][date][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][date][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * date : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkDate($d)
        {
            // INIT : default by api
            $name     = $d['api']['label'];       // name field
            $min      = (int)$d['api']['min'];    // min (timestamp)
            $max      = (int)$d['api']['max'];    // max (timestamp)
            $required = $d['api']['required'];    // required

            // INIT : Human date
            $minHumanDate = ToolsController::convertTimestampToDate($min, "d-m-Y");
            $maxHumanDate = ToolsController::convertTimestampToDate($max, "d-m-Y");

            // INIT : form value
            $f_value  = $d['form']['value'];      // form value
            $timestamp_value = ToolsController::convertDateToTimestamp($f_value); // value (timestamp)

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (empty($min) && empty($max)) {
                return array("success", $f_value);
            } elseif (!empty($min) && !empty($max)) {
                if ($timestamp_value >= $min && $timestamp_value <= $max) {
                    return array("success", $f_value);
                } elseif ($timestamp_value < $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$minHumanDate);
                } elseif ($timestamp_value > $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$maxHumanDate);
                }
            } elseif (!empty($min)) {
                if ($timestamp_value >= $min) {
                    return array("success", $f_value);
                } elseif ($timestamp_value < $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$minHumanDate);
                }
            } elseif (!empty($max)) {
                if ($timestamp_value <= $max) {
                    return array("success", $f_value);
                } elseif ($timestamp_value > $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$maxHumanDate);
                }
            }
            return false;
        }



        //---------------------------------------------------------------------
        // TIME
        //---------------------------------------------------------------------

        /**
         * time
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeTime($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['time']['value']) &&
                !empty($_POST['form_cf'][$d->id]['time']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['time']['value'];
            }

            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>
            <input type="time" name="form_cf['.$d->id.'][time][value]" step="300" min="'.$d->preferences->min.'" max="'.$d->preferences->max.'" value="'.$valueField.'" class="'.$class.'" '.$isRequiredField.'>

            <input type="hidden" name="form_cf['.$d->id.'][time][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][time][min]" value="'.$d->preferences->min.'">
            <input type="hidden" name="form_cf['.$d->id.'][time][max]" value="'.$d->preferences->max.'">
            <input type="hidden" name="form_cf['.$d->id.'][time][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][time][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][time][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * time : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkTime($d)
        {
            // INIT : default by api
            $name     = $d['api']['label'];    // name field
            $min      = $d['api']['min'];      // min
            $max      = $d['api']['max'];      // max
            $required = $d['api']['required']; // required

            // INIT : form value
            $f_value  = $d['form']['value'];   // form value

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (empty($min) && empty($max)) {
                return array("success", $f_value);
            } elseif (!empty($min) && !empty($max)) {
                if ($f_value >= $min && $f_value <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value < $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$min);
                } elseif ($f_value > $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$max);
                }
            } elseif (!empty($min)) {
                if ($f_value >= $min) {
                    return array("success", $f_value);
                } elseif ($f_value < $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$min);
                }
            } elseif (!empty($max)) {
                if ($f_value <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value > $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$max);
                }
            }
            return false;
        }




        //---------------------------------------------------------------------
        // BOOLEAN
        //---------------------------------------------------------------------

        /**
         * boolean
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeBoolean($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['boolean']['value']) &&
                !empty($_POST['form_cf'][$d->id]['boolean']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['boolean']['value'];
            } elseif (isset($d->preferences->defaultValue) && !empty($d->preferences->defaultValue)) {
                $valueField = $d->preferences->defaultValue;
            }

            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            $on = $off = '';
            if ($valueField == 'Y') {
                $on = 'checked="checked"';
            } elseif ($valueField == 'N') {
                $off = 'checked="checked"';
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>

            <div class="form_flex">
                <label for="form_cf['.$d->id.'][boolean][value][on]">
                    <input type="radio" id="form_cf['.$d->id.'][boolean][value][on]" name="form_cf['.$d->id.'][boolean][value]" value="Y" class="'.$class.'" '.$on.'> 
                    '.__("Yes", PLUGIN_NOM_LANG).'
                </label>
    
                <label for="form_cf['.$d->id.'][boolean][value][off]">
                    <input type="radio" id="form_cf['.$d->id.'][boolean][value][off]" name="form_cf['.$d->id.'][boolean][value]" value="N" class="'.$class.'" '.$off.'>
                    '.__("No", PLUGIN_NOM_LANG).'
                </label>
            </div>

            <input type="hidden" name="form_cf['.$d->id.'][boolean][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][boolean][default]" value="'.sanitize_text_field($d->preferences->defaultValue).'">
            <input type="hidden" name="form_cf['.$d->id.'][boolean][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][boolean][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][boolean][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * boolean : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkBoolean($d)
        {
            // INIT : default by api
            //$name     = $d['api']['label'];    // name field
            $default  = $d['api']['default'];    // default value
            $required   = $d['api']['required']; // required

            // INIT : form value
            $f_value    = $d['form']['value'];   // form value

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (empty($f_value)) {
                return array("success", $default);
            } elseif (!empty($f_value)) {
                return array("success", $f_value);
            }
            return false;
        }




        //---------------------------------------------------------------------
        // AMOUNT
        //---------------------------------------------------------------------

        /**
         * amount
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeAmount($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['amount']['value']) &&
                !empty($_POST['form_cf'][$d->id]['amount']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['amount']['value'];
            } elseif (isset($d->preferences->defaultValue) && !empty($d->preferences->defaultValue)) {
                $valueField = $d->preferences->defaultValue;
            }

            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            $t_accountPrefs = new models\TSellsyAccountPrefs();
            $responseAccountPrefs = $t_accountPrefs->getCurrencies();
            $options = [];
            foreach ($responseAccountPrefs->response as $vAccountPrefs) {
                if (isset($vAccountPrefs->id)) {
                    $selected = "";
                    if (!empty($valueField) && $valueField == $vAccountPrefs->id) {
                        $selected = "selected";
                    } elseif (isset($d->preferences->listId) && $d->preferences->listId == $vAccountPrefs->id) {
                        $selected = "selected";
                    }
                    $options[] = '<option value="'.$vAccountPrefs->id.'" '.$selected.'>'.$vAccountPrefs->symbol.'</option>';
                }
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>
            <input type="number" name="form_cf['.$d->id.'][amount][value]" min="'.$d->preferences->min.'" max="'.$d->preferences->max.'" value="'.ToolsController::formatNumber($valueField).'" step="any" class="'.$class.'" '.$isRequiredField.'>

            <label>'.__("Currency", PLUGIN_NOM_LANG).'</label>
            <select name="form_cf['.$d->id.'][amount][currencyid]" class="'.$class.'">
                <option value="">---- '.__("Select value", PLUGIN_NOM_LANG).' ----</option>
                '.implode("", $options).'
            </select>

            <input type="hidden" name="form_cf['.$d->id.'][amount][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][amount][default]" value="'.sanitize_text_field($d->preferences->defaultValue).'">
            <input type="hidden" name="form_cf['.$d->id.'][amount][defaultCurrency]" value="'.sanitize_text_field($d->preferences->listId).'">
            <input type="hidden" name="form_cf['.$d->id.'][amount][min]" value="'.$d->preferences->min.'">
            <input type="hidden" name="form_cf['.$d->id.'][amount][max]" value="'.$d->preferences->max.'">
            <input type="hidden" name="form_cf['.$d->id.'][amount][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][amount][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][amount][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * amount : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkAmount($d)
        {
            // INIT : default by api
            $name     = $d['api']['label'];    // name field
            $default  = $d['api']['default'];  // default value
            $min      = $d['api']['min'];      // min
            $max      = $d['api']['max'];      // max
            $required = $d['api']['required']; // required

            // INIT : form value
            $f_value  = $d['form']['value'];   // form value

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (empty($f_value) && isset($default) && !empty($default)) {
                return array( "success", $default );
            } elseif (empty($min) && empty($max)) {
                if (empty($f_value) && !empty($default)) {
                    return array("success", $default);
                }
                return array("success", $f_value);
            } elseif (!empty($min) && !empty($max)) {
                if ($f_value >= $min && $f_value <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value < $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".ToolsController::formatNumber($min));
                } elseif ($f_value > $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".ToolsController::formatNumber($max));
                }
            } elseif (!empty($min)) {
                if ($f_value >= $min) {
                    return array("success", $f_value);
                } elseif ($f_value < $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".ToolsController::formatNumber($min));
                }
            } elseif (!empty($max)) {
                if ($f_value <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value > $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".ToolsController::formatNumber($max));
                }
            }
            return false;
        }




        //---------------------------------------------------------------------
        // UNIT
        //---------------------------------------------------------------------

        /**
         * unit
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeUnit($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';
            $unitIdField     = '';
            $t_accountDatas  = new models\TSellsyAccountDatas();
            
            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['unit']['value']) &&
                !empty($_POST['form_cf'][$d->id]['unit']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['unit']['value'];
            } elseif (isset($d->preferences->defaultValue) && !empty($d->preferences->defaultValue)) {
                $valueField = $d->preferences->defaultValue;
            }

            if (isset($_POST['form_cf'][$d->id]['unit']['unitid']) &&
                !empty($_POST['form_cf'][$d->id]['unit']['unitid'])
            ) {
                $unitIdField = $_POST['form_cf'][$d->id]['unit']['unitid'];
            }



            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            $responseAccountDatas = $t_accountDatas->getUnits();
            $options = [];
            foreach ($responseAccountDatas->response as $vAccountDatas) {
                if (isset($vAccountDatas->id) && $vAccountDatas->isEnabled == 'Y' && $vAccountDatas->status == 'ok') {
                    $selected = "";
                    if (isset($unitIdField) && !empty($unitIdField) && $unitIdField == $vAccountDatas->id) {
                        $selected = "selected";
                    } elseif (isset($d->preferences->listId) && !empty($d->preferences->listId) && $d->preferences->listId == $vAccountDatas->id) {
                        $selected = "selected";
                    }
                    $options[] = '<option value="'.$vAccountDatas->id.'" '.$selected.'>'.$vAccountDatas->value.'</option>';
                }
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>
            <input type="number" name="form_cf['.$d->id.'][unit][value]" min="'.$d->preferences->min.'" max="'.$d->preferences->max.'" value="'.ToolsController::formatNumber($valueField).'" step="any" class="'.$class.'" '.$isRequiredField.'>

            <label>'.__("Unit", PLUGIN_NOM_LANG).'</label>
            <select name="form_cf['.$d->id.'][unit][unitid]" class="'.$class.'">
                <option value="">---- '.__("Select value", PLUGIN_NOM_LANG).' ----</option>
                '.implode("", $options).'
            </select>

            <input type="hidden" name="form_cf['.$d->id.'][unit][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][unit][default]" value="'.sanitize_text_field($d->preferences->defaultValue).'">
            <input type="hidden" name="form_cf['.$d->id.'][unit][min]" value="'.$d->preferences->min.'">
            <input type="hidden" name="form_cf['.$d->id.'][unit][max]" value="'.$d->preferences->max.'">
            <input type="hidden" name="form_cf['.$d->id.'][unit][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][unit][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][unit][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * unit : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkUnit($d)
        {
            // INIT : default by api
            $name     = $d['api']['label'];    // name field
            $default  = $d['api']['default'];  // default value
            $min      = $d['api']['min'];      // min
            $max      = $d['api']['max'];      // max
            $required = $d['api']['required']; // required

            // INIT : form value
            $f_value  = $d['form']['value'];   // form value

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (empty($f_value) && isset($default) && !empty($default)) {
                return array( "success", $default );
            } elseif (empty($min) && empty($max)) {
                if (empty($f_value) && !empty($default)) {
                    return array("success", $default);
                }
                return array("success", $f_value);
            } elseif (!empty($min) && !empty($max)) {
                if ($f_value >= $min && $f_value <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value < $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".ToolsController::formatNumber($min));
                } elseif ($f_value > $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".ToolsController::formatNumber($max));
                }
            } elseif (!empty($min)) {
                if ($f_value >= $min) {
                    return array("success", $f_value);
                } elseif ($f_value < $min) {
                    return array("error", __("Your value is too small", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".ToolsController::formatNumber($min));
                }
            } elseif (!empty($max)) {
                if ($f_value <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value > $max) {
                    return array("error", __("Your value is too big", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".ToolsController::formatNumber($max));
                }
            }
            return false;
        }




        //---------------------------------------------------------------------
        // RADIO
        //---------------------------------------------------------------------

        /**
         * radio
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeRadio($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['radio']['value']) &&
                !empty($_POST['form_cf'][$d->id]['radio']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['radio']['value'];
//          } elseif (isset($d->preferences->defaultValue) && !empty($d->preferences->defaultValue)) {
//              $valueField = $d->preferences->defaultValue;
            }

            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            $radios = "";
            foreach ($d->preferencesList as $vPreferenceList) {
                $checked = "";

                if (isset($valueField) && !empty($valueField) && $valueField == $vPreferenceList->value) {
                    $checked = "checked";
                } elseif ($vPreferenceList->isDefault == 'Y') {
                    $checked = "checked";
                }
                $radios .= '
                <label for="form_cf['.$d->id.'][radio][value]['.$vPreferenceList->id.']">
                    <input type="radio" 
                        id="form_cf['.$d->id.'][radio][value]['.$vPreferenceList->id.']" 
                        name="form_cf['.$d->id.'][radio][value]" 
                        value="'.esc_html($vPreferenceList->value).'" 
                        class="'.$class.'" '.$checked.'> '.esc_html($vPreferenceList->value).'
                </label>';
            }

            // field
            $field = '
            <label id="">'.$d->name.' '.$isRequiredLabel.'</label>
            '.$radios.'

            <input type="hidden" name="form_cf['.$d->id.'][radio][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][radio][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][radio][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][radio][required]" value="'.$isRequiredField.'">';

            return $field;
        }

        /**
         * radio : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkRadio($d)
        {
            // INIT : default by api
            $required = $d['api']['required']; // required

            // INIT : form value
            $f_value = $d['form']['value'];    // form value

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (!empty($f_value)) {
                return array("success", $f_value);
            }
            return false;
        }




        //---------------------------------------------------------------------
        // CHECKBOX
        //---------------------------------------------------------------------

        /**
         * checkbox
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeCheckbox($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            $checkbox = "";
            foreach ($d->preferencesList as $vPreferenceList) {
                $checked = "";
                $valueField = "";
                if (isset($_POST['form_cf'][$d->id]['checkbox']['value'][$vPreferenceList->id]) &&
                    !empty($_POST['form_cf'][$d->id]['checkbox']['value'][$vPreferenceList->id])
                ) {
                    $valueField = $_POST['form_cf'][$d->id]['checkbox']['value'][$vPreferenceList->id];
                }

                if (isset($valueField) && !empty($valueField) && $valueField == $vPreferenceList->value) {
                    $checked = "checked";
                } elseif ($vPreferenceList->isDefault == 'Y' && empty($_POST)) {
                    $checked = "checked";
                }
                $checkbox .= '
                <label for="form_cf['.$d->id.'][checkbox][value]['.$vPreferenceList->id.']">
                    <input type="checkbox" 
                        id="form_cf['.$d->id.'][checkbox][value]['.$vPreferenceList->id.']" 
                        name="form_cf['.$d->id.'][checkbox][value]['.$vPreferenceList->id.']" 
                        value="'.esc_attr($vPreferenceList->value).'" 
                        class="'.$class.'" '.$checked.'> '.esc_html($vPreferenceList->value).'
                </label>';
            }

            // field
            $field = '
            <label id="">'.$d->name.' '.$isRequiredLabel.'</label>
            '.$checkbox.'

            <input type="hidden" name="form_cf['.$d->id.'][checkbox][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][checkbox][min]" value="'.$d->preferences->min.'">
            <input type="hidden" name="form_cf['.$d->id.'][checkbox][max]" value="'.$d->preferences->max.'">
            <input type="hidden" name="form_cf['.$d->id.'][checkbox][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][checkbox][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][checkbox][required]" value="'.$isRequiredField.'">';

            return $field;
        }

        /**
         * checkbox : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkCheckbox($d)
        {
            // INIT : default by api
            $required = $d['api']['required']; // required
            $min      = (int)$d['api']['min']; // min
            $max      = (int)$d['api']['max']; // max

            // INIT : form value
            $f_value = $d['form']['value'];  // form value
            $f_value_size = count($f_value); // form value size

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (empty($min) && empty($max)) {
                return array("success", $f_value);
            } elseif ($min > 0 && $max > 0) {
                if ($f_value_size >= $min && $f_value_size <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value_size <= $min) {
                    return array("error", __("You must check more box", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$min);
                } elseif ($f_value_size >= $max) {
                    return array("error", __("You must check less box", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$max);
                }
            } elseif ($min > 0) {
                if ($f_value_size >= $min) {
                    return array("success", $f_value);
                } elseif ($f_value_size < $min) {
                    return array("error", __("You must check more box", PLUGIN_NOM_LANG).". ".__("Min", PLUGIN_NOM_LANG). " : ".$min);
                }
            } elseif ($max > 0) {
                if ($f_value_size <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value_size > $max) {
                    return array("error", __("You must check less box", PLUGIN_NOM_LANG).". ".__("Max", PLUGIN_NOM_LANG). " : ".$max);
                }
            }
            return false;
        }



        //---------------------------------------------------------------------
        // ITEM
        //---------------------------------------------------------------------

        /**
         * item
         * Note : string with min/max
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeItem($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['item']['value']) &&
                !empty($_POST['form_cf'][$d->id]['item']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['item']['value'];
            }
            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            $selectOption = "";
            $typeCatalogueInCF = [];
            $t_catalogue = new models\TSellsyCatalogue();
            if (isset($d->preferencesList)) {
                foreach ($d->preferencesList as $v) {
                    // product and/or service
                    if (isset($v->value) && !empty($v->value)) {
                        $typeCatalogueInCF[] = $v->value;
                    }
                }

                // getProduct and/or getService (only 100 first)
                if (!empty($typeCatalogueInCF)) {
                    $resCatalogs = $t_catalogue->getList100FirstProductAndService($typeCatalogueInCF);

                    foreach ($resCatalogs as $resCatalogTypeKey => $resCatalogTypeValue) {
                        if ($resCatalogTypeKey == "item") {
                            $tradLabel = __("Item", PLUGIN_NOM_LANG);
                        } elseif ($resCatalogTypeKey == "service") {
                            $tradLabel = __("Service", PLUGIN_NOM_LANG);
                        } else {
                            $tradLabel = "";
                        }

                        $selectOption .= "<optgroup label='".$tradLabel."'>";
                            foreach ($resCatalogTypeValue->response->result as $resCatalog) {
                                $selected = "";
                                if (isset($valueField) && !empty($valueField) && $valueField == $resCatalog->id) {
                                    $selected = "selected";
                                }

                                $selectOption .= "<option value='".$resCatalog->id."' ".$selected.">".$resCatalog->name."</option>";
                            }
                        $selectOption .= "</optgroup>";
                    }

                }
            }


            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>

            <select name="form_cf['.$d->id.'][item][value]" class="'.$class.'">
                <option value="">---- '.__("Select value", PLUGIN_NOM_LANG).' ----</option>
                '.$selectOption.'
            </select>

            <input type="hidden" name="form_cf['.$d->id.'][item][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][item][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][item][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][item][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * item : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkItem($d)
        {
            // INIT : default by api
            //$name     = $d['api']['label'];       // name field
            //$default  = $d['api']['default'];     // default value
            $required   = $d['api']['required'];    // required

            // INIT : form value
            $f_value    = $d['form']['value'];      // form value

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (!empty($f_value)) {
                return array("success", $f_value);
            }
            return false;
        }

        //---------------------------------------------------------------------
        // STAFF
        //---------------------------------------------------------------------

        /**
         * item
         * Note : string with min/max
         * @param $d object
         * @param $tbl_class array
         * @return string input
         */
        public function getTypeStaff($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['staff']['value']) &&
                !empty($_POST['form_cf'][$d->id]['staff']['value'])
            ) {
                $valueField = $_POST['form_cf'][$d->id]['staff']['value'];
            }
            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            $selectOption = "";
            $t_staff = new models\TSellsyStaffs();

            // getStaff (only 100 first)
            $resStaffs = $t_staff->getList100FirstStaff();

            foreach ($resStaffs->response->result as $resStaff) {
                $selected = "";
                if (isset($valueField) && !empty($valueField) && $valueField == $resStaff->id) {
                    $selected = "selected";
                }

                $selectOption .= "<option value='".$resStaff->id."' ".$selected.">".$resStaff->fullName."</option>";
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>

            <select name="form_cf['.$d->id.'][staff][value]" class="'.$class.'">
                <option value="">---- '.__("Select value", PLUGIN_NOM_LANG).' ----</option>
                '.$selectOption.'
            </select>

            <input type="hidden" name="form_cf['.$d->id.'][staff][name]" value="'.sanitize_text_field($d->name).'">
            <input type="hidden" name="form_cf['.$d->id.'][staff][min]" value="'.$d->preferences->min.'">
            <input type="hidden" name="form_cf['.$d->id.'][staff][max]" value="'.$d->preferences->max.'">
            <input type="hidden" name="form_cf['.$d->id.'][staff][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][staff][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            <input type="hidden" name="form_cf['.$d->id.'][staff][required]" value="'.$isRequiredField.'">
            ';

            return $field;
        }

        /**
         * item : check
         * @param array $d datas
         * @return array|bool error or success message
         */
        public function checkStaff($d)
        {
            // INIT : default by api
            //$name     = $d['api']['label'];       // name field
            //$default  = $d['api']['default'];     // default value
            $required   = $d['api']['required'];    // required

            // INIT : form value
            $f_value    = $d['form']['value'];      // form value

            // CHECK
            if ($required == 'required' && empty($f_value)) {
                return array("error", __("Field is required", PLUGIN_NOM_LANG));
            } elseif (!empty($f_value)) {
                return array("success", $f_value);
            }
            return false;
        }

    }//fin class
}//fin if
