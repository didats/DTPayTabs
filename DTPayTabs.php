<?php
/*
 *  Copyright (C) 2015
 *  Didats Triadi (http://didatstriadi.com)
 *  Version 0.1 copyright (C) 2015
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
require_once("curl.php");

class DTPayTabs {
   public $apiKey;
   private $curl, $userName, $userPassword, $postData, $baseURL, $failedURL;
   private $responsePayment, $responseVerify;

   /*
   Constructor
   Set some important variables
   */
   function __construct() {
      // set base url on every request
      $this->baseURL = "https://www.paytabs.com/api/";

      // you will need to specify the failedURL to show the error on the user side
      $this->failedURL = "http://google.com";

      // create curl
      $this->curl = new Curl;
      $this->curl->follow_redirects = false;

      // error message when creating payment page
      $this->responsePayment = array("Your payment has rejected", "Your payment has prepared", "PIN rejected, payment rejected", "PIN accepted, payment approved", "Payment is completed. 3D secure is also approved (if applicable)", "Unknown status", 10 => "Pay Page is created. User must go to the page to complete the payment.");

      // error message when verify the payment
      $this->responseVerify = array("The payment is rejected", 2=> "PIN Rejected", 6=> "Payment is completed. 3D secure is also approved (if applicable)", "Unknown Status");
   }

   /*
   Authentication
   */
   function Auth($username, $password) {
      $this->userName = $username;
      $this->userPassword = $password;

      // start asking for authentication
      $url = $this->baseURL . "authentication";
      $curlResult = $this->curl->post($url, array('merchant_id' => $this->userName, 'merchant_password' => $this->userPassword));
      $curlData = json_decode($curlResult->body);

      // access granted, return true
      if($curlData->access == "granted") {
         $this->apiKey = $curlData->api_key;

         return true;
      }
      else return false;
   }

   /*
   Payment
   The method need a postData variable as an array of data. The same data you will get on the documentation PDF file.
   */
   function Pay($postData) {
      $this->postData = $postData;

      $host= gethostname();
      $ip = gethostbyname($host);

      $this->postData['api_key'] = $this->apiKey;

      $curlResult = $this->curl->post($this->baseURL . "create_pay_page", $this->postData);
      $data = json_decode($curlResult->body);

      if(isset($data->payment_url)) {
         return array('error' => 0, 'paymentURL' => $data->payment_url);
      }
      else return array('error' => 1, 'paymentURL' => $this->failedURL, 'message' => $this->responsePayment[$data->response]);
   }

   /*
   Verify the payment
   Verify the result of payment by giving payment Reference
   */
   function Verify($paymentReference) {
      $curlResult = $this->curl->post($this->baseURL . "verify_payment", array('payment_reference' => $paymentReference, 'api_key' => $this->apiKey));
      $data = json_decode($curlResult->body);

      if(!isset($data->error_code)) {
         return array('error' => 0, 'message' => $data->result);
      }
      else {
         return array('error' => 1, 'message' => $this->responseVerify[$data->response]);
      }
   }

}
