<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');


/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    News
 * @license    LGPL
 * @filesource
 */

/**
 * Class mod_acquistoShop_AGB
 *
 * Front end module "mod_acquistoShop_AGB".
 * @copyright  Sascha Brandhoff 2011
 * @author     Sascha Brandhoff <http://www.contao-acquisto.org>
 * @package    Controller
 */

#define('API_ENDPOINT', 'https://api-3t.sandbox.paypal.com/nvp');
#define('PAYPAL_URL', 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=');

define('API_ENDPOINT', 'https://api-3t.paypal.com/nvp');
define('PAYPAL_URL', 'https://www.paypal.com/webscr&cmd=_express-checkout&token=');
define('USE_PROXY',FALSE);
define('VERSION', '53.0');

class paypal extends Module {
    protected $paymentName = 'Paypal';
    protected $strTemplate = 'cao_paypal';

    public function __construct()
    {
#        parent::__consturct();
    }

    public function getConfigData() {
        $arrConfig = array(
            'API_USERNAME' => array
            (
                'label'                   => array('API-Benutzername'),
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            ),
            'API_PASSWORD' => array
            (
                'label'                   => array('API-Passwort'),
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            ),
            'API_SIGNATURE' => array
            (
                'label'                   => array('API-Signatur'),
                'inputType'               => 'text',
                'search'                  => true,
                'eval'                    => array('mandatory'=>true, 'maxlength'=>64, 'tl_class'=>'w50')
            )
        );

        return $arrConfig;
    }

    public function getPaymentInfo() {
        return $this->paymentName;
    }

    public function generateForm($payment_id) {
        $arrConfig = $this->getConfig($payment_id);

        $paymentForm =
        '<form method="post" action="%s">
         <input type="hidden" name="FORM_SUBMIT" value="tl_acquistoShop_warenkorb">
         <input type="hidden" name="REQUEST_TOKEN" value="' . REQUEST_TOKEN . '">
         <input type="hidden" name="paymentType" value="Order" >
         <input type="hidden" value="%01.2f" name="paymentAmount">
         <input type="hidden" value="EUR" name="currencyCodeType">
         <input type="submit" value="Kaufen">
         </form>';

        if(is_array($arrConfig)) {
            foreach($arrConfig as $var => $val) {
                $paymentForm = str_replace("[__VAR_" . $var . "__]", $val, $paymentForm);
            }
        }

        return $paymentForm;
    }

    public function getConfig($payment_id) {
        $this->Import('Database');
        $arrConfig = $this->Database->prepare("SELECT * FROM tl_shop_zahlungsarten WHERE id = ?")->limit(1)->execute($payment_id);
        return unserialize($arrConfig->configData);
    }

