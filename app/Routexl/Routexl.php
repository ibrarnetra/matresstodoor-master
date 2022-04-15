<?php

/*
 * Copyright (c) 2015-2020, RouteXL
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * 1. Redistributions of source code must retain the above copyright notice, 
 * this list of conditions and the following disclaimer.
 * 
 * 2. Redistributions in binary form must reproduce the above copyright notice, 
 * this list of conditions and the following disclaimer in the documentation 
 * and/or other materials provided with the distribution.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE 
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR 
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES 
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; 
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON 
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS 
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
*/

namespace App\Routexl;

use Exception;

/**
 * RouteXL API Connector
 * @Package RouteXL
 * @Subpackage API
 * @Version 1.0
 */
class Routexl
{
   public $res = ['status' => true, 'message' => 'Success', 'data' => ''];
   public $http_code = 0;
   public $error = '';
   public $output;
   /**
    * Optimize an itinerary with multiple destinations
    * @param array locations Locations to be routed
    * @return bool Success 
    */
   public function tour($locations)
   {
      try {
         // Use libcurl to connect and communicate
         $ch = curl_init(); // Initialize a cURL session
         curl_setopt($ch, CURLOPT_URL, 'https://api.routexl.com/tour'); // Set the URL
         curl_setopt($ch, CURLOPT_HEADER, 0); // No header in the output
         curl_setopt($ch, CURLOPT_POST, 1); // Do a regular HTTP POST
         curl_setopt($ch, CURLOPT_POSTFIELDS, 'locations=' . json_encode($locations)); // Add the locations
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the output as a string
         curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate'); // Compress
         curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // Basic authorization
         curl_setopt($ch, CURLOPT_USERPWD, '' . getConstant("ROUTEXL_USERNAME") . ':' . getConstant("ROUTEXL_PASSWORD") . ''); // Your credentials

         // Execute the given cURL session
         $this->output = curl_exec($ch); // Get the output
         $this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Last received HTTP code
         $this->error = curl_error($ch); // Get the last error
         curl_close($ch); // Close the connection
      } catch (Exception $e) {
         $this->error = $e->getMessage();
      }

      if ($this->http_code == 200) {
         $this->res['data'] = json_decode($this->output);
      } else {
         $this->res['status'] = false;
         $this->res['message'] = $this->error;
         $this->res['data'] = $this->output;
      }
      return json_encode($this->res);
   }
}
