<?php
    error_reporting( E_ALL );


$OrderNumber = "100000076";
    // require_once "lib/nusoap.php";
    require_once "../app/Mage.php";
    Mage::app('admin');
    try {
        $order = Mage::getModel('sales/order')->loadByIncrementId('100000076');
        print_r($order->debug());
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
