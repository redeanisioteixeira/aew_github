<?php
/**
 * BO da entidade Usuario Tipo
 */
class Aew_Model_Bo_ConteudoDigital extends Aew_Model_Bo_ItemPerfil implements Sec_OrdenavelPorData
{
    protected $usuarioPublicador; //Aew_Model_Bo_Usuario
    protected $usuarioLogado; //Aew_Model_Bo_Usuario
    protected $idcomponentecurricular;
    protected $usuarioAprova; //Aew_Model_Bo_Usuario
    protected $titulo; //varchar(250)
    protected $autores; //varchar(250)
    protected $fonte; //varchar(250)
    protected $descricao; //text
    protected $acessibilidade; //text
    protected $tamanho; //varchar(50)
    protected $datapublicacao; //timestamp
    protected $flaprovado; //tinyint(1)
    protected $qtddownloads; //int(11)
    protected $avaliacao; //int(11)
    protected $acessos; //int(11)
    protected $formato; //Aew_Model_Bo_Formato
    protected $formatoDownload; //int(11)
    protected $formatoGuiaPedagogico; //int(11)
    protected $licenca; //text
    protected $site; //varchar(250)
    protected $idcanal; //int(11)
    protected $servidor; //int(11)
    protected $fldestaque; //tinyint(1)
    protected $datacriacao = null;
    protected $flsitetematico;
    protected $resumo;
    protected $componentesCurriculares = array();
    protected $niveisEnsino = array();
    protected $conteudoLicenca ;
    protected $tagsConteudo = array();
    protected $votos = array();
    protected $comentarios = array();
    protected $conteudosRelacionados = array();
    protected $conteudoTipo;
    protected $mediavoto;
    protected $quantidadevoto;
    protected $conteudodigitalCategoria ;
    
    private $siteValido = array('tvescola' => 'tvescola.mec.gov.br','irdeb' => 'irdeb.ba.gov.br', 'dominiopublico' => 'dominiopublico.gov.br');
    private $extensaovalida = array(1 => "mp4", 2 => "flv", 3 => "webm", 4 => "aac", 5 => "mp3", 6 => "vorbis", 7 => "ogg", 8 => "swf", 9 => "link", 10 => "avi", 11 => "gif", 12 => "zip");

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->setUsuarioPublicador(new Aew_Model_Bo_Usuario());
        $this->setFormato(new Aew_Model_Bo_Formato());
        $this->setFormatoDownload(new Aew_Model_Bo_Formato());
        $this->setFormatoGuiaPedagogico(new Aew_Model_Bo_Formato());
        $this->setConteudoLicenca(new Aew_Model_Bo_ConteudoLicenca());
        $this->setUsuarioAprova(new Aew_Model_Bo_Usuario());
        $this->setConteudoDigitalCategoria(new Aew_Model_Bo_ConteudoDigitalCategoria());
    }
    
    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    function toArray() 
    {
        $data = parent::toArray();
        
        if(is_null($this->getConteudoDigitalCategoria()))
        {
            $data['idconteudodigitalcategoria'] = null;
        }elseif($this->getConteudoDigitalCategoria()->getId())
        {
            $data['idconteudodigitalcategoria'] = $this->getConteudoDigitalCategoria()->getId();
            $this->getDao()->setTableInTableField('idconteudodigitalcategoria', $this->getDao()->getName());
        }
        
        if($this->getIdFavorito())
        {
            $this->getDao()->setTableInTableField('idfavorito', 'conteudodigitalfavorito');
        }
        
        if(is_null($this->getFormato())) 
        {
            $data['idformato'] = null;
            $data['nomeformato'] = null;
        }elseif($this->getFormato()->getId())
            {
                $data['idformato'] = $this->getFormato()->getId();
                $data['nomeformato'] = $this->getFormato()->getNome();
            }
        
        if(is_null($this->getFormatoDownload()))
        {
            $data['idformatodownload'] = null;
            $data['nomeformatodownload'] = null;
            
        }elseif($this->getFormatoDownload()->getId())
            {
                $data['idformatodownload'] = $this->getFormatoDownload()->getId();
                $data['nomeformatodownload'] = $this->getFormatoDownload()->getNome();
            }
        
        if(is_null($this->getFormatoGuiaPedagogico()))
        {
            $data['idformatoguiapedagogico'] = null;
            $data['nomeformatoguiapedagogico'] = null;
        }elseif($this->getFormatoGuiaPedagogico()->getId())
            {
                $data['idformatoguiapedagogico'] = $this->getFormatoGuiaPedagogico()->getId();
                $data['nomeformatoguiapedagogico'] = $this->getFormatoGuiaPedagogico()->getNome();
            }
        
        if($this->getUsuarioPublicador()->getId())
        {
            $data['idusuariopublicador'] = $this->getUsuarioPublicador()->getId();
        }
        
        if($this->getConteudoLicenca()->getId())
        {
            $data['idlicencaconteudo'] = $this->getConteudoLicenca()->getId();
        }
        
        if(is_null($this->getDataCriacao()) || !$this->getDataCriacao())
        {
            $data['datacriacao'] = null;
        }
        
        foreach($this->getTags() as $tag)
        {
            if(is_array($tag->getId()))
            {
                $data['idconteudotag'] = $tag->getId();
            }
            else
            {
                $data['idconteudotag'][] = $tag->getId();
            }
        }
        
        return $data;
    }
    
    /**
     * 
     * @return Aew_Model_Bo_Usuario
     */
    function getUsuarioLogado() {
        return $this->usuarioLogado;
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuarioLogado
     */
    function setUsuarioLogado(Aew_Model_Bo_Usuario $usuarioLogado) {
        $this->usuarioLogado = $usuarioLogado;
    }

    /**
     * 
     * @return Aew_Model_Bo_ConteudoDigitalCategoria
     */
    public function getConteudoDigitalCategoria()
    {
        return $this->conteudodigitalCategoria;
    }

    /**
     * 
     * @param Aew_Model_BO_ConteudoDigital $conteudodigitalCategoria
     */
    public function setConteudoDigitalCategoria(Aew_Model_Bo_ConteudoDigitalCategoria $conteudodigitalCategoria = null)
    {
        $this->conteudodigitalCategoria = $conteudodigitalCategoria;
    }

    /**
     * @return array
     */
    public function getConteudosRelacionados()
    {
        return $this->conteudosRelacionados;
    }

    /**
     * 
     * @param array $conteudosRelacionados
     */
    public function setConteudosRelacionados($conteudosRelacionados)
    {
        $this->conteudosRelacionados = $conteudosRelacionados;
    }
    
    /**
     * 
     * @return float
     */
    function getMediavoto() {
        return $this->mediavoto;
    }

    /**
     * 
     * @return int
     */
    function getQuantidadevoto() {
        return $this->quantidadevoto;
    }

    /**
     * 
     * @param float $mediavoto
     */
    function setMediavoto($mediavoto) {
        $this->mediavoto = $mediavoto;
    }

    /**
     * 
     * @param int $quantidadevoto
     */
    function setQuantidadevoto($quantidadevoto) {
        $this->quantidadevoto = $quantidadevoto;
    }

    /**
     * adiciona localmente conteudos relacionados
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     */
    public function addConteudoRelacionado(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        array_push($this->conteudosRelacionados, $conteudo);
    }

    /**
     * 
     * @return array
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @return Aew_Model_Bo_ConteudoTipo
     */
    public function getConteudoTipo()
    {
        return $this->conteudoTipo;
    }

    /**
     * retorna BO conteudo tipo do conteudo digital
     * @param Aew_Model_Bo_ConteudoTipo $conteudoTipo
     */
    public function setConteudoTipo(Aew_Model_Bo_ConteudoTipo $conteudoTipo)
    {
        $this->conteudoTipo = $conteudoTipo;
    }

    /**
     * 
     * @return Aew_Model_Bo_ConteudoLicenca
     */
    public function getConteudoLicenca()
    {
        return $this->conteudoLicenca;
    }
    
    /**
     * @param Aew_Model_Bo_ConteudoLicenca $conteudoLicenca
     */
    public function setConteudoLicenca(Aew_Model_Bo_ConteudoLicenca $conteudoLicenca)
    {
        $this->conteudoLicenca = $conteudoLicenca;
    }
            
    public function getIdComponenteCurricular()
    {
        return $this->idcomponentecurricular;
    }

    public function setIdComponenteCurricular($idcomponentecurricular)
    {
        $this->idcomponentecurricular = $idcomponentecurricular;
    }

    public function exchangeArray($data)
    {
        parent::exchangeArray($data);

        $this->getFormato()->exchangeArray($data);
        $this->getUsuarioPublicador()->exchangeArray($data);
        $this->getConteudoLicenca()->exchangeArray($data);
        $this->setTags(isset($data['tags'])? $data['tags']:null);
        $this->setComponentesCurriculares(isset($data['componentes'])? $data['componentes']:null);
        $this->getConteudoDigitalCategoria()->exchangeArray($data);
        
        $this->getFormatoDownload()->setId(isset($data['idformatodownload'])? $data['idformatodownload']: null);
        $this->getFormatoDownload()->setNome(isset($data['nomeformatodownload'])? $data['nomeformatodownload']: null);
        
        $this->getFormatoGuiaPedagogico()->setId(isset($data['idformatoguiapedagogico'])? $data['idformatoguiapedagogico']: null);
        $this->getFormatoGuiaPedagogico()->setNome(isset($data['nomeformatoguiapedagogico'])? $data['nomeformatoguiapedagogico']: null);
    }

    public function getNiveisEnsino()
    {
        return $this->niveisEnsino;
    }

    public function setNiveisEnsino($niveisEnsino)
    {
        $this->niveisEnsino = $niveisEnsino;
    }
        
    function addNivelEnsino(Aew_Model_Bo_NivelEnsino $nivelEnsino)
    {
        $add = true;
        foreach($this->niveisEnsino as $nivel)
        {
            if(($nivel->getId()==$nivelEnsino->getId())||
                (!$nivelEnsino->getId()))
            return false;
        }
        array_push($this->niveisEnsino, $nivelEnsino);
    }
        
    /**
     * retorna coleção de tags deste conteudo digital
     * @return array
     */
    public function getTags()
    {
        return $this->tagsConteudo;
    }

    public function addTag(Aew_Model_Bo_Tag $tag)
    {
        array_push($this->tagsConteudo, $tag);
    }
        
    /**
     * @param array|string $tags
     */
    public function setTags($tags)
    {
        if(is_array($tags))
        {
            $this->tagsConteudo = $tags; 
        }
        elseif(is_string($tags))
        {
            $nometags = explode(',',$tags);
            foreach( $nometags as $nometag)
            {
                $boTag = new Aew_Model_Bo_Tag();
                $boTag->setNome($nometag);
                $boTag = $boTag->select(1);
                
                if($boTag instanceof Aew_Model_Bo_Tag)
                {
                    $this->addTag($boTag);   
                  
                }
            }
        }
        
    }

    /**
     * @return array $componentesCurriculares
     */
    public function getComponentesCurriculares() 
    {
        return $this->componentesCurriculares;
    }

    /**
     * 
     * @param array $componentesCurriculares
     */
    public function setComponentesCurriculares($componentesCurriculares='') 
    {
        if(is_array($componentesCurriculares))
        {
            $this->componentesCurriculares = $componentesCurriculares;
        }
        else if($componentesCurriculares && is_string($componentesCurriculares))
        {
            $idscomponentes = explode(',',$componentesCurriculares);
            foreach ($idscomponentes as $idcomponente)
            {
                $componente = new Aew_Model_Bo_ComponenteCurricular();
                $componente->setId($idcomponente);
                if($componente->selectAutoDados())
                {
                    $componenteConteudo = new Aew_Model_Bo_ConteudoDigitalComponente();
                    $componenteConteudo->setId(array($idcomponente,  $this->getId()));
                    $this->addComponenteCurricular($componenteConteudo);
                }
            }
        }
    }

    /**
     * @return Aew_Model_Bo_Usuario
     */
    public function getUsuarioPublicador() {
        return $this->usuarioPublicador;
    }

    /**
     * @return Aew_Model_Bo_Usuario
     */
        public function getUsuarioAprova() {
            return $this->usuarioAprova;
        }

    /**
     * @return boolean
     */
    public function getFlaprovado() {
        return $this->flaprovado;
    }

    /**
     * Retorna o diretorio para as imagens associadas aos conteúdos
     */
    public static function getImagemAssociadaDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'imagem-associada';
        ELSE:
            $path = MEDIA_PATH.DS.'conteudos'.DS.'imagem-associada';
        endif;

        return $path;
    }

    /**
     * 
     * @return Aew_Model_Bo_Formato
     */
    public function getFormato() {
        return $this->formato;
    }

    /**
     * retorna o objeto representando o formato de download
     * do conteudo digital
     * @return Aew_Model_Bo_Formato
     */
    public function getFormatoDownload() {
        return $this->formatoDownload;
    }

    /**
     * 
     * @return Aew_Model_Bo_Formato
     */
    public function getFormatoGuiaPedagogico() {
        return $this->formatoGuiaPedagogico;
    }
    
    /**
     * 
     * @return array
     */
    public function getVotos()
    {
        return $this->votos;
    }

    /**
     * 
     * @param array $votos
     */
    public function setVotos($votos)
    {
        $this->votos = $votos;
    }

    /**
     * 
     * @return int
     */
    public function getIdCanal() {
        return $this->idcanal;
    }

    /**
     * objeto que contem dados onde conteudo esta alocado fisicamente
     * @return Aew_Model_Bo_Servidor
     */
    public function getServidor() {
        return $this->servidor;
    }

    /**
     * 
     * @return boolean
     */
    public function getDestaque() {
        return $this->fldestaque;
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuarioPublicador
     */
    public function setUsuarioPublicador($usuarioPublicador) {
        $this->usuarioPublicador = $usuarioPublicador;
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuarioAprova
     */
    public function setUsuarioAprova($usuarioAprova) {
        $this->usuarioAprova = $usuarioAprova;
    }

    /**
     * 
     * @param boolean $flaprovado
     */
    public function setFlaprovado($flaprovado) {
        $this->flaprovado = $flaprovado;
    }

    /**
     * 
     * @param Aew_Model_Bo_Formato $formato
     */
    public function setFormato(Aew_Model_Bo_Formato $formato = null){
        $this->formato = $formato;
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Formato $formatoDownload
     */
    public function setFormatoDownload(Aew_Model_Bo_Formato $formatoDownload = null) {
        $this->formatoDownload = $formatoDownload;
    }

    /**
     * 
     * @param Aew_Model_Bo_Formato $formatoGuiaPedagogico
     */
    public function setFormatoGuiaPedagogico(Aew_Model_Bo_Formato $formatoGuiaPedagogico = null) {
        $this->formatoGuiaPedagogico = $formatoGuiaPedagogico;
    }
    
    /**
     * 
     * @param iint $idcanal
     */
    public function setIdCanal($idcanal) {
        $this->idcanal = $idcanal;
    }

    /**
     * 
     * @param Aew_Model_Bo_Servidor $servidor
     */
    public function setServidor(Aew_Model_Bo_Servidor $servidor) {
        $this->servidor = $servidor;
    }

    /**
     * 
     * @param boolean $fldestaque
     */
    public function setDestaque($fldestaque) {
        $this->fldestaque = $fldestaque;
    }

    /**
     * 
     * @return string
     */
    public function getResumo() {
        return $this->resumo;
    }

    /**
     * 
     * @param string $resumo
     */
    public function setResumo($resumo) {
        $this->resumo = $resumo;
    }

    /**
     * 
     * @return string
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * 
     * @return string
     */
    public function getAutores() {
        return $this->autores;
    }

    /**
     * 
     * @return string
     */
    public function getFonte() {
        return $this->fonte;
    }

    /**
     * 
     * @return string
     */
    public function getDescricao() {
        return $this->descricao;
    }

    /**
     * 
     * @return string
     */
    public function getAcessibilidade() {
        return $this->acessibilidade;
    }

    /**
     * 
     * @return string
     */
    public function getSite() 
    {
        return $this->site;
    }

    /**
     * se site esta ativo
     * @return boolean
     */
    public function getSiteStatus() {
        $result = 0;
        return $result = 200;
        $arrValidos = array(0 => 200, 1 => 301, 2 => 302);
        $status = $this->extrairContentType($this->getSite());

        if(isset($status['http_code'])){
            foreach($arrValidos as $key=>$value){
                if($status['http_code'] == $value){
                    $result = $value;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * 
     * @return string
     */
    public function getTamanho() {
        return $this->tamanho;
    }

    /**
     * 
     * @return string
     */
    public function getDataPublicacao() {
        return $this->datapublicacao;
    }

    /**
     * 
     * @return string
     */
    public function getDataCriacao() {
        return $this->datacriacao;
    }

    /**
     * 
     * @return boolean
     */
    public function getFlSiteTematico() {
        return $this->flsitetematico;
    }

    /**
     * 
     * @return int
     */
    public function getQtdDownloads() {
        return (is_null($this->qtddownloads) ? 0 : $this->qtddownloads);
    }

    /**
     * 
     * @return int
     */
    public function getAcessos() {
        return $this->acessos;
    }

    /**
     * 
     * @return float
     */
    public function getAvaliacao() {
        return $this->avaliacao;
    }

    /**
     * 
     * @return Aew_Model_Bo_Licenca
     */
    public function getLicenca() {
        return $this->licenca;
    }

    /**
     * 
     * @param string $titulo
     */
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    /**
     * 
     * @param string $autores
     */
    public function setAutores($autores) {
        $this->autores = $autores;
    }

    /**
     * 
     * @param string $fonte
     */
    public function setFonte($fonte) {
        $this->fonte = $fonte;
    }

    /**
     * 
     * @param string $descricao
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    /**
     * 
     * @param string $acessibilidade
     */
    public function setAcessibilidade($acessibilidade) {
        $this->acessibilidade = $acessibilidade;
    }

    /**
     * 
     * @param string $site
     */
    public function setSite($site) {
        $this->site = $site;
    }

    /**
     * 
     * @param string $tamanho
     */
    public function setTamanho($tamanho) {
        $this->tamanho = $tamanho;
    }

    /**
     * 
     * @param string $datapublicacao
     */
    public function setDataPublicacao($datapublicacao) {
        $this->datapublicacao = $datapublicacao;
    }

    /**
     * 
     * @param string $datacriacao
     */
    public function setDataCriacao($datacriacao = null) {
        $this->datacriacao = Sec_Date::setDoctrineDate($datacriacao);
    }

    /**
     * 
     * @param boolean $flsitetematico
     */
    public function setFlSiteTematico($flsitetematico) {
        $this->flsitetematico = $flsitetematico;
    }

    /**
     * 
     * @param int $qtdDownloads
     */
    public function setQtdDownloads($qtdDownloads) {
        $this->qtddownloads = $qtdDownloads;
    }

    /**
     * 
     * @param int $acessos
     */
    public function setAcessos($acessos) {
        $this->acessos = $acessos;
    }

    /**
     * 
     * @param int $avaliacao
     */
    public function setAvaliacao($avaliacao) {
        $this->avaliacao = $avaliacao;
    }

    /**
     * 
     * @param Aew_Model_Bo_Licenca $licenca
     */
    public function setLicenca(Aew_Model_Bo_Licenca $licenca) {
        $this->licenca = $licenca;
    }

    
    public function selectCanalPortal()
    {
        $canalPortal = $this->getDao()->selectCanalPortal();
        return $canalPortal;
    }

    public function selectResumoconteudosPorTipo()
    {
        $canalPortal = $this->getDao()->selectResumoconteudosPorTipo();
        return $canalPortal;
    }

    public function executarSql($query)
    {
        $resultado = $this->getDao()->executarSql($query);
        return $resultado;
    }

    /**
     * remove todas as tags deste conteudo digital
     * @return boolean
     */
    function deleteALlTags()
    {
        if(!$this->getId())
            return false;
        
        $tag = new Aew_Model_Bo_ConteudoDigitalTag();
        $tag->setId($this->getId());
        
        return $tag->delete();
    }
    
    /**
     * remove as tags deste conteudo digital
     * @param type $tags
     * @return type
     */
    public function deleteTags($tags = "")
    {
        if(!$this->getId())
            return ;
        
        if($tags)
            $this->setTags ($tags);
        
        foreach($this->getTags() as $tag)
        {
            $this->deleteTag($tag);
        }
        $this->setTags(array());
    }
    
    /**
     * remove todos os componentes deste conteudo digital
     * @return int
     */
    function deleteAllComponentesCurriculares()
    {
        if(!$this->getId())
            return;
        
        $componente = new Aew_Model_Bo_ConteudoDigitalComponente();
        $componente->setIdconteudodigital($this->getId());
        
        return $componente->delete(); 
    }
    
    /**
     * 
     * @param array $componentes
     */
    public function deleteComponentesCurriculares( $componentes ='')
    {
        if(!$this->getId())
            return;
        
        if($componentes)
            $this->setComponentesCurriculares ($componentes);
        
        foreach($this->getComponentesCurriculares() as $componenteCurricular)
        {
            $this->deleteComponeteCurricular($componenteCurricular);
        }
        $this->setComponentesCurriculares(array());
    }
    /**
     * Notifica o usuario publicador da aprovacao ou nao aprovacao de seu conteudo
     * @param $conteudo
     * @param $mensagem
     * @return boolean
     */
    public function notificarAprovacao($mensagem)
    {
        $mail = new Sec_Mail();
        $nome = $this->getUsuarioPublicador()->getNome();
        $email = $this->getUsuarioPublicador()->getEmail();
        $mail->setBodyHtml($mensagem);
		$mail->setFrom(Sec_Global::getSystemEmail(), 'Ambiente Educacional Web');
		$mail->addTo($email, $nome);
		$mail->setSubject('Aviso sobre aprovação de conteúdo digital');
		$result = $mail->send();
		return $result;
    }

    /**
     * Aumenta o número de acessos de um conteudo
     * @return int
     */
    public function aumentarAcesso()
    {
        $this->setAcessos($this->getAcessos()+1);
        return $this->update();
    }

    /**
     * Aumenta o número de download de um conteudo
     * @return int
     */
    public function aumentarDownload()
    {
        $this->setQtdDownloads($this->getQtdDownloads()+1);
        return $this->update();
    }

    /**
     * Retorna a Path para o conteudo para download
     */
    public function getConteudoDownloadPath()
    {
        $formato = $this->getFormatoDownload()->getNome();
        if($this->getFormatoDownload()->getNome() == 'link'):
            $arquivos = glob($this->getConteudoDownloadDirectory().DS.$this->getId().'.*');
            if(count($arquivos)):
                $arquivo = $arquivos[0];
                $formato = explode('.',$arquivo);
                $formato = end($formato);
            endif;
        endif;
        
        return $this->getConteudoDownloadDirectory().DS.$this->getId().'.'.$formato;
    }
    
    /**
     * retorna o diretorio para os conteudos para download
     */
    public static function getConteudoDownloadDirectory()
    {
        
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'download';
        else:
            $path = MEDIA_PATH.DS.'conteudos'.DS.'download';
        endif;
        
        return $path;
    }

    /**
     * Retorna o diretorio para os guias pedagogicos
     */
    public static function getGuiaPedagogicoDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'guias-pedagogicos';
        else:
            $path = MEDIA_PATH.DS.'guias-pedagogicos';
        endif;
    	return $path;
    }

    /**
     * 
     * @param type $form
     * @param Aew_Model_Bo_Usuario $usuario
     * @param string $mensagem
     */
    public function gravarColaborativo($form, $usuario, $mensagem)
    {
	$usuario = new Aew_Model_Bo_Usuario();
	if($usuario->getCpf()==""):
            $usuario->setCpf("00000000000");
	endif;
	$id = $usuario->getId().'.'.$usuario->getCpf();
	$fileColaborativo = Aew_Model_Bo_ConteudoDigital::getConteudoDownloadDirectory().DS.$id.'.avi';
	if(file_exists($fileColaborativo)):
            $apagar = unlink($fileColaborativo);
	endif;
	$fileColaborativo = Aew_Model_Bo_ConteudoDigital::getConteudoDownloadDirectory().DS.$id.'.mp4';
	if(file_exists($fileColaborativo)):
            $apagar = unlink($fileColaborativo);
	endif;
	$file = $form->ficha;
	if($file->isUploaded()):
            $dirty = true;
            $ext = Sec_File::getExtension($file->getFileName());
            $file->addFilter('Rename', array('target' => Aew_Model_Bo_ConteudoDigital::getColaborativoDownloadDirectory().DS.$id.'.'.$ext,'overwrite' => true));
            $file->receive();
	endif;

	$file = $form->video;
	if($file->isUploaded()):
            $dirty = true;
            $ext = Sec_File::getExtension($file->getFileName());
            $file->addFilter('Rename', array('target' => Aew_Model_Bo_ConteudoDigital::getColaborativoDownloadDirectory().DS.$id.'.'.$ext,'overwrite' => true));
            $file->receive();
	endif;
	$this->enviarEmail($usuario, $mensagem);
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @param string $mensagem
     */
    public function enviarEmail(Aew_Model_Bo_Usuario $usuario, $mensagem)
    {
	$nome = $usuario->getNome();
	$email = $usuario->getEmail();
	$mail = new Sec_Mail();
	$mail->setBodyHtml($mensagem);
	$mail->setFrom(Sec_Global::getSystemEmail(), 'Ambiente Educacional Web');
	$mail->addTo($email, $nome);
	$mail->setSubject('Bem vindo ao Ambiente Educacional Web - Obrigado por participar!');
	try
        {
            $result = $mail->send();
	} 
        catch (Exception $e)
        {
	}
    }

    /**
     * Envia um email para um amigo falando sobre o conteudo
     * @param Sec_Form $form
     * @param string $mensagem
     * @return boolean
     */
    public function enviarParaAmigo($form, $mensagem)
    {
        $mail = new Sec_Mail();
        $nome = $form->getValue('nome');
        $email = $form->getValue('email');
        $mail->setBodyHtml($mensagem);
	$mail->setFrom(Sec_Global::getSystemEmail(), 'Ambiente Educacional Web');
	$mail->addTo($email, $nome);
	$mail->setSubject('Conheça esse conteúdo');
	$result = $mail->send();
	return $result;
    }

    /**
     * @param Aew_Model_Bo_ComponenteCurricular $componente
     */
    function addComponenteCurricular(Aew_Model_Bo_ComponenteCurricular $componente)
    {
        array_push($this->componentesCurriculares, $componente);
    }

    /**
     * 
     * @param Aew_Model_Bo_CategoriaComponenteCurricular $componenteCategoria
     */
    function addCategoriaComponenteCurricular(Aew_Model_Bo_CategoriaComponenteCurricular $componenteCategoria)
    {
        array_push($this->componentesCurriculares, $componenteCategoria);
    }
    
    /**
     * Retorna a Path para o conteudo para visualização
     */
    public function getConteudoVisualizacaoPath()
    {
        $formato = $this->getFormato()->getNome();
        if($this->getFormato()->getNome() == 'link'):
            $arquivos = glob($this->getConteudoVisualizacaoDirectory().DS.$this->getId().'.*');
            if(count($arquivos)):
                $arquivo = $arquivos[0];
                $formato = explode('.',$arquivo);
                $formato = end($formato);
            endif;
        endif;

        return $this->getConteudoVisualizacaoDirectory().DS.$this->getId().'.'.$formato;
    }
    
    /**
     * retorna o diretorio para os conteudos para visualização
     */
    public static function getConteudoVisualizacaoDirectory($tamanhoArquivo = null)
    {
        Aew_Model_Bo_ConteudoDigital::obtemDiscoGravacao($tamanhoArquivo);
         
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'visualizacao';
        else:
            $path = MEDIA_PATH.DS.'conteudos'.DS.'visualizacao';
        endif; 
        
        return $path;
    }
    
    /**
     * 
     * @param string $tamanhoArquivo
     * @return int
     */
    public static function obtemDiscoGravacao($tamanhoArquivo='')
    {
	$servidorBo = new Aew_Model_Bo_Servidor();
        if(!is_dir($servidorBo->getPathservidor()))
        {
            return 0;
        }
	$servidores = $servidorBo->select();
	$espacoLivre = array();
	foreach($servidores as $servidor):
		$espacoLivre[]['path'] = $servidor->getPathservidor();
		$espacoLivre[]['espaco_livre'] = disk_free_space($servidor->getPathservidor());
		$espacoLivre[]['espaco_gb'] = round(disk_free_space($servidor->getPathservidor())/(1024*1024),2)."GB livres";
		$espacoLivre[]['espaco_total'] = disk_total_space($servidor->getPathservidor());
	endforeach;
        return $espacoLivre;		
    }
    
    /**
     * 
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Tag $tag
     * @return array
     */
    function selectTags($num=0,$offset=0, Aew_Model_Bo_Tag $tag = null)
    {
        if(!$this->getId())
            return array();
        
        $tagcomponente = new Aew_Model_Bo_ConteudoDigitalTag();
        if($tag)
        {
            $tagcomponente->exchangeArray($tag->toArray());
        }
        $tagcomponente->setId($this->getId());
        $tags = $tagcomponente->select($num, $offset);
        $this->setTags($tags);
        return $this->getTags();
    }
    
    /**
     * 
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     * @return array
     */
    function selectComentarios($num=0,$offset=0, Aew_Model_Bo_Usuario $usuarioAutor = null,$totalComenatrios = false)
    {
        if(!$this->getId())
            return array();
        $comentario = new Aew_Model_Bo_ConteudoDigitalComentario();
        $comentario->setIdconteudodigital($this->getId());
        if($usuarioAutor)
            $comentario->setUsuarioAutor($usuarioAutor);
        
        $this->setComentarios($comentario->select($num, $offset,null,$totalComenatrios));
        return $this->getComentarios();
    }
    
    /**
     * Retorna o diretorio para as imagens associadas de qr-code
     * @return string
     */
    public static function getQRCodeDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'qr-code';
        else:
            $path = MEDIA_PATH.DS.'conteudos'.DS.'qr-code';
        endif;
        
        return $path;
    }

    /**
     * Retorna a url para as imagens associadas de qr-code
     * @return string
     */
    public static function getQRCodeUrl()
    {
        if(CONTEUDO_PATH):
            $path = DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'qr-code';
        else:
            $path = DS.'conteudos'.DS.'qr-code';
        endif;
        
        return $path;
    }
    
    /**
     * @return string
     */
    function getLinkPerfil($urlAbsoluta = false)
    {
        $path = '';
        if($urlAbsoluta):
            $path = new Zend_View_Helper_ServerUrl();
            $path = $path->serverUrl();    
        endif;
        switch($this->getConteudoDigitalCategoria()->getCanal()->getId()):
            case 1:
                $linkPerfil = '/tv-anisio-teixeira/programas/exibir/id/';
                break;
            
            default:
                $linkPerfil = '/conteudos-digitais/conteudo/exibir/id/';
                break;
                
        endswitch;
        return $path.$linkPerfil.$this->getId();
    }

    /**
     * Retorna a Url para o conteudo para download
     * @param $baseUrl
     */
    public function getUrlConteudoDownload($baseUrl)
    {
        if($this->getFormatoDownload()->getNome()) 
            return $baseUrl.'/conteudos'.DS.'conteudos-digitais'.DS.'download/'.$this->getId().'.'.$this->getFormatoDownload()->getNome();
    }

    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ConteudoDigitalComponente $componente
     * @return array|Aew_Model_Bo_ComponenteCurricular
     */
    function selectComponentesCurriculares($num=0,$offset=0, Aew_Model_Bo_ComponenteCurricular $componente = null)
    {
        if(!$this->getId())
            return array();
        $conteudoComponente = new Aew_Model_Bo_ConteudoDigitalComponente();
        if($componente)
        {
            $conteudoComponente->setId($componente->getId(),$this->getId());
        }
        else
        {
            $conteudoComponente->setId(array('',  $this->getId()));
        }
        $componentes = $conteudoComponente->select($num, $offset);
        $this->setComponentesCurriculares($componentes);
        foreach($componentes as $componente)
        {
            $this->addNivelEnsino($componente->getNivelEnsino());
        }
        return $this->getComponentesCurriculares();
    }
    
    function busca($num=0, $offset=0, array $ordem=null)
    {
       return  $this->getDao()->busca($this,$num,$offset,$ordem);
    }

    /**
     * retorna url para a ação de remoção do conteudo em questão
     * da lista de destaques
     * @param Aew_Model_Bo_Usuario $usuario usuario logado
     * @return string
     */
    function getUrlRemoverDestaque(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->getDestaque())
        if($usuario->acessoAtualizarDestaqueConteudo($this))
        return '/conteudos-digitais/conteudo/removerdestaque/id/'.$this->getId();
    }
    
    /**
     * @return string
     */
    function getUrlConteudosRelacionados()
    {
        return '/conteudos-digitais/relacionados/listar/id/'.$this->getId();
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlAdicionarDestaque(Aew_Model_Bo_Usuario $usuario)
    {
        if(!$this->getDestaque())
        if($usuario->acessoAtualizarDestaqueConteudo($this))
        {
            return '/conteudos-digitais/conteudo/destaque/id/'.$this->getId();
        }
    }
    
    
    static function getUrlAdicionar(Aew_Model_Bo_Usuario $usuario)
    {
        if($usuario->isCoordenador())
        return "conteudos-digitais/conteudo/adicionar";
    }

    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlApagar(Aew_Model_Bo_ItemPerfil $usuario)
    {
        $url = '';
        if((!$usuario) || (!$this->getId()))
         return "";
        if($usuario->isCoordenador())
        $url = '/conteudos-digitais/conteudo/apagar/id/'.$this->getId();
        return $url;
    }
    
    /**
     * retorna string de url de edicao para o usuario
     * @param Aew_Model_Bo_Usuario $usuario usuario logado que acessa o conteudo digital
     * @return string
     */
    function getUrlEditar(Aew_Model_Bo_Usuario $usuario = null)
    {
        $url = '';
        if((!$usuario) || (!$this->getId()))
         return "";
        if($usuario->isCoordenador())
        $url = '/conteudos-digitais/conteudo/editar/id/'.$this->getId();
        return $url;
    }
    
    /**
     * 
     * @return string
     */
    function getUrlDenunciar()
    {
        return  '/aew/home/denunciar/';
    }
    
    /**
     * 
     * @return string
     */
    function getUrlRemoverFavorito(Aew_Model_Bo_ItemPerfil $usuario=null)
    {
        if($usuario)
        {
            $favorito = $usuario->selectConteudosDigitaisFavoritos(1, 0, $this);
            if($favorito instanceof Aew_Model_Bo_ItemPerfil)
            {
                return '/conteudos-digitais/conteudo/remover-favorito/id/'.$this->getId().'/'.$usuario->perfilTipo().'/'.$usuario->getId();
            }
        }
        return false;
        
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return boolean|string
     */
    function getUrlAdicionarfavorito(Aew_Model_Bo_Usuario $usuario)
    {
        if($usuario)
        {
            $favorito = $usuario->selectConteudosDigitaisFavoritos(1, 0, $this);
            if(!$favorito instanceof Aew_Model_Bo_ItemPerfil)
            {
                return '/conteudos-digitais/conteudo/favorito/id/'.$this->getId();
            }
        }
        return false;
    }
    
    /**
     * @return string
     */
    function getIncorporarConteudoUrl($urlRelativa = true)
    {
        $path = "";
        if(!$urlRelativa):
            $path = new Zend_View_Helper_ServerUrl();
            $path = $path->serverUrl();    
        endif;
        if($this->getFormato()->getConteudoTipo()->getId() == Aew_Model_Bo_ConteudoTipo::$AUDIO ||
            $this->getFormato()->getConteudoTipo()->getId() == Aew_Model_Bo_ConteudoTipo::$VIDEO ||
            $this->getFormato()->getConteudoTipo()->getId() == Aew_Model_Bo_ConteudoTipo::$ANIMACAO_SIMULACAO ||
            $this->getFormatoDownload()->getConteudoTipo()->getId() == Aew_Model_Bo_ConteudoTipo::$AUDIO ||
            $this->getFormatoDownload()->getConteudoTipo()->getId() == Aew_Model_Bo_ConteudoTipo::$VIDEO ||
            $this->getFormatoDownload()->getConteudoTipo()->getId() == Aew_Model_Bo_ConteudoTipo::$ANIMACAO_SIMULACAO)
        return $path.DS.'conteudos-digitais'.DS.'conteudo/incorporar-conteudo'.DS.'id'.DS.$this->getId();
        
    }
    
    /**
     * Retorna a Url para o guia pedagogico
     * @param type $urlRelativa
     * @return boolean|string
     */
    public function getGuiaPedagogicoUrl($urlRelativa = true)
    {	
        $path = "";
        if(!$urlRelativa):
            $path = new Zend_View_Helper_ServerUrl();
            $path = $path->serverUrl();    
        endif;
        
        $arquivo = $this->getGuiaPedagogicoDirectory().DS.$this->getId().".".$this->getFormatoGuiaPedagogico()->getNome();
        
        if(!file_exists($arquivo)){
            return false;
        }
            
        if(CONTEUDO_PATH):
            $path .= DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'guias-pedagogicos'.DS.$this->getId().'.'.$this->getFormatoGuiaPedagogico()->getNome();
        else:
            $path .= DS.'guias-pedagogicos'.DS.$this->getId().'.'.$this->getFormatoGuiaPedagogico()->getNome();
        endif;
        
        return $path;
    }
        
    
    
    /**
     * insere um voto no conteudo digital
     * @param  Aew_Model_Bo_ItemPerfil $avaliador
     * @param  int $voto
     * @return int
     */
    public function insertVoto(Aew_Model_Bo_ItemPerfil $avaliador, $voto)
    {
        $votoConteudo = new Aew_Model_Bo_ConteudoDigitalVoto($avaliador);
        $votoConteudo->setUsuario($avaliador);
        $votoConteudo->setIdconteudodigital($this->getId());
        $votoc = $votoConteudo->select(1);
        if($votoc instanceof Aew_Model_Bo_ConteudoDigitalVoto)
        {
            $votoConteudo = $votoc; 
        }
        $votoConteudo->setDatacriacao(date('Y-m-d h:i:s'));
        $votoConteudo->setVoto($voto);
        return $votoConteudo->save();
    }
    
    /**
     * insere um componente curricular para este conteudo digital
     * @param Aew_Model_Bo_ConteudoDigitalComponente $conteudoComponente
     * @return int
     */
    function insertComponenteCurricular(Aew_Model_Bo_ComponenteCurricular $componente)
    {
        $conteudoComponente = new Aew_Model_Bo_ConteudoDigitalComponente();
        if(is_array($componente->getId()))
        {
            $idcomponente = $componente->getId();
            $idcomponente = $idcomponente[0];
        }
        else 
        {
            $idcomponente = $componente->getId();
        }
        $conteudoComponente->setId(array($idcomponente,$this->getId()));
        return $conteudoComponente->insert(); 
    }
    
    /**
     * seleciona os votos insseridos neste conteudo
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Usuario $usuario
     * @return array
     */
    function selectVotos($num=0,$offset=0,  Aew_Model_Bo_ItemPerfil $usuario=null)
    {
        if(!$this->getId())
            return array();
        $votoConteudo = new Aew_Model_Bo_ConteudoDigitalVoto();
        if($usuario)
        {
            $votoConteudo->setUsuario ($usuario);
        }
        $votoConteudo->setIdconteudodigital($this->getId());
        $this->setVotos($votoConteudo->select($num, $offset));
        return $this->getVotos();
    }
    
    /**
     * remove um componete curricular deste conteudo
     * @param Aew_Model_Bo_ConteudoDigitalComponente $conteudoComponente
     * @return int
     */
    function deleteComponeteCurricular(Aew_Model_Bo_ComponenteCurricular $conteudoComponente)
    {
        $componenteConteudo = new Aew_Model_Bo_ConteudoDigitalComponente();
        if(is_array($conteudoComponente->getId()))
        {
            $componenteConteudo->setId($conteudoComponente->getId());
        }
        else
        {
            $componenteConteudo->setId(array($conteudoComponente->getIdConteudoDigital(),$this->getId()));
        }
        return $conteudoComponente->delete();
    }
    
    /**
     * 
     * @param type $urlAbsoluta
     * @return boolean|string
     */
    public function getConteudoVisualizacaoUrl($urlAbsoluta = false)
    {   
        $path = '';
        $arquivo = '';
        if($urlAbsoluta):
            $path = new Zend_View_Helper_ServerUrl();
            $path = $path->serverUrl();    
        endif;

        $formato = $this->getFormato()->getNome();
        if($this->getFormato()->getNome() == 'link'):
            $arquivos = glob($this->getConteudoVisualizacaoDirectory().DS.$this->getId().'.*');
            if(count($arquivos)):
                $arquivo = $arquivos[0];
                $formato = explode('.',$arquivo);
                $formato = end($formato);
            endif;
        else:
            $arquivo = $this->getConteudoVisualizacaoDirectory().DS.$this->getId().'.'.$formato;
        endif;
        
        if(!file_exists($arquivo)):
            return false;
        endif;
        
        if(CONTEUDO_PATH):
            $path .= DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'visualizacao'.DS.$this->getId().'.'.$formato;
        else:
            $path .= DS.'conteudos'.DS.'visualizacao'.DS.$this->getId().'.'.$formato;
        endif;
        
        return $path;
    }

    
    /**
     * Retorna a Url d conteudo para download
     * @param type $urlRelativa
     * @param type $urlAbsoluta
     * @return boolean|string
     */
    public function getConteudoDownloadUrl($urlRelativa = false,$urlAbsoluta = false)
    {   
        $path = '';
        $arquivo = '';
        if($urlAbsoluta):
            $path = new Zend_View_Helper_ServerUrl();
            $path = $path->serverUrl();    
        endif;

        $formato = $this->getFormatoDownload()->getNome();
        if($this->getFormatoDownload()->getNome() == 'link'):
            $arquivos = glob($this->getConteudoDownloadDirectory().DS.$this->getId().'.*');
            if(count($arquivos)):
                $arquivo = $arquivos[0];
                $formato = explode('.',$arquivo);
                $formato = end($formato);
            endif;
        else:
            $arquivo = $this->getConteudoDownloadDirectory().DS.$this->getId().'.'.$formato;
        endif;
        
        if(!file_exists($arquivo)):
            return false;
        endif;
 
        $attr = $this->getAtributosArquivo($arquivo);
        
        if($urlRelativa):
            $path .= DS.'conteudos-digitais'.DS.'conteudo'.DS.'baixar'.DS.'id'.DS.$this->getId();
        else:
            if(CONTEUDO_PATH):
                $path .= DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'download'.DS.$this->getId().'.'.$formato;
            else:
                $path .= DS.'conteudos'.DS.'download'.DS.$this->getId().'.'.$formato;
            endif;
        endif;

        return $path;
    }

    /***
     * meta-dados do arquivo relacionado ao conteudo
     */
    public function getAtributosArquivo($arquivoPath)
    {
        $attributos = array();
        $arr_extensaovalida =  array(1 => "mp4", 2 => "webm", 3 => "ogg", 3 => "flv", 4 => "mp3", 5 => "wmv", 6 =>"mpg", 7 =>"avi", 7 =>"zip", 8 =>"pdf" );
        $extensao = explode(".", $arquivoPath);
        $extensao = end($extensao);
        
        if($this->getFormato()->getNome() == "link"):
            $attributos['filesize'] = $this->tamanhoArquivo($arquivoPath);
        elseif(file_exists($arquivoPath)):
            if(filesize($arquivoPath)>0):
                $attributos['filesize'] = $this->tamanhoArquivo($arquivoPath);
            endif;
        endif;

        if(isset($attributos['filesize'])):
            if(array_search($extensao, $arr_extensaovalida) == true && $attributos['filesize'] > 0):
                $movie = @new ffmpeg_movie($arquivoPath, false);
                if(isset($movie->ffmpeg_movie)):
                    $attributos['duration'] = $this->_converterHora($movie->getDuration());
                endif;
            endif;
        endif;

        return $attributos;
    }		

    /**
     * Retorna a Url da imagem associada para o conteudo
     * @return string
     */
    static function getImagemAssociadaUrl()
    {   
        if(CONTEUDO_PATH):
            $path = DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'imagem-associada';
        else:
            $path = DS.'conteudos'.DS.'imagem-associada';
        endif;
        return $path;
        
    }    
    
    /**
     * converte duracao em minutos para o formato h:m:s
     * @param string $duration
     * @return type
     */
    private function _converterHora($duration)
    {
        $seconds = (integer) $duration;
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;

        $hours   = ($hours<10 ? "0" : "").$hours;
        $minutes = ($minutes<10 ? "0" : "").$minutes;
        $seconds = ($seconds<10 ? "0" : "").$seconds;

        return "$hours:$minutes:$seconds";
    }
    
    /**
     * Retorna a URL para a imagem associada
     * @param boolean $todas
     * @param boolean $associada
     * @return string
     */
    public function getConteudoImagem($todas = false, $associada = false)
    {
        $id = $this->getId();
        $imagem = "";
        $extensoesValidas = array("png", "jpg", "gif" );
        
        //--- Verifica se possui imagem associada
        foreach($extensoesValidas as $key => $value):
            $image_path = $this->getImagemAssociadaDirectory()."/$id.$value";
            if(file_exists($image_path)):
                $imagem = $this->getImagemAssociadaUrl()."/$id.$value";
                break;
            endif;
        endforeach;

        if($associada == true):
            return $imagem;
        endif;

        if($imagem == ""):
            $this->_gerarMiniatura();
        
            //--- Verifica se possui imagem thumbail (gerada automáticamente)
            $imagens = glob($this->getImagemAssociadaDirectory()."/sinopse/$id.*.jpg");

            if(count($imagens)):
                shuffle($imagens);

                if($todas == false):
                    $imagens[0] = str_replace($this->getImagemAssociadaDirectory(),"",$imagens[0]);
                    $imagem = $this->getImagemAssociadaUrl().$imagens[0];
                else:
                    $imagem = str_replace($this->getImagemAssociadaDirectory(),$this->getImagemAssociadaUrl(),$imagens);
                endif;
                
            endif;
        endif;

        if($imagem == ""):
			if($this->getUsuarioPublicador()->getId() == 545 || strpos(strtolower($this->getFonte()), 'emitec') !== false):
				$imagem = $this->getImagemAssociadaUrl()."/emitec-video.jpg";
			endif;
		endif;

        if($imagem == ""):
            //--- Seleciona imagem padrão
            $tipoIcone = $this->getFormato()->getConteudoTipo()->getIconeTipo();
            $imagem = "/assets/img/icones/$tipoIcone.png";
        endif;

        return $imagem;
    }
    
    /**
     * 
     * @return boolean
     */
    function delete()
    {
        if(!parent::delete())
        {
            return false;
        }
        $conteudoFileDownload = $this->getConteudoDownloadDirectory().DS.
                            $this->getId().".".
                            $this->getFormatoDownload()->getNome();
        $conteudoFileVisualizacao = $this->getConteudoVisualizacaoDirectory().DS.
                            $this->getId().".".
                            $this->getFormatoDownload()->getNome();
        $guiaPedagogicoFile = $this->getGuiaPedagogicoDirectory().DS.
                              $this->getId().".".
                              $this->getFormatoguiaPedagogico()->getNome();
        if(file_exists($conteudoFileDownload)){
            if(false == unlink($conteudoFileDownload)){
                
            }
        }

        if(file_exists($conteudoFileVisualizacao)){
            if(false == unlink($conteudoFileVisualizacao)){
                
            }
        }

        if(file_exists($guiaPedagogicoFile)){
            if(false == unlink($guiaPedagogicoFile)){
                
            }
        }
        return true;
        
    }
    
    /**
     * 
     * @return string
     */
    function getUrlPerfilPublicador()
    {
        return '/conteudos-digitais/conteudos/publicador/id/'. $this->getUsuarioPublicador()->getId();
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return boolean|string
     */
    function getUrlAprovar(Aew_Model_Bo_Usuario $usuario)
    {
        if($usuario->acessoAprovarReprovarConteudo())
        return '/conteudos-digitais/conteudo/aprovar/id/'.$this->getId();
        return false;
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return boolean|string
     */
    function getUrlReprovar(Aew_Model_Bo_Usuario $usuario)
    {
        if($usuario->acessoAprovarReprovarConteudo())
        return '/conteudos-digitais/conteudo/id/'. $this->getId(); 
        return false;
    }

    /**
     * 
     * @return string
     */
    public function getData()
    {
        return $this->getDataPublicacao();
    }
    
    /**
     * @param Aew_Model_Bo_Tag $tag
     * @return int
     */
    public function insertTag(Aew_Model_Bo_Tag $tag)
    {
        $tagConteudo = new Aew_Model_Bo_ConteudoDigitalTag();
        
        if(is_array($tag->getId()))
        {
            $tagConteudo->setId($tag->getId());
        }
        else
        {
            $tagConteudo->setId(array($this->getId(),$tag->getId()));
        }
        
        $result = $tagConteudo->insert();
        
        //if($result)
        //{
        //    $this->addTag($tagConteudo);
        //}
        
        return $result;
    }
    
    /**
     * insere tags na base de dados
     * @param array|string $tags
     */
    public function insertTags($tags = "")
    {
        if($tags)
            $this->setTags($tags);

        if(is_array($this->getTags()))
        {
            foreach ($this->getTags() as $tag)
            {
                $this->insertTag($tag);
            }
        }
    }
    
    /**
     * 
     * @param array|string $componentes
     */
    public function insertComponentes($componentes = '')
    {
        if($componentes)
        {
            $this->setComponentesCurriculares($componentes);
        }
        foreach ($this->getComponentesCurriculares() as $componente) 
        {
            $this->insertComponenteCurricular($componente);
        }
    }
    
    /**
     * @param Aew_Model_Bo_ConteudoDigitalRelacionado $conteudo
     * @return int
     */
    public function insertConteudoRelacionado( Aew_Model_Bo_ConteudoDigitalRelacionado $conteudo)
    {
        $conteudoRelacionado = new Aew_Model_Bo_ConteudoDigitalRelacionado();
        $conteudoRelacionado->setIdconteudodigital($this->getId());
        $conteudoRelacionado->setIdconteudodigitalrelacionado($conteudo->getId());
        $result = $conteudoRelacionado->insert();
        if($result)
            $this->addConteudoRelacionado ($conteudo);
        return $result;
    }
   
    /**
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     * @param int $num
     * @param int $offset
     * @return array
     */
    public function selectConteudosRelacionados($num=0, $offset=0, $tags = null,$options = null)
    {   
        return $this->getDao()->selectConteudosRelacionados($this, $num, $offset, $tags);
    }
    
    /**
     * remove tag do conteudo digital
     * @param Aew_Model_Bo_Tag $tag
     * @return type
     */
    public function deleteTag(Aew_Model_Bo_Tag $tag)
    {
        $tagConteudo = new Aew_Model_Bo_ConteudoDigitalTag();
        if(is_array($tag->getId()))
        $tagConteudo->setId($tag->getId());
        else
        $tagConteudo->setId(array($this->getId(),$tag->getId()));
        $result = $tagConteudo->delete();
        return $result;
    }

    /**
     * nao implementado
     */
    public function perfilTipo()
    {
        
    }

    /**
     * nao implementado
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Album $album
     */
    public function selectAlbuns($num = 0, $offset = 0, Aew_Model_Bo_Album $album = null)
    {
        
    }

    /**
     * nao implementado
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Blog $blog
     */
    public function selectBlogs($num = 0, $offset = 0, Aew_Model_Bo_Blog $blog = null)
    {
        
    }
           
    /**
     * nao implementado
     */
    public function selectFotoPerfil()
    {
        
    }
    
    /**
     * retorna o diretorio para os conteudos para espaço colaborativo
     * @return string
     */
    public static function getColaborativoDownloadDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'colaborativo';
        else:
            $path = MEDIA_PATH.DS.'conteudos'.DS.'colaborativo';
        endif;
        
        return $path;
    }

    /**
     * 
     * @param Aew_Model_BO_ConteudoDigital $conteudoDigital
     * @return int
     */
    function deleteConteudoDigitalRelacionado(Aew_Model_BO_ConteudoDigital $conteudoDigital)
    {
        $conteudoRelacionado = new Aew_Model_Bo_ConteudoDigitalRelacionado();
        $conteudoRelacionado->exchangeArray($conteudoDigital->toArray());
        $conteudoRelacionado->setIdconteudodigital($this->getId());
        return $conteudoRelacionado->delete();
    }
    
    
    
    /**
     * 
     * @param array $arrayDownload
     * @param array $arrayVisualiza
     */
    public function getCriaJson($arrayDownload,$arrayVisualiza) {
        
        $data=array();
        $data['download']=$arrayDownload;
        $data['visualiza']= $arrayVisualiza;
        
        $json_string = json_encode($data);
        $file = '/tmp/files.json';
        file_put_contents($file, $json_string);
    }
    /**
     *  Le Diretorio Download ou Visualiza
     * @param type $path
     * @param type $tipo
     * @return boolean|array
     */
    public function getFiles($download,$visualiza,$formatos)
    {
        $formato = new Aew_Model_Bo_Formato();
        
        
        switch ($formatos) {
            case 1:
                $extencoes = $formato->getListImagem();
                break;
            case 2:
                $extencoes = $formato->getListAudio();
                break;
            case 3:
                $extencoes = $formato->getListVideo();
                break;
            case 4:
                $extencoes = $formato->getListDocumento();
                break;
            case 5:
                $extencoes = $formato->getListOutros();
                break;
            default:
                return false;
        }
        
        if($download){
            $pathDownload = $download.'/'.'*.'.'{'.$extencoes.'}';
        }
        if($visualiza){
            $pathVisualiza = $visualiza.'/'.'*.'.'{'.$extencoes.'}';
        }
        
        $items = array();
        foreach(glob($pathDownload, GLOB_BRACE) as $file):
            $fileName = end(explode("/",$file));
            
            $id = current(explode(".", $fileName));
            $extensao = end(explode(".", $fileName)); 
            $size = $this->tamanhoHumano(filesize($file));
            
            $items[$id]['id'] = $id;
            $items[$id]['download'] = $file;
            $items[$id]['formatoD'] = '';
            $items[$id]['tamanhoDownload'] = 
            $items[$id]['titulo'] = '';
            $items[$id]['categoria'] = '';
            $items[$id]['visualizacao'] = '';
            $items[$id]['formatoV'] = '';
            $items[$id]['tamanhoV'] = '';
            $items[$id]['usuarioPublicador'] = '';
            $items[$id]['aprovado'] = '';
            
            if(intVal($id)>0):
                // Procura Arquivo no Banco
                $conteudo = $this->procuraArquivoEmBanco($id,$extensao,$file,$fileName,$size,1);
                $items[$id]['id'] = $id;
                $items[$id]['download'] = $fileName;
                $items[$id]['titulo'] = $conteudo[$id]['titulo'];
                $items[$id]['categoria'] = $conteudo[$id]['categoria'];
                $items[$id]['tamanhoDownload'] = $size;
                $items[$id]['formatoD'] = $conteudo[$id]['formatoD'];
                $items[$id]['usuarioPublicador'] = $conteudo[$id]['usuarioPublicador'];
                $items[$id]['aprovado'] = $conteudo[$id]['aprovado'];
                $items[$id]['pathD'] = $conteudo[$id]['pathD'];
                $items[$id]['formatoRealD'] = $conteudo[$id]['formatoRealD'];
            endif;
        endforeach;
        foreach(glob($pathVisualiza, GLOB_BRACE) as $file):
            $fileName = end(explode("/",$file));
            $id = current(explode(".", $fileName));
            $extensao = end(explode(".", $fileName)); 
            $size = $this->tamanhoHumano(filesize($file));
            //Prenche o Array 
            $conteudo = $this->procuraArquivoEmBanco($id,$extensao,$file,$fileName,$size,2);
            if(array_key_exists($id, $conteudo_download) == false):
                if(intVal($id)>0):
                $items[$id]['id'] = $id;
                $items[$id]['titulo'] = $conteudo[$id]['titulo'];
                $items[$id]['categoria'] = $conteudo[$id]['categoria'];
                $items[$id]['usuarioPublicador'] = $conteudo[$id]['usuarioPublicador'];
                $items[$id]['aprovado'] = $conteudo[$id]['aprovado'];
                $items[$id]['visualizacao'] = '';
                $items[$id]['tamanhoV'] = $conteudo[$id]['tamanhoV'];
                $items[$id]['formatoV'] = '';
                $items[$id]['pathV'] = $conteudo[$id]['pathV'];
                endif;
            endif;
            $items[$id]['visualizacao'] = $conteudo[$id]['visualizacao'];
            $items[$id]['tamanhoV'] = $conteudo[$id]['tamanhoV'];
            $items[$id]['formatoV'] = $conteudo[$id]['formatoV'];
            $items[$id]['formatoRealV'] = $conteudo[$id]['formatoRealV'];
            $items[$id]['pathV'] = $conteudo[$id]['pathV'];
        endforeach;
        
        return $items;
        
    }
    
    /**
     * Procura se arquivo existe no banco de dados
     * @param int $id
     * @param string $extensao
     * @param string $filePath
     * @param string $fileNam
     */     
    public function procuraArquivoEmBanco($id,$extensao,$filePath,$fileName,$size,$tipo)
    {
        $conteudo = new Aew_Model_Bo_ConteudoDigital();
        $conteudo->setId($id);
        $conteudo = $conteudo->select(1);
        $array = array();
        
        if($tipo == 1):
            $array[$id]['download']= $fileName;
            $array[$id]['tamanhoDownload']= $size;
            $array[$id]['pathD']= $filePath;
        else:    
            $array[$id]['visualizacao']= $fileName;
            $array[$id]['tamanhoV']= $size;
            $array[$id]['pathV']= $filePath;
        endif;
        if($conteudo!= null):
            if($extensao != $conteudo->getFormatoDownload()->getNome() && $tipo == 1):
                $array[$id]['formatoRealD']= $extensao;
            endif;
            if($extensao != $conteudo->getFormato()->getNome() && $tipo == 2):
                $array[$id]['formatoRealV']= $extensao;
            endif;
            $array[$id]['titulo']= $conteudo->getTitulo();
            $array[$id]['categoria'] = $conteudo->getConteudoDigitalCategoria()->getNome();
            $array[$id]['formatoD'] = $conteudo->getFormatoDownload()->getNome();
            $array[$id]['usuarioPublicador'] = $conteudo->getUsuarioPublicador()->getNome();
            $array[$id]['aprovado'] = $conteudo->getFlaprovado();
            $array[$id]['formatoV'] = $conteudo->getFormato()->getNome();
            return  $array;
        else:
            $array[$id]['id'] = $id;
            $array[$id]['titulo']= "ARQUIVO N/ENCONTRADO ou N/APROVADO ";
            $array[$id]['path']= $filePath;
            return $array;
        endif;
        
       
    } 
    /**
     * Retorna o tamanho do arquivo físico
     * @param int $size se debe enviar o tamnaho do arquivo com filesize
     * @param int $precicao 
     * @return string retorna peso do arquivo ex: 20 MB 2 Bytes 1GB etc.. 
     */
    public function tamanhoHumano($size , $precicao = 1 )  
    {  
        if($size == 0)  
        {return "0 Bytes";}  
        $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");  
        return round($size/pow(1024, ($i = floor(log($size, 1024)))), $precicao ) . $filesizename[$i];  
    }  
    
    /**
     * caminho para visualizacao do arquivo
     * @param  string $arquivo
     * @param  string $isDownload
     * @param  string $formato
     * @param  string $urlAbsoluta
     * @param  boolean $gerarimagem
     * @return string
     */
    public function getConteudoVisualizar(&$arquivo = "", &$isDownload = "", &$formato = "", $urlAbsoluta = false, $gerarimagem = false)
    {
        $link = "";

        //--- Verifica se o contéudo a visualizar é um link externo
        if($this->getSite())
        {
            foreach($this->siteValido as $key => $value)
            {
                $pos = strpos($this->getSite(), $value);
                if($pos):
                    switch ($key):
                        case 'dominiopublico':
                            $site = str_replace(array("DetalheObraForm"),array("DetalheObraDownload"),$this->getSite());
                            foreach(get_headers($site) as $key=>$value)
                            {
                                if(stripos($value, "Location") !== false)
                                {
                                    $site = str_replace("Location: ", "", $value);
                                    $formato = end(explode(".",$site));
                                    if(array_search($formato, $this->extensaovalida) == true)
                                    {
                                        $link = $site;
                                        break;
                                    }
                                }
                            }
                            break;
                            
                        case 'tvescolaold':
                            $html = file_get_contents($this->getSite());
                            if($html):
                                //--- Expressão regular para procurar link de video para visualizar
                                $regex = '/file:\s[\'"]\s*(.*?)\s*[\'"]/i';

                                if(preg_match($regex, $html, $contents)):
                                    $link = $contents[1];
                                endif;
                            endif;
                            break;
                            
                        default:
                            $link = $this->getSite();
                            break;
                            
                    endswitch;
                endif;
            }
        }

        $isDownload = false;
        if($link):
            $arquivo = $link;
            return $link;
        endif; 

		if(!$gerarimagem)
		{

		    if(file_exists($this->getConteudoVisualizacaoPath()))
		    {
		        $formato = $this->getFormato()->getNome();
		        $link = $this->getConteudoVisualizacaoUrl($urlAbsoluta);
		        $arquivo = $this->getConteudoVisualizacaoPath();

		        if(file_exists($this->getConteudoDownloadPath()))
		        {
		            $isDownload = true;
		        }        
		    }        

		    $formato = (!$formato ? $this->getFormato()->getNome() : $formato);
		    if(!$link || !array_search($formato, $this->extensaovalida))
		    {

				$formato = $this->getFormatoDownload()->getNome();
		        if(file_exists($this->getConteudoDownloadPath($formato)))
		        {
		            $formato = $this->getFormatoDownload()->getNome();
		            $link = $this->getConteudoDownloadUrl($urlAbsoluta);
		            $arquivo = $this->getConteudoDownloadPath();
		            $isDownload = true;
		        }        
		    }
		}
		else
		{
		    $formato = $this->getFormatoDownload()->getNome();
	        if(file_exists($this->getConteudoDownloadPath($formato)))
	        {
	            $formato = $this->getFormatoDownload()->getNome();
	            $link = $this->getConteudoDownloadUrl($urlAbsoluta);
	            $arquivo = $this->getConteudoDownloadPath();
	            $isDownload = true;
	        }        

		    if(!$link || !array_search($formato, $this->extensaovalida))
		    {
				$formato = $this->getFormato()->getNome();
				if(file_exists($this->getConteudoVisualizacaoPath()))
				{
				    $formato = $this->getFormato()->getNome();
				    $link = $this->getConteudoVisualizacaoUrl($urlAbsoluta);
				    $arquivo = $this->getConteudoVisualizacaoPath();
			        $isDownload = false;
				}        
		    }
		}
        return $link;
    }
        
    /**
     * gera miniatura da imagem associada ao conteudo
     */
    private function _gerarMiniatura()
    {
        $uploadfile = "";
        $isDownload = false;
        $formato = "";

        $resultado = $this->getConteudoVisualizar($uploadfile, $isDownload, $formato);
        if(!$uploadfile):
            return;
        endif;
        
        $thumbaildir = $this->getImagemAssociadaDirectory().'/sinopse/';

        $site = false;
        foreach($this->siteValido as $key => $value):
            $site = strpos($this->getSite(), $value);
            if($site):
                break; 
            endif;
        endforeach;
        
        if($site):
            $nome[0] = $this->getid();
        else:
            $nome = explode(".", substr(strrchr($uploadfile, "/"), 1));
            if(filesize($uploadfile) == 0):
                return;
            endif;
        endif;

        $imagens = glob($thumbaildir.$nome[0].".*.jpg");
        
        if(count($imagens) > 0):
            return; 
        endif;
        
        $uploadfile = str_replace(" ","%20",$uploadfile);

        if($uploadfile != ""):

            $inicio = true;
            $comparar = new Sec_CompareImages();
            
            $movie = @new ffmpeg_movie($uploadfile, true);

            $status = (isset($movie->ffmpeg_movie) ? true : false);
            
            if(!$status)
                return;

            $duration = $movie->getDuration();
            $frames   = $movie->getFrameCount();

            $thumbnailOf = 1;
            $diferenca   = 100;
            $i           = 1;
            $j           = 1;
            $percentual  = 3; // Percentual para extrair quantidade de frames

            $width = 480;
            $height = 320;

            $imgAnterior = "";
            while($thumbnailOf < $frames):

                $thumbnailOf = (integer) (($percentual * $i) * $frames) / 100;
                if($thumbnailOf >= $frames):
                    break;
                endif;

                // criamos a instancia do frame com a classe ffmpeg_frame
                $frame = new ffmpeg_frame($movie);

	            $status = (isset($frame->ffmpeg_frame) ? true : false);
            
	            if(!$status)
	                return;
                        
                // recebe o frame
                $frame = $movie->getFrame($thumbnailOf);

                if(is_object($frame)):
                    $apagar = true;
                    // converte para uma imagem GD
                    $image = $frame->toGDImage();

                    //salva no HD.
                    $idImagem = $nome[0].".".($j < 10 ? "0" : "")."$j.jpg";
                    ImageJpeg($image, $thumbaildir.$idImagem, 100);
                    ImageDestroy($image);

                    if($comparar->vazio($thumbaildir.$idImagem) == false):
                        //chmod($thumbaildir.$idImagem,0776);

                        //--- Redimensiona
                        $im = ImageCreateFromJpeg($thumbaildir.$idImagem);
                        if($im):
                            $im = $this->_resize($im, $width, $height);
                            ImageJpeg($im, $thumbaildir.$idImagem, 100);
                            ImageDestroy($im);
                        endif;

                        if($imgAnterior != ""):
                            $diferenca = $comparar->compare($imgAnterior, $thumbaildir.$idImagem);
                        endif;

                        if($diferenca>16):
                            $apagar = false;
                            $imagens[] = "thumbail/$idImagem";
                            $imgAnterior = $thumbaildir.$idImagem;
                            $j++;
                        endif;

                    endif;

                    if($apagar == true):
                        unlink($thumbaildir.$idImagem);
                        $j = ($j > 1 ? $j-- : 1);
                    endif;

                endif;

                $i++;
            endwhile;

        endif;
    }
    

    /**
     * 
     * @param resource $img
     * @param int $new_side
     * @param int $new_height
     * @return resource
     */
    private function _resize(&$img, $new_side, $new_height = 0)
    {
        $width  = imagesx($img);
        $height = imagesy($img);

        $new_width  = $new_side;
        $new_height = ($new_height == 0 ? ($new_side * $height) / $width : $new_height);

        $new_img = ImageCreateTrueColor($new_width, $new_height);
        ImagecolorAllocate($new_img, 255, 255, 255);

        ImageCopyResampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        ImageDestroy($img);

        return $new_img;
    }

    /**
     * Retorna o tamanho de um determinado arquivo em KB, MB, GB TB, etc
     * @param string $arquivo O arquivo a ser verificado
     * @return string O tamanho do arquivo (já formatado)
     */
    private function tamanhoArquivo($arquivo)
    {
        $tamanhoarquivo = 0;

        $tamanhoarquivo = filesize($arquivo);
        
        //-- Medidas
        $medidas = array('KB','MB','GB','TB');
        if($this->getFormato()->getNome() == "link"):
            $attr = $this->extrairContentType($arquivo);
        
            if(isset($attr["length"])):
                $tamanhoarquivo = $attr["length"];
            endif;
        else: 
            if(file_exists($arquivo)){
                $tamanhoarquivo = filesize($arquivo);
            }
        endif;

        //-- Se for menor que 1KB arredonda para 1KB
        if($tamanhoarquivo < 999){
            $tamanhoarquivo = 1000;
        }

        for($i = 0; $tamanhoarquivo > 999; $i++){
            $tamanhoarquivo /= 1024;
        }

        return round($tamanhoarquivo,1).$medidas[$i-1];
    }
    
    /**
     * 
     * @param type $arquivo
     * @return type
     */
    public function extrairContentType($arquivo)
    {
        $tipo = "";
        ob_start();
        
        // Crear un gestor curl
        $ch = curl_init($arquivo);
        
        // Ejecutar
        $teste = curl_exec($ch);
        // Comprobar si ocurrió un error
        if(!curl_errno($ch))
        {
            $info = curl_getinfo($ch);
            $tipo["type"] = $info["content_type"];
            $tipo["length"] = $info["size_download"];
            $tipo["http_code"] = $info["http_code"];
        }
        // Cerrar manipulador
        curl_close($ch);
        ob_get_clean();
        return $tipo;
    }

    /**
     * nao implementado
     * @param Aew_Model_Bo_Foto $foto
     */
    public function saveFotoPerfil(Aew_Model_Bo_Foto $foto)
    {
        
    }

    /**
     * nao implementado
     * @param Aew_Model_Bo_ItemPerfil $avaliador
     * @param type $avaliacao
     */
    public function insertAvaliacao(Aew_Model_Bo_ItemPerfil $avaliador, $avaliacao)
    {
        
    }

    /**
     * nao implementado
     * @param type $num
     * @param type $offset
     * @param \Aew_Model_Bo_ItemPerfil $avaliador
     */
    public function selectAvaliacoes($num = 0, $offset = 0, \Aew_Model_Bo_ItemPerfil $avaliador = null)
    {
        
    }

    /**
     * atualiza|insere conteudo no banco de dados
     * @param type $conteudo
     * @param type $upload
     * @param type $adicionarTag
     * @return type
     */
    function saveConteudo($conteudo = null, $upload = false, $adicionarTag = false) 
    {

        if(!is_null($conteudo))
        {
            if(empty($conteudo['idformato']) || !empty($conteudo['site']))
			{
		        $conteudoTipo = new Aew_Model_Bo_ConteudoTipo();
				$conteudoTipo->setId($conteudo['idconteudotipo']);

	            $formato = new Aew_Model_Bo_Formato();
    	        $formato->setConteudoTipo($conteudoTipo);
                $formato->setNome('link');

                $formato = $formato->select(1);

                $this->setFormato($formato);
			}

            if(empty($conteudo['idconteudodigitalcategoria']))
            {
                $this->setConteudoDigitalCategoria();
            }

            if(empty($conteudo['datacriacao']))
            {
                $this->setDataCriacao();
            }
            else
            {
                $this->setDataCriacao($conteudo['datacriacao']);
            }
        }
        
        $result = parent::save();
        
        if($result && !$upload)
        {
            if($conteudo['tags'])
            {
                $arrTag = array();
                $tags = explode(',', $conteudo['tags']);
                foreach($tags as $tagNome)
                {
                    $options = array();
                    $tagNome = trim($tagNome);
                    if(!empty($tagNome))
                    {
						//--- Expressão regular que evita criar tag iguais por causa de carateres especiais como ponto (.)
						$expRegular = "CONCAT('[',CHR(92),CHR(39),CHR(92),CHR(34),CHR(92),CHR(46),CHR(45),CHR(58),CHR(59),CHR(44),'\s”“]+')"; 

                        $options['where']['lower(sem_acentos(tag.nometag)) like lower(sem_acentos((?)))'] = $tagNome;
						//$options["where"]["sem_acentos(LOWER(REGEXP_REPLACE(tag.nometag,$expRegular, '','g'))) = sem_acentos(LOWER(REGEXP_REPLACE(?,$expRegular, '','g')))"] = $tagNome;

                        $tagBo = new Aew_Model_Bo_Tag();
                        $tag = $tagBo->select(0, 0, $options);
                        if($tag)
                        {   
                            $tagBo->setId($tag[0]->id);
                            $arrTag[] = $tagBo;
                        }
                        else
                        {
                            if($adicionarTag)
                            {
                                $tagBo->setNome($tagNome);
                                if($tagBo->insert())
                                {
                                    $arrTag[] = $tagBo;
                                }
                            }
                        }
                    }
                }

                if(count($arrTag))
                {
                    $this->deleteALlTags();
                    $this->setTags($arrTag);
                    $this->insertTags();
                }
            }
            
            if($conteudo['componentes'])
            {
                $arrComponentes = array();
                
                $componentesCurricular = explode(',', $conteudo['componentes']);
                foreach($componentesCurricular as $componente)
                {
                    $componenteBo = new Aew_Model_Bo_ComponenteCurricular();
                    $componenteBo->setId($componente);
                    $arrComponentes[] = $componenteBo;
                }
                
                if(count($arrComponentes))
                {
                    $this->selectComponentesCurriculares();
                    $this->deleteComponentesCurriculares(); 
                    
                    $this->setComponentesCurriculares($arrComponentes);
                    $this->insertComponentes();
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Atualiza campo 'avaliacao' da tabela 'conteudodigital'
     * @return int
     */
    function atualizaAvaliacao()
    {
        $avaliacao = 0;
        $count = 0;
        foreach ($this->selectVotos() as $voto) 
        {
            $avaliacao += $voto->getVoto();
            $count++;
        }
        $this->setAvaliacao(intval($avaliacao)/$count);
        $this->update();
    }
    
    /**
     * cria objeto de acesso banco de dados
     * @return \Aew_Model_Dao_ConteudoDigital
     */
    protected function createDao()
    { 
        return new Aew_Model_Dao_ConteudoDigital();
    }
}
