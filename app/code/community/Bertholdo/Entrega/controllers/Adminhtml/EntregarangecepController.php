<?php
class Bertholdo_Entrega_Adminhtml_EntregarangecepController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->loadLayout()->_setActiveMenu('entrega/adminhtml_entregarangecep');
		return $this;
	}

    public function indexAction()
    {
		$this->_initAction();
		//$this->renderLayout();

		$block = $this->getLayout()->createBlock('Mage_Core_Block_Template','adminhtml_entregarangecep', array('template' => 'entrega/entregarangecep.phtml'));
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
    }

    public function importacaoCsvAction()
    {
		set_time_limit(0);

		$inicio = 0;
		$fim = 0;
		$msgRetorno = "";
		$linhasFile = array();

		$csvRangeCeps = $_FILES['file_upload']['name'];
		$tipoFile = $_FILES['file_upload']['type'];

		$resource = Mage::getSingleton('core/resource');
		$entrega_range_cep = $resource->getTableName('entrega_range_cep');

		$objBD_read = Mage::getSingleton('core/resource')->getConnection('core/read');
		$objBD_write = Mage::getSingleton('core/resource')->getConnection('core/write');

		$helper = Mage::helper('entrega/data');
		$inicio = $helper->execucao();


		try
		{
				// die($tipoFile);
			if ($this->getRequest()->getPost())
			{

				if( !empty($csvRangeCeps) )
				{
					// SALVANDO O ARQUIVO

					$uploaderFile = new Varien_File_Uploader('file_upload');
					$uploaderFile->setAllowedExtensions(array());
					$uploaderFile->setAllowRenameFiles(false);
					$uploaderFile->setFilesDispersion(false);

					$uploaderFilepath = Mage::getBaseDir('media') . DS . 'Bertholdo' . DS . 'importcsv' . DS ;
					$filepath = $uploaderFilepath.$csvRangeCeps;

					// CRIANDO E VERIFICANDO O DIRETÓRIO

					if (file_exists($filepath))
					{
						unlink("$filepath");
					}
					else
					{
						mkdir("$uploaderFilepath", 0777, true);
					}

					$uploaderFile->save( $uploaderFilepath, $csvRangeCeps );

					// LEITURA DO CSV

					if ( ($handle = fopen($filepath, "r")) !== FALSE )
					{
						$row = 0;
						while ( ($data = fgetcsv($handle, 10000, ";")) !== FALSE )
						{
							$linhasFile[$row] = $data;
							$row++;
						}
						fclose($handle);
					}
					else
					{
						$message = $this->__("Return: ERROR - Reading CSV file.");
						Mage::getSingleton('adminhtml/session')->addError($message);
					}

					// LIMPANDO A TABELA DE RANGE DE CEPS CASO NÃO QUEIRA UTILIZAR OS CEPS EXISTENTES

					$objBD_write->query("TRUNCATE $entrega_range_cep");

					// ATUALIZANDO PRODUTOS

					$qtdLinhas = count($linhasFile);
					for($i = 1; $i < $qtdLinhas; $i++)
					{
						// PULANDO LINHAS DE LIXO

						if (empty($linhasFile[$i][0])) continue;

						// CAMPOS DO CSV PADRÃO

						$cep_ini = $helper->limpaString($linhasFile[$i][0]);
						$cep_fim = $helper->limpaString($linhasFile[$i][1]);
						$peso_ini = $linhasFile[$i][2];
						$peso_fim = $linhasFile[$i][3];
						$valor_compra = $linhasFile[$i][4];
						$valor_sem_desc = $linhasFile[$i][5];
						$valor_com_desc = $linhasFile[$i][6];

						// ATUALIZANDO OS RANGES DE CEP

						try
						{
							$query = "REPLACE INTO $entrega_range_cep (cep_ini,cep_fim,peso_ini,peso_fim,valor_compra,valor_sem_desc,valor_com_desc) values ('$cep_ini', '$cep_fim', '$peso_ini', '$peso_fim', '$valor_compra','$valor_sem_desc','$valor_com_desc')";

							$objBD_write->query($query);
						}
						catch (Exception $ex)
						{
							//Zend_Debug::dump($ex->getMessage());
							//Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
						}
					}

					$fim = $helper->execucao();

					$message = $this->__("Return: Import successful. <br/> Time: %s minute", number_format(($fim-$inicio)));
					Mage::getSingleton('adminhtml/session')->addSuccess($message);
				}
				else
				{
					$message = $this->__("Return: ERROR - Reading CSV file.");
					Mage::getSingleton('adminhtml/session')->addError($message);
				}
			}
			else
			{
				$message = $this->__("Return: ERROR - Sending form data.");
				Mage::getSingleton('adminhtml/session')->addError($message);
			}
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			Mage::getSingleton('adminhtml/session')->addError($message);
		}
		$this->_redirect('*/*');
    }

	public function exportacaoCsvAction()
    {
		$csvRangeCeps = "entrega_range_ceps.csv";

		$resource = Mage::getSingleton('core/resource');
		$entrega_range_cep = $resource->getTableName('entrega_range_cep');

		$objBD_read = Mage::getSingleton('core/resource')->getConnection('core/read');
		$helper = Mage::helper('entrega/data');

		try
		{
			if ($this->getRequest()->getPost())
			{
				// CRIANDO O DIRETÓRIO

				$uploaderFilepath = Mage::getBaseDir('media') . DS . 'Bertholdo' . DS . 'importcsv' . DS ;
				$filepath = $uploaderFilepath.$csvRangeCeps;

				// CRIANDO E VERIFICANDO O DIRETÓRIO

				if (file_exists($filepath))
				{
					unlink("$filepath");
				}
				else
				{
					mkdir("$uploaderFilepath", 0777, true);
				}

				// CABEÇALHO DO CSV

				$fp = fopen($filepath, 'w');
				fputcsv($fp, array("Cep_Inicial;Cep_Final;Peso_1;Peso_2;Valor_Compra;Valor_Sem_Desconto;Valor_Com_Desconto"));

				// LOOP NA TABELA

				$tuplas = $objBD_read->query("SELECT cep_ini, cep_fim, peso_ini, peso_fim, valor_compra, valor_sem_desc, valor_com_desc FROM $entrega_range_cep")->fetchAll();

				foreach($tuplas as $key => $value)
				{
					$cep_ini = $value['cep_ini'];
					$cep_fim = $value['cep_fim'];
					$peso_ini = ($value['peso_ini']/1);
					$peso_fim = ($value['peso_fim']/1);
					$valor_compra = str_replace(",",".",$value['valor_compra']);
					$valor_sem_desc = str_replace(",",".",$value['valor_sem_desc']);
					$valor_com_desc = str_replace(",",".",$value['valor_com_desc']);

					fputcsv($fp, array($cep_ini.';'.$cep_fim.';'.$peso_ini.';'.$peso_fim.';'.$valor_compra.';'.$valor_sem_desc.';'.$valor_com_desc));
				}

				$helper->download($filepath);
				fclose($fp);

				exit;
			}
			else
			{
				$message = $this->__("Return: ERROR - Sending form data.");
				Mage::getSingleton('adminhtml/session')->addError($message);
			}
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			Mage::getSingleton('adminhtml/session')->addError($message);
		}
		$this->_redirect('*/*');
	}

	protected function _isAllowed()
	{
	    return true;
	}
}
?>
