<?php

include 'sankhya.php';

$sankhya = new Sankhya();

// $parsed = $sankhya->criar_nota(3677, 600, 16.1);
$parsed = $sankhya->criar_nota(3677, 15, 16.1);
var_dump($parsed);

$nota = (string)$parsed->responseBody->pk->NUNOTA;
// $nota = 50181;

$parsed = $sankhya->incluir_item_nota($nota, array(
  // array(
  //   'id' => 2740,
  //   'qtd' => 6,
  //   'valor' => 84
  // ),
  array(
    'id' => 337,
    'qtd' => 1,
    'valor' => 3000
  )
));
var_dump($parsed);

// $parsed = $sankhya->confirmar_nota($nota);
// var_dump($parsed);
