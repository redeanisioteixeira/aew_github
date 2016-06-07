<?php

/**
 * BO da entidade ComuTopicoMsg
 */

class Aew_Model_Bo_ComuTopicoMsg extends Aew_Model_Bo_Comentario
{
    protected $idcomutopicomsg; //int(11)
    protected $mensagem; //text
    protected $datacriacao; //timestamp
    protected $idcomutopico; //int(11)
    protected $pai; //int(11)
    protected $ativo; //tinyint(1)
    
    /**
     * 
     * @return int
     */
    public function getIdcomutopicomsg()
    {
        return $this->idcomutopicomsg;
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
     * @return string
     */
    public function getDatacriacao()
    {
        return $this->datacriacao;
    }

    /**
     * 
     * @return int
     */
    public function getIdcomutopico()
    {
        return $this->idcomutopico;
    }

    /**
     * 
     * @return type
     */
    public function getPai()
    {
        return $this->pai;
    }

    /**
     * 
     * @return type
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * 
     * @param int $idcomutopicomsg
     */
    public function setIdcomutopicomsg($idcomutopicomsg)
    {
        $this->idcomutopicomsg = $idcomutopicomsg;
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
     * @param string $datacriacao
     */
    public function setDatacriacao($datacriacao)
    {
        $this->datacriacao = $datacriacao;
    }

    /**
     * 
     * @param int $idcomutopico
     */
    public function setIdcomutopico($idcomutopico)
    {
        $this->idcomutopico = $idcomutopico;
    }

    /**
     * 
     * @param int $pai
     */
    public function setPai($pai)
    {
        $this->pai = $pai;
    }

    /**
     * 
     * @param boolean $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * nao implementado
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     */
    public function selectComentarios($num = 0, $offset = 0, Aew_Model_Bo_Usuario $usuarioAutor = null)
    {
        
    }
    
    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     * @return type
     */
    public function selectComentariosRelacionados($num = 0,$offset=0, Aew_Model_Bo_Usuario $usuarioAutor = null)
    {
        if(!$this->getId()){return array();}
        $comentario = new Aew_Model_Bo_ComuTopicoMsg(); 
        
        if($usuarioAutor)
            $comentario->setUsuario($usuarioAutor);
        
        $comentario->setPai($this->getId());
        $this->setComentarios($comentario->select($num,$offset,$options,true));
        return $this->getComentarios();
        
    }        
   
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ComuTopicoMsg
     */
    protected function createDao() {
        return new Aew_Model_Dao_ComuTopicoMsg();
    }

    /**
     * 
     * @param int $id
     */
    public function setId($id) {
        $this->idcomutopicomsg = $id;
    }
    
    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->idcomutopicomsg;
    }
    
    /**
     * 
     * @return string
     */
    function getData()
    {
        return $this->datacriacao;
    }
    /**
     * Responder comentário
     * @param Aew_Model_Bo_ItemPerfil $usuario
     * @return type
     */
    function getUrlResponder(Aew_Model_Bo_ItemPerfil $usuario)
    {
        return "/espaco-aberto/forum/responder/id/".$this->getId().'/usuario/'.$usuario->getId();
    }
    /**
     * Apaga comentário
     * @param Aew_Model_Bo_ItemPerfil $usuario
     * @return type
     */
    public function getUrlApagar(Aew_Model_Bo_ItemPerfil $usuario)
    {
        if($this->isAutor($usuario))
        {
            return "/espaco-aberto/forum/apagarmsg/id/".$this->getId();
        }
    }
}