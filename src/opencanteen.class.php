<?php
    /**
     * Copyright (c) 2009
     * Philipp Giese, Frederik Leidloff
     *
     * Permission is hereby granted, free of charge, to any person obtaining a
     * copy of this software and associated documentation files (the "Software"),
     * to deal in the Software without restriction, including without limitation
     * the rights to use, copy, modify, merge, publish, distribute, sublicense,
     * and/or sell copies of the Software, and to permit persons to whom the
     * Software is furnished to do so, subject to the following conditions:
     *
     * The above copyright notice and this permission notice shall be included in
     * all copies or substantial portions of the Software.
     *
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
     * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
     * DEALINGS IN THE SOFTWARE.
     **/

    require_once("request.inc.php");
    require_once("config.inc.php");
    
    require_once("canteen.class.php");
    require_once("offer.class.php");
    require_once("application.class.php");

    class OpenCanteen {
        private $application = null;
        
        function __construct() {
            if(APIKEY != "" && APPLICATIONSECRET != "") {
                $this->application = new Application(APIKEY, APPLICATIONSECRET);
            }
        }
        
        /**
         * Get a quick overview of all canteens that are currently available in the
         * system
         *
         * @return  Array<Canteen>  An array filled with Canteen-objects
         *                          Have a look into canteen.class.php for
         *                          further information
         *
         **/
        public static function getCanteens() {
            $path = "/canteen";
            
            $response = doRequest($path);         
            
            if($response['status'] == OK) {
                
                $canteensAsJson = '{"canteens":'.$response['content'].'}';                
                $canteensAsJson = json_decode($canteensAsJson);
                
                $canteens = array();

              
                foreach($canteensAsJson->canteens as $canteen) {

                    $canteen = new canteen($canteen->id, $canteen->label, $canteen->city, $canteen->location);
                    array_push($canteens, $canteen);
                }
                
                
                return $canteens;
            }
            
            return array();
        }
        
        /**
         * Get a specific canteen and the offers that are currently served
         *
         * @param   Integer $id The ID of the canteen one is looking for
         *
         * @return  Canteen     The specific canteen
         *
         **/
        public static function getCanteen($id) {
            
            $path = "/canteen/".$id;
            
            $response = doRequest($path);

            if($response['status'] == OK) {
                
                $canteen = json_decode($response['content']);
        
                $canteen = new Canteen($canteen->id, $canteen->label, $canteen->city, $canteen->location);                
                                 
                return $canteen;
            }
            
            return null;
        }
        
        /**
         * Get all Offers, that have not expired. Thus all offers
         * that are available today and in the future
         *
         * @return  array<Offer>    List of offers
         *
         **/
        public static function getOffers() {
            $path = "/offer";
            
            $response = doRequest($path);
            
            if($response['status'] == OK) {
                
                $offersAsJson = '{"offers":'.$response['content'].'}';
                $offersAsJson = json_decode($offersAsJson);
                
                $offers = array();
                
                foreach($offersAsJson->offers as $offer) {
                    $canteen = self::getCanteen($offer->canteenId);
                    
                    array_push($offers, new Offer($offer->id, $offer->label, $offer->price, $canteen));
                }
                
                return $offers;
            }
            
            return array();
        }
        
        /**
         * Get a specific offer with id $id
         *
         * @param   Integer     $id     The ID of the Offer
         *
         * @return  Offer               The specific Offer or null if
         *                              if no Offer was found
         **/
        public static function getOffer($id) {
            $path = "/offer/".$id;
            
            $response = doRequest($path);
            
            if($response['status'] == OK) {
                
                $offer   = json_decode($response['content']);                                
                $canteen = self::getCanteen($offer->canteenId);
                
                return new Offer($offer->id, $offer->label, $offer->price, $canteen);       
                
            }
            
            return null;
        }
        
        /**
         * Register a new Application for the Service
         *
         * @param   String  $appSecret  Your wish for your application Secret
         *
         * @return  String              Your new API-Key
         *
         **/
        function registerApp($appSecret, $callbackUri = "") {
            $path = "/app";
            
            $option = array("applicationSecret" => $appSecret,
                            "callbackUri"       => $callbackUri);
            
            $response = doRequest($path, null, "post", json_encode($option));
            
            if($response['status'] == CREATED) {
                $json = json_decode($response['content']);
                
                //TODO: Store the API-Key we get
                
                $this->application = new Application();
                return $this->application;
            }
            
            return null;
        }
        
        /**
         * Getter for Application
         *
         * @return  Application
         *
         **/
        function getApplication() {
            return $this->application;
        }
    }
?>