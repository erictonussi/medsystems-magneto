<?php
/**
 * Overrite Customer model
 */

// include_once("Mage/Customer/controllers/AccountController.php");
// include_once("Mage/Customer/Model/Customer.php");
class Tonussi_CpfLogin_Model_Customer extends Mage_Customer_Model_Customer
{
  public function loadByEmail($taxvat)
  {

      $result = Mage::getModel('customer/customer')
        ->getCollection()
        ->addAttributeToSelect('taxvat')
        ->addAttributeToFilter('taxvat', $taxvat)
        ->load()
        ->getFirstItem();

      // $this->_getResource()->loadByEmail($this, $result->email);
      $this->_getResource()->load($this, $result->entity_id);

      return $this;
  }
}
?>
