<?php
/**
 * BO da entidade Comunidade
 */
class Aew_Model_Bo_Comunidade extends Aew_Model_Bo_ItemPerfil
{
    protected $descricao,$datacriacao,$qtdvisitas,$idfavorito;
    protected $usuario,$comuAgenda,$comunidadeAlbum,$comunidadeTag;
    protected $comuRelacionada,$topicos=array();
    protected $ativa;
    protected $nomecomunidade;
    protected $comunidadesRelacionadas = array(), $comunidadesARelacionar = array();
    protected $moderadoresComunidade = array(), $membros = array(), $membrosAtivos = array(), $tagsConteudo = array();
    private   $membrosBloqueados = array();
    private   $membrosPendentes  = array();
    protected $flmoderausuario, $flpendente;
    /**
     * Construtor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setFotoPerfil(new Aew_Model_Bo_ComunidadeFoto());
        $this->setUsuario(new Aew_Model_Bo_Usuario());
    }

    /**
     * retorna array de membros da comunidade
     * @return array
     */
    public function getMembrosAtivos()
    {
        return $this->membrosAtivos;
    }

    /**
     * 
     * @return int
     */
    public function getFlpendente()
    {
        return $this->flpendente;
    }

    /**
     * adiciona membro a comunidade
     * @param Aew_Model_Bo_Usuario $usuario
     */
    public function addMembro(Aew_Model_Bo_Usuario $usuario)
    {
        array_push($this->membros,$usuario);
    }
    
    /**
     * insere na variavel local o array de membros ativos
     * @param array $membrosAtivos
     */
    public function setMembrosAtivos(array $membrosAtivos)
    {
        $this->membrosAtivos = $membrosAtivos;
    }

    /**
     * 
     * @param int $flpendente
     */
    public function setFlpendente($flpendente)
    {
        $this->flpendente = $flpendente;
    }

    /**
     * 
     * @return atring
     */
    public function getNome()
    {
        return $this->nomecomunidade;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nomecomunidade = $nome;
    }

    /**
     * retorna array de objetos dos membros pendentes a aprova
     * @return array
     */
    public function getMembrosPendentes()
    {
        return $this->membrosPendentes;
    }

    /**
     * 
     * @param array $membrosPendentes
     */
    public function setMembrosPendentes($membrosPendentes)
    {
        $this->membrosPendentes = $membrosPendentes;
    }

    /**
     * 
     * @return array
     */
    public function getMembrosBloqueados()
    {
        return $this->membrosBloqueados;
    }

    /**
     * insere array de membros bloqueados na variavel local
     * @param array $membrosBloqueados
     */
    public function setMembrosBloqueados($membrosBloqueados)
    {
        $this->membrosBloqueados = $membrosBloqueados;
    }

    /**
     * retorna array de comunidades realacionadas
     * @return array
     */
    public function getComunidadesRelacionadas()
    {
        return $this->comunidadesRelacionadas;
    }

    /**
     * insere array de comunidades relacionadas na variavel local
     * @param array $comunidadesRelacionadas
     */
    public function setComunidadesRelacionadas($comunidadesRelacionadas)
    {
        $this->comunidadesRelacionadas = $comunidadesRelacionadas;
    }

    /**
     * 
     * @return array
     */
    public function getComunidadesARelacionar()
    {
        return $this->comunidadesARelacionar;
    }

    /**
     * 
     * @param array $comunidadesARelacionar
     */
    public function setComunidadesARelacionar($comunidadesARelacionar)
    {
        $this->comunidadesARelacionar = $comunidadesARelacionar;
    }
    
    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->getUsuario()->exchangeArray($data);
        $this->getFotoPerfil()->exchangeArray($data);
        $this->setTags(isset($data["tags"]) ? $data["tags"] : null);
    }
    
    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        
        if($this->getUsuario()->getId())
        {
            $data['idusuario'] = $this->getUsuario()->getId();
        }
        
        if($this->getTags())
        {
            foreach($this->getTags() as $tag)
            {
                if(is_array($tag->getId()))
                {
                    $data['idtag'] = $tag->getId();
                }
                else
                {
                    $data['idtag'][] = $tag->getId();
                }
            }
        }
        
        return $data;
    }

    /**
     * se a comunidade e passivel ou nao a moderacao
     * @return boolean
     */
    public function getFlmoderausuario()
    {
        return $this->flmoderausuario;
    }

    /**
     * 
     * @param boolean $flmoderausuario
     */
    public function setFlmoderausuario($flmoderausuario)
    {
        $this->flmoderausuario = $flmoderausuario;
    }

    /**
     * 
     * @return array
     */
    public function getConteudosDigitaisfavoritos()
    {
        return $this->conteudosDigitaisfavoritos;
    }

    /**
     * 
     * @param array $conteudosDigitaisfavoritos
     */
    public function setConteudosDigitaisfavoritos($conteudosDigitaisfavoritos)
    {
        $this->conteudosDigitaisfavoritos = $conteudosDigitaisfavoritos;
    }

    /**
     * 
     * @return url
     */
    public function getLinkPerfil()
    {
        return '/espaco-aberto/comunidade/exibir/comunidade/'.$this->getId();
    }
    
    /**
     * informa se a comunidade esta ativa ou nao
     * @return boolean
     */
    public function getAtiva() {
        return $this->ativa;
    }

    /**
     * 
     * @param boolean $ativa
     */
    public function setAtiva($ativa) {
        $this->ativa = $ativa;
    }

    /**
     * 
     * @param array $moderadores
     */
    public function setModeradoresComunidade(array $moderadores) {
        $this->moderadoresComunidade = $moderadores;
    }

    /**
     * 
     * @return array
     */
    public function getModeradoresComunidade() {
        return $this->moderadoresComunidade;
    }

    /**
     * 
     * @return array
     */
    public function getMembros() 
    {
        return $this->membros;
    }

    /**
     * 
     * @param array $membros
     */
    public function setMembros(array $membros) 
    {
        $this->membros = $membros;
    }

    /**
     * id relacionando comunidade com tabela favorito
     * @return type
     */
    public function getIdFavorito() 
    {
        return $this->idfavorito;
    }

    /**
     * 
     * @return int
     */
    public function getIdUsuario() {
        return $this->idUsuario;
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
    public function getDataCriacao() {
        return $this->datacriacao;
    }

    /**
     * 
     * @return string
     */
    public function getQtdVisitas() {
       return $this->qtdvisitas;
    }
    /**
     * retorna dono da comunidade
     * @return Aew_Model_Bo_Usuario
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * 
     * @return Aew_Model_Bo_Album
     */
    public function getComunidadeAlbum() {
        return $this->comunidadeAlbum;
    }

    /**
     * 
     * @return type
     */
    public function getComunidade() {
        return $this->comunidade;
    }

    /**
     * 
     * @return Aew_Model_Bo_Comunidade
     */
    public function getComuRelacionada() {
        return $this->comuRelacionada;
    }

    /**
     * 
     * @return array
     */
    public function getTopicos() {
        return $this->topicos;
    }

    /**
     * 
     * @return int
     */
    public function getComuVoto() {
        return $this->comuVoto;
    }

    /**
     * 
     * @param type $idfavorito
     */
    public function setIdfavorito($idfavorito) {
        $this->idfavorito = $idfavorito;
    }

    /**
     * 
     * @param int $idUsuario
     */
    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
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
     * @param string $datacriacao
     */
    public function setDataCriacao($datacriacao) {
        $this->datacriacao = $datacriacao;
    }

    /**
     * 
     * @param int $qtdVisitas
     */
    public function setQtdVisitas($qtdVisitas) {
        $this->qtdvisitas = $qtdVisitas;
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario usuario dono da comunidade
     */
    public function setUsuario(Aew_Model_Bo_Usuario $usuario) {
        $this->usuario = $usuario;
    }

    public function setComuAgenda($comuAgenda) {
        $this->comuAgenda = $comuAgenda;
    }

    /**
     * 
     * @param Aew_Model_Bo_ComunidadeAlbum $comunidadeAlbum
     */
    public function setComunidadeAlbum(Aew_Model_Bo_ComunidadeAlbum $comunidadeAlbum) {
        $this->comunidadeAlbum = $comunidadeAlbum;
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
        else if(is_string($tags))
        {
            $nometags = explode(',',$tags);
            foreach( $nometags as $nometag)
            {
                $boTag = new Aew_Model_Bo_Tag();
                $boTag->setNome(trim($nometag));
                
                $boTag = $boTag->select(1);
                if($boTag instanceof Aew_Model_Bo_Tag)
                {
                    $this->addTag($boTag);
                }
            }
        }
    }
    
    /**
     * 
     * @param type $comuRelacionada
     */
    public function setComuRelacionada($comuRelacionada) {
        $this->comuRelacionada = $comuRelacionada;
    }

    /**
     * 
     * @param array $topicos
     */
    public function setTopicos(array $topicos) {
        $this->topicos = $topicos;
    }

    /**
     * 
     * @param type $comuVoto
     */
    public function setComuVoto($comuVoto) {
        $this->comuVoto = $comuVoto;
    }

    /**
     * Retorna se um usuário é dono de uma comunidade
     * @param Aew_Model_Bo_Usuario $usuario
     * @return boolean
     */
    public function isDono(Aew_Model_Bo_Usuario $usuario)
    {
        if($usuario->getId() == $this->getUsuario()->getId())
        {
            return true;
        }
        return false;
    }

    /**
     * Retorna se um usuário é moderado de uma comunidade
     * @param Aew_Model_Bo_Usuario $usuario
     * @return boolean
     */
    public function isModerador(Aew_Model_Bo_Usuario $usuario)
    {
        foreach ($this->getModeradoresComunidade() as $moderador) 
        {
            if($moderador->getId() == $usuario->getId())
            {
                return true;
            }
        }
        return false;
    }


    /**
     * Retorna se um usuário é participante de uma comunidade
     * @param int $idComunidade
     * @param int $idUsuario
     * @param bool $incluirDono dono é moderador?
     */
    public function isParticipante(Aew_Model_Bo_Usuario $usuario)
    {
        $participantes = $this->selectMembros(0, 0, $usuario);
        if(count($participantes)>0)
        return true;
    }
    
    /**
     * retorna verdadeiro se for pendente e false caso contrario
     * @param Aew_Model_Bo_Usuario $usuario
     * @return boolean
     */
    public function isPendente(Aew_Model_Bo_Usuario $usuario)
    {
        $membro = $this->selectMembrosPendentes(1,0,$usuario);
        if($membro instanceof Aew_Model_Bo_Usuario)
        return true;
        return false;
    }

    /**
     * Aumenta o número de acessos de uma comunidade
     * @param Comunidade $comunidade
     * @return Comunidade
     */
    public function aumentarAcesso()
    {
        $qtdVisitas = $this->setQtdVisitas((int)$this->getQtdVisitas()+1);
        $this->setQtdVisitas($qtdVisitas);
        return $this->update();
    }

    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ComuUsuario $usuario
     * @return array
     */
    public function selectMembros($num=0,$offset=0,  Aew_Model_Bo_Usuario $usuario = null)
    {
        $usuarioMembro = new Aew_Model_Bo_ComuUsuario();
        if($usuario)
        {
            $usuarioMembro->setIdusuario($usuario->getId());
            $usuarioMembro->setNome($usuario->getNome());
        }
        $usuarioMembro->setComunidade($this);
        $this->setMembros($usuarioMembro->select($num,$offset));
        return $this->getMembros();
    }

    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Usuario $usuario
     * @return type
     */
    function selectMembrosAtivos($num=0, $offset=0, Aew_Model_Bo_Usuario $usuario = null, $options = null)
    {
        $comuUsuario = new Aew_Model_Bo_ComuUsuario();
        if($usuario)
            $comuUsuario->setId($usuario->getId());
        
        $comuUsuario->setFlpendente('FALSE');
        $comuUsuario->setBloqueado('FALSE');
        $comuUsuario->setComunidade($this);
        
        $this->setMembrosAtivos($comuUsuario->select($num, $offset, $options));
        
        return $this->getMembrosAtivos();
    }
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ComuUsuario $usuario
     * @return array
     */
    public function selectMembrosBloqueados($num=0, $offset=0, Aew_Model_Bo_Usuario $usuario = null, $options = null)
    {
        $usuarioBloqueado = new Aew_Model_Bo_ComuUsuario();
        if($usuario)
        {
            $usuarioBloqueado->setIdusuario ($usuario->getId ());
            $usuarioBloqueado->setNome($usuario->getNome());
        }
        $usuarioBloqueado->setComunidade($this);
        $usuarioBloqueado->setBloqueado(true);
        $this->setMembrosBloqueados($usuarioBloqueado->select($num, $offset, $options, true));
        
        return $this->getMembrosBloqueados();
    }
    
    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuario
     * @return array
     */
    public function selectMembrosPendentes($num=0,$offset=0,  Aew_Model_Bo_Usuario $usuario=null)
    {
        $usuarioPendente = new Aew_Model_Bo_ComuUsuario();
        if($usuario)
        {
            $usuarioPendente->setId($usuario->getId());
            $usuarioPendente->setNome($usuario->getNome());
        }
        $usuarioPendente->setFlpendente(true);
        $usuarioPendente->setComunidade($this);
        $this->setMembrosPendentes($usuarioPendente->select($num, $offset, $usuarioPendente));
        return $this->getMembrosPendentes();
    }


    /**
     * 
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ComuRelacionada $comunidade
     * @return type
     */
    public function selectComunidadesRelacionadas($num=0, $offset=0, Aew_Model_Bo_ComuRelacionada $comunidade = null, $options = null)
    {
        if(!$comunidade)
        {
            $comunidade = new Aew_Model_Bo_ComuRelacionada();
        }
        
        $comunidade->setIdcomunidade($this->getId());
        $this->setComunidadesRelacionadas($comunidade->select($num, $offset, $options, true));
        
        return $this->getComunidadesRelacionadas();
    }

    /**
     * 
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ComuRelacionada $comunidade
     * @return type
     */
    public function selectComunidadesARelacionar($num=0, $offset=0, Aew_Model_Bo_Comunidade $comunidade = null, $options = null)
    {
        if(!$comunidade)
        {
            $comunidade = new Aew_Model_Bo_Comunidade();
        }

        $comunidade->setId($this->getId());
        
        $comunidadesRelacionadas = $comunidade->selectComunidadesRelacionadas();
        if($comunidadesRelacionadas)
        {
            foreach($comunidadesRelacionadas as  $comunidade)
            {
                $arrayComunidade[]= $comunidade->getIdcomunidaderelacionada();
            }
        }
        
        if($arrayComunidade)
        {
            $options['where']['comunidade.idcomunidade NOT IN(?)'] = array($arrayComunidade);
        }
        
        $comunidade = new Aew_Model_Bo_Comunidade();
        $comunidade->setAtiva(true);
        $this->setComunidadesARelacionar($comunidade->select($num, $offset, $options, true));
        
        return $this->getComunidadesARelacionar();
    }
    
    
    /**
     * @param Aew_Model_Bo_Comunidade
     * @param int $num
     * @param int $offset
     * @return array
     */
    public function selectComunidadesRelacionadasTag($num=0, $offset=0, $tags = null, $options = null)
    {   
        return $this->getDao()->selectComunidadesRelacionadasTag($this, $num, $offset, $tags);
    }
    
    /**
     * 
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ComuUsuario $usuario
     * @return type
     */
    public function selectModeradores($num=0,$offset=0, Aew_Model_Bo_ComuUsuario $usuario=null)
    {
        if(!$usuario)
        $usuario = new Aew_Model_Bo_ComuUsuario();
        $usuario->setComunidade($this);
        $usuario->setFlmoderador(true); 
        $moderadores = array();
        foreach($usuario->select($num,$offset) as $moderador)
        {
            array_push($moderadores, $moderador);
        }
        $this->setModeradoresComunidade($moderadores);
        return $this->getModeradoresComunidade();
        
    }
    
    /**
     * Seleciona a fotoperfil na base de dados
     * @return UsuarioFoto ou false se objeto não tiver id
     */
    function selectFotoPerfil()
    {
        if(!$this->getId())
        return false;
        
        $fotoPerfil = new Aew_Model_Bo_ComunidadeFoto();
        $fotoPerfil->setIdComunidade($this->getId());
        $foto = $fotoPerfil->select();
        if(isset($foto[0]))
        {
            $this->setFotoPerfil($foto[0]);
        }
        else
        $this->setFotoPerfil (new Aew_Model_Bo_ComunidadeFoto());
        return $this->getFotoPerfil();
    }
    
    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Foto $foto
     * @return array
     */
    function selectFotos($num = 0, $offset = 0, Aew_Model_Bo_Foto $foto = null) 
    {
        $fotoUsuario = new Aew_Model_Bo_UsuarioFoto();
        if($foto)
        {
            $fotoUsuario->exchangeArray($foto->toArray());
        }
        $this->setFotos($fotoUsuario->select($num,$offset));
        return $this->getFotos();
    }
    
    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuario
     * @return array
     */
    function selectTopicos($num = 0, $offset = 0,  Aew_Model_Bo_Usuario $usuario=null)
    {
        if(!$this->getId())
        {
            return array();
        }
        $topico = new Aew_Model_Bo_ComuTopico();
        if($usuario)
        $topico->setUsuarioAutor ($usuario);
        $topico->setIdComunidade($this->getId());
        $this->setTopicos($topico->select($num,$offset));
        return $this->getTopicos();
    }

    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Tag $tag
     * @param type $options
     * @return array
     */
    function selectTags($num = 0, $offset = 0, Aew_Model_Bo_Tag $tag = null, $options = null)
    {
        if(!$this->getId())
        {
            return array();
        }
        
        $tagComunidade = new Aew_Model_Bo_ComunidadeTag();
        if($tag)
        {
            $tagComunidade->exchangeArray($tag->toArray());
        }
        
        $tagComunidade->setIdComunidade($this->getId());
        $tags = $tagComunidade->select($num, $offset, $options);

        $this->setTags($tags);
        return $this->getTags();
    }
    
    /**
     * 
     * @return string
     */
    public function perfilTipo() {
        return 'comunidade';
    }

    /**
     * 
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ComunidadeBlog $comuBlog
     */    
    function selectBlogs($num=0,$offset=0,Aew_Model_Bo_Blog $blog=null)
    {
        $comuBlog = new Aew_Model_Bo_ComunidadeBlog();
        if($blog)
        {
           $comuBlog->setId($blog->getId());
           $comuBlog->exchangeArray($blog->toArray()) ;
        }
        $comuBlog->setIdcomunidade($this->getId());
        $this->setBlogs($comuBlog->select($num, $offset));
        return $this->getBlogs();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuarioLogado
     * @return string
     */
    function getUrlEntrar(Aew_Model_Bo_Usuario $usuarioLogado)
    {
        if(!$this->isParticipante($usuarioLogado))
        {
            return '/espaco-aberto/comunidade/participar/comunidade/'.$this->getId();
        }
        return false;
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuarioLogado
     * @return string
     */
    function getUrlSair(Aew_Model_Bo_Usuario $usuarioLogado)
    {
        if($this->isParticipante($usuarioLogado))
        {
            return '/espaco-aberto/comunidade/sair/comunidade/'.$this->getId().'/usuario/'.$usuarioLogado->getId();
        }
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuarioLogado
     * @return string
     */
    function getUrlAdicionarRelacao(Aew_Model_Bo_Usuario $usuarioLogado, Aew_Model_Bo_ItemPerfil $usuarioPerfil)
    {
        if($this->isDono($usuarioLogado) || $usuarioLogado->isCoordenador())
        {
            return '/espaco-aberto/comunidade/adicionar-relacao/comunidade/'.$usuarioPerfil->getId().'/relacionar/'.$this->getId();
        }
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuarioLogado
     * @param Aew_Model_Bo_ItemPerfil $usuarioPerfil
     * @return string
     */
    function getUrlRemoverRelacao(Aew_Model_Bo_Usuario $usuarioLogado, Aew_Model_Bo_ItemPerfil $usuarioPerfil)
    {
        if($this->isDono($usuarioLogado) || $usuarioLogado->isCoordenador())
        {
            return '/espaco-aberto/comunidade/remover-relacao/comunidade/'.$usuarioPerfil->getId().'/relacionado/'.$this->getIdComunidade();
        }
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlApagarComunidade(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->isDono($usuario) ||  $usuario->isAdmin())
        {
            return '/espaco-aberto/comunidade/apagar/comunidade/'.$this->getId();
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getUrlListaForuns()
    {
        return '';
    }
    
    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Album $album
     * @return array
     */
    public function selectAlbuns($num = 0, $offset = 0, Aew_Model_Bo_Album  $album = null)
    {
        $comunidadeAlbum = new Aew_Model_Bo_ComunidadeAlbum();
        if($album)
        {
            $comunidadeAlbum->exchangeArray($album->toArray());
            $comunidadeAlbum->setId($album->getId());
        }
        $comunidadeAlbum->setIdcomunidade($this->getId());
        $this->setAlbuns($comunidadeAlbum->select($num, $offset));
        return $this->getAlbuns();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_ComuTopico $topico
     * @return type
     */
    public function insertTopico(Aew_Model_Bo_ComuTopico $topico)
    {
        $topico->setIdcomunidade($this->getId());
        return $topico->save();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_ComuTopico $topico
     * @return type
     */
    public function deleteTopico(Aew_Model_Bo_ComuTopico $topico)
    {
        $topico->setIdcomunidade($this->getId());
        return $topico->delete();
    }
    
    /**
     * 
     * @return int|boolean
     */
    function deleteALlTags()
    {
        if(!$this->getId())
            return false;
        
        $tag = new Aew_Model_Bo_ComunidadeTag();
        $tag->setId($this->getId());
        $tag->delete();
    }
    
    /**
     * insere tags relacionados a comunidades no banco de dados
     * @param string|array $tags
     */
    public function insertTags($tags = "")
    {
        if($tags)
            $this->setTags($tags);
        
        /*--- Apaga todas as tags antes de salvar ---*/
        $this->deleteALlTags();
        

        if(is_array($this->getTags()))
        {
            foreach ($this->getTags() as $tag) 
            {
                $this->insertTag($tag);
            }
        }
    }
    
    /**
     * @param Aew_Model_Bo_Tag $tag
     * @return int
     */
    public function insertTag(Aew_Model_Bo_Tag $tag)
    {
        $tagConteudo = new Aew_Model_Bo_ComunidadeTag();
        if(is_array($tag->getId()))
        {
            $tagConteudo->setId($tag->getId());
        }
        else
        {
            $tagConteudo->setId(array($this->getId(),$tag->getId()));
        }
        
        $result = $tagConteudo->insert();
        if($result)
        {
            $this->addTag($tagConteudo);
        }
        
        return $result;
    }
    
    /**
     * verifica se membro esta bloqueado
     * @param Aew_Model_Bo_Usuario $membro
     * @return boolean
     */
    function isBloqueado(Aew_Model_Bo_Usuario $membro)
    {
        $comuusuario = new Aew_Model_Bo_ComuUsuario();
        $comuusuario->setIdusuario($membro->getIdusuario());
        $comuusuario->setComunidade($this);
        $comuusuario->setBloqueado(true);
        if($comuusuario->select(1) instanceof Aew_Model_Bo_ComuUsuario)
        {
            return true;
        }
        
        return false;
    }
 
    /**
     * insere novo membro na comunidade
     * @param Aew_Model_Bo_Usuario $membro
     */
     function insertMembro(Aew_Model_Bo_Usuario $membro)
    {
        $novoMembro = new Aew_Model_Bo_ComuUsuario();
        $novoMembro->setComunidade($this);
        $novoMembro->setIdusuario($membro->getId());
        $novoMembro->setDatacriacao(date('Y-m-d h:i:s'));
        $return = $novoMembro->save();
        if($return)
        {
            $this->addMembro($novoMembro);
        }
        return $return;
    }
    
    /**
     * insere novo membro na comunidade
     * @param Aew_Model_Bo_Usuario $membro
     */
    function insertRequisicaoMembro(Aew_Model_Bo_Usuario $membro)
    {
        $novoMembro = new Aew_Model_Bo_ComuUsuario();
        $novoMembro->setComunidade($this);
        $novoMembro->setIdusuario($membro->getId());
        $novoMembro->setFlpendente(true);
        $return = $novoMembro->insert();
        if($return)
        {
            $this->addMembro($novoMembro);
        }
        return $return;
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $membro
     * @return mixed
     */
    function aprovaMembro(Aew_Model_Bo_Usuario $membro)
    {
        $novoMembro = new Aew_Model_Bo_ComuUsuario();
        $novoMembro->setIdComunidade($this->getId());
        $novoMembro->exchangeArray($membro->toArray());
        $novoMembro->setFlpendente("FALSE");
        $novoMembro->setFlativo(true);
        $return = $novoMembro->save();
        if($return)
        {
            $this->addMembro($novoMembro);
        }
        return $return;
    }
    
    /**
     * @return string|boolean
     */
    function getUrlListaForum()
    {
        if($this->getId())
        return "/espaco-aberto/forum/listar/comunidade/".$this->getId();
        return false;
    }
    
    /**
     * url da lista de membros da comunidade
     * @return string|boolean
     */
    function getUrlListaMembros()
    {
        if($this->getId())
        return "/espaco-aberto/membro/listar/comunidade/".$this->getId();
        return false;
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string   
     */
    function getUrlModerar(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->isDono($usuario)|| ($this->isModerador($usuario)|| ($usuario->isSuperAdmin()) && ($this->getId())))
        return "/espaco-aberto/moderador/listar/comunidade/".$this->getId();
        return false;
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @param Aew_Model_Bo_Usuario $membro
     * @return type
     */
    function getUrlRemoverMembro(Aew_Model_Bo_Usuario $usuario, Aew_Model_Bo_Usuario $membro)
    {
        if($this->isDono($usuario) || $this->isModerador($usuario) || ($usuario->isSuperAdmin()))
        {
            return "/espaco-aberto/membro/apagar/comunidade/".$this->getId()."/id/".$membro->getId();
        }
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @param Aew_Model_Bo_Usuario $membro
     * @return string
     */
    function getUrlAdicionarModerador(Aew_Model_Bo_Usuario $usuario, Aew_Model_Bo_ComuUsuario $membro)
    {
        if((($this->isDono($usuario) || $this->isModerador($usuario) || ($usuario->isSuperAdmin())) 
                && (!$this->isModerador($membro))&& (!$this->isDono($membro)) )) 
        {
            return "/espaco-aberto/moderador/adicionar/comunidade/".$this->getId()."/id/".$membro->getId();
        }
    }
    
    function getUrlRemoverModerador(Aew_Model_Bo_Usuario $usuario, Aew_Model_Bo_ComuUsuario $membro)
    {
        if((($this->isDono($usuario) || $this->isModerador($usuario) || ($usuario->isSuperAdmin())) 
                && ($this->isModerador($membro))&& (!$this->isDono($membro)) )) 
        {
            return "/espaco-aberto/moderador/remover/comunidade/".$this->getId()."/id/".$membro->getId();
        }
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @param Aew_Model_Bo_Usuario $membroPendente
     * @return string
     */
    function getUrlAprovar(Aew_Model_Bo_Usuario $usuario, Aew_Model_Bo_ComuUsuario $membroPendente)
    {
        if($this->isDono($usuario) || $this->isModerador($usuario) || $usuario->isSuperAdmin())
        {
            return "/espaco-aberto/moderador/aprovar/comunidade/".$this->getId()."/id/".$membroPendente->getId();
        }
        return false;
    }

    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @param Aew_Model_Bo_Usuario $membro
     * @return string
     */
    function getUrlBloquearMembro(Aew_Model_Bo_Usuario $usuario, Aew_Model_Bo_ComuUsuario $membro)
    {
        if($this->isBloqueado($membro))
        {
            return false;
        }
        
        if($this->isDono($usuario) || $this->isModerador($usuario) || $usuario->isSuperAdmin())
        {
            return "/espaco-aberto/moderador/bloquear/comunidade/".$this->getId()."/id/".$membro->getId();
        }
        
        return false;
    }

    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @param Aew_Model_Bo_Usuario $membro
     * @return string
     */
    function getUrlDesbloquearMembro(Aew_Model_Bo_Usuario $usuario, Aew_Model_Bo_ComuUsuario $membro)
    {
        if(!$this->isBloqueado($membro))
        {
            return false;
        }
        
        if($this->isDono($usuario) || $this->isModerador($usuario) || $usuario->isSuperAdmin())
        {
            return "/espaco-aberto/moderador/desbloquear/comunidade/".$this->getId()."/id/".$membro->getId();
        }
        return false;
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $moderador
     * @param Aew_Model_Bo_Usuario $membroPendente
     * @return boolean
     */
    function aprovarMembroPendente(Aew_Model_Bo_Usuario $moderador, Aew_Model_Bo_Usuario $membroPendente)
    {
        $membro = new Aew_Model_Bo_ComuUsuario();
        //$membro->exchangeArray($membroPendente->toArray());
        $membro->setId($membroPendente->getId());
        $membro->setComunidade($this);
        if($this->isDono($moderador) || $this->isModerador($moderador) || $moderador->isSuperAdmin())
        {
            //$membro->setIdusuario($membroPendente->getIdusuario());
            $membro->setFlpendente("FALSE");
            $result = $membro->update();
            if($result)
                $this->addMembro($membro);
            
            return $result;
        }
        return false;   
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $moderador
     * @param Aew_Model_Bo_ComuUsuario $membroBloqueado
     * @return boolean
     */
    function bloquearMembro(Aew_Model_Bo_Usuario $moderador, Aew_Model_Bo_ComuUsuario $membroBloqueado)
    {
        if($this->isDono($moderador) || $this->isModerador($moderador) || $moderador->isSuperAdmin())
        {
            $membroBloqueado->setBloqueado('TRUE');
            return $membroBloqueado->update();
        }
        
        return false;
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $moderador
     * @param Aew_Model_Bo_ComuUsuario $membroBloqueado
     * @return boolean
     */
    function desbloquearMembro(Aew_Model_Bo_Usuario $moderador, Aew_Model_Bo_ComuUsuario $membroBloqueado)
    {
        if($this->isDono($moderador) || $this->isModerador($moderador) || $moderador->isSuperAdmin())
        {
            $membroBloqueado->setBloqueado('FALSE');
            return $membroBloqueado->update();
        }
        return false;
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @param Aew_Model_Bo_Usuario $membroPendente
     * @return string
     */
    function getUrlReprovar(Aew_Model_Bo_Usuario $usuario, Aew_Model_Bo_Usuario $membroPendente)
    {
        if($this->isDono($usuario) || $this->isModerador($usuario) || $usuario->isSuperAdmin())
        {
            return "/membro/apagar/comunidade/".$this->getId()."/id/".$membroPendente->getId();
        }
    }
    
    /**
     * remove membro da comunidade
     * @param Aew_Model_Bo_Usuario $membro membro a ser removido
     * @return int
     */
    function deleteMembro(Aew_Model_Bo_ComuUsuario $membro)
    {
        $membro->setComunidade($this);
        return $membro->delete();
    }
    
    
    
    /**
     * @param Aew_Model_Bo_Usuario $usuarioDono
     * @param Aew_Model_Bo_Usuario $moderador
     * @return boolean
     */
    function deleteModerador(Aew_Model_Bo_Usuario $usuarioDono,  Aew_Model_Bo_ComuUsuario $moderador)
    {
        if($this->isDono($usuarioDono)|| $usuarioDono->isSuperAdmin())
        {
            $moderador->setFlmoderador(false);
            return $moderador->update();
        }
        return false;
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $moderador
     * @return int
     */
    function insertModerador(Aew_Model_Bo_Usuario $moderador)
    {
        $novoModerador = new Aew_Model_Bo_ComuUsuario();
        
        $novoModerador->setid($this->getId());
        //$novoModerador->exchangeArray($moderador->toArray());
        $novoModerador->setComunidade($this);
        $novoModerador->setFlmoderador(true);
        return $novoModerador->update();
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $moderador
     */
    function addModerador(Aew_Model_Bo_Usuario $moderador)
    {
        array_push($this->moderadoresComunidade, $moderador);
    }

    /**
     * @param Aew_Model_Bo_Album $album
     * @return int
     */
    function saveAlbum(Aew_Model_Bo_Album $album)
    {
        $albumComunidade = new Aew_Model_Bo_ComunidadeAlbum();
        $albumComunidade->setIdcomunidade($this->getId());
        $album->setId($album->getId());
        $albumComunidade->exchangeArray($album->toArray());
        return parent::saveAlbum($albumComunidade);
    }
    /**
     *  Adiciona relação entre comunidades
     * @param Aew_Model_Bo_Comunidade $comunidadeRelacionada
     * @return int
     */
    function insertComunidadeRelacionada(Aew_Model_Bo_Comunidade $comunidadeRelacionada)
    {
        $relacionar = new Aew_Model_Bo_ComuRelacionada();
        $relacionar->setIdcomunidade($this->getId());
        $relacionar->setIdcomunidaderelacionada($comunidadeRelacionada->getId());
        return $relacionar->insert();
    }
    
    /**
     * Deleta relação da comunidade
     * @param Aew_Model_Bo_Comunidade $comunidadeRelacionada
     * @return int 
     */
    function deleteComunidadeRelacionada(Aew_Model_Bo_Comunidade $comunidadeRelacionada)
    {
        $relacionada = new Aew_Model_Bo_ComuRelacionada();
        $relacionada->setIdcomunidade($this->getId());
        $relacionada->setIdcomunidaderelacionada($comunidadeRelacionada->getId());
        $relacionada = $relacionada->select(1);
        return $relacionada->delete();
    }
    /**
     * Comunidade é relacionada?
     * @param Aew_Model_Bo_Comunidade $comunidadeRelacionada
     * @return boolean 
     */
    public function isRelacionado(Aew_Model_Bo_Comunidade $comunidadeRelacionada)
    {
        $relacionada = new Aew_Model_Bo_ComuRelacionada();
        $relacionada->setIdcomunidade($this->getId());
        $relacionada->setIdcomunidaderelacionada($comunidadeRelacionada->getId());
        return $relacionada->select(1);
    }
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlConfigurar(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->isDono($usuario) || $usuario->isCoordenador())
        {    
            return '/espaco-aberto/comunidade/editar/comunidade/'.$this->getId();
        }    
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlAceitarComunidade(Aew_Model_Bo_Usuario $usuario)
    {
        if($usuario->isCoordenador())
        {
            return "/espaco-aberto/comunidade/aceitar/comunidade/".$this->getId();
        }
        return false;
    }

    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlRecusarComunidade(Aew_Model_Bo_Usuario $usuario)
    {
        if($usuario->isCoordenador())
        {
            return "/espaco-aberto/comunidade/recusar/comunidade/".$this->getId();
        }
        return false;
    }

    /**
     * @return string
     */
    function getUrlListaAlbuns()
    {
        if($this->getId())
        return "/espaco-aberto/album/listar/comunidade/".$this->getId();
    }
    
    /**
     * @return string
     */
    function getUrlListaBlogs()
    {
        if($this->getId())
        return '/espaco-aberto/blog/listar/comunidade/'.$this->getId();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlBloquear(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->isDono($usuario)||($this->isModerador($usuario))|| ($usuario->isSuperAdmin()))
        return '/espaco-aberto/comunidade/bloquear/comunidade/'.$this->getId();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlDesbloquear(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->isBloqueado($usuario))
        return '/espaco-aberto/moderador/desbloquear/comunidade/'.$this->getId().'/id/'.$usuario->getId();
    }
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlTrocarImagem(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->isDono($usuario)||($this->isModerador($usuario))|| ($usuario->isSuperAdmin()))
        return    '/espaco-aberto/perfil/trocar-imagem/comunidade/'.$this->getId();
    }

    /**
     * @param Aew_Model_Bo_Foto $foto
     * @return int
     */
    public function saveFotoPerfil(Aew_Model_Bo_Foto $foto)
    {
        $fotoComunidade = new Aew_Model_Bo_ComunidadeFoto();
        $fotoComunidade->exchangeArray($foto->toArray());
        $fotoComunidade->setId($foto->getId());
        $fotoComunidade->setIdcomunidade($this->getId());
        $fotoComunidade->setFotoFile($foto->getFotoFile());
        $fotoComunidade->getFotoFile()->setDestination(Aew_Model_Bo_ComunidadeFoto::getFotoDirectory());
        return parent::saveFotoPerfil($fotoComunidade);
    }

    /**
     * @param Aew_Model_Bo_ItemPerfil $avaliador
     * @param int $avaliacao
     * @return int
     */
    public function insertVoto(Aew_Model_Bo_ItemPerfil $avaliador, $avaliacao)
    {
        $votoComu = new Aew_Model_Bo_ComuVoto();
        $votoComu->setUsuario($avaliador);
        $votoComu->setVoto($avaliacao);
        $votoComu->setDatacriacao(date('Y-m-d'));
        $votoComu->setIdcomunidade($this->getId());
        $result = $votoComu->save();
        if($result)
            $this->addAvaliacao ($votoComu);
        return $result;
    }
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Usuario $usuario
     */
    public function selectVotos($num=0,$offset=0,  Aew_Model_Bo_ItemPerfil $usuario=null)
    {
        $votoComu = new Aew_Model_Bo_ComuVoto();
        if($usuario)
        $votoComu->setUsuario ($usuario);
        $votoComu->setIdcomunidade($this->getId());
        $this->setVotos($votoComu->select($num, $offset));
        return $this->getVotos();
    }
    
    /**
     * @param Aew_Model_Bo_ItemPerfil $avaliador
     * @param int 
     */
    public function saveAvaliacao(Aew_Model_Bo_ItemPerfil $avaliador, $voto)
    {
        $avaliacao = new Aew_Model_Bo_ComuVoto();
        $avaliacao->setIdcomunidade($this->getId());
        $avaliacao->setUsuario($avaliador);
        $avaliacaoUsuario = $avaliacao->select(1);
        if($avaliacaoUsuario instanceof Sec_Model_Bo_Abstract)
        {
            $avaliacaoUsuario->setVoto($voto);
            $avaliacaoUsuario->setDatacriacao(date('Y-m-d h:i:s'));
            return $avaliacaoUsuario->save();
        }
        $avaliacao->setVoto($voto);
        $avaliacao->setDatacriacao(date('Y-m-d h:i:s'));
        return $avaliacao->save();
    }
    
    /**
     * @param Aew_Model_Bo_Blog $blog
     * @return int
     */
    public function saveBlog(Aew_Model_Bo_Blog $blog) 
    {
        $blogComunidade = new Aew_Model_Bo_ComunidadeBlog();
        $blogComunidade->exchangeArray($blog->toArray());
        $blogComunidade->setId($blog->getId());
        $blogComunidade->setIdcomunidade($this->getId());
        $result = parent::saveBlog($blogComunidade);
        if($result)
            return $blogComunidade;
        return $result;
    }

    /**
     * insere um registro de comunidade no banco de dados
     * @return mixed  chave primeria da comunidade inserida (idcomunidade).
     */
    function insert()
    {
        $favorito = new Aew_Model_Bo_Favorito();
        $favorito->insert();
        $this->setIdfavorito($favorito->getId());
        return parent::insert();
    }
    
    public function selectFotosDosAlbuns($num = 0, $offset = 0, Aew_Model_Bo_Foto $foto=null)
    {
        $comunidadeAlbumFoto = new Aew_Model_Bo_ComunidadeAlbumFoto();
        $comunidadeAlbumFoto->setIdcomunidade($this->getId());
        if($foto)
        {
            $comunidadeAlbumFoto->setId($foto->getId());
        }
        return $comunidadeAlbumFoto->select($num, $offset);
    }

    /**
     * salva objeto comunidade no banco de dados
     * @return int
     */
    function save() 
    {
        $result = parent::save();

        if($result)
        {
            $this->insertTags(); 
        }
        return $result;
    }
     
    /**
     * cria um objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_Comunidade
     */
    protected function createDao() {
        return new  Aew_Model_Dao_Comunidade();
    }
}