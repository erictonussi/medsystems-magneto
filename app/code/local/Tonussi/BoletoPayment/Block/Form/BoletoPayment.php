<?php
// app/code/local/Tonussi/BoletoPayment/Block/Form/BoletoPayment.php
class Tonussi_BoletoPayment_Block_Form_BoletoPayment extends Mage_Payment_Block_Form
{
  protected function _construct()
  {
    parent::_construct();
    $this->setTemplate('boletopayment/form/boletopayment.phtml');
  }
}
