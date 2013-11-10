<?php
use PayPal\CoreComponentTypes\BasicAmountType;
use PayPal\EBLBaseComponents\AddressType;
use PayPal\EBLBaseComponents\BillingAgreementDetailsType;
use PayPal\EBLBaseComponents\PaymentDetailsItemType;
use PayPal\EBLBaseComponents\PaymentDetailsType;
use PayPal\EBLBaseComponents\SetExpressCheckoutRequestDetailsType;
use PayPal\EBLBaseComponents\DoExpressCheckoutPaymentRequestDetailsType;
use PayPal\PayPalAPI\SetExpressCheckoutReq;
use PayPal\PayPalAPI\SetExpressCheckoutRequestType;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentReq;
use PayPal\PayPalAPI\DoExpressCheckoutPaymentRequestType;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsReq;
use PayPal\PayPalAPI\GetExpressCheckoutDetailsRequestType;
use PayPal\IPN\PPIPNMessage;
use PayPal\PayPalAPI\GetTransactionDetailsReq;
use PayPal\PayPalAPI\GetTransactionDetailsRequestType;
use PayPal\Service\PayPalAPIInterfaceServiceService;
require_once UDS_PATH . 'vendor/autoload.php';
/**
 * UDSSL PayPal client
 */
class UDSSL_PayPal_Client{
    /**
     * Mode
     */
    public $mode;

    /**
     * URLs
     */
    public $url, $return_url, $cancel_url, $notify_url;

    /**
     * Allow IPNotify
     */
    public $allow_ipn = true;

    /**
     * Config
     */
    private $config;

    /**
     * Construct the client
     */
    function __construct(){
        $this->setup();
    }

    /**
     * Set up the client
     */
    function setup(){
        $options = get_option('udssl_store_options');
        $options = $options['paypal']['paypal_classic'];

        if('true' == $options['sandbox_mode']){
            $this->mode = 'sandbox';
            $this->url  = 'https://www.sandbox.paypal.com/';
        } else {
            $this->mode = 'live';
            $this->url  = 'https://www.paypal.com/';
        }

        $this->config = array(
           'mode'            => $this->mode,
           'acct1.UserName'  => $options['username'],
           'acct1.Password'  => $options['password'],
           'acct1.Signature' => $options['signature']
       );

        $this->return_url = home_url() . '/store/download/';
        $this->cancel_url = home_url() . '/store/cart/';
        $this->notify_url = home_url() . '/ipn/';
    }

    /**
     * PayPal SetExpressCheckout
     */
    function setCheckout($cart, $total){
        $item = $cart[0];

        $items = array();
        foreach($cart as $item){
            /**
             * Item
             */
            $itemDetails           = new PaymentDetailsItemType();
            $itemDetails->Name     = $item['name'];
            $itemDetails->Amount   = $item['rate'];
            $itemDetails->Quantity = $item['quantity'];
            $itemDetails->Number   = $item['transaction_id'];
            $items[] = $itemDetails;
        }

        /**
         * Payment
         */
        $paymentDetails                        = new PaymentDetailsType();
	    $paymentDetails->PaymentDetailsItem    = $items;

        /**
         * Request Details
         */
        $setECReqDetails                    = new SetExpressCheckoutRequestDetailsType();
        $setECReqDetails->PaymentDetails[0] = $paymentDetails;
        $setECReqDetails->InvoiceID         = 'UDSSL_Invoice_' . date('Y_m_d_H_i_s', current_time('timestamp'));
        $setECReqDetails->CancelURL         = $this->cancel_url;
        $setECReqDetails->ReturnURL         = $this->return_url;

        $setECReqDetails->NoShipping         = 1;
        $setECReqDetails->AddressOverride    = 0;
        $setECReqDetails->ReqConfirmShipping = 0;
        $setECReqDetails->AllowNote          = 1;
        $setECReqDetails->OrderTotal         = new BasicAmountType('USD', $total);

        /**
         * Request Type
         */
        $setECReqType = new SetExpressCheckoutRequestType();
        $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;

        /**
         * Request
         */
        $setECReq = new SetExpressCheckoutReq();
        $setECReq->SetExpressCheckoutRequest = $setECReqType;

        $paypalService = new PayPalAPIInterfaceServiceService($this->config);

        try {
            $setECResponse = $paypalService->SetExpressCheckout($setECReq);
        } catch (Exception $ex) {
            wp_die('PayPal SetExpressCheckout Error', 'udssl');
        }

        if(isset($setECResponse)) {
            if($setECResponse->Ack =='Success') {
                $token = $setECResponse->Token;
                $payPalURL = $this->url . 'webscr?cmd=_express-checkout&token=' . $token . '&useraction=commit';
                global $udssl_theme;
                $udssl_theme->store->database->paypal_set_record($cart, $token);
                wp_redirect($payPalURL);
                exit;
            }
        }

        wp_die('PayPal SetExpressCheckout Error', 'udssl');
    }

