<?php
/**
 * BO da entidade comentario. Representa um comentario geral no sistema AEW
 *
 * @author tiagolns
 */
abstract class Aew_Model_Bo_Comentario extends Sec_Model_Bo_Abstract
{
    //put your code here
    protected $comentario,$datacriacao,$comentarios,$usuarioAutor,$comentariosRelacionados=array();
    protected $mensagem;
    
    public function __construct() 
    {
        $this->setUsuarioAutor(new Aew_Model_Bo_Usuario());
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
        if($this->getUsuarioAutor()->getId())
        {
            $data['idusuario'] = $this->getUsuarioAutor()->getId();
            $this->getDao()->setTableInTableField('idusuario', $this->getDao()->getName());
        }
        return $data;
    }
    
    /**
     * comentarios feitos a partir deste comentario
     * @return array
     */
    public function getComentariosRelacionados()
    {
        return $this->comentariosRelacionados;
    }

    /**
     * 
     * @param type $comentariosRelacionados
     */
    public function setComentariosRelacionados($comentariosRelacionados)
    {
        $this->comentariosRelacionados = $comentariosRelacionados;
    }

    /**
     * 
     * @return Aew_Model_Bo_UsuarioAutor
     */
    public function getUsuarioAutor()
    {
        return $this->usuarioAutor;
    }

    /**
     * 
     * @param Aew_Model_Bo_UsuarioAutor $usuarioAutor
     */
    public function setUsuarioAutor(Aew_Model_Bo_Usuario $usuarioAutor)
    {
        $this->usuarioAutor = $usuarioAutor;
    }

    /**
     * texto do comentario
     * @return string
     */
    public function getComentario() {
        return $this->comentario?$this->comentario:$this->mensagem;
    }

    /**
     * 
     * @param string $comentario
     */
    public function setComentario($comentario) {
        $this->comentario = $comentario;
        $this->mensagem = $comentario;
    }

    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    public function exchangeArray($data) 
    {
        parent::exchangeArray($data);
        $this->getUsuarioAutor()->exchangeArray($data);
    }
    
    /**
     * 
     * @return array
     */
    public function getComentarios() {
        return $this->comentarios;
    }

    /**
     * 
     * @param array $comentarios
     */
    public function setComentarios(array $comentarios) {
        $this->comentarios = $comentarios;
    }

    /**
     * 
     * @return string
     */
    public function getDataCriacao() {
        return $this->datacriacao;
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     * @return boolean
     */
    public function isAutor(Aew_Model_Bo_Usuario $usuarioAutor)
    {
        if($this->getUsuarioAutor()->getId()==$usuarioAutor->getId())
        return true;
        return FALSE;
    }
    
    /**
     * 
     * @param string $dataCriacao
     */
    public function setDataCriacao($dataCriacao) {
        $this->datacriacao = $dataCriacao;
    }
    
    /**
     * seleciona no banco de dados comentarios relacionados
     */
    abstract function selectComentariosRelacionados($num=0,$offset=0,Aew_Model_Bo_Usuario $usuarioAutor=null);
    
    /**
     * retorna  url da acao de apagar comentario
     * @return string
     */
    abstract function getUrlApagar(Aew_Model_Bo_ItemPerfil $usuario);
}