<?php
/**
 * Overrite Customer model
 */

// include_once("Mage/Customer/controllers/AccountController.php");
// include_once("Mage/Customer/Model/Customer.php");
echo 'I am here'; die;
class Tonussi_CpfLogin_Model_Customer extends Mage_Customer_Model_Customer
{
  public function validate() {
       echo 'I am here'; die;
     }
}
?>
