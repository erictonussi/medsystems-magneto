<?php
$json_input = file_get_contents('php://input');

if ($json_input) {
  file_put_contents("config.json", $json_input, 0);
  file_put_contents("backup/config_".date("Y-m-d-H-i-s").".json", $json_input, 0);
  die($json_input);
}
// echo 'Hello ' . htmlspecialchars($_POST["teste"]) . '!';
?>
{
  "grupos": {
    "3040800": 5
  }
}
