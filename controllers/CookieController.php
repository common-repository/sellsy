<?php

namespace com\sellsy\sellsy\controllers;

use com\sellsy\sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('CookieController')) {
    class CookieController
    {
        private $name;
        private $delay;

        public function __construct($name)
        {
            $this->name    = $name;
            $this->delay   = time()+60*60*24*30;
        }

        /**
         * Exec cookie : add or update
         */
        public function exec()
        {
            // if not cookie, add it empty
            if (!isset($_COOKIE[$this->name])) {
                $this->add();
            }
            $this->update();
        }

        /**
         * Add cookie (empty)
         * @return bool
         */
        public function add()
        {
            if (setcookie($this->name, "", $this->delay, "/")) {
                return true;
            }
            return false;
        }

        /**
         * Get data cookie
         * @return bool|array false or cookie
         */
        public function get()
        {
            if (!isset($_COOKIE[$this->name])) {
                return false;
            }

            $cookie = stripslashes($_COOKIE[$this->name]);

            // check cookie isn't json, delete
            if (!ToolsController::isJson($cookie)) {
                $this->delete();
                return false;
            }
            if (isset($cookie) && !empty($cookie)) {
                return stripslashes($cookie);
            }
            return false;
        }

        /**
         * Update data cookie
         * @return true, false
         */
        public function update()
        {
            // INIT
            $url = ToolsController::getUrl();
            $dateTime = new \DateTime();

            // check cookie isn't json, delete
            if (isset($_COOKIE[$this->name]) && !ToolsController::isJson($_COOKIE[$this->name])) {
                $this->delete();
                return false;
            }

            // if not 1st update
            if (isset($_COOKIE[$this->name]) && !empty($_COOKIE[$this->name])) {
                $res_decode = json_decode(stripslashes($_COOKIE[$this->name]), true);

                if (sizeof($res_decode) > SELLSY_COOKIE_MAX) {
                    $diff = sizeof($res_decode) - SELLSY_COOKIE_MAX;
                    $res_decode = array_slice($res_decode, $diff);
                }
            }

            // add
            $res_decode[$dateTime->getTimestamp()] = $url;
            // encode
            $res_encode = json_encode($res_decode);

            // save
            if (setcookie($this->name, $res_encode, $this->delay, "/")) {
                return true;
            }
        }

        /**
         * Delete cookie
         * @return bool
         */
        public function delete()
        {
            unset($_COOKIE[$this->name]);
            if (setcookie($this->name, "", time() - 3600, "/")) {
                return true;
            }
            return false;
        }

        /**
         * Create array for tracking by API
         * @return bool|array false or cookie
         */
        public function datasForTracking()
        {
            // INIT
            $cookieTracking = array();
            // GET
            $cookieDatas = $this->get();
            $cookieDatas = json_decode($cookieDatas, true);
            // ERROR
            if (false === $cookieDatas || null === $cookieDatas) {
                return false;
            }

            // STOCK
            if (isset($cookieDatas) && !empty($cookieDatas)) {
                foreach ($cookieDatas as $kC => $vC) {
                    $cookieTracking[] = array(
                        'type'        => "url",
                        'url'        => $vC,
                        'timestamp' => $kC,
                    );
                }
            }
            return $cookieTracking;
        }

        /**
         * Get last value in cookie
         * @return false or $response
         */
        public function getLast()
        {
            $cookieUtm = new CookieController(SELLSY_COOKIE_UTM);
            $cookieUtmDatas = $cookieUtm->datasForTracking();
            if ($cookieUtmDatas) {
                return end($cookieUtmDatas);
            }
            return false;
        }

    }//fin class
}//fin if
