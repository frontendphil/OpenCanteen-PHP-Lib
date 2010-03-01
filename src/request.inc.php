<?php
	/**
	 * Copyright (c) 2009
	 * Philipp Giese, Frederik Leidloff, Matthias Quasthoff
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

	/**
	 * Performs a simple request
	 * 
	 * @param   array   $url	Has to contain to fields
	 *							  1. The hostname
	 *							  2. The pathname
	 *
	 * @param   string  $auth   Some features require basic
	 *						  authentication. So this is the
	 *						  place, where the key for the
	 *						  Authorization-Header has its
	 *						  place
	 *
	 * @param   string  $method The request method
	 *							  1. GET (standard)
	 *							  2. POST
	 *							  3. UPDATE
	 *							  4. DELETE
	 *							  5. OPTION
	 *
	 * @param   string  $data   Request-body
	 *
	 * @return  json	$buf	Response
	 **/
    
	require_once("config.inc.php");
	
	function doRequest($path, $auth=false, $method="GET", $data="") {  

		$host = HOST;
		$s = curl_init($host . $path);
	
		switch(strtoupper($method)) {
			case "GET":
                curl_setopt($s, CURLOPT_HTTPGET, true);
				break;
			case "POST":
                curl_setopt($s, CURLOPT_POST, true);
				break;
			case "PUT":
                curl_setopt($s, CURLOPT_PUT, true);
				break;
            case "DELETE":
                curl_setopt($s, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
		}		
		
		$headers = array();
		if($auth) {
			curl_setopt($s, CURLOPT_USERPWD, APIKEY.":".APPLICATIONSECRET);
		}

		if (strtoupper($method)=="POST") {
			$headers = array("Content-Type: text/json");
			curl_setopt($s, CURLOPT_POSTFIELDS, $data);
		}

		curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true);		

		$r = curl_exec($s);
		$status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        curl_close($s);
		
		$response = array(
			"header" => "",
			"content" => $r,
			"status" => $status,
		);

		return $response;
	}
?>
