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

    require_once('request.inc.php');
    require_once('config.inc.php');

    class Offer {
        private $id;
        private $label;
        private $canteen;
        private $price;
        private $date;
        
        /**
         * TODO
         * Support for Price, Rating, Date, Votes, Expires
         **/
        
        function __construct($id, $label, $price = null, $canteen) {
            $this->id = $id;
            $this->label = $label;
            $this->canteen = $canteen;
        }
                                
        public function getLabel() {
            return $this->label;
        }
		
        public function getCanteen() {
            return $this->canteen;
        }
        
        public function setLabel($label) {
            $this->label = $label;
        }
        
        public function servedIn() {
            return $this->canteen;
        }
        
        public function getId() {
            return $this->id;
        }
	
        /**
         * Rate an Offer
         *
         * @param   User        $user       The user that rates the offer
         * @param   Offer       $offer      The offer that will be rated
         * @param   Integer     $rating     The Rating [0..10]
         *
         * @return  Boolean                 True on success otherwise false
         *
         **/
        function rateOffer($user, $rating) {
            if($user instanceof User && $rating instanceof Integer) {
            
                $path = "/rating";
                
                $option = array("userId"    => $user->getId(),
                                "offerId"   => $this->id,
                                "rating"    => $rating);
                
                $response = doRequest($path, true, "post", json_encode($option));
                
                return ($response['status'] == CREATED);
            }
            
            return false;
        }
    }
?>