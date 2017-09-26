<?php
// app/code/local/Tonussi/BoletoPayment/Block/Info/BoletoPayment.php
class Tonussi_BoletoPayment_Block_Info_BoletoPayment extends Mage_Payment_Block_Info
{
  protected function _prepareSpecificInformation($transport = null)
  {
    if (null !== $this->_paymentSpecificInformation)
    {
      return $this->_paymentSpecificInformation;
    }

    $data = array();
    // if ($this->getInfo()->getBoletoSankhyaId())
    // {
    //   $data[Mage::helper('payment')->__('Custom Field One')] = $this->getInfo()->gettBoletoSankhyaId();
    // }

    if ($this->getInfo()->getBoletoDescricao())
    {
      $data[Mage::helper('payment')->__('Parcela')] = $this->getInfo()->getBoletoDescricao();
    }

    $transport = parent::_prepareSpecificInformation($transport);

    return $transport->setData(array_merge($data, $transport->getData()));
  }
}
