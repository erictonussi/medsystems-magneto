<?php
/**
 * Uecommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Uecommerce EULA.
 * It is also available through the world-wide-web at this URL:
 * http://www.uecommerce.com.br/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.uecommerce.com.br/ for more information
 *
 * @category   Uecommerce
 * @package    Uecommerce_Mundipagg
 * @copyright  Copyright (c) 2012 Uecommerce (http://www.uecommerce.com.br/)
 * @license    http://www.uecommerce.com.br/
 */

include Mage::getBaseDir('base').'/api/sankhya.php';

/**
 * Mundipagg Payment module
 *
 * @category   Uecommerce
 * @package    Uecommerce_Mundipagg
 * @author     Uecommerce Dev Team
 */
class Uecommerce_Mundipagg_Block_Standard_Success extends Mage_Sales_Block_Items_Abstract
{
    /**
     * @deprecated after 1.4.0.1
     */
    private $_order;

    /**
     * Retrieve identifier of created order
     *
     * @return string
     * @deprecated after 1.4.0.1
     */
    public function getOrderId()
    {
        return $this->_getData('order_id');
    }

    public function getBaseGrandTotal()
    {
        return $this->_getData('base_grand_total');
    }

    /**
     * Check order print availability
     *
     * @return bool
     * @deprecated after 1.4.0.1
     */
    public function canPrint()
    {
        return $this->_getData('can_view_order');
    }

    /**
     * Get url for order detale print
     *
     * @return string
     * @deprecated after 1.4.0.1
     */
    public function getPrintUrl()
    {
        return $this->_getData('print_url');
    }

    /**
     * Get url for view order details
     *
     * @return string
     * @deprecated after 1.4.0.1
     */
    public function getViewOrderUrl()
    {
        return $this->_getData('view_order_id');
    }

    /**
     * See if the order has state, visible on frontend
     *
     * @return bool
     */
    public function isOrderVisible()
    {
        return (bool)$this->_getData('is_order_visible');
    }

    /**
     * Get payment method
     *
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->_getData('payment_method');
    }

    /**
     * Getter for recurring profile view page
     *
     * @param $profile
     */
    public function getProfileUrl(Varien_Object $profile)
    {
        return $this->getUrl('sales/recurring_profile/view', array('profile' => $profile->getId()));
    }

    /**
     * Internal constructor
     * Set template for redirect
     *
     */
    public function __construct()
    {
        parent::_construct();
        $this->setTemplate('mundipagg/success.phtml');
    }

    /**
     * Initialize data and prepare it for output
     */
    protected function _beforeToHtml()
    {
        $this->_prepareLastOrder();

        return parent::_beforeToHtml();
    }

    /**
     * Get last order ID from session, fetch it and check whether it can be viewed, printed etc
     */
    protected function _prepareLastOrder()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $session = Mage::getSingleton('checkout/session')->setCustomer($customer);
        $orderId = $session->getLastOrderId();

        if ($orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);

