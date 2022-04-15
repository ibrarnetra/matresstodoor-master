<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeNet extends Model
{
    function _authorizeAndCapture(
        $card_number = '',
        $card_exp = '',
        $card_cvv = '',
        $customer_first_name = '',
        $customer_last_name = '',
        $customer_company = '',
        $customer_address = '',
        $customer_country = '',
        $customer_state = '',
        $customer_city = '',
        $customer_postcode = '',
        $customer_id = '',
        $customer_email = '',
        $invoice_total = '',
        $invoice_no = '',
        $order_description = '',
        $mode = 'PRODUCTION'
    ) {
        $card_number =  preg_replace('/\s/', '', $card_number);
        /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(getConstant('API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(getConstant('TRANSACTION_KEY'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();

        ### PRODUCTION ###
        $creditCard->setCardNumber($card_number);
        $creditCard->setExpirationDate($card_exp);
        $creditCard->setCardCode($card_cvv);
        
  


        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create order information
        $order = new AnetAPI\OrderType();
        if ($mode == 'SANDBOX') {
            ### SANDBOX ###
            // $order->setInvoiceNumber("10101");
            // $order->setDescription("Golf Shirts");
            $order->setInvoiceNumber($invoice_no);
            $order->setDescription($order_description);
        } else {
            ### PRODUCTION ###
            $order->setInvoiceNumber($invoice_no);
            $order->setDescription($order_description);
        }

        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        if ($mode == 'SANDBOX') {
            ### SANDBOX ###
            // $customerAddress->setFirstName("Ellen");
            // $customerAddress->setLastName("Johnson");
            // $customerAddress->setCompany("Souveniropolis");
            // $customerAddress->setAddress("14 Main Street");
            // $customerAddress->setCity("Pecan Springs");
            // $customerAddress->setState("TX");
            // $customerAddress->setZip("44628");
            // $customerAddress->setCountry("USA");
            $customerAddress->setFirstName($customer_first_name);
            $customerAddress->setLastName($customer_last_name);
            $customerAddress->setCompany($customer_company);
            $customerAddress->setAddress($customer_address);
            $customerAddress->setCity($customer_city);
            $customerAddress->setState($customer_state);
            $customerAddress->setZip((isset($customer_postcode) && !is_null($customer_postcode)) ? $customer_postcode : '');
            $customerAddress->setCountry($customer_country);
        } else {
            ### PRODUCTION ###
            $customerAddress->setFirstName($customer_first_name);
            $customerAddress->setLastName($customer_last_name);
            $customerAddress->setCompany($customer_company);
            $customerAddress->setAddress($customer_address);
            $customerAddress->setCity($customer_city);
            $customerAddress->setState($customer_state);
            $customerAddress->setZip((isset($customer_postcode) && !is_null($customer_postcode)) ? $customer_postcode : '');
            $customerAddress->setCountry($customer_country);
        }

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        if ($mode == 'SANDBOX') {
            ### SANDBOX ###
            // $customerData->setId("99999456654");
            // $customerData->setEmail("EllenJohnson@example.com");
            $customerData->setId($customer_id);
            $customerData->setEmail($customer_email);
        } else {
            ### PRODUCTION ###
            $customerData->setId($customer_id);
            $customerData->setEmail($customer_email);
        }

        // Add values for transaction settings
        $duplicateWindowSetting = new AnetAPI\SettingType();
        $duplicateWindowSetting->setSettingName("duplicateWindow");
        $duplicateWindowSetting->setSettingValue("60");

        // Add some merchant defined fields. These fields won't be stored with the transaction,
        // but will be echoed back in the response.
        $merchantDefinedField1 = new AnetAPI\UserFieldType();
        if ($mode == 'SANDBOX') {
            ### SANDBOX ###
            // $merchantDefinedField1->setName("customerLoyaltyNum");
            // $merchantDefinedField1->setValue("1128836273");
            $merchantDefinedField1->setName($customer_first_name . " " . $customer_last_name);
            $merchantDefinedField1->setValue($customer_id);
        } else {
            ### PRODUCTION ###
            $merchantDefinedField1->setName($customer_first_name . " " . $customer_last_name);
            $merchantDefinedField1->setValue($customer_id);
        }

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        if ($mode == 'SANDBOX') {
            ### SANDBOX ###
            // $transactionRequestType->setAmount("99.99");
            $transactionRequestType->setAmount($invoice_total);
        } else {
            ### PRODUCTION ###
            $transactionRequestType->setAmount($invoice_total);
        }


        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setShipTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
        $transactionRequestType->addToUserFields($merchantDefinedField1);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        if ($mode == 'SANDBOX') {
            ### SANDBOX ###
            $response = $controller->executeWithApiResponse(getConstant('SANDBOX_ENDPOINT'));
        } else {
            ### PRODUCTION ###
            $response = $controller->executeWithApiResponse(getConstant('PRODUCTION_ENDPOINT'));
        }
    
      
        $res = [
            'status' => false,
            'data' => [],
            'message' => 'No Response'
        ];

        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $res = [
                        'status' => true,
                        'data' => [
                            'transaction_id' => $tresponse->getTransId(),
                            'transaction_code' => $tresponse->getResponseCode(),
                            'message_code' => $tresponse->getMessages()[0]->getCode(),
                            'auth_code' => $tresponse->getAuthCode(),
                            'description' => $tresponse->getMessages()[0]->getDescription(),
                        ],
                        'message' => 'Transaction Success'
                    ];
                } else {
                    if ($tresponse->getErrors() != null) {
                        $res = [
                            'status' => false,
                            'data' => [
                                'error_code' => $tresponse->getErrors()[0]->getErrorCode(),
                                'error_message' => $tresponse->getErrors()[0]->getErrorText(),
                            ],
                            'message' => 'Transaction Failed'
                        ];
                    }
                }
                // Or, print errors if the API request wasn't successful
            } else {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $res = [
                        'status' => false,
                        'data' => [
                            'error_code' => $tresponse->getErrors()[0]->getErrorCode(),
                            'error_message' => $tresponse->getErrors()[0]->getErrorText(),
                        ],
                        'message' => 'Transaction Failed'
                    ];
                } else {
                    $res = [
                        'status' => false,
                        'data' => [
                            'error_code' =>  $response->getMessages()->getMessage()[0]->getCode(),
                            'error_message' => $response->getMessages()->getMessage()[0]->getText(),
                        ],
                        'message' => 'Transaction Failed'
                    ];
                }
            }
        }

        return $res;
    }

    function refundTransaction()
    {
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(getConstant('API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(getConstant('TRANSACTION_KEY'));

        
        // Set the transaction's refId
        $refId = 'ref' . time();
    
        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber("4242424242424242");
        $creditCard->setExpirationDate("2024-01");
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);
        //create a transaction
        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("refundTransaction"); 
        $transactionRequest->setAmount('500');
        $transactionRequest->setPayment($paymentOne);
        $transactionRequest->setRefTransId('ref1640085700');
     
    
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest( $transactionRequest);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
        
        if ($response != null)
        {
          if($response->getMessages()->getResultCode() == "Ok")
          {
            $tresponse = $response->getTransactionResponse();
            
              if ($tresponse != null && $tresponse->getMessages() != null)   
            {
              echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
              echo "Refund SUCCESS: " . $tresponse->getTransId() . "\n";
              echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n"; 
                echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
            }
            else
            {
              echo "Transaction Failed \n";
              if($tresponse->getErrors() != null)
              {
                echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";            
              }
            }
          }
          else
          {
            echo "Transaction Failed \n";
            $tresponse = $response->getTransactionResponse();
            if($tresponse != null && $tresponse->getErrors() != null)
            {
              echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
              echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";                      
            }
            else
            {
              echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
              echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
            }
          }      
        }
        else
        {
          echo  "No response returned \n";
        }
    
        return $response;
      }



}
