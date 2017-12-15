<?php

include Mage::getBaseDir('base').'/api/sankhya.php';


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
    public function orderExport($observer)
    {
        $order = $observer->getEvent()->getOrder();
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
              return;
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
        $arredondamento = 0;

        // foreach ($order->getAllItems() as $item) {
        foreach ($order->getAllVisibleItems() as $item) {
          // $prod_opts = $item->getData('product_options');
          // $prod_opts = unserialize($prod_opts);

          // // var_dump($prod_opts); die();

          // foreach($prod_opts['options'] as $opt){
          //    $opt_value = $opt['value'];
          //    echo "\n$opt_value";
          // }

          $valor = $item->getPrice();
          $valorJuros =  $item->getPrice() * ($juros/100);
          $arredondamento = $valorJuros * 100 % 1 / 100;
          $valorJuros = intval($valorJuros * 100) / 100;

          $items[] = array(
              'id'            => Mage::getModel('catalog/product')->load($item->getProductId())->getsankhya_id(),
              // 'name'          => $item->getName(),
              // 'sku'           => $item->getSku(),
              'valor'         => $item->getPrice() + $valorJuros,
              'qtd'   => $item->getQtyOrdered(),
          );
        }

        // var_dump($items); die();

        $sankhya = new Sankhya();

        // echo "parceiro: $parceiro, tipo_venda: $tipo_venda:, shipping: , $shipping \n";

        $parsed = $sankhya->criar_nota($parceiro, $tipo_venda, $shipping * (1 + $juros/100) + $arredondamento, $orderId);
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

    public function saveCustomData($observer)
    {
        $event = $observer->getEvent();
        $order = $event->getOrder();
        // $fieldVal = Mage::app()->getFrontController()->getRequest()->getParams();
        // var_dump($fieldVal);
        // $order->setDeliveryDate($fieldVal['delivery_date']);
        // $order->setSankhyaNota($fieldVal['sankhya_nota']);
        // die('s: '.$order->getSankhyaNota());
    }
}
