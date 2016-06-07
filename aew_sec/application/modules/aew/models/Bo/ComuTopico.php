<?php

/**
 * BO da entidade ComuTopico
 */

class Aew_Model_Bo_ComuTopico extends Aew_Model_Bo_Comentario
{
    protected $idcomutopico;
    protected $idcomunidade; //int(11)
    protected $titulo; //varchar(250)
    protected $datacriacao; //timestamp
    protected $mensagem; //text
    
    /**
     * 
     * @return int
     */
    function getIdcomutopico() {
        return $this->idcomutopico;
    }

    /**
     * 
     * @param int $idcomutopico
     */
    function setIdcomutopico($idcomutopico) {
        $this->idcomutopico = $idcomutopico;
    }

    /**
     * 
     * @return int
     */
    public function getIdcomunidade()
    {
        return $this->idcomunidade;
    }

    /**
     * 
     * @return int
     */
    public function getIdusuario()
    {
        return $this->idusuario;
    }

    /**
     * 
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * 
     * @return string
     */
    public function getDatacriacao()
    {
        return $this->datacriacao;
    }

    /**
     * 
     * @return string
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * 
     * @param int $idcomunidade
     */
    public function setIdcomunidade($idcomunidade)
    {
        $this->idcomunidade = $idcomunidade;
    }

    /**
     * 
     * @param int $idusuario
     */
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }

    /**
     * 
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * 
     * @param string $datacriacao
     */
    public function setDatacriacao($datacriacao)
    {
        $this->datacriacao = $datacriacao;
    }

    /**
     * 
     * @param string $mensagem
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
    }

    /**
     * 
     * @param Aew_Model_Bo_ComuTopicoMsg $mensagem
     * @return int
     */    
    function insertMensagem(Aew_Model_Bo_ComuTopicoMsg $mensagem)
    {
        $mensagem->setIdcomutopico($this->getId());
        return $mensagem->insert();
    }
        
    /**
     * retorna a url para exibicao do topico
     * @return string
     */
    function getUrl()
    {
        return '/espaco-aberto/forum/exibir/id/'.$this->getId();
    }
    
    /**
     * Comentarios pais do forum
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     * @param type $options
     * @return type
     */
    public function selectComentarios($num = 0, $offset = 0, Aew_Model_Bo_Usuario $usuarioAutor = null,$options = null)
    {
        if(!$this->getId()){return array();}
        
        $comentario = new Aew_Model_Bo_ComuTopicoMsg(); 
        if($usuarioAutor)
            $comentario->setUsuario($usuarioAutor);
        
        $comentario->setPai(0);
        $comentario->setIdcomutopico($this->getIdcomutopico());
        
        $this->setComentarios($comentario->select($num,$offset,$options,true));
        return $this->getComentarios();
    }
    
    /**
     * Comentarios relacionados (filhos) do forum
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     * @param type $options
     * @return type
     */
    public function selectComentariosRelacionados($num = 0, $offset = 0, Aew_Model_Bo_Usuario $usuarioAutor = null,$options = null)
    {
        //if(!$this->getId()){return array();}
            $comentario = new Aew_Model_Bo_ComuTopicoMsg(); 
        
        if($usuarioAutor)
            $comentario->setUsuario($usuarioAutor);
        
        $comentario->setPai($comentario->getIdcomutopicomsg());
        $this->setComentarios($comentario->select($num,$offset,$options,true));
        return $this->getComentarios();
    }
    
    /**
     * retirn url para apagar comentario
     * @param \Aew_Model_Bo_ItemPerfil $usuario
     */
    public function getUrlApagar(\Aew_Model_Bo_ItemPerfil $usuario) {}

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ComuTopico
     */
    protected function createDao() {
        return new Aew_Model_Dao_ComuTopico();
    }

    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->idcomutopico;
    }
    
    /**
     * 
     * @param int $id
     */
    function setId($id) {
        $this->idcomutopico = $id;
    }
    
    /**
     * 
     * @return string
     */
    function getData()
    {
        return $this->datacriacao;
    }
}