    /**
     * PayPal getExpressCheckout
     */
    function getCheckoutDetails($payerId, $token){
        $token   = urlencode($token);
        $payerId = urlencode($payerId);

        $getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);
        $getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
        $getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;

        $paypalService = new PayPalAPIInterfaceServiceService($this->config);
        try {
            $getECResponse = $paypalService->GetExpressCheckoutDetails($getExpressCheckoutReq);
        } catch (Exception $ex) {
            wp_die('PayPal GetExpressCheckoutDetails Error', 'udssl');
        }

        if(isset($getECResponse)) {
            if($getECResponse->Ack =='Success' && $getECResponse->GetExpressCheckoutDetailsResponseDetails->CheckoutStatus != 'PaymentActionCompleted') {
                $getEC = $getECResponse->GetExpressCheckoutDetailsResponseDetails;
                return $getEC;
            }
        }
        return false;
    }

    /**
     * PayPal DoExpressCheckout
     */
    function doCheckout($payerId, $token, $value){
        $token = urlencode($token);
        $payerId = urlencode($payerId);

        $orderTotal = new BasicAmountType();
        $orderTotal->currencyID = 'USD';
        $orderTotal->value = $value;

        $paymentDetails= new PaymentDetailsType();
        $paymentDetails->OrderTotal = $orderTotal;

        $paymentAction = 'Sale';

        $DoECRequestDetails                    = new DoExpressCheckoutPaymentRequestDetailsType();
        $DoECRequestDetails->PayerID           = $payerId;
        $DoECRequestDetails->Token             = $token;
        $DoECRequestDetails->PaymentAction     = $paymentAction;
        $DoECRequestDetails->PaymentDetails[0] = $paymentDetails;

        $DoECRequest = new DoExpressCheckoutPaymentRequestType();
        $DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;

        $DoECReq = new DoExpressCheckoutPaymentReq();
        $DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

        $paypalService = new PayPalAPIInterfaceServiceService($this->config);
        try {
            $DoECResponse = $paypalService->DoExpressCheckoutPayment($DoECReq);
        } catch (Exception $ex) {
            wp_die('PayPal DoExpressCheckout Error', 'udssl');
        }

        if(isset($DoECResponse)){
            if($DoECResponse->Ack =='Success'){
                return true;
            }
        } else {
            wp_die('PayPal DoExpressCheckout Error', 'udssl');
        }
        return true;
    }

    /**
     * PayPal IPN Message
     */
    function ipnMessage(){
        $ipnMessage = new PPIPNMessage(null, $this->config);

        if($ipnMessage->validate()) {
            $raw_data = $ipnMessage->getRawData();
            return $raw_data;
        } else {
            wp_die('PayPal IPN Message Error', 'udssl');
        }
    }

    /**
     * PayPal getTxnDetails
     */
    function getTxnDetails($txn_id){
        $transactionDetails = new GetTransactionDetailsRequestType();

        /*
         * Unique identifier of a transaction.
        */
        $transactionDetails->TransactionID = $txn_id;

        $request = new GetTransactionDetailsReq();
        $request->GetTransactionDetailsRequest = $transactionDetails;

        $paypalService = new PayPalAPIInterfaceServiceService($this->config);

        try {
            $transDetailsResponse = $paypalService->GetTransactionDetails($request);
        } catch (Exception $ex) {
            wp_die('PayPal getTxnDetails Error', 'udssl');
        }
        if(isset($transDetailsResponse)) {
            if($transDetailsResponse->Ack =='Success'){
                return $transDetailsResponse;
            }
        } else {
            wp_die('PayPal getTxnDetails Error', 'udssl');
        }
    }

    /**
     * PayPal SetExpressCheckout Subscription
     */
    function setCheckoutSubscribe(){
    }

    /**
     * PayPal Subscribe
     */
    function subscribe(){
        $returnURL = home_url() . '/success/';
        $cancelURL = home_url();

        $scope = array();
        $scope[] = 'EXPRESS_CHECKOUT';

        $requestEnvelope          = new RequestEnvelope("en_US");
        $request                  = new RequestPermissionsRequest($scope, $returnURL);
        $request->requestEnvelope = $requestEnvelope;

        $service = new PermissionsService($this->config);

        try {
            $response = $service->RequestPermissions($request);
        } catch(Exception $ex) {
            wp_die('PayPal Subscription Permissions Error', 'udssl');
        }

        echo "<pre>";
        print_r($response);
        echo "</pre>";
        exit;

        if(strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
            $token = $response->token;
            echo $token;
            exit;
        } else {
            wp_die('PayPal Subscription Permissions Error', 'udssl');
        }
    }
}
?>
