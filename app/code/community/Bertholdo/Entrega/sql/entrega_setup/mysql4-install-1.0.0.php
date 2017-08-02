<?php

$installer = $this;

$installer->startSetup();

try
{
	$installer->run("
		CREATE TABLE IF NOT EXISTS {$this->getTable('entrega_range_cep')} (
			`cep_ini` varchar(9) NOT NULL,
			`cep_fim` varchar(9) NOT NULL,
			`peso_ini` float(5,2) NOT NULL,
			`peso_fim` float(5,2) NOT NULL,
			`valor_compra` float(5,2) NOT NULL,
			`valor_sem_desc` float(5,2) NOT NULL,
			`valor_com_desc` float(5,2) NOT NULL,
			PRIMARY KEY (`cep_ini`,`peso_ini`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		INSERT INTO {$this->getTable('entrega_range_cep')} (`cep_ini`, `cep_fim`, `peso_ini`, `peso_fim`, `valor_compra`, `valor_sem_desc`, `valor_com_desc`) VALUES
		('30000000', '31999999', 0, 10, 0.00, 10.80, 0.00),
		('30000000', '31999999', 10.1, 100, 0.00, 22.50, 0.00),
		('32000000', '32399999', 0, 10, 0.00, 13.80, 0.00),
		('32000000', '32399999', 10.1, 100, 0.00, 22.50, 0.00),
		('32400000', '32499999', 0, 10, 0.00, 13.80, 0.00),
		('32500000', '32899999', 0, 10, 0.00, 13.80, 0.00),
		('33000000', '33199999', 0, 10, 0.00, 13.80, 0.00),
		('33200000', '33299999', 0, 10, 0.00, 13.80, 0.00),
		('33400000', '33499999', 0, 10, 0.00, 13.80, 0.00),
		('33600000', '33699999', 0, 10, 0.00, 13.80, 0.00),
		('33800000', '33950999', 0, 10, 0.00, 13.80, 0.00),
		('34000000', '34299999', 0, 10, 0.00, 13.80, 0.00),
		('34500000', '34799999', 0, 10, 0.00, 13.80, 0.00),
		('34800000', '34989999', 0, 10, 0.00, 13.80, 0.00);
	");
}
catch(Exception $e)
{
	Mage::log($ex->getMessage());
}

$installer->endSetup();