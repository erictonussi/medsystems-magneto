<?php

class Sankhya {

  var $jsessionid;

  var $estados = array(
    '1' => array(
      'estado' => 'SAO PAULO',
      'uf' => 'SP'
    ),
    '2' => array(
      'estado' => 'MINAS GERAIS',
      'uf' => 'MG'
    ),
    '3' => array(
      'estado' => 'DISTRITO FEDERAL',
      'uf' => 'DF'
    ),
    '4' => array(
      'estado' => 'GOIAS',
      'uf' => 'GO'
    ),
    '5' => array(
      'estado' => 'MATO GROSSO',
      'uf' => 'MT'
    ),
    '6' => array(
      'estado' => 'BAHIA',
      'uf' => 'BA'
    ),
    '7' => array(
      'estado' => 'RIO DE JANEIRO',
      'uf' => 'RJ'
    ),
    '8' => array(
      'estado' => 'PARANA',
      'uf' => 'PR'
    ),
    '9' => array(
      'estado' => 'PARA',
      'uf' => 'PA'
    ),
    '10' => array(
      'estado' => 'PERNAMBUCO',
      'uf' => 'PE'
    ),
    '11' => array(
      'estado' => 'RONDONIA',
      'uf' => 'RO'
    ),
    '12' => array(
      'estado' => 'MATO GROSSO DO SUL',
      'uf' => 'MS'
    ),
    '13' => array(
      'estado' => 'SANTA CATARINA',
      'uf' => 'SC'
    ),
    '14' => array(
      'estado' => 'TOCANTINS',
      'uf' => 'TO'
    ),
    '15' => array(
      'estado' => 'RIO GRANDE DO SUL',
      'uf' => 'RS'
    ),
    '16' => array(
      'estado' => 'ESPIRITO SANTO',
      'uf' => 'ES'
    ),
    '17' => array(
      'estado' => 'PARAIBA',
      'uf' => 'PB'
    ),
    '18' => array(
      'estado' => 'AMAZONAS',
      'uf' => 'AM'
    ),
    '19' => array(
      'estado' => 'ALAGOAS',
      'uf' => 'AL'
    ),
    '20' => array(
      'estado' => 'ACRE',
      'uf' => 'AC'
    ),
    '21' => array(
      'estado' => 'CEARA',
      'uf' => 'CE'
    ),
    '22' => array(
      'estado' => 'SERGIPE',
      'uf' => 'SE'
    ),
    '23' => array(
      'estado' => 'PIAUI',
      'uf' => 'PI'
    ),
    '24' => array(
      'estado' => 'RORAIMA',
      'uf' => 'RR'
    ),
    '26' => array(
      'estado' => 'RIO GRANDE DO NORTE',
      'uf' => 'RN'
    ),
    '28' => array(
      'estado' => 'AMAPA',
      'uf' => 'AP'
    ),
    '31' => array(
      'estado' => 'MARANHÃO',
      'uf' => 'MA'
    ),
    '32' => array(
      'estado' => 'EXTERIOR USA',
      'uf' => 'EX'
    ),
    '33' => array(
      'estado' => 'EXTERIOR ITALY',
      'uf' => 'EX'
    ),
    '34' => array(
      'estado' => 'EXTERIOR GERMANY',
      'uf' => 'EX'
    ),
    '35' => array(
      'estado' => 'EXTERIOR ISRAEL',
      'uf' => 'EX'
    ),
    '36' => array(
      'estado' => 'EXTERIOR FRANCE',
      'uf' => 'EX'
    ),
    '37' => array(
      'estado' => 'EXTERIOR SWITZERLAND',
      'uf' => 'EX'
    ),
    '38' => array(
      'estado' => 'EXTERIOR TOKYO',
      'uf' => 'EX'
    ),
    '39' => array(
      'estado' => 'EXTERIOR HONK KONG',
      'uf' => 'EX'
    ),
    '41' => array(
      'estado' => 'EXTERIOR SEOUL',
      'uf' => 'EX'
    )
  );

