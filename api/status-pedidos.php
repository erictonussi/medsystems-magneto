<?php

require '../app/Mage.php';
Mage::init();

$orders = Mage::getModel('sales/order')
    ->getCollection()
    // ->addAttributeToSelect('name')
    // ->addAttributeToSelect('sankhya_nota')
    ->addAttributeToFilter('sankhya_nota', array('notnull' => true))
    ->addAttributeToFilter('status', array('in' => ['pending', 'processing']))
    // ->addFieldToFilter('status',array('neq' => 'complete'))
    ->load();

// echo "notas: ".sizeof($orders);

$notas = [];
$status = [];
foreach ($orders as $order) {
  if ( $order->sankhya_nota) {
    // echo "\n\n";
    // var_dump($order);
    // echo "\n$order->sankhya_nota";
    // echo "\n$order->status";

    $notas[] = $order->sankhya_nota;
    $status[$order->sankhya_nota] = array(
      nota => $order->sankhya_nota,
      current => $order->status,
      order => $order
    );
  }
}

// echo "\n\n";

include 'sankhya.php';

$sankhya = new Sankhya();

// die();

$parsed = $sankhya->consulta_pedidos($notas);

foreach ($parsed->responseBody->entities[0]->entity as $entity) {
    // echo "\n\nID: $entity->f233";
    // echo "\nCONFIRMADA: $entity->f280";
  // var_dump($entity);
  $status[(int)$entity->f233]["novo"] = $entity->f280 == "Sim" ? "processing" : "pending";
}

// die();

$parsed = $sankhya->curl_call('mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords',
  '<serviceRequest serviceName="CRUDServiceProvider.loadRecords">
    <requestBody>
      <dataSet rootEntity="CompraVendavariosPedido">
        <entity path="">
          <fieldset list="*"/>
        </entity>
        <criteria>
          <expression>TGFVAR.NUNOTAORIG in (' . join($notas, ',') . ')</expression>
          <!--parameter type="N">50239</parameter-->
        </criteria>
      </dataSet>
    </requestBody>
  </serviceRequest>', true);

// var_dump($parsed);

foreach ($parsed->responseBody->entities[0]->entity as $entity) {

  // echo "\n\nnota: $entity->f7";

  // $notas_geradas[] = $entity->f7;
    // echo "\nCONFIRMADA: $entity->f280";

  // echo "\n\n";
  // var_dump($entity);

  // $status[(int)$entity->f5]["novo"] = $entity->f280 == "Sim" ? "processing" : "pending";
  $parsed_nota = $sankhya->consulta_pedidos([$entity->f7]);

  foreach ($parsed_nota->responseBody->entities[0]->entity as $entity_nota) {
    // echo "\n\nID: $entity_nota->f233";
    // echo "\nCONFIRMADA: $entity_nota->f280";
    // var_dump($entity_nota);

    if ( $entity_nota->f280 == "Sim" ) {
    //   $status[(int)$entity_nota->f233]["novo"] = "complete";
      $status[(int)$entity->f5]["novo"] = "complete";
    }


  }

}

// var_dump($status);

echo "<pre>";
foreach ($status as $order) {
  if ( $order[current] != $order[novo] ) {
    echo "\n\n$order[nota]: $order[current] -> $order[novo]";

    $order['order']->setStatus($order[novo]);
    $order['order']->save();
  } else {
    echo "\n\n$order[nota]: não alterada ($order[current])";
  }
}
echo "</pre>";

?>
