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
    
    require_once("config.inc.php");
    require_once("request.inc.php");

    class Canteen {       
        private $canteenId;
        private $label;
        private $city;
        private $location;
        private $creator;
        private $offers;
        
        function __construct($canteenId, $label, $city, $location) {
                        
            $this->canteenId = $canteenId;
            $this->label     = $label;
            $this->city      = $city;
            $this->location  = $location;
            $this->offers    = array();
   
        }
        
        public function getLabel() {
            return $this->label;
        }
        
        public function getId() {
            return $this->canteenId;
        }
        
        public function getCreator() {
            return $this->creator;
        }
        
        public function getOffers() {
            $path = "/canteen/".$this->canteenId;
            
            $response = doRequest($path);
                        
            if($response['status'] == OK) {
                $canteen = json_decode($response['content']);
                
                var_dump($canteen->offers);
                
                if(sizeof($canteen->offers) > 0) {
                
                    foreach($canteen->offers[0] as $offer) {
                        
                        $newOffer = new Offer($offer->id, $offer->label, $offer->price, $this);
                        
                        array_push($this->offers, $newOffer);
                    }
                    
                    return $this->offers;
                }
            }         
                  
            return array();
        }
    }
?>