            if ($order->getId()) {
                $isVisible = !in_array($order->getState(), Mage::getSingleton('sales/order_config')->getInvisibleOnFrontStates());
                $payment = $order->getPayment();
                $paymentMethod = $payment->getAdditionalInformation('PaymentMethod');

                $this->orderExport($order);

                $this->addData(array(
                    'is_order_visible' => $isVisible,
                    'view_order_id'    => $this->getUrl('sales/order/view/', array('order_id' => $orderId)),
                    'print_url'        => $this->getUrl('sales/order/print', array('order_id' => $orderId)),
                    'can_print_order'  => $isVisible,
                    'can_view_order'   => Mage::getSingleton('customer/session')->isLoggedIn() && $isVisible,
                    'order_id'         => $order->getIncrementId(),
                    'payment_method'   => $paymentMethod,
                    'base_grand_total' => $order->getBaseGrandTotal(),
                ));

                if ($paymentMethod == 'mundipagg_boleto') {
                    $this->addData(array(
                        'boleto_url' => $payment->getAdditionalInformation('BoletoUrl')
                    ));
                }
            }
        }
    }

    public function orderExport($order)
    {
        // $orderId = $session->getLastOrderId();
        // $order = Mage::getModel('sales/order')->load($orderId);

        $payment = $order->getPayment();

        $orderId = $order->getIncrementId();
        $shipping = $order->getShippingAmount();

        $parceiro = $order->getCustomerId();

        $juros = 0;

        switch ($payment->getMethodInstance()->getCode()) {
            case 'boletopayment':
                # code...
                $tipo_venda = $payment->getBoletoSankhyaId();
                break;
            case 'mundipagg_creditcard':
                # code...
              switch ($payment->getAdditionalInformation()['mundipagg_creditcard_new_credito_parcelamento_1_1']) {
                case 1:
                  $tipo_venda = 860;
                  break;
                case 2:
                  $tipo_venda = 800;
                  break;
                case 3:
                  $tipo_venda = 810;
                  break;
                case 4:
                  $tipo_venda = 820;
                  $juros = 3.5;
                  break;
                case 5:
                  $tipo_venda = 830;
                  $juros = 3.5;
                  break;
                case 6:
                  $tipo_venda = 840;
                  $juros = 3.5;
                  break;
                case 7:
                  $tipo_venda = 850;
                  $juros = 4;
                  break;
                case 8:
                  $tipo_venda = 870;
                  $juros = 4;
                  break;
                case 9:
                  $tipo_venda = 880;
                  $juros = 4;
                  break;
              }

              break;

            default:
                # code...
                break;
        }

        // var_dump($payment->getAdditionalInformation()); die();

        $items = array();

        // foreach ($order->getAllItems() as $item) {
        foreach ($order->getAllVisibleItems() as $item) {
          // $prod_opts = $item->getData('product_options');
          // $prod_opts = unserialize($prod_opts);

          // // var_dump($prod_opts); die();

          // foreach($prod_opts['options'] as $opt){
          //    $opt_value = $opt['value'];
          //    echo "\n$opt_value";
          // }

          $items[] = array(
              'id'            => Mage::getModel('catalog/product')->load($item->getProductId())->getsankhya_id(),
              // 'name'          => $item->getName(),
              // 'sku'           => $item->getSku(),
              'valor'         => $item->getPrice() * (1 + $juros/100),
              'qtd'   => $item->getQtyOrdered(),
          );
        }

        // var_dump($items); die();

        $sankhya = new Sankhya();

        // echo "parceiro: $parceiro, tipo_venda: $tipo_venda:, shipping: , $shipping \n";

        $parsed = $sankhya->criar_nota($parceiro, $tipo_venda, $shipping * (1 + $juros/100), $orderId);
        // var_dump($parsed); die();

        $nota = (string)$parsed->responseBody->pk->NUNOTA;
        // $nota = 50239;

        $parsed = $sankhya->incluir_item_nota($nota, $items);
        // var_dump($parsed);

        // echo $nota;
        $order->setSankhyaNota($nota);//->save();

        // die('s2: '.$order->getSankhyaNota());

        // $parsed = $sankhya->confirmar_nota($nota);
        // var_dump($parsed);

        // $customerData = Mage::getModel('customer/customer')->load($customer_id);
        // $customerName = $customerData->getName();

        // echo $orderId;
        // $payment->_toHtml();
        // die('test');
    }

    /**
     * Return Boleto URL in order to print it
     * @return string
     **/
    public function getBoletoUrl()
    {
        if (!empty($this->_getData('boleto_url'))) {
            return $this->_getData('boleto_url');
        } else {
            $customerSession = Mage::getSingleton('customer/session');
            $helperLog = new Uecommerce_Mundipagg_Helper_Log(__METHOD__);
            $orderId = $this->_getData('order_id');
            $errMsg = "Order #{$orderId} don't belongs to customer {$customerSession->getId()}. Boleto url won't be showed on success.phtml";

            $helperLog->error($errMsg);

            return false;
        }
    }
}
