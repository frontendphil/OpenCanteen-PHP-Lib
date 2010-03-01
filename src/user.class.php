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
    
    class User {
        private $id;
        private $canteens;
        
        function __construct($id) {
            $this->id = $id;
        }
        
        function getId() {
            return $this->id;
        }
        
        /**
         * Stores that a User has eaten an Offer
         *
         * @param   User    $user   User-Object
         * @param   Offer   $offer  Offer-Object
         *
         * @return  Bool            true on success otherwise
         *                          false
         **/
        function eatOffer($offer) {
            if($offer instanceof Offer) {
                $path = "/user/".$this->id."/offer/".$offer->getId();
                
                $response = doRequest($path, true, 'post');
                
                return ($response['status'] == OK);
            }
            
            return false;
        }
        
        /**
         * Deletes a relation between a user and an Offer
         *
         * @param   Offer   $offer  The Offer-Object to spit-out
         *
         * @return  Bool            true on success otherwise
         *                          false
         **/
        function spitOutOffer($offer) {
            if($offer instanceof Offer) {
                $path = "/user/".$this->id."/offer/".$offer->getId();
                
                $response = doRequest($path, true, 'delete');
                
                return ($response['status'] == OK);
            }
            
            return false;
        }
        
        /**
         * Adds a Canteen to the Users Favorites
         *
         * @param   Canteen     $canteen    The Canteen-Object to add
         *
         * @return  Bool                    true on success otherwise
         *                                  false
         **/
        function addCanteen($canteen) {
            if($canteen instanceof Canteen) {
                $path = "/user/".$this->id."/canteen/".$canteen->getId();
                
                $response = doRequest($path, true, 'post');
                                
                return ($response['status'] == CREATED);
            }
            
            return false;
        }
        
        /**
         * Removes a Canteen to the Users Favorites
         *
         * @param   Canteen     $canteen    The Canteen-Object to add
         *
         * @return  Bool                    true on success otherwise
         *                                  false
         **/
        function removeCanteen($canteen) {
            if($canteen instanceof Canteen) {
                $path = "/user/".$this->id."/canteen/".$canteen->getId();                
                
                $response = doRequest($path, true, 'delete');
                
                return ($response['status'] == CREATED);
            }
            
            return false;
        }
        
        /**
         * Get all Favorite Canteens of a User
         *
         * @return  Array<Canteen>  List of Canteens
         *                                  
         **/
        function getCanteens() {
            $path = "/user/".$this->id;
            
            $response = doRequest($path, true);
            
            if($response["status"] == OK) {
                
                $user = json_decode($response["content"]);

                $canteens = array();
                                    
                foreach($user->canteens as $canteen) {
                    array_push($canteens, new Canteen($canteen->id, $canteen->label, $canteen->city, $canteen->location));
                }
                
                return $canteens;

            }
            
            return array();
        }
        
        function requestLink() {
            $path = "/user/".$this->id."/linkkey";
            
            $response = doRequest($path, true);
            
            if($response['status'] == OK) {
                $json = json_decode($response['content']);
                
                return $json->linkKey;
            }
            
            return "";
        }
        
        function link($key) {
            $path = "/user/".$this->id."/link";
            
            $response = doRequest($path, true, "post");
            
            if($response['status'] == CREATED) {
                return true;
            }
            
            return false;
        }
        
        function unlink() {
            $path = "/user/".$this->id."/link";
            
            $response = doRequest($path, true, "delete");
            
            if($response['status'] == OK) {
                return true;
            }
            
            return false;
        }
    }
?>