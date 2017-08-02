<?php
class Bertholdo_Entrega_Helper_Data extends Mage_Core_Helper_Abstract {

	public function limpaString($texto)
	{
		$aFind = array('&','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','/',':','.','(',')','-','�','�','n�','N�','�','�','�','�','�','�','�','�','�','�','�','n�','N�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','`','�','^','~','�');

		$aSubs = array('e','a','a','a','a','e','e','i','o','o','o','u','u','c','A','A','A','A','E','E','I','O','O','O','U','U','C','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');

		$novoTexto = str_replace($aFind,$aSubs,$texto);
		
		$novoTexto = trim($novoTexto);
		$novoTexto = addslashes($novoTexto);

		return $novoTexto;
	}

	public function limpaStringExtra($texto)
	{

		$novoTexto = strtoupper(strtr($texto ,"����������������","����������������"));
		$novoTexto = trim($novoTexto);

		$novoTexto = addslashes($novoTexto);
		$novoTexto = limpaString($novoTexto);

		return $novoTexto;
	}

	public function execucao()
	{
		$sec = explode(" ",microtime());
		$tempo = $sec[1] + $sec[0];
		return floor($tempo / 60);
	}

	function download( $file )
	{
		if ( is_file( $file ) && is_readable( $file ) )
		{
			if ( is_resource( $fh = fopen( $file , 'r' ) ) )
			{
				header( 'Content-type: text/csv' );
				header( sprintf( 'Content-Disposition: attachment; filename="%s"' , basename( $file ) ) );

				while ( !feof( $fh ) )
				{
					echo fread( $fh , 1024 );
					flush();
				}
				fclose( $fh );
			} 
			else 
			{
				trigger_error( 'N�o foi poss�vel abrir o arquivo para leitura.' , E_USER_ERROR );
			}
		} 
		else 
		{
			trigger_error( 'O arquivo n�o existe ou n�o temos permiss�o de leitura.' , E_USER_ERROR );
		}
	}
}
?>