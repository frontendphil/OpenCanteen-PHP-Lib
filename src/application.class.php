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
    require_once("user.class.php");    
    
    class Application {
            
        /**
         * Get a specific User
         *
         * @param   Integer     $id     ID of the User
         *
         * @return  User                User-Object or null if
         *                              no User was found
         **/
        function getUser($id) {
            $path = "/user/".$id;
            
            $response = doRequest($path, true);
            
            if($response['status'] == OK) {
                $json = json_decode($response['content']);
                
                $user = new User($json->id);
                
                return $user;
            }
            
            return null;
        }
        
        /**
         *  Get all Users that are associated with the own
         *  Application
         *
         *  @return     Array<User>     List of User-Objects
         *
         **/
        function getAllUsers() {
            $path = "/user";
                        
            $response = doRequest($path, true);
            
            if($response['status'] == OK) {
                $usersJSON = json_decode($response['content']);
                
                $users = array();
                                
                foreach($usersJSON as $user) {
                    array_push($users, new User($user->id));
                }

                return $users;
            }
            
            return array();
        }
        
        /**
         * Create a new User
         *
         * @param   String  $id     Wish-ID of the new User-Object
         *
         * @return  User            User-Object of created User or
         *                          null if something went wrong
         **/
        function createUser($id) {
            $path = "/user";
            
            $json = array("id" => $id);
            
            $response = doRequest($path, true, 'post', json_encode($json));

            if($response['status'] == CREATED) {
                return $this->getUser($id);
            }
            
            return null;
        }
    }
?>