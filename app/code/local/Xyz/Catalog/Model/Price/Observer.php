<?php
class Xyz_Catalog_Model_Price_Observer
{
    public function __construct()
    {
    }
    /**
     * Applies the special price percentage discount
     * @param   Varien_Event_Observer $observer
     * @return  Xyz_Catalog_Model_Price_Observer
     */
    public function apply_discount_percent($observer)
    {
      $event = $observer->getEvent(); 	
         $model = $event->getPage();
   	     print_r($model->getData());
         die('test');
    }
}