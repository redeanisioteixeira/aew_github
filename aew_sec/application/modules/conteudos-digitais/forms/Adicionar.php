<?php
class ConteudosDigitais_Form_Adicionar extends Sec_Form 
{
    protected $extensaovalida = array(1 => "mp4", 2 => "flv", 3 => "webm", 4 => "aac", 5 => "mp3", 6 => "vorbis", 7 => "ogg", 8 => "swf", 9 => "link", 10 => "avi", 11 => "gif", 12 => 'jpg', 13 => 'zip', 14 => 'pdf');
    
    public function init()
    {
        $formatoBo = new Aew_Model_Bo_Formato();
        $options = array();
        $options['where']['formato.nomeformato in (?)'] = array('pdf', 'doc','docx', 'zip', 'rar');
        
        $conteudoExtensions = '';
        $guiaPedagogicoExtensions = $formatoBo->getList($options);
        $imagemAssociativaExtensions = $formatoBo->getListImagem();

        $conteudoMaxSize = '500MB';
        $guiaPedagogicoMaxSize = '60MB';
        $ImagemAssociativaMaxSize = '5MB';
        
        $this->setAttrib('id', 'conteudo-digital-form')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('role', 'formulario');

        $mensagemComponente = $this->createElement('radio', 'mensagem_componente');
        $mensagemComponente->setLabel('');
        $this->addElement($mensagemComponente);

        $titulo = $this->createElement('text','titulo');
        $titulo->setLabel('Titulo :')
                ->setDescription('<span class="text-info" >Adicione o nome original da mídia.</span>')
                ->setAttrib('maxlength', 250)
                ->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->addFilters(array(new Zend_Filter_StringTrim()))
                ->setAttrib('title', 'Titulo');
        $this->addElement($titulo);
        
        if(!$this->AdicionarCanal($conteudoExtensions)){
            $conteudoTipoBo = new Aew_Model_Bo_ConteudoTipo();
            $conteudoTipoArray = $conteudoTipoBo->getAllForSelect('idconteudotipo', 'nomeconteudotipo', 'Selecione');

            $conteudoTipo = $this->createElement('select','idconteudotipo');
            $conteudoTipo->setLabel('Tipo de Conteúdo :')
                    ->setRequired(true)
                    ->setDescription('<span class="text-info">Escolha a opção que mais se adéqua à mídia que deseja publicar, conforme opções disponíveis.</span>')
                    ->setMultiOptions($conteudoTipoArray)
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('title', 'Tipo do conteúdo');
            $this->addElement($conteudoTipo);
            
            $flsitetematico = $this->createElement('checkbox', 'flsitetematico');
            $flsitetematico->setLabel('Incluir conteúdo como <b>"Site Temático"</b> :')
                        ->setAttrib('id', 'flsitetematico')
                        ->setCheckedValue('t')
                        ->setUncheckedValue('f')
                        ->setAttrib('title', 'Destinar conteúdo para Sites Temáticos');
            $this->addElement($flsitetematico);
        }

        $descricao = $this->createElement('richText','descricao');
        $descricao->setLabel('Descrição :')
                ->setRequired(true)
                ->setDescription('<span class="text-info">Descreva as mídias de forma <strong>resumida</strong> e <strong>objetiva</strong>. Esta é a primeira apresentação da mídia e pode ser o diferencial na hora do usuário escolher se acessa ou não. Verifique outras descrições para adotar o modelo que melhor se adéqua.</span>')
                ->addFilters(array(new Zend_Filter_StringTrim()))
                ->setAttrib('title', 'Descrição conteúdo digital')
                ->setAttrib('class', 'form-control')
                ->setAttrib('rows','20');
        $this->addElement($descricao);
        
        $palavrasChave = $this->createElement('textarea','tags');
        $palavrasChave->setLabel('Palavras-Chave :')
                    ->setRequired(true)
                    ->setDescription('<span class="text-info">Defina com cautela os descritores, pois esse é o campo mais importante para o sucesso da busca. Lembre de separar por vírgulas (,) as palavras escolhidas.')
                    ->addFilters(array(new Zend_Filter_StripTags(), new Sec_Filter_StringToLower()))
                    ->setAttrib('title', 'Palavras-Chave')
                    ->setAttrib('autocomplete', true)
                    ->setAttrib('class', 'form-control resize-none not-enter opcao-busca-tag')
                    ->setAttrib('rows','5');
        $this->addElement($palavrasChave);
        
        $autores = $this->createElement('text','autores');
        $autores->setLabel('Autores :')
                    ->setDescription('<span class="text-info">Nome dos autores ou grupo de trabalho responsável pelo desenvolvimento da mídia.</span>')
                    ->setAttrib('maxlength', 250)
                    ->setRequired(true)
                    ->setAttrib('class', 'form-control')
                    ->addFilters(array(new Zend_Filter_StringTrim()))
                    ->setAttrib('title', 'Autores');
        $this->addElement($autores);

        $fonte = $this->createElement('text','fonte');
        $fonte->setLabel('Fonte :')
                    ->setDescription('<span class="text-info">Indique o site ou o nome da instituição que produziu a mídia.</span>')
                    ->setAttrib('maxlength', 250)
                    ->setRequired(true)
                    ->setAttrib('class', 'form-control')
                    ->addFilters(array(new Zend_Filter_StringTrim()))
                    ->setAttrib('title', 'Fonte');
        $this->addElement($fonte);

        $licencaBo = new Aew_Model_Bo_ConteudoLicenca();
        $options = array();
        $options['orderBy'] = 'conteudolicenca.idconteudolicenca ASC';
        $licencaArray = $licencaBo->getAllForSelect('idconteudolicenca', 'nomeconteudolicenca', 'Selecione','idconteudolicencapai', $options);
        
        $licenca = $this->createElement('select','idconteudolicenca');
        $licenca->setLabel('Licença do conteúdo :')
                ->setDescription('<span class="text-info">Escolha a opção que mais se adéqua à mídia que deseja publicar, conforme opções disponíveis. Se precisar de ajuda clique <a data-toggle="modal" data-target="#modalGeral"  href="/administracao/licenca/listar"><b><u>aqui</u></b></a></span>')
                ->setRequired(true)
                ->setAttrib('class', 'form-control')
                ->setMultiOptions($licencaArray)
                ->setAttrib('title', 'Licença do conteúdo');
        $this->addElement($licenca);

        $iconeLicenca = $this->createElement('image','iconeLicenca');
        $iconeLicenca->setAttrib('class', 'desativado')
                    ->setAttrib('width', '25%')
                    ->setAttrib('disabled', true);
        $this->addElement($iconeLicenca);
        
        $this->addDisplayGroup(array('idconteudolicenca','iconeLicenca'), 'group_imagem_licenca',array('class' => 'panel panel-info padding-all-10 background-azul'));
        
        $radio = $this->createElement('radio', 'desejaCadastrar');
        $radio->setLabel('Selecione origem do contéudo digital :')
                    ->setDescription('<span class="text-info">Selecione a opção <b><u>enviar arquivo do contéudo digital</u></b> se o arquivo original a ser publicado estiver no seu computador, permitindo sua inserção neste ambiente. Se quiser fazer apenas um link para outro site marque a opção <b><u>link do conteúdo digital</u></b>.</span>')
                    ->setAttrib('class', 'cadastrar-origem-midia')
                    ->addMultiOptions(array('enviar'=>'Enviar arquivo do contéudo digital', 'indicar'=>'Link do conteúdo digital'));
        $this->addElement($radio);
	
        $separador = $this->createElement('radio', 'separador_lnk');
        $separador->setLabel('<h5 class="margin-none"><b><i class="fa fa-link"></i> Link do conteúdo digital :</b></h5>');
        $this->addElement($separador);

        $site = $this->createElement('text','site');
        $site->setLabel('Endereço do site recomendado :')
                    ->setDescription('<span class="text-info">Insira o endereço do site recomendado.</span>')
                    ->setAttrib('class', 'form-control')
                    ->addFilters(array(new Zend_Filter_StringTrim()))
                    ->setAttrib('title', 'Referência do conteúdo');
        $this->addElement($site);

        $this->addDisplayGroup(array('separador_lnk','site'), 'group_indicar',array('class' => 'desativado panel panel-info padding-all-10 background-azul'));

        $separador = $this->createElement('radio', 'separador_arq');
        $separador->setLabel('<h5 class="margin-none pull-left"><b><i class="fa fa-folder-open"></i> Arquivo do conteúdo digital :</b></h5>');
        $this->addElement($separador);

        $conteudov = $this->createElement('file','conteudov');
        $conteudov->setLabel('<i class="link-azul fa fa-eye"></i> Conteúdo para visualização :')
                    ->setDescription('<span class="text-info margin-top-10 margin-bottom-10">Esta é a mídia que ficará disponível para visualização no ambiente. É interessante que estes arquivos sejam convertidos para extensões mais usadas na internet. Localize no seu computador o arquivo  a ser publicado. <u class="formatos-dinamico">Formatos permitidos: <b></b></u>.</span>')
                    ->setDestination(Aew_Model_Bo_ConteudoDigital::getConteudoVisualizacaoDirectory())
                    ->setAttrib('class', 'rounded shadow-center padding-all-05')
                    ->addValidator('Count', false, 1)
                    ->addValidator('Size', false, $conteudoMaxSize);
        $this->addElement($conteudov);
        
        $apagarconteudov = $this->createElement('checkbox', 'apagarconteudov');
        $apagarconteudov->setAttrib('class', 'desativado')
                ->setOptions(array('class' => 'desativado'))
                ->setCheckedValue('t')
                ->setUncheckedValue('f');
        $this->addElement($apagarconteudov);
        
        $conteudod = $this->createElement('file','conteudod');
        $conteudod->setLabel('<i class="link-azul fa fa-download"></i> Conteúdo para download :')
                    ->setDescription('<span class="text-info margin-top-10 margin-bottom-10">Esta é a mídia que ficará disponível para download e outros usuários poderão baixar o arquivo. É possível publicar diversas mídias. Sugerimos que ao optar por publicar arquivos de áudio ou vídeo, tentem converter para extensões compatíveis com o monitor educacional. Após clicar em “selecionar conteúdo”, localize no seu computador o arquivo  a ser publicado. <u class="formatos-dinamico">Formatos permitidos: <b></b></u>.</span>')
                    ->setDestination(Aew_Model_Bo_ConteudoDigital::getConteudoDownloadDirectory())
                    ->setAttrib('class', 'rounded shadow-center padding-all-05')
                    ->addValidator('Count', false, 1)
                    ->addValidator('Size', false, $conteudoMaxSize);
        $this->addElement($conteudod);

        $apagarconteudod = $this->createElement('checkbox', 'apagarconteudod');
        $apagarconteudod->setAttrib('class', 'desativado')
                ->setDescription('<span class="visualizacao-conteudo"></span>')
                ->setOptions(array('class' => 'desativado'))
                ->setCheckedValue('t')
                ->setUncheckedValue('f');
        $this->addElement($apagarconteudod);
        
        $this->addDisplayGroup(array('separador_arq','conteudov','apagarconteudov','conteudod','apagarconteudod'), 'group_enviar',array('class' => 'panel panel-info padding-all-10 background-azul'));
        
        $guiaPedagogico = $this->createElement('file','guiaPedagogico'); 
        $guiaPedagogico->setLabel('<i class="link-azul fa fa-book"></i> Guia Pedagógico :')
                    ->setDescription('<span class="text-info">Localize no seu computador o guia pedagógico que é um documento criado com objetivo de facilitar o planejamento das aulas por parte do(a)s professore(a)s. Veja outros modelos de guia para conhecer melhor a proposta. <u>Formatos permitidos: <b>'.$guiaPedagogicoExtensions.'</b></u>.</span>')
                    ->setDestination(Aew_Model_Bo_ConteudoDigital::getGuiaPedagogicoDirectory())
                    ->setAttrib('class', 'formatos-dinamico rounded shadow-center padding-all-05')
                    ->addValidator('Extension', false, $guiaPedagogicoExtensions)
                    ->addValidator('Size', false, $guiaPedagogicoMaxSize)
                    ->setAttrib('title', 'Guia Pedagógico');
        $this->addElement($guiaPedagogico);

        $apagarGuiaPedagogico = $this->createElement('checkbox', 'apagarGuiaPedagogico');
        $apagarGuiaPedagogico->setAttrib('class', 'desativado')
                ->setOptions(array('class' => 'desativado'))
                ->setCheckedValue('t')
                ->setUncheckedValue('f');
        $this->addElement($apagarGuiaPedagogico);
        
        $this->addDisplayGroup(array('guiaPedagogico','apagarGuiaPedagogico'), 'group_guia',array('class' => 'panel panel-info padding-all-10 background-azul'));
        
        $imagemAssociada = $this->createElement('file','imagemAssociada');
        $imagemAssociada->setLabel('<i class="link-azul fa fa-picture-o"></i> Imagem associada :')
                    ->setDescription('<span class="text-info">Imagem no formato <b>'.$imagemAssociativaExtensions.'</b>. Dimensão: <b>400px x 220px</b>. Tamanho máximo: <b>'.$ImagemAssociativaMaxSize.'</b></span>')
                    ->setDestination(Aew_Model_Bo_ConteudoDigital::getImagemAssociadaDirectory())
                    ->setAttrib('class', 'rounded shadow-center padding-all-05')
                    ->addValidator('Count', false, 1)
                    ->addValidator('Extension', false, $imagemAssociativaExtensions)
                    ->addValidator('Size', false, $ImagemAssociativaMaxSize);
        $this->addElement($imagemAssociada);

        $apagarImagemAssociada = $this->createElement('checkbox', 'apagarImagemAssociada');
        $apagarImagemAssociada->setAttrib('class', 'desativado')
                    ->setOptions(array('class' => 'desativado'))
                    ->setCheckedValue('t')
                    ->setUncheckedValue('f');
        $this->addElement($apagarImagemAssociada);

        $iconeImagem = $this->createElement('image','iconeImagem');
        $iconeImagem->setAttrib('class', 'desativado')
                    ->setAttrib('width', '60%')
                    ->setAttrib('disabled', true);
        $this->addElement($iconeImagem);
        
        $this->addDisplayGroup(array('imagemAssociada','apagarImagemAssociada','iconeImagem'), 'group_imagem',array('class' => 'panel panel-info padding-all-10 background-azul'));

        $dataCriacao = $this->createElement('date','datacriacao');
        $dataCriacao->setLabel('Data de criação :')
                ->setDescription('<span class="text-info">Este campo deve ser preenchido caso saiba a data que a mídia foi produzida.</span>')
                ->setAttrib('class', 'form-control')
                ->addValidator('Date', false, array('format'=>'dd/MM/yyyy'))
                ->setAttrib('title', 'Data de criação de conteúdo');
        $this->addElement($dataCriacao);

        $acessibilidade = $this->createElement('text','acessibilidade');
        $acessibilidade->setLabel('Acessibilidade:')
                    ->setDescription('<span class="text-info">Indique se a mídia possui alternativas de acesso como legendas, LIBRAS, áudio descrição e outras alternativas.</span>')
                    ->addFilters(array(new Zend_Filter_StringTrim()))
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('title', 'Acessibilidade');
        $this->addElement($acessibilidade);
       
        $conteudoDigitalBo = new Aew_Model_Bo_ConteudoDigital();
        $conteudoDigital = $conteudoDigitalBo->selectCanalPortal();
        
        /*
        $canalArray = $conteudoDigitalBo->getAllForSelect('idcanalportal', 'nomecanalportal', false, '', null, $conteudoDigital);
        
        $canalPortal = $this->createElement('select','idcanal');
        $canalPortal->setLabel('Canal Portal :')
                    ->setDescription('<span class="text-info">Este campo está relacionado com o <b>portal da educação</b>. Caso considere que o conteúdo publicado seja apenas para <i>professores</i> ou <i>estudantes</i>, escolha um perfil, pois ele aparecerá apenas no canal do grupo escolhido. Caso ache que a mídia possa ser utilizada pelos dois públicos, mantenha a opção <i>“todos”</i>.</span>')
                    ->setRegisterInArrayValidator(false)
                    ->setMultiOptions($canalArray)
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('title', 'Canal');
        $this->addElement($canalPortal);
        */
        $termoVal = new Zend_Validate_GreaterThan(0);
        $termoVal->setMessage('Você deve aceitar os termos para adicionar seu conteúdo', Zend_Validate_GreaterThan::NOT_GREATER);
        
        $termo = $this->createElement('checkbox','termoCompromisso');
        $termo->setLabel("Li e concordo com os <a href='/home/termo-condicoes-uso' target='_blank'><b><u>termos e condições de uso</u></b></a> :")
                ->setRequired(true)
                ->setAttrib('title', 'Termos e condições de uso')
                ->addValidator($termoVal)
                ->setChecked(false);
        $this->addElement($termo);

        $aprovado = $this->createElement('checkbox', 'flaprovado');
        $aprovado->setLabel('<b class="link-azul">Conteúdo Aprovado : </b>')
                ->setCheckedValue('t')
                ->setUncheckedValue('f')
                ->setChecked(false)
                ->setAttrib('id', 'flaprovado');
        $this->addElement($aprovado);
        
        $enviar = $this->createElement('submit','salvar');
        $enviar->setLabel("Salvar")
                ->setAttrib('id', 'salvar')
                ->setAttrib('class', 'btn btn-primary pull-right');
        $this->addElement($enviar);
        
        $this->addDisplayGroup(array('termoCompromisso','flaprovado','salvar'), 'group_salvar',array('class' => 'padding-all-10'));

        $id = $this->createElement('hidden', 'idconteudodigital');
        $this->addElement($id);

        $idformato = $this->createElement('hidden', 'idformato');
        $this->addElement($idformato);

        $nomeformato = $this->createElement('hidden','nomeformato');
        $this->addElement($nomeformato);
	
        $idformatodownload = $this->createElement('hidden', 'idformatodownload');
        $this->addElement($idformatodownload);

        $nomeformatodownload = $this->createElement('hidden','nomeformatodownload');
        $this->addElement($nomeformatodownload);

        $idformatoguiapedagogico = $this->createElement('hidden', 'idformatoguiapedagogico');
        $this->addElement($idformatoguiapedagogico);

        $nomeformatoguiapedagogico = $this->createElement('hidden', 'nomeformatoguiapedagogico');
        $this->addElement($nomeformatoguiapedagogico);
        
        $componentes = $this->createElement('hidden', 'componentes');
        $this->addElement($componentes);
    }