  function __construct () {

    $parsed = $this->curl_call('mge/service.sbr?serviceName=MobileLoginSP.login',
      '<serviceRequest serviceName="MobileLoginSP.login">
          <requestBody>
            <NOMUSU>lojav</NOMUSU>
            <INTERNO>07etlp01</INTERNO>
          </requestBody>
        </serviceRequest>');

    $this->jsessionid = $parsed->responseBody->jsessionid;
    // echo $parsed->responseBody->jsessionid;
  }

  function curl_call($service, $xml, $userMgeSession = false) {
    $ch = curl_init('http://sankhya.medsystems.com.br:8180/'.$service . ( $userMgeSession ? '&mgeSession='.$this->jsessionid : '' ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: text/xml;charset=ISO-8859-1',
        'User-Agent: teste'
        ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 600);

    if ( $this->jsessionid ) {
      curl_setopt($ch, CURLOPT_COOKIE, 'JSESSIONID='.$this->jsessionid);
    }

    $xml = curl_exec($ch);
    curl_close($ch);

    return new SimpleXMLElement($xml);
    // print_r($parsed);
  }

  function consulta_produtos ($expression) {
    return $this->curl_call('mgecom/service.sbr?serviceName=ConsultaProdutosSP.consultaProdutos',
      '<serviceRequest serviceName="ConsultaProdutosSP.consultaProdutos">
        <requestBody>
          <filtros>
            <criterio resourceID="br.com.sankhya.com.cons.consultaProdutos" orderByExpression="this.CODPROD">
              <CODPROD />
              <CARACTERISTICAS />
            </criterio>
            <filtroPersonalizado>
              <expression>' . $expression . '</expression>
            </filtroPersonalizado>
          </filtros>
        </requestBody>
      </serviceRequest>', true);
  }

  //http://177.188.121.233:8180/mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords&counter=3192918232&application=GruposProdutosServicos&mgeSession=B4427DD4AF60991A9E5A816F3CE7536C&resourceID=br.com.sankhya.core.cad.gruposProdutosServicos&vss=2&allowConcurrentCalls=true&globalID=e840c550727d46f091e87173d7da8fa1&re=1
  function consulta_grupos () {
    return $this->curl_call('mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords',
      '<serviceRequest serviceName="CRUDServiceProvider.loadRecords"><requestBody><dataSet rootEntity="GrupoProduto" crudListener="br.com.sankhya.modelcore.crudlisteners.HierarchyCrudListener" includePresentationFields="S" parallelLoader="false" datasetid="1502136504555_2"><entity path=""><fieldset list="*"/></entity><entity path="Natureza"><field name="DESCRNAT"/></entity><entity path="Projeto"><field name="ABREVIATURA"/></entity><criteria><expression>(this.CODGRUPOPROD &gt; 0)</expression></criteria></dataSet><clientEventList/></requestBody></serviceRequest>', true);
  }

  function crud ($xml) {
    return $this->curl_call('mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords', $xml);
  }

  function consulta_precos ($expression) {
    return $this->curl_call('mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords',
      '<serviceRequest serviceName="CRUDServiceProvider.loadRecords">
        <requestBody>
          <dataSet datasetid="1512591740195_1" includePresentationFields="S" parallelLoader="true" parentEntity="TabelaPreco" rootEntity="Excecao">
            <entity path="">
              <fieldset list="*"/>
              <field name="$USERFIELDS"/>
            </entity>
            <entity path="Produto">
              <fieldset list="DESCRPROD,COMPLDESC,REFFORN,REFERENCIA,DECVLR,TIPCONTEST,LISCONTEST,TITCONTEST"/>
            </entity>
            <entity path="LocalFinanceiro">
              <field name="DESCRLOCAL"/>
            </entity>
            <foreingKey>
              <NUTAB>
                <![CDATA[21]]>
              </NUTAB>
            </foreingKey>
            <criteria>
              <expression>CODPROD = ?</expression>
              <parameter type="N">'.$expression.'</parameter>
            </criteria>
          </dataSet>
          <clientEventList/>
        </requestBody>
      </serviceRequest>', true);
  }

  function criar_nota ($parceiro, $tipo_venda, $frete, $orderId) {
    // http://sankhya.medsystems.com.br:8280/mgecom/service.sbr?serviceName=CACSP.incluirAlterarCabecalhoNota&counter=1126141774&application=CentralNotasStack&mgeSession=E089598F488E464833FCB5B9BEE793DC&resourceID=br.com.sankhya.com.mov.CentralNotas&vss=1&globalID=591240c599604aa59c93bc70e57a6f4e&re=1
    return $this->curl_call('mgecom/service.sbr?serviceName=CACSP.incluirAlterarCabecalhoNota',
      "<serviceRequest serviceName=\"CACSP.incluirAlterarCabecalhoNota\">
        <requestBody>
          <nota ownerServiceCall=\"CentralNotas\">
            <cabecalho>
              <AD_OBS1/>
              <DTADIAM/>
              <CODSITE/>
              <BASESUBSTSEMRED/>
              <DTNEG>". date("d/m/Y") ." 0:00:00</DTNEG>
              <CODCONTATOENTREGA/>
              <TIPNOTAPMB/>
              <AD_CODFIADOR1/>
              <REGESPTRIBUT/>
              <TOTDISPDESC/>
              <REBOQUE3/>
              <AD_CODPARC1/>
              <CODVTP/>
              <DTENVSUF/>
              <LOCEMBARQ/>
              <KMVEICULO/>
              <LIBPENDENTE/>
              <TPASSINANTE/>
              <DHPROTOC/>
              <INDPRESNFCE>0</INDPRESNFCE>
              <PESOBRUTO/>
              <CHAVECTE/>
              <CODUFDESTINO/>
              <CODART/>
              <VLRINDENIZ/>
              <STATUSCONFERENCIA/>
              <CNPJADQUIRENTE/>
              <DTENTSAIINFO/>
              <PESOAENTREGAR/>
              <CODUFENTREGA/>
              <BASECOFINSST/>
              <NUMCONTRATO/>
              <VLRICMSDIFALDEST/>
              <CODOBRA/>
              <CLASCONS/>
              <AD_OBSERVACAO_CLIENTE/>
              <CODGRUPOTENSAO/>
              <HRMOV/>
              <VLRMOEDA/>
              <CODOBSPADRAO/>
              <IPIEMB/>
              <OBSERVACAOAC/>
              <CODHISTAC/>
              <CIOT/>
              <HRENTSAI/>
              <NUMCOTACAO/>
              <CODCIDENTREGA/>
              <VLRICMSEMB/>
              <VLROUTROS/>
              <CODLOCALORIG/>
              <AD_CONDICAO/>
              <IDIPROC/>
              <CODEMPFUNC/>
              <CHAVENFSE/>
              <NROCAIXA/>
              <NUODP/>
              <CODMOTORISTA/>
              <REBOQUE2/>
              <VLRFRETECPL/>
              <BASECOFINS/>
              <AD_DTTREINAMENTO/>
              <MARCA/>
              <PESO/>
              <COMISSAO/>
              <NUMNFSE/>
              <LIBCONF/>
              <NUMCF/>
              <AD_ESPECIAIS/>
              <VLRPIS/>
              <UFEMBARQ/>
              <NUMPEDIDO2/>
              <CODCIDORIGEM/>
              <CODDOCA/>
              <CODUSUCOMPRADOR/>
              <CODUSU/>
              <PESOLIQITENS/>
              <CHAVENFEREF/>
              <VLRFRETETOTAL/>
              <SITUACAOCTE/>
              <AD_CANCELAR/>
              <STATUSWMS/>
              <TERMACORDNOTA/>
              <AD_FRETE/>
              <TIPLIBERACAO/>
              <VLRROYALT/>
              <NUMALEATORIOCTE/>
              <VLRMERCADORIA/>
              <LOTACAO/>
              <NURD8/>
              <VLRICMSFCP/>
              <VLRICMSFCPINT/>
              <TOTALCUSTOPROD/>
              <AD_NUREL/>
              <TPAMBCTE/>
              <TPAMBNFSE/>
              <VIATRANSP/>
              <DTREMRET/>
              <M3AENTREGAR/>
              <CODCC/>
              <COMGER/>
              <VLREMB/>
              <QTDVOL/>
              <CODMAQ/>
              <BASEPIS/>
              <ALIQIRF/>
              <VLRINDENIZDIST/>
              <VLRDESTAQUE/>
              <AD_VLRCREDISS/>
              <VLRSEG/>
              <EXIGEISSQN/>
              <FORMPGTCTE/>
              <VLRNOTA/>
              <REBOQUE1/>
              <TIPOPTAGJNFE/>
              <AD_STATUS_CREDITO/>
              <VLRIRF/>
              <AD_CODPARC2/>
              <AD_GARANTIA/>
              <VLRVENDOR/>
              <VOLUME/>
              <AD_CODFIADOR2/>
              <VLRJURO/>
              <VLRDESCTOT/>
              <VLRDESCSERV/>
              <CODUSUINC/>
              <PERCDESC/>
              <PERCDESCFOB/>
              <CODCIDDESTINO/>
              <STATUSCTE/>
              <BASEICMS/>
              <AD_CONDICAO_ESPECIAL/>
              <NULOTENFSE/>
              <NUMPROTOC/>
              <DTCONTAB/>
              <CODPARCCONSIGNATARIO/>
              <VLRICMS/>
              <VLRCOFINS/>
              <DANFE/>
              <PENDENTE/>
              <AD_STATUS_PEDIDO/>
              <TOTALCUSTOSERV/>
              <OCCN48/>
              <PEDIDOIMPRESSO/>
              <CPFCNPJADQUIRENTE/>
              <VLRSUBST/>
              <MODENTREGA/>
              <BASESUBSTIT/>
              <NULOTENFE/>
              <NUCONFATUAL/>
              <TPAMBNFE/>
              <BASEIRF/>
              <HRADIAM/>
              <VLRICMSSEG/>
              <OBSERVACAO>Venda efetuada pelo site. Número: #$orderId</OBSERVACAO>
              <NUPCA/>
              <DTENTSAI/>
              <STATUSNFE/>
              <BASEIPI/>
              <BASEICMSFRETE/>
              <CODTIPOPER>103</CODTIPOPER>
              <CODVEICULO/>
              <PESOBRUTOMANUAL/>
              <DHPROTOCCTE/>
              <TIPOCTE/>
              <CODPARCREDESPACHO/>
              <ORDEMCARGA/>
              <VLRJURODIST/>
              <VLRIPI/>
              <ANTT/>
              <DHREGDPEC/>
              <RETORNADOAC/>
              <VLRTOTLIQITEMMOE/>
              <TPEMISNFE/>
              <BASEISS/>
              <CODVEND/>
              <CHAVENFE/>
              <CODCID/>
              <UFVEICULO/>
              <TIPPROCIMP/>
              <NUMREGDPEC/>
              <ICMSFRETE/>
              <CODTIPVENDA>$tipo_venda</CODTIPVENDA>
              <ISSRETIDO>N</ISSRETIDO>
              <NUPEDFRETE/>
              <AD_PRAZO_ENTREGA/>
              <NUNOTAPEDFRET/>
              <SEQCARGA/>
              <SERIENFDES/>
              <VLRISS/>
              <!--DHTIPVENDA>04/04/2014 10:36:37</DHTIPVENDA-->
              <VLRPISST/>
              <CODVENDTEC>0</CODVENDTEC>
              <DIGITAL/>
              <BASEINSS/>
              <CODTPD/>
              <CODPARCTRANSP/>
              <CODFUNC/>
              <VENCFRETE/>
              <SERIENOTA/>
              <TIPSERVCTE/>
              <NUMERACAOVOLUMES/>
              <NATUREZAOPERDES/>
              <MOTNAORETERISSQN/>
              <FUSOEMISSEPEC/>
              <M3/>
              <TIPFRETE>N</TIPFRETE>
              <CODMOEDA/>
              <MODELONFDES/>
              <RATEADO/>
              <SITESPECIALRESP/>
              <PRODUETLOC/>
              <NUREM/>
              <CONFIRMADA/>
              <BASEPISST/>
              <AD_CODVERIF/>
              <AD_NUNFE/>
              <PESOBRUTOITENS/>
              <LACRES/>
              <AD_STATUS_COMENTARIO/>
              <NOMEADQUIRENTE/>
              <NOTAEMPENHO/>
              <TROCO/>
              <DTALTER/>
              <CLASSIFICMS/>
              <CODPARCREMETENTE/>
              <NUFOP/>
              <NUNOTA/>
              <CODPARCDEST/>
              <NOTASCF/>
              <VLRCOMPENSACAO/>
              <IRFRETIDO>S</IRFRETIDO>
              <VLRCOFINSST/>
              <VENCIPI/>
              <SITUACAOWMS/>
              <VLRREPREDTOT/>
              <NUMNOTA>0</NUMNOTA>
              <CODRASTREAMENTOECT/>
              <CODPARC>$parceiro</CODPARC>
              <AD_DTVENCISS/>
              <AD_HISTORICO/>
              <DESCRHISTAC/>
              <DESCTERMACORD/>
              <VLRFRETE>$frete</VLRFRETE>
              <VLRINSS/>
              <PESOLIQUIMANUAL/>
              <STATUSNOTA/>
              <DTENVIOPMB/>
              <TPLIGACAO/>
              <PLACA/>
              <VLRDESCTOTITEM/>
              <DTVAL/>
              <CODUFORIGEM/>
              <NUMPROTOCCTE/>
              <VLRSTEXTRANOTATOT/>
              <VLRSTFCPINT/>
              <VLRSTFCPINTANT/>
              <DTPREVENT/>
              <NUMOS/>
              <NUMALEATORIO/>
              <DTDECLARA/>
              <CTELOTACAO/>
              <TIPMOV>P</TIPMOV>
              <AD_ALIISS/>
              <CIF_FOB>F</CIF_FOB>
              <TPEMISNFSE/>
              <UFADQUIRENTE/>
              <CODEMP>2</CODEMP>
              <TPEMISCTE/>
              <NROREDZ/>
              <NUTRANSF/>
              <NUNOTASUB/>
              <DHTIPOPER>04/12/2015 12:25:56</DHTIPOPER>
              <VLRDESCTOTITEMMOE/>
              <NULOTECTE/>
              <STATUSNFSE/>
              <DHEMISSEPEC/>
              <PRODPRED/>
              <CODCONTATO>1</CODCONTATO>
              <DTMOV/>
              <CODNAT>310101</CODNAT>
              <CODCENCUS>1010300</CODCENCUS>
              <CODCIDPREST/>
              <CODEMPNEGOC/>
              <CODPROJ>10101</CODPROJ>
              <!--CODPROJ/-->
              <CODPARCTRANSPFINAL/>
              <TIPIPIEMB>N</TIPIPIEMB>
              <APROVADO/>
              <LOCALCOLETA/>
              <DTFATUR/>
              <LOCALENTREGA/>
              <VLRICMSDIFALREM/>
              <CODMODDOCNOTA/>
            </cabecalho>
          </nota>
          <txProperties>
            <prop name=\"br.com.sankhya.mgefin.recalculo.custopreco.Automatico\" value=\"false\"/>
            <prop name=\"cabecalhoNota.inserindo.pedidoWeb\" value=\"false\"/>
            <prop name=\"br.com.sankhya.mgefin.checarfinanceiro.RecalcularVencimento\" value=\"false\"/>
            <prop name=\"br.com.sankhya.mgefin.checarfinanceiro.VlrEntrada\" value=\"0\"/>
          </txProperties>
          <clientEventList>
            <clientEvent>br.com.sankhya.mgecomercial.event.estoque.insuficiente.produto</clientEvent>
            <clientEvent>central.save.grade.itens.mostrar.popup.serie</clientEvent>
            <clientEvent>central.save.grade.itens.mostrar.popup.info.lote</clientEvent>
            <clientEvent>br.com.sankhya.mgecomercial.event.cadastrarDistancia</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.imobilizado</clientEvent>
            <clientEvent>br.com.sankhya.mgecomercial.event.faturamento.confirmacao</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.central.itens.KitRevenda.msgValidaFormula</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.compra.SolicitacaoComprador</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.central.itens.KitRevenda</clientEvent>
            <clientEvent>br.com.utiliza.dtneg.servidor</clientEvent>
            <clientEvent>br.com.sankhya.mgecomercial.event.compensacao.credito.debito</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.coleta.entrega.recalculado</clientEvent>
            <clientEvent>br.com.sankhya.mgecomercial.event.baixaPortal</clientEvent>
            <clientEvent>br.com.sankhya.mgefin.solicitacao.liberacao.orcamento</clientEvent>
            <clientEvent>br.com.sankhya.exibir.variacao.valor.item</clientEvent>
            <clientEvent>br.com.sankhya.mgefin.event.fixa.vencimento</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.event.troca.item.por.produto.alternativo</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.cancelamento.notas.remessa</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.valida.ChaveNFeCompraTerceiros</clientEvent>
            <clientEvent>br.com.sankhya.actionbutton.clientconfirm</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.central.itens.VendaCasada</clientEvent>
            <clientEvent>br.com.sankhya.mgecomercial.event.estoque.componentes</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.nota.adicional.SolicitarUsuarioGerente</clientEvent>
            <clientEvent>br.com.sankhya.importacaoxml.cfi.para.produto</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.event.troca.item.por.produto.substituto</clientEvent>
            <clientEvent>br.com.sankhya.exibe.msg.variacao.preco</clientEvent>
            <clientEvent>br.com.sankhya.mgecom.expedicao.SolicitarUsuarioConferente</clientEvent>
          </clientEventList>
        </requestBody>
      </serviceRequest>", true);
  }

  function inicializa_item ($nota, $item) {
    // http://sankhya.medsystems.com.br:8280/mgecom/service.sbr?serviceName=CACSP.inicializaProdutoItemNota&counter=1141032206&application=CentralNotasStack&mgeSession=E089598F488E464833FCB5B9BEE793DC&resourceID=br.com.sankhya.com.mov.CentralNotas&vss=1&globalID=eace3ed9d3ef4b91b2632cb3d5228ced&re=1
    return $this->curl_call('mgecom/service.sbr?serviceName=CACSP.inicializaProdutoItemNota',
      "<serviceRequest serviceName=\"CACSP.inicializaProdutoItemNota\">
        <requestBody>
          <NOTA CIF_FOB=\"F\" CODEMP=\"2\" CODEMPNEGOC=\"2\" CODLOCALORIG=\"0\" CODPARC=\"60\" CODPROD=\"$item\" CODTIPOPER=\"103\" CODTIPVENDA=\"560\" CODVEND=\"0\" DTNEG=\"". date("d/m/Y") ." 0:00:00\" EHMATERIAPRIMA=\"false\" NUNOTA=\"$nota\"/>
        </requestBody>
      </serviceRequest>", true);
  }

  function incluir_item_nota ($nota, $itens) {
    // http://sankhya.medsystems.com.br:8280/mgecom/service.sbr?serviceName=CACSP.incluirAlterarItemNota&counter=1141031953&application=CentralNotasStack&mgeSession=E089598F488E464833FCB5B9BEE793DC&resourceID=br.com.sankhya.com.mov.CentralNotas&vss=1&globalID=eace3ed9d3ef4b91b2632cb3d5228ced&re=1

    $itens_xml = "";
    for ($i=0; $i < sizeof($itens); $i++) {
      $item = $itens[$i];
      $parsed = $this->inicializa_item($nota, $item['id']);
      $item_nota = $parsed->responseBody->NOTA;
      // $itens_xml .= "
      //   <item>
      //     <AD_DESCMAX/>
      //     <AD_DESPCOMPL/>
      //     <AD_DISPFINAL/>
      //     <AD_DISPINICIAL/>
      //     <AD_FRANQUIA1/>
      //     <AD_GARANTIA/>
      //     <AD_MOT_BONIFICA/>
      //     <AD_VLRDISPARO/>
      //     <ALIQICMS>4</ALIQICMS>
      //     <ALIQICMSRED/>
      //     <ALIQIPI>0</ALIQIPI>
      //     <ALIQISS/>
      //     <ALTPRECO/>
      //     <ALTURA/>
      //     <ATUALESTOQUE/>
      //     <ATUALESTTERC/>
      //     <BASEICMS/>
      //     <BASEIPI/>
      //     <BASEISS/>
      //     <BASESTANT>0</BASESTANT>
      //     <BASESTUFDEST>0</BASESTUFDEST>
      //     <BASESUBSTIT>0</BASESUBSTIT>
      //     <BASESUBSTITANT/>
      //     <BASESUBSTITUNITORIG/>
      //     <BASESUBSTSEMRED/>
      //     <BASICMMOD/>
      //     <BASICMSTMOD/>
      //     <CODANTECIPST/>
      //     <CODCAV/>
      //     <CODCFO/>
      //     <CODCFPS/>
      //     <CODEMP/>
      //     <CODENQIPI/>
      //     <CODESPECST/>
      //     <CODEXEC/>
      //     <CODLOCALDEST/>
      //     <CODLOCALORIG>" . $item_nota->CODLOCALORIG . "</CODLOCALORIG>
      //     <CODMOTDESONERAICMS/>
      //     <CODOBSPADRAO/>
      //     <CODPARCEXEC/>
      //     <CODPROC/>
      //     <CODPROD>".$item['id']."</CODPROD>
      //     <CODPROMO/>
      //     <CODTPA/>
      //     <CODTRIB/>
      //     <CODTRIBISS/>
      //     <CODUSU/>
      //     <CODVEND>0</CODVEND>
      //     <CODVOL>" . $item_nota->CODVOL . "</CODVOL>
      //     <CODVOLPAD>" . $item_nota->CODVOLPAD . "</CODVOLPAD>
      //     <CODVOLPARC/>
      //     <COMPLDESC/>
      //     <CONTROLE/>
      //     <CONTROLEDEST/>
      //     <CSOSN/>
      //     <CSTIPI/>
      //     <CUSTO>" . $item_nota->CUSTO . "</CUSTO>
      //     <DTALTER/>
      //     <DTINICIO/>
      //     <DTVIGOR/>
      //     <ENDIMAGEM/>
      //     <ESPESSURA/>
      //     <ESTOQUE/>
      //     <FATURAR/>
      //     <GRUPOTRANSG/>
      //     <GTINNFE/>
      //     <GTINTRIBNFE/>
      //     <IDALIQICMS/>
      //     <LARGURA/>
      //     <M3>0</M3>
      //     <MARCA>" . $item_nota->MARCA . "</MARCA>
      //     <NCM>" . $item_nota->NCM . "</NCM>
      //     <NROPROCESSO/>
      //     <NRSERIERESERVA/>
      //     <NUFOP/>
      //     <NUMCONTRATO/>
      //     <NUMEROOS/>
      //     <NUMPEDIDO2/>
      //     <NUNOTA>$nota</NUNOTA>
      //     <NUPROMOCAO/>
      //     <NUTAB>20</NUTAB>
      //     <OBSERVACAO>Venda efetuada pelo site.</OBSERVACAO>
      //     <ORIGPROD/>
      //     <PENDENTE/>
      //     <PERCCOM/>
      //     <PERCCOMGER/>
      //     <PERCDESC>0</PERCDESC>
      //     <PERCDESCBASE/>
      //     <PERCDESCBONIF/>
      //     <PERCDESCDIGITADO/>
      //     <PERCDESCPROM/>
      //     <PERCDESCTGFDES/>
      //     <PERCGERMIN/>
      //     <PERCPUREZA/>
      //     <PESOBRUTO/>
      //     <PESOLIQ/>
      //     <PRECOBASE>0</PRECOBASE>
      //     <PRECOBASEQTD/>
      //     <PRODUTOALTERNATIVO>N</PRODUTOALTERNATIVO>
      //     <PRODUTONFE/>
      //     <QTDCONFERIDA/>
      //     <QTDENTREGUE/>
      //     <QTDFAT/>
      //     <QTDFIXADA/>
      //     <QTDFORMULA/>
      //     <QTDNEG>".$item['qtd']."</QTDNEG>
      //     <QTDPECA/>
      //     <QTDPENDENTE/>
      //     <QTDUNIDPAD>".$item['qtd']."</QTDUNIDPAD>
      //     <QTDVOL>1</QTDVOL>
      //     <QTDWMS/>
      //     <REFERENCIA/>
      //     <REFFORN/>
      //     <RESERVA/>
      //     <SEQPEDIDO2/>
      //     <SEQUENCIA/>
      //     <SOLCOMPRA/>
      //     <STATUSLOTE>N</STATUSLOTE>
      //     <STATUSNOTA>A</STATUSNOTA>
      //     <TERCEIROS/>
      //     <USOPROD>R</USOPROD>
      //     <VARIACAOFCP/>
      //     <VLRACRESCDESC/>
      //     <VLRCOM/>
      //     <VLRCOMGER/>
      //     <VLRCUS>0</VLRCUS>
      //     <VLRDESC>0</VLRDESC>
      //     <VLRDESCBONIF/>
      //     <VLRDESCDIGITADO/>
      //     <VLRDESCMOE>0</VLRDESCMOE>
      //     <VLRICMS>0</VLRICMS>
      //     <VLRICMSANT/>
      //     <VLRICMSUFDEST>0</VLRICMSUFDEST>
      //     <VLRIPI>0</VLRIPI>
      //     <VLRISS/>
      //     <VLRLIQPROM/>
      //     <VLRPROMO/>
      //     <VLRPTOPUREZA/>
      //     <VLRREPRED/>
      //     <VLRRETENCAO/>
      //     <VLRSTEXTRANOTA/>
      //     <VLRSUBST>0</VLRSUBST>
      //     <VLRSUBSTANT/>
      //     <VLRSUBSTUNITORIG/>
      //     <VLRSUGERIDO/>
      //     <VLRTOT>".($item['qtd']*$item['valor'])."</VLRTOT>
      //     <VLRTOTLIQ>".($item['qtd']*$item['valor'])."</VLRTOTLIQ>
      //     <VLRTOTLIQMOE>0</VLRTOTLIQMOE>
      //     <VLRTOTMOE>0</VLRTOTMOE>
      //     <VLRTROCA/>
      //     <VLRUNIDPAD>".$item['valor']."</VLRUNIDPAD>
      //     <VLRUNIT>".$item['valor']."</VLRUNIT>
      //     <VLRUNITDOLAR/>
      //     <VLRUNITLIQ>".$item['valor']."</VLRUNITLIQ>
      //     <VLRUNITLIQMOE>0</VLRUNITLIQMOE>
      //     <VLRUNITLOC/>
      //     <VLRUNITMOE>0</VLRUNITMOE>
      //   </item>
      // ";
      $itens_xml .= "
        <item>
          <AD_DESCMAX/>
          <AD_DESPCOMPL/>
          <AD_DISPFINAL/>
          <AD_DISPINICIAL/>
          <AD_FRANQUIA1/>
          <AD_GARANTIA/>
          <AD_MOT_BONIFICA/>
          <AD_VLRDISPARO/>
          <ALIQICMS>4</ALIQICMS>
          <!-- <ALIQICMSRED/> -->
          <ALIQIPI>0</ALIQIPI>
          <ALIQISS/>
          <ALTPRECO/>
          <ALTURA/>
          <ATUALESTOQUE/>
          <ATUALESTTERC/>
          <!-- <BASEICMS/> -->
          <BASEIPI/>
          <BASEISS/>
          <BASESTANT>0</BASESTANT>
          <BASESTUFDEST>0</BASESTUFDEST>
          <BASESUBSTIT>0</BASESUBSTIT>
          <BASESUBSTITANT/>
          <BASESUBSTITUNITORIG/>
          <BASESUBSTSEMRED/>
          <BASICMMOD/>
          <!-- <BASICMSTMOD/> -->
          <CODANTECIPST/>
          <CODCAV/>
          <CODCFO/>
          <CODCFPS/>
          <CODEMP/>
          <CODENQIPI/>
          <CODESPECST/>
          <CODEXEC/>
          <CODLOCALDEST/>
          <CODLOCALORIG>" . $item_nota->CODLOCALORIG . "</CODLOCALORIG>
          <!-- <CODMOTDESONERAICMS/> -->
          <CODOBSPADRAO/>
          <CODPARCEXEC/>
          <CODPROC/>
          <CODPROD>".$item['id']."</CODPROD>
          <CODPROMO/>
          <CODTPA/>
          <CODTRIB/>
          <CODTRIBISS/>
          <CODUSU/>
          <CODVEND>0</CODVEND>
          <CODVOL>" . $item_nota->CODVOL . "</CODVOL>
          <CODVOLPAD>" . $item_nota->CODVOLPAD . "</CODVOLPAD>
          <CODVOLPARC/>
          <COMPLDESC/>
          <CONTROLE/>
          <CONTROLEDEST/>
          <CSOSN/>
          <CSTIPI/>
          <CUSTO>" . $item_nota->CUSTO . "</CUSTO>
          <DTALTER/>
          <DTINICIO/>
          <DTVIGOR/>
          <ENDIMAGEM/>
          <ESPESSURA/>
          <ESTOQUE/>
          <FATURAR/>
          <GRUPOTRANSG/>
          <GTINNFE/>
          <GTINTRIBNFE/>
          <!-- <IDALIQICMS/> -->
          <LARGURA/>
          <M3>0</M3>
          <MARCA>" . $item_nota->MARCA . "</MARCA>
          <NCM>" . $item_nota->NCM . "</NCM>
          <NROPROCESSO/>
          <NRSERIERESERVA/>
          <NUFOP/>
          <NUMCONTRATO/>
          <NUMEROOS/>
          <NUMPEDIDO2/>
          <NUNOTA>$nota</NUNOTA>
          <NUPROMOCAO/>
          <NUTAB>20</NUTAB>
          <OBSERVACAO>Venda efetuada pelo site.</OBSERVACAO>
          <ORIGPROD/>
          <PENDENTE/>
          <PERCCOM/>
          <PERCCOMGER/>
          <PERCDESC>0</PERCDESC>
          <PERCDESCBASE/>
          <PERCDESCBONIF/>
          <PERCDESCDIGITADO/>
          <PERCDESCPROM/>
          <PERCDESCTGFDES/>
          <PERCGERMIN/>
          <PERCPUREZA/>
          <PESOBRUTO/>
          <PESOLIQ/>
          <PRECOBASE>0</PRECOBASE>
          <PRECOBASEQTD/>
          <PRODUTOALTERNATIVO>N</PRODUTOALTERNATIVO>
          <PRODUTONFE/>
          <QTDCONFERIDA/>
          <QTDENTREGUE/>
          <QTDFAT/>
          <QTDFIXADA/>
          <QTDFORMULA/>
          <QTDNEG>".$item['qtd']."</QTDNEG>
          <QTDPECA/>
          <QTDPENDENTE/>
          <QTDUNIDPAD>".$item['qtd']."</QTDUNIDPAD>
          <QTDVOL>1</QTDVOL>
          <QTDWMS/>
          <REFERENCIA/>
          <REFFORN/>
          <RESERVA/>
          <SEQPEDIDO2/>
          <SEQUENCIA/>
          <SOLCOMPRA/>
          <STATUSLOTE>N</STATUSLOTE>
          <STATUSNOTA>A</STATUSNOTA>
          <TERCEIROS/>
          <USOPROD>R</USOPROD>
          <VARIACAOFCP/>
          <VLRACRESCDESC/>
          <VLRCOM/>
          <VLRCOMGER/>
          <VLRCUS>0</VLRCUS>
          <VLRDESC>0</VLRDESC>
          <VLRDESCBONIF/>
          <VLRDESCDIGITADO/>
          <VLRDESCMOE>0</VLRDESCMOE>
          <!-- <VLRICMS>0</VLRICMS> -->
          <!-- <VLRICMSANT/> -->
          <!-- <VLRICMSUFDEST>0</VLRICMSUFDEST> -->
          <VLRIPI>0</VLRIPI>
          <VLRISS/>
          <VLRLIQPROM/>
          <VLRPROMO/>
          <VLRPTOPUREZA/>
          <VLRREPRED/>
          <VLRRETENCAO/>
          <VLRSTEXTRANOTA/>
          <VLRSUBST>0</VLRSUBST>
          <VLRSUBSTANT/>
          <VLRSUBSTUNITORIG/>
          <VLRSUGERIDO/>
          <VLRTOT>".($item['qtd']*$item['valor'])."</VLRTOT>
          <VLRTOTLIQ>".($item['qtd']*$item['valor'])."</VLRTOTLIQ>
          <VLRTOTLIQMOE>0</VLRTOTLIQMOE>
          <VLRTOTMOE>0</VLRTOTMOE>
          <VLRTROCA/>
          <VLRUNIDPAD>".$item['valor']."</VLRUNIDPAD>
          <VLRUNIT>".$item['valor']."</VLRUNIT>
          <VLRUNITDOLAR/>
          <VLRUNITLIQ>".$item['valor']."</VLRUNITLIQ>
          <VLRUNITLIQMOE>0</VLRUNITLIQMOE>
          <VLRUNITLOC/>
          <VLRUNITMOE>0</VLRUNITMOE>
        </item>
      ";
    }

    return $this->curl_call('mgecom/service.sbr?serviceName=CACSP.incluirAlterarItemNota',
      "<serviceRequest serviceName=\"CACSP.incluirAlterarItemNota\">
        <requestBody>
          <nota NUNOTA=\"$nota\" ownerServiceCall=\"GradeItens25702\">
            <itens>
              $itens_xml
            </itens>
          </nota>
        </requestBody>
      </serviceRequest>", true);
  }

  function confirmar_nota ($nota) {
    // http://sankhya.medsystems.com.br:8280/mgecom/service.sbr?serviceName=CACSP.confirmarNota&counter=1141032008&application=CentralNotasStack&mgeSession=E089598F488E464833FCB5B9BEE793DC&resourceID=br.com.sankhya.com.mov.CentralNotas&vss=1&globalID=eace3ed9d3ef4b91b2632cb3d5228ced&re=1
    return $this->curl_call('mgecom/service.sbr?serviceName=CACSP.confirmarNota',
      "<serviceRequest serviceName=\"CACSP.confirmarNota\">
        <requestBody>
          <nota atualizaPrecoItemPedCompra=\"false\" confirmacaoCentralNota=\"true\" ehPedidoWeb=\"false\" ownerServiceCall=\"CentralNotas7426\">
            <NUNOTA>$nota</NUNOTA>
          </nota>
        </requestBody>
      </serviceRequest>", true);
  }

  function get_users ($ids) {
    // http://sankhya.medsystems.com.br:8280/mge/service.sbr?serviceName=Pesquisa.applySearch
    return $this->curl_call('mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords',
      "<serviceRequest serviceName=\"CRUDServiceProvider.loadRecords\">
        <requestBody>
          <dataSet crudListener=\"br.com.sankhya.modelcore.crudlisteners.ParceiroCrudListener\" datasetid=\"1514463233118_1\" includePresentationFields=\"S\" parallelLoader=\"true\" rootEntity=\"Parceiro\">
            <entity path=\"\">
              <fieldset list=\"CODPARC,NOMEPARC,EMAIL,CGC_CPF,CEP,NUMEND,COMPLEMENTO,TELEFONE\"/>
            </entity>
            <entity path=\"Endereco\">
              <field name=\"TIPO\"/>
              <field name=\"NOMEEND\"/>
            </entity>
            <entity path=\"Bairro\">
              <field name=\"NOMEBAI\"/>
            </entity>
            <entity path=\"Cidade\">
              <field name=\"NOMECID\"/>
              <field name=\"UF\"/>
            </entity>
            <criteria>
              <expression>this.CLIENTE = 'S' AND this.EMAIL != '' and this.CODPARC not in (". join($ids, ',') .")</expression>
              <!--expression>(this.CODPARC &lt; 30) AND this.CLIENTE = 'S' AND this.EMAIL != '' and this.CODPARC not in (". join($ids, ',') .")</expression-->
            </criteria>
          </dataSet>
        </requestBody>
      </serviceRequest>");
  }

  // http://sankhya.medsystems.com.br:8280/mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords
  function consulta_endereco ($cep) {
    return $this->curl_call('mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords',
      '<serviceRequest serviceName="CRUDServiceProvider.loadRecords">
        <requestBody>
          <dataSet datasetid="1513621429746_2" includePresentationFields="S" parallelLoader="true" rootEntity="CEP">
            <entity path="">
              <fieldset list="*"/>
            </entity>
            <entity path="Endereco">
              <field name="NOMEEND"/>
              <field name="TIPO"/>
            </entity>
            <entity path="Bairro">
              <field name="NOMEBAI"/>
            </entity>
            <entity path="Cidade">
              <field name="NOMECID"/>
              <field name="UF"/>
            </entity>
            <entity path="UnidadeFederativa">
              <field name="*"/>
            </entity>
            <criteria>
              <expression>(this.CEP = ?)</expression>
              <parameter type="S">'.$cep.'</parameter>
            </criteria>
          </dataSet>
          <clientEventList>
            <clientEvent>parceiro.mostra.mensagem.criticaie</clientEvent>
          </clientEventList>
        </requestBody>
      </serviceRequest>', true);
  }

  // http://sankhya.medsystems.com.br:8280/mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords
  function consulta_pedidos ($notas) {
    return $this->curl_call('mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords',
      '<serviceRequest serviceName="CRUDServiceProvider.loadRecords">
        <requestBody>
          <dataSet crudListener="br.com.sankhya.modelcore.dwfdata.listeners.tgf.CabecalhoNotaListenerAdapter" datasetid="1507817435280_8" includePresentationFields="S" parallelLoader="true" rootEntity="CabecalhoNota">
            <entity path="">
              <fieldset list="*"/>
              <fieldset except="true" list="CODTPD,CODVTP"/>
              <fieldset except="true" list="DHTIPVENDA,DHTIPOPER"/>
            </entity>
            <!--entity path="TipoOperacao">
              <fieldset list="DESCROPER,NFSE,SOLCOMPRA,ATUALFIN,ATUALEST,CODMODDOC,BASENUMERACAO,NFE"/>
              <fieldset list="DESCROPER,NFSE,SOLCOMPRA"/>
            </entity>
            <entity path="Parceiro">
              <fieldset list="NOMEPARC,TIPOFATUR"/>
            </entity>
            <entity path="Contato">
              <field name="NOMECONTATO"/>
            </entity>
            <entity path="Natureza">
              <field name="DESCRNAT"/>
            </entity>
            <entity path="Empresa">
              <field name="NOMEFANTASIA"/>
            </entity>
            <entity path="Empresa.EmpresaFinanceiro">
              <field name="NFSE"/>
              <field name="PRZREGCANNOTA"/>
              <field name="PRZTOLCANNOTA"/>
              <field name="TEMDENUESPONT"/>
            </entity>
            <entity path="TipoNegociacao">
              <fieldset list="DESCRTIPVENDA,FIXAVENC"/>
            </entity>
            <entity path="Vendedor">
              <field name="APELIDO"/>
            </entity>
            <entity path="Funcionario">
              <field name="NOMEFUNC"/>
            </entity>
            <entity path="Transportadora">
              <field name="NOMEPARC"/>
            </entity>
            <entity path="Moeda">
              <field name="NOMEMOEDA"/>
            </entity>
            <entity path="LoteNotaFiscalEletronica">
              <field name="NUMRECEB"/>
              <field name="DHRECEB"/>
            </entity>
            <entity path="Empresa.Cidade.UnidadeFederativa">
              <field name="UF"/>
            </entity>
            <entity path="Projeto">
              <field name="IDENTIFICACAO"/>
            </entity-->
            <criteria>
              <expression>(NOT EXISTS (SELECT NUNOTA FROM TGFCAN WHERE TGFCAN.NUNOTA = this.NUNOTA) AND this.TIPMOV IN (?, ?, ?, ?) AND this.NUNOTA in (' . join($notas, ',') . '))</expression>
              <parameter type="S">P</parameter>
              <parameter type="S">V</parameter>
              <parameter type="S">D</parameter>
              <parameter type="S">A</parameter>
              <!--parameter type="N">50215</parameter-->
            </criteria>
          </dataSet>
        </requestBody>
      </serviceRequest>', true);
  }

  function __destruct () {

  }
}
?>
