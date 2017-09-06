<?php

include 'sankhya.php';

$sankhya = new Sankhya();

$parsed = $sankhya->criar_nota(60, 560);

$nota = (string)$parsed->responseBody->pk->NUNOTA;

$parsed = $sankhya->incluir_item_nota($nota, array(
  array(
    'id' => 2740,
    'qtd' => 6,
    'valor' => 84
  ),
  array(
    'id' => 337,
    'qtd' => 5,
    'valor' => 10
  )
));
var_dump($parsed);

$parsed = $sankhya->confirmar_nota($nota);