    public function pay($payment_id) {
        $this->Import('Database');
        $this->Import('acquistoShop', 'Shop');

        $token = $_REQUEST['token'];

        if(! isset($token)) {
            $serverName = $_SERVER['SERVER_NAME'];
            $serverPort = $_SERVER['SERVER_PORT'];
            $url=dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);

            $paymentAmount=$_REQUEST['paymentAmount'];
            $currencyCodeType=$_REQUEST['currencyCodeType'];
            $paymentType=$_REQUEST['paymentType'];

            $objPageListe = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")->limit(1)->execute($this->replaceInsertTags($this->Shop->getInsertTagPID()));
            $strUrlListe  = $this->generateFrontendUrl($objPageListe->fetchAssoc(), '');

            $returnURL =urlencode($url. '/' . $strUrlListe . '?do=pay&currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType.'&paymentAmount='.$paymentAmount);
            $cancelURL =urlencode($url. '/' . $strUrlListe . '?do=pay&paymentType=$paymentType');

            $nvpstr="&Amt=".$paymentAmount."&PAYMENTACTION=".$paymentType."&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$currencyCodeType;

            $resArray=$this->hash_call("SetExpressCheckout",$nvpstr, $payment_id);
            $_SESSION['reshash']=$resArray;
            $ack = strtoupper($resArray["ACK"]);

            if($ack=="SUCCESS"){
                $token = urldecode($resArray["TOKEN"]);
                $payPalURL = PAYPAL_URL.$token;
                header("Location: ".$payPalURL);
            } else  {
                $location = "APIError.php";
                header("Location: $location");
            }
        }  else {
            $token =urlencode( $_REQUEST['token']);
            $nvpstr="&TOKEN=".$token;

            $resArray=$this->hash_call("GetExpressCheckoutDetails",$nvpstr, $payment_id);
            $_SESSION['reshash']=$resArray;
            $ack = strtoupper($resArray["ACK"]);

            if($ack=="SUCCESS") {
                // require_once "GetExpressCheckoutDetails.php";
                // Neu

                $_SESSION['token']=$_REQUEST['token'];
                $_SESSION['payer_id'] = $_REQUEST['PayerID'];

                $_SESSION['paymentAmount']=$_REQUEST['paymentAmount'];
                $_SESSION['currCodeType']=$_REQUEST['currencyCodeType'];
                $_SESSION['paymentType']=$_REQUEST['paymentType'];

                $resArray=$_SESSION['reshash'];

                $token =urlencode( $_SESSION['token']);
                $paymentAmount =urlencode ($_SESSION['paymentAmount']);
                $paymentType = urlencode($_SESSION['paymentType']);
                $currCodeType = urlencode($_SESSION['currCodeType']);
                $payerID = urlencode($_SESSION['payer_id']);
                $serverName = urlencode($_SERVER['SERVER_NAME']);

                $nvpstr='&TOKEN='.$token.'&PAYERID='.$payerID.'&PAYMENTACTION='.$paymentType.'&AMT='.$paymentAmount.'&CURRENCYCODE='.$currCodeType.'&IPADDRESS='.$serverName ;

                $resArray=$this->hash_call("DoExpressCheckoutPayment",$nvpstr,$payment_id);
                $ack = strtoupper($resArray["ACK"]);

                if($ack!="SUCCESS"){
                    $_SESSION['reshash']=$resArray;
                    $this->paymentState = false;
                    $location = "APIError.php";
                    header("Location: $location");
                } else {
                    $this->paymentState = true;
                    return true;
                }
            } else  {
                $this->paymentState = false;
                $location = "APIError.php";
                header("Location: $location");
            }
        }
    }

    public function compile()
    {

    }

    function hash_call($methodName,$nvpStr, $payment_id)
    {
        //declaring of global variables
        global $nvp_Header;
        $arrConfig = $this->getConfig($payment_id);

        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,API_ENDPOINT);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
       //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php
        if(USE_PROXY)
        curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT);

        //NVPRequest for submitting to server
        $nvpreq="METHOD=".urlencode($methodName)."&VERSION=".urlencode(VERSION)."&PWD=".urlencode($arrConfig['API_PASSWORD'])."&USER=".urlencode($arrConfig['API_USERNAME'])."&SIGNATURE=".urlencode($arrConfig['API_SIGNATURE']).$nvpStr;
    #    echo $nvpreq;
        //setting the nvpreq as POST FIELD to curl
        curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

        //getting response from server
        $response = curl_exec($ch);

        //convrting NVPResponse to an Associative Array
        $nvpResArray=$this->deformatNVP($response);
        $nvpReqArray=$this->deformatNVP($nvpreq);
        $_SESSION['nvpReqArray']=$nvpReqArray;

        if (curl_errno($ch)) {
            // moving to display page to display curl errors
              $_SESSION['curl_error_no']=curl_errno($ch) ;
              $_SESSION['curl_error_msg']=curl_error($ch);
              $location = "APIError.php";
              header("Location: $location");
         } else {
             //closing the curl
            curl_close($ch);
        }

        return $nvpResArray;
    }

/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
  * It is usefull to search for a particular key and displaying arrays.
  * @nvpstr is NVPString.
  * @nvpArray is Associative Array.
  */

    function deformatNVP($nvpstr) {
        $intial=0;
        $nvpArray = array();

        while(strlen($nvpstr)){
            //postion of Key
            $keypos= strpos($nvpstr,'=');
            //position of value
            $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

            /*getting the Key and Value values and storing in a Associative Array*/
            $keyval=substr($nvpstr,$intial,$keypos);
            $valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
            //decoding the respose
            $nvpArray[urldecode($keyval)] =urldecode( $valval);
            $nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
        }

        return $nvpArray;
    }
}

?>