<?php
$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE `{$installer->getTable('sales/quote_payment')}`
ADD `boleto_sankhya_id` VARCHAR( 255 ) NOT NULL,
ADD `boleto_descricao` VARCHAR( 255 ) NOT NULL;

ALTER TABLE `{$installer->getTable('sales/order_payment')}`
ADD `boleto_sankhya_id` VARCHAR( 255 ) NOT NULL,
ADD `boleto_descricao` VARCHAR( 255 ) NOT NULL;
");
$installer->endSetup();
