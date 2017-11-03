<?php
// app/code/local/Tonussi/BoletoPayment/Helper/Data.php
class Tonussi_BoletoPayment_Helper_Data extends Mage_Core_Helper_Abstract
{
  function getPaymentGatewayUrl()
  {
    return Mage::getUrl('boletopayment/payment/gateway', array('_secure' => false));
  }
}