    /**
     * Popula o formulario
     */
    public function populate(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $values = array();

        $conteudoData = $conteudo->toArray();
        foreach ($conteudoData as $key => $value) 
        {
            $values[$key] = $value;
        }

        $formatoBo = $conteudo->getFormato();
        $conteudoTipoBo = $formatoBo->getConteudoTipo();
        
        $formatoData = $formatoBo->toArray();
        $conteudoTipoData = $conteudoTipoBo->toArray();

        //--- seta valor do campo Tipo de Conteúdo
		if($conteudoTipoData['idconteudotipo'])
		{
        	$this->getElement('idconteudotipo')->setValue($conteudoTipoData['idconteudotipo']);
			$extensoes = $extensoes = implode(', ',$this->getFileExtensions($conteudoTipoData['idconteudotipo']));
		}

        if($conteudo->getDataCriacao())
        {
            $datacriacao = new Sec_Date($values['datacriacao']);
            $this->getElement('datacriacao')->setValue($datacriacao->toString('dd/MM/YYYY'));
            //$values['datacriacao'] = $conteudo->getDataCriacao();
        }
        
        $componentes = $conteudo->getComponentesCurriculares();
        if(count($componentes))
        {
            $values['componentes'] = '';
            $virgula = '';
            foreach($componentes as $componente)
            {
                $idcomponente = $componente->getId();
                $values['componentes'] .= $virgula.$idcomponente[0];
                $virgula = ',';
            }
        }
        
        $tags = $conteudo->getTags();
        if(count($tags) > 0)
        {
            $values['tags'] = '';
            $virgula = '';
            foreach($tags as $tag)
            {
                $values['tags'] .= $virgula.$tag->getNome();
                $virgula = ', ';
            }
        }

        if($this->getElement('separador_canal'))
		{
            if($conteudo->getConteudoDigitalCategoria()->getId())
            {
                $this->getElement('idconteudodigitalcategoria')->setAttrib('disable', false);

                $categoria = new Aew_Model_Bo_ConteudoDigitalCategoria();

                $options = array();
                $options['where']['conteudodigitalcategoria.idcanal = ?'] = array($conteudo->getConteudoDigitalCategoria()->getCanal()->getId());
                $options['orderBy'] = 'conteudodigitalcategoria.nomeconteudodigitalcategoria ASC';

                $categorias = $categoria->getAllForSelect('idconteudodigitalcategoria', 'nomeconteudodigitalcategoria', 'Selecione', 'idconteudodigitalcategoriapai', $options, null);

                $this->getElement('idconteudodigitalcategoria')->setMultiOptions($categorias);
                
                $values['idcanalcategoria'] = $conteudo->getConteudoDigitalCategoria()->getCanal()->getId();
                $values['idconteudodigitalcategoria'] = $conteudo->getConteudoDigitalCategoria()->getId();
            }
        }
        
        if($conteudo->getConteudoLicenca()->getId())
        { 
            $licenca = new Aew_Model_Bo_ConteudoLicenca();
            $options = array();
            $options['orderBy'] = 'conteudolicenca.idconteudolicenca ASC';
            $licencas = $licenca->getAllForSelect('idconteudolicenca', 'nomeconteudolicenca', 'Selecione','idconteudolicencapai', $options);

            $this->getElement('idconteudolicenca')->setMultiOptions($licencas);
            
            $this->getElement('iconeLicenca')->setAttrib('class', 'img-rounded shadow-center menu-azul')
                	->setAttrib('src', $conteudo->getConteudoLicenca()->getImagemAssociada());
            
            $values['idconteudolicenca'] = $conteudo->getConteudoLicenca()->getId();
        }

        if($conteudo->getSite())
        {
            $this->getElement('separador_arq')->setRequired(false);
            $this->getElement('site')->setRequired(true);
            $this->getElement('desejaCadastrar')->setValue('indicar');
			$this->getElement('desejaCadastrar')->setAttrib('disabled', true);
        } 
        
        if($conteudo->getConteudoVisualizacaoUrl())
        {
            $values['site'] = '';

            $this->getElement('separador_arq')->setRequired(false);
            $this->getElement('desejaCadastrar')->setValue('enviar');
			$this->getElement('desejaCadastrar')->setAttrib('disabled', true);

            $formato = "sprite-tipos-arquivos-application sprite-tipos-arquivos-application-".$conteudo->getFormato()->getNome();
            $link = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-10 margin-bottom-10 row"><b><i class="fa fa-folder-open"></i> Nome de arquivo : </b><a href="'.$conteudo->getConteudoVisualizacaoUrl().'" target="_blank" class="menu-azul rounded padding-all-05"><i class="absolute '.$formato.'"></i><span class="padding-left-20">'.basename($conteudo->getConteudoVisualizacaoUrl()).'</span></a></div>';

            $visualizar = new Sec_View_Helper_ShowVisualizar();
	
            $visualizar = $visualizar->ShowVisualizar($conteudo, $conteudo->getConteudoVisualizacaoUrl());

            $description  = '<span><b><i class="link-azul fa fa-eye"></i> Conteúdo para visualização : </b>';
            $description .= '   <span class="link-vermelho normal"><i class="fa fa-trash"></i> apagar </span>';
            $description .= '<span>';

            $element = $this->getElement('apagarconteudov');
            $element->setLabel($description)
                    ->setOptions(array('class' => 'vertical-align apagar-arquivo'));

            $description = '<span class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'.$visualizar.$link.'</span>';

            $element->setDescription($description);
        }

        if($conteudo->getConteudoDownloadUrl())
        {
            $values['site'] = '';

            $this->getElement('separador_arq')->setRequired(false);
            $this->getElement('desejaCadastrar')->setValue('enviar');
			$this->getElement('desejaCadastrar')->setAttrib('disabled', true);

            $formato = "sprite-tipos-arquivos-application sprite-tipos-arquivos-application-".$conteudo->getFormatoDownload()->getNome();

            $link = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-10 margin-bottom-10 row"><b><i class="fa fa-folder-open"></i> Nome de arquivo : </b><a href="'.$conteudo->getConteudoDownloadUrl().'" target="_blank" class="menu-azul rounded padding-all-05"><i class="absolute '.$formato.'"></i><span class="padding-left-20">'.basename($conteudo->getConteudoDownloadUrl()).'</span></a></div>';

            $description  = '<span><b><i class="link-azul fa fa-download"></i> Conteúdo para download : </b>';
            $description .= '   <span class="link-vermelho normal"><i class="fa fa-trash"></i> apagar </span>';
            $description .= '<span>';

            $element = $this->getElement('apagarconteudod');
            $element->setLabel($description)
                    ->setOptions(array('class' => 'vertical-align apagar-arquivo')); 

            if($conteudo->getConteudoDownloadUrl() != $conteudo->getConteudoVisualizacaoUrl())
            {
                $visualizar = new Sec_View_Helper_ShowVisualizar();
                $visualizar = $visualizar->ShowVisualizar($conteudo, $conteudo->getConteudoDownloadUrl());

                $description = '<span class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'.$visualizar.$link.'</span>';
                $element->setDescription($description);
            }
        }

        if($conteudo->getGuiaPedagogicoUrl())
        {
            $values['idformatoguiapedagogico'] = $conteudo->getFormatoGuiaPedagogico()->getId();
            $values['nomeformatoguiapedagogico'] = $conteudo->getFormatoGuiaPedagogico()->getNome();
            
            $formato = "sprite-tipos-arquivos-application sprite-tipos-arquivos-application-".$conteudo->getFormatoGuiaPedagogico()->getNome();

            $link = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-10 margin-bottom-10 row"><b><i class="fa fa-folder-open"></i> Nome de arquivo : </b><a href="'.$conteudo->getConteudoDownloadUrl().'" target="_blank" class="menu-azul rounded padding-all-05"><i class="absolute '.$formato.'"></i><span class="padding-left-20">'.basename($conteudo->getGuiaPedagogicoUrl()).'</span></a></div>';
                
            $element = $this->getElement('apagarGuiaPedagogico');
            
            $description  = '<span class="text-info"><b><i class="fa fa-book"></i> Guia Pedagógico : </b>';
            $description .= '   <span class="link-vermelho normal"><i class="fa fa-trash"></i> apagar </span>';
            $description .= '<span>';
            
            $element->setLabel($description)
                    ->setOptions(array('class' => 'vertical-align apagar-arquivo'));

            $visualizar = new Sec_View_Helper_ShowVisualizar();
            $visualizar = $visualizar->ShowVisualizar($conteudo, $conteudo->getGuiaPedagogicoUrl(), Aew_Model_Bo_ConteudoTipo::$DOCUMENTO_EXPERIMENTO);

            $description = '<span class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'.$visualizar.$link.'</span>';
            $element->setDescription($description);
        }
        
        if($conteudo->getConteudoImagem(false, true))
        {   
            if(!strpos($conteudo->getConteudoImagem(false, true), '/sinopse/'))
            {
                $formato = explode(".", $conteudo->getConteudoImagem());
                $formato = end($formato);
                
                $formato = "sprite-tipos-arquivos-application sprite-tipos-arquivos-application-$formato";
                $link = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-10 margin-bottom-10 row"><b><i class="fa fa-folder-open"></i> Nome de arquivo : </b><a href="'.$conteudo->getConteudoImagem().'" target="_blank" class="menu-azul rounded padding-all-05"><i class="absolute '.$formato.'"></i><span class="padding-left-20">'.basename($conteudo->getConteudoImagem()).'</span></a></div>';
                
                $element = $this->getElement('iconeImagem');
                $element->setAttrib('class', 'img-rounded shadow-center')
                        ->setAttrib('src', $conteudo->getConteudoImagem())
                        ->setDescription($link);

                $element = $this->getElement('apagarImagemAssociada');

                $description  = '<span class="text-info"><b><i class="fa fa-picture-o"></i> Imagem associada :</b>';
                $description .= '   <span class="link-vermelho normal"><i class="fa fa-trash"></i> apagar </span>';
                $description .= '<span>';
                
                $element->setLabel($description)
                        ->setOptions(array('class' => 'vertical-align apagar-arquivo'));
            }       
        }

        parent::populate($values);
    }

    public function isValid($data)
    {
        $data['desejaCadastrar'] = (!trim($data['site']) ? 'enviar' : 'indicar');
        
        $this->getElement('mensagem_componente')->setRequired(false);
        
        switch($data['desejaCadastrar'])
        {
            case 'enviar':
                if(!$data['idconteudodigital'])
                {
                    $formatosPermitidos = ($data['idconteudotipo'] == 5 ? array('webm','mp4') : $this->getFileExtensions($data['idconteudotipo']));

                    $contV = $this->getElement('conteudov');
                    $existeConteudoV = count($contV->getFileName()) > 0;

                    $contD = $this->getElement('conteudod');
                    $existeConteudoD = count($contD->getFileName()) > 0;
                    
                    if(!$existeConteudoV && !$existeConteudoD)
                    {
                        $contV->addValidator('Extension', false, $formatosPermitidos);
                        $contD->addValidator('Extension',false,$formatosPermitidos);
                        
                        $this->getElement('separador_arq')->addError('Deve enviar, pelo menos, o arquivo de visualização ou download, ou pode enviar ambos.');
                    }
                }
                break;

            case 'indicar':
                $data['site'] = trim($data['site']);
                break;
        }

        if(!$data['componentes'])
        {
            $this->getElement('mensagem_componente')->setRequired(true);
            $this->getElement('mensagem_componente')->addError('Selecione pelo menos um componente curricular, disciplina, área específica ou série');
        }

        return parent::isValid($data); 
    }
    
    /**
     * Adiciona restricoes para novos conteudos
     */
    public function adicionarRestricoes(Aew_Model_Bo_ConteudoDigital $conteudo = null)
    {
        $acl = Sec_Acl::getInstance();
        $usuario = Sec_Controller_Action::getLoggedUserObject();
        
        if((!$acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'conteudos-digitais:conteudo', 'sites-tematicos')) || ($acl->isAllowed(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR)))
        {
            $this->removeElement('flsitetematico');
        }
        
        if(!$usuario->isCoordenador())
        {
            $this->removeElement('flaprovado');
        }
        
        if(!is_null($conteudo))
        {	
            if($conteudo->getConteudoVisualizacaoUrl())
            {
                $this->removeElement('conteudov');
            }

            if($conteudo->getConteudoDownloadUrl())
            {
                $this->removeElement('conteudod');
            }

            if($conteudo->getGuiaPedagogicoUrl())
            {
                $this->removeElement('guiaPedagogico');
            }

            if($conteudo->getConteudoImagem(false, true))
            {   
                $this->removeElement('imagemAssociada');
            }
        }
    }

    public function AdicionarCanal(&$extensoes = null)
    {
        $usuario = Sec_Controller_Action::getLoggedUserObject();
        if(!$usuario->isAdmin() && !(($usuario->isCoordenador() && $usuario->getId() == 93) || ($usuario->isCoordenador() && $usuario->getId() == 545)))
        {
            return false;
        }
        
        $options = array();
        
        $disabled = false;
        $idCanal = 0;
        if($usuario->isCoordenador() && $usuario->getId() == 93)
        {
            $idCanal = 1;
            $disabled = true;
        }

        if($usuario->isCoordenador() && $usuario->getId() == 545)
        {
            $idCanal = 2;
            $disabled = true;
        }

        if($disabled)
        {
            $options['where'] = 'conteudotipo.idconteudotipo = 5';
        }

        $separadorCanal = $this->createElement('radio', 'separador_canal');
        $separadorCanal->setLabel('<h5 class="margin-none menu-azul inline"><b><i class="fa fa-home"></i> Canal proprietário do contéudo digital:</b></h5>');
        $this->addElement($separadorCanal);
        
        $conteudoTipoBo = new Aew_Model_Bo_ConteudoTipo();
        $conteudoTipoArray = $conteudoTipoBo->getAllForSelect('idconteudotipo', 'nomeconteudotipo', ($disabled ? false : 'Selecione'), '', $options);
        
        $conteudoTipo = $this->createElement('select','idconteudotipo');
        
        if(!$disabled)
        {
            $conteudoTipo->setDescription('<span class="text-info">Escolha a opção que mais se adéqua à mídia que deseja publicar, conforme opções disponíveis.</span>');
        }
        
        $conteudoTipo->setLabel('Tipo de Conteúdo :')
                        ->setRequired(true)
                        ->setMultiOptions($conteudoTipoArray)
                        ->setAttrib('class', 'form-control')
                        ->setAttrib('title', 'Tipo do conteúdo');
        $this->addElement($conteudoTipo);

        $flsitetematico = $this->createElement('checkbox', 'flsitetematico');
        $flsitetematico->setLabel('<span class="text-danger">Incluir conteúdo como <b>"Site Temático"</b> : </span>')
                    ->setAttrib('id', 'flsitetematico')
                    ->setCheckedValue('t')
                    ->setUncheckedValue('f')
                    ->setAttrib('title', 'Destinar conteúdo para Sites Temáticos');
        $this->addElement($flsitetematico);
        
        $canalBo = new Aew_Model_Bo_Canal();
        $options = array();
        if($disabled)
        {
            $options['where']['canal.idcanal = ?'] = $idCanal;
        }
        
        $canais = $canalBo->getAllForSelect('idcanal', 'nomecanal',($disabled ? false : 'Selecione um Canal'),'',$options);
        $canal = $this->createElement('select','idcanalcategoria');
                
        if($disabled)
        {
            $canal->setRequired(true);
        }
        else
        {
            $canal->setDescription('<span class="text-info">Canal do conteúdo digital.</span>');
        }

        $canal->setLabel('Canal :')
                ->setRegisterInArrayValidator(false)
                ->setMultiOptions($canais)
                ->setAttrib('class', 'form-control select-dinamic')
                ->setAttrib('idloadcontainer','idconteudodigitalcategoria')
                ->setAttrib('type-action', 'html-action')
                ->setAttrib('rel', '/conteudos-digitais/conteudo/categorias-canal')
                ->setAttrib('title', 'Canal');
        $this->addElement($canal);
       
        $options = array();
        if($idCanal)
        {
            $options['where']['conteudodigitalcategoria.idcanal = ?'] = $idCanal;
        }
        
        $options['orderBy'] = "conteudodigitalcategoria.nomeconteudodigitalcategoria ASC";

        $conteudoCategoriaBo = new Aew_Model_Bo_ConteudoDigitalCategoria();
        $conteudoCategoria = $conteudoCategoriaBo->getAllForSelect('idconteudodigitalcategoria', 'nomeconteudodigitalcategoria', 'Selecione','idconteudodigitalcategoriapai', $options);
        
        $categoria = $this->createElement('select','idconteudodigitalcategoria');

        if($disabled)
        {
            $categoria->setMultiOptions($conteudoCategoria)->setRequired(true);
        }
        
        $categoria->setLabel('Categoria Conteudo Digital :')
                    ->setDescription('<span class="text-info">Categoria do conteúdo.</span>')
                    ->setRegisterInArrayValidator(false)
                    ->setAttrib('id', 'idconteudodigitalcategoria')
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('title', 'Categoria Conteudo Digital');
        $this->addElement($categoria);

        if($idCanal){
            $this->addDisplayGroup(array('separador_canal','idconteudotipo','idcanalcategoria','idconteudodigitalcategoria'), 'group_canal',array('class' => 'panel panel-info padding-all-10 background-azul'));
            $extensoes = implode(', ',$this->getFileExtensions(5));
        }
        else{
            $this->addDisplayGroup(array('separador_canal','idcanalcategoria','idconteudodigitalcategoria'), 'group_canal',array('class' => 'panel panel-info padding-all-10 background-azul'));
        }

        return true;
    }
    
    /**
     * Permite a configuração dos decorators no form
     */
    public function configDecorators()
    {
        $element = $this->getElement('titulo');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('idconteudotipo');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('autores');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('tags');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('datacriacao');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('fonte');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('descricao');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('acessibilidade');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('guiaPedagogico');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('apagarGuiaPedagogico');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);
        
        /*
        $element = $this->getElement('idcanal');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);
        */
        
        if($this->getElement('separador_canal'))
        {
            $element = $this->getElement('separador_canal');
            $element->getDecorator('description')->setOption('escape', false);
            $element->getDecorator('label')->setOption('escape', false);

            $element = $this->getElement('idcanalcategoria');
            $element->getDecorator('description')->setOption('escape', false);
            $element->getDecorator('label')->setOption('escape', false);

            $element = $this->getElement('idconteudodigitalcategoria');
            $element->getDecorator('description')->setOption('escape', false);
            $element->getDecorator('label')->setOption('escape', false);
        }
        
        $element = $this->getElement('desejaCadastrar');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('separador_lnk');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('separador_arq');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);
        
        $element = $this->getElement('conteudov');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('apagarconteudov');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('conteudod');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('apagarconteudod');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);
        
        $element = $this->getElement('site');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('titulo');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('idconteudolicenca');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('imagemAssociada');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('iconeImagem');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);
        
        $element = $this->getElement('apagarImagemAssociada');
        $element->getDecorator('description')->setOption('escape', false);
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('termoCompromisso');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('flsitetematico');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('termoCompromisso');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('flaprovado');
        $element->getDecorator('label')->setOption('escape', false);
   }
   
   public function getFileExtensions($typeFile)
   {
        $types = array(); 

        $conteudoTipo = new Aew_Model_Bo_ConteudoTipo();
       
        $conteudoTipo->setId($typeFile);
        if(!$conteudoTipo->selectAutoDados())
        {
            return array();
        }

		$formato = new Aew_Model_Bo_Formato();
		$formato->setConteudoTipo($conteudoTipo);

		$options['orderBy'] = 'formato.nomeformato ASC';
       
		$formatos = $formato->select(0, 0, $options);
       
		foreach($formatos as $formato)   
		{
			array_push($types, $formato->getNome());
		}

		return $types;
   }
   
   public function getExtensaoArquivo($fileName)
   {
       if(is_string($fileName))
       {
           $ext = explode('.', $fileName);
           return $ext[count($ext)-1];
       }
       return '';
   }
}
