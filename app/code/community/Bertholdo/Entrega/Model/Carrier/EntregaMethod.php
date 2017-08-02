<?php
class Bertholdo_Entrega_Model_Carrier_EntregaMethod extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
	protected $_code = 'options_entrega';

	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{
		if (!Mage::getStoreConfig('carriers/'.$this->_code.'/active')) 
		{
			return false;
		}
		 
		$handling = Mage::getStoreConfig('carriers/'.$this->_code.'/handling');

		$this->_result = Mage::getModel('shipping/rate_result');

		$method = Mage::getModel('shipping/rate_result_method');

		$method->setCarrier($this->_code);
		$method->setCarrierTitle(Mage::getStoreConfig('carriers/'.$this->_code.'/title'));

		$method->setMethod('Entrega');
		$method->setMethodTitle(Mage::getStoreConfig('carriers/'.$this->_code.'/title'));

		$aFind = array('/',':','.','(',')','-');
		$aSubs = array('','','','','','');
		$this->_toZip = str_replace($aFind,$aSubs,$request->getDestPostcode());

		$this->_packageWeight = number_format($request->getPackageWeight(), 3, '.', '');

		$this->_packageValue = $request->getBaseCurrency()->convert($request->getPackageValue(), $request->getPackageCurrency());

		$resource = Mage::getSingleton('core/resource');
		$entrega_range_cep = $resource->getTableName('entrega_range_cep');

		$objBD = Mage::getSingleton('core/resource')->getConnection('core/read');
		$tuplas = $objBD->query("SELECT cep_ini, cep_fim, peso_ini, peso_fim, valor_compra, valor_sem_desc, valor_com_desc FROM $entrega_range_cep")->fetchAll();

		$erroEntrega = true;
		foreach($tuplas as $key => $value)
		{
			// VERIFICO REGRA DO INTERVALO DO CEP
			if ( ($this->_toZip >= $value['cep_ini']) && ($this->_toZip <= $value['cep_fim']) )
			{
				// VERIFICO REGRA DO PESO DOS PRODUTOS NO CARRINHO
				if ( ($this->_packageWeight >= $value['peso_ini']) && ($this->_packageWeight <= $value['peso_fim']) )
				{
				  if ( $value['valor_compra'] > 0 )
				  {
					// VERIFICO REGRA DE PREÇO DOS PRODUTOS DO CARRINHO
					if ( ($this->_packageValue <= $value['valor_compra']) )
					{
						$erroEntrega = false;

						$this->_result->append($method->setPrice($value['valor_sem_desc']));

						return $this->_result;
					}
					else
					{
					  $erroEntrega = false;

					  $this->_result->append($method->setPrice($value['valor_com_desc']));

					  return $this->_result;
					}
				  }
				  else
				  {
					$erroEntrega = false;

					$this->_result->append($method->setPrice($value['valor_sem_desc']));

					return $this->_result;
				  }
				}
				else
					continue;
			}
			else
				continue;	
		}

		if ($erroEntrega)
		{
			//$message = Mage::helper('shipping')->__("Dear customer: we are not making this CEP delivers choose other means.");
			$message = "Prezado cliente: Neste CEP não efetuamos entrega, escolha outro meio.";
			$this->_throwError($message);
				
			return $this->_result;
		}
	}

	protected function _throwError($message, $log = null, $line = 'NO LINE', $custom = null)
	{
		$this->_result = null;
		$this->_result = Mage::getModel('shipping/rate_result');

		$error = Mage::getModel('shipping/rate_result_error');
		$error->setCarrier($this->_code);
		$error->setCarrierTitle($this->getConfigData('title'));
		
		if(is_null($custom))
		{
			Mage::log($this->_code . ' [' . $line . ']: ' . $log);
			$error->setErrorMessage($message);
		}
		else
		{
			Mage::log($this->_code . ' [' . $line . ']: ' . $log);
			$error->setErrorMessage(sprintf($this->getConfigData($message), $custom));
		}        
		
		$this->_result->append($error);
	}

	public function getAllowedMethods()
	{
	  return array($this->_code => $this->getConfigData('title'));
	}

}