<?php
include 'sankhya.php';
$sankhya = new Sankhya();

require '../app/Mage.php';
Mage::init();

$products = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToSelect('name')
    ->addAttributeToSelect('sankhya_id')
    ->load();
$productos = [];

foreach ($products as $product) {
  if ( $product->sankhya_id) {

    $parsed = $sankhya->crud(
      '<serviceRequest serviceName="CRUDServiceProvider.loadRecords">
        <requestBody>
          <dataSet rootEntity="Estoque" relationName="Estoque" parentEntity="Produto">
            <entity path="">
              <fieldset list="ESTOQUE"/>
            </entity>
            <!--entity path="Parceiro">
              <field name="NOMEPARC"/>
            </entity>
            <entity path="LocalFinanceiro">
              <field name="DESCRLOCAL"/>
            </entity>
            <entity path="Empresa">
              <field name="NOMEFANTASIA"/>
            </entity-->
            <foreingKey>
              <CODPROD>
                <![CDATA['.$product->sankhya_id.']]>
              </CODPROD>
            </foreingKey>
            <criteria>
              <expression>CODLOCAL = 2121</expression>
            </criteria>
          </dataSet>
        </requestBody>
      </serviceRequest>'
    );
    // var_dump($parsed); //die();

    // $produtos = [];
    $estoque = 0;
    foreach ($parsed->responseBody->entities[0]->entity as $entity) {
      $estoque += $entity->f0;
      // echo "\nname: $entity->f0";
      // echo "\n\n\n";
    }


    // // Check if there is a stock item object
    $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId());
    $stockItemData = $stockItem->getData();
    if (empty($stockItemData)) {

        // Create the initial stock item object
        $stockItem->setData('manage_stock',1);
        $stockItem->setData('is_in_stock',$estoque ? 1 : 0);
        $stockItem->setData('use_config_manage_stock', 0);
        $stockItem->setData('stock_id',1);
        $stockItem->setData('product_id',$product->getId());
        $stockItem->setData('qty',0);
        $stockItem->save();

        // Init the object again after it has been saved so we get the full object
        $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId());
    }

    $productos[] = array(
      "name" => $product->name,
      "current" => $stockItem->qty,
      "new" => $estoque
    );

    // Set the quantity
    $stockItem->setData('qty', $estoque);
    $stockItem->setData('is_in_stock', $estoque > 0);
    $stockItem->save();

  }
}

echo json_encode($productos);
