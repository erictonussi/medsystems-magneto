<?php
//boleto_sankhya_id
//boleto_descricao

// app/code/local/Tonussi/BoletoPayment/Model/Paymentmethod.php
class Tonussi_BoletoPayment_Model_Paymentmethod extends Mage_Payment_Model_Method_Abstract {
  protected $_code  = 'boletopayment';
  protected $_formBlockType = 'boletopayment/form_boletopayment';
  protected $_infoBlockType = 'boletopayment/info_boletopayment';

  public function assignData($data)
  {
    $info = $this->getInfoInstance();

    if ($data->getBoletoSankhyaId())
    {
      $info->setBoletoSankhyaId($data->getBoletoSankhyaId());
    }

    if ($data->getBoletoDescricao())
    {
      $info->setBoletoDescricao($data->getBoletoDescricao());
    }

    return $this;
  }

  public function validate()
  {
    parent::validate();
    $info = $this->getInfoInstance();

    if (!$info->getBoletoSankhyaId())
    {
      $errorCode = 'invalid_data';
      $errorMsg = $this->_getHelper()->__("BoletoSankhyaId is a required field.\n");
    }

    if (!$info->getBoletoDescricao())
    {
      $errorCode = 'invalid_data';
      $errorMsg .= $this->_getHelper()->__('BoletoDescricao is a required field.');
    }

    if ($errorMsg)
    {
      Mage::throwException($errorMsg);
    }

    return $this;
  }

  public function getOrderPlaceRedirectUrl()
  {
    // return Mage::getUrl('boletopayment/payment/redirect', array('_secure' => false));
    return Mage::getUrl('boleto', array('_secure' => false));
  }
}
