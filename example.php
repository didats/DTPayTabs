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
// include the file
require 'DTPayTabs.php';

// create the object
$paytabs = new DTPayTabs();

if($paytabs->Auth("your-username", "your-password")) {
   // payment
   $paymentResult = $paytabs->Pay(array(
                     "cc_first_name" => "Didats",
                     "cc_last_name" => "Triadi",
                     "phone_number" => "123123123456",
                     "billing_address" => "TEST BILL ADDRESS",
                     "city" => "Malang",
                     "state"=> "TEST STATE",
                     "postal_code" => "12345",
                     "country" => "BHR",
                     "email" => "foo@bar.com",
                     "amount" => "224",
                     "discount" => "123.1"
                     "reference_no" => "ABC-123 "
                     "currency" => "BHD",
                     "title" => "TEST TITLE",
                     "ip_customer" => "1.1.1.0",
                     "ip_merchant" => "1.1.1.0",
                     "unit_price" => "12.21 || 21.20",
                     "quantity" => "2 || 3|| 1",
                     "address_shipping" => "Flat 3021 Manama Bahrain",
                     "state_shipping" => "Manama",
                     "city_shipping" => "Manama",
                     "postal_code_shipping" => "1234",
                     "country_shipping" => "BHR",
                     "products_per_title" => "MobilePhone||Charger||Camera",
                     "channelOfOperations" => "Physical Goods",
                     "Product Category" => "Electronics",
                     "ProductName" => "MobilePhone||Charger||Camera",
                     "ShippingMethod" => "Cash on Delivery",
                     "DeliveryType" => "FedEx",
                     "CustomerID" => "t12341112",
                     "msg_lang" => "English",
                     "return_url" => "Your site URL"
                     )
   );

   // you will get the data like this
   // array(
   //    'error' => 0, // 0 means no error. 1 means there is an error
   //    'paymentURL' => "", // this is the url you will need to redirect your user to
   //    'message' => "" // showed only when the error = 1.
   // );
   //
}


// if you wanted to verify the payment
$paytabs->Verify($payment_reference);
