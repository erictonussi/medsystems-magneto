<?php

include 'sankhya.php';
include 'magento.php';

$magento = new Magento();
$sankhya = new Sankhya();

$parsed = $sankhya->crud(
  '<serviceRequest serviceName="CRUDServiceProvider.loadRecords">
    <requestBody>
      <dataSet rootEntity="Produto" includePresentationFields="S" parallelLoader="false" disableRowsLimit="false" orderByExpression="this.CODPROD">
        <entity path="">
          <fieldset list="CODPROD, DESCRPROD, CARACTERISTICAS, TIPLANCNOTA, PESOBRUTO" />
        </entity>
        <criteria>
          <expression>(this.CODPROD &lt; ?)</expression>
          <parameter type="N">36</parameter>
        </criteria>
      </dataSet>
    </requestBody>
   </serviceRequest>'
);

foreach ($parsed->responseBody->entities[0]->entity as $entity) {
  var_dump($entity);

  $sku = str_replace(' ', '-', $entity->f1);
  $sku = preg_replace('/-+/', '-', $sku);

  echo "\nsankhya_id: $entity->f0";
  echo "\nsku: $sku";
  echo "\nurl_key: $sku";
  echo "\nname: $entity->f1";
  echo "\ndescription: $entity->f2";

  // echo trim($entity->f0), "\n";
  // echo trim($entity->f1), "\n";
  // echo trim($entity->f2), "\n";

  $result = $magento->product_create("$entity->f0", $sku, "$entity->f1", "$entity->f2");


  echo "\n\n\n\n";
}




// print_r($parsed);

?>
