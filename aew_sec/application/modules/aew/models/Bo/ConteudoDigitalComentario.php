<?php
/**
 * Classe que representa comentarios em um conteudo digital
 */
class Aew_Model_Bo_ConteudoDigitalComentario extends Aew_Model_Bo_Comentario
{
    
    protected $idconteudodigital; //int(11)
    protected $idconteudodigitalcategoria;//int(11)
    
    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    function toArray()
    {
        $data = parent::toArray();
        if($this->getIdconteudodigitalcategoria())
        {
            $data['idconteudodigitalcategoria'] = $this->getIdconteudodigitalcategoria();
            $this->getDao()->setTableInTableField('idconteudodigitalcategoria', 'conteudodigitalcategoria');
        }
        return $data;
    }

    /**
     * 
     * @return int
     */
    public function getIdconteudodigital()
    {
        return $this->idconteudodigital;
    }

    /**
     * 
     * @param int $idconteudodigital
     */
    public function setIdconteudodigital($idconteudodigital)
    {
        $this->idconteudodigital = $idconteudodigital;
    }
    
    /**
     * 
     * @return int
     */
    function getIdconteudodigitalcategoria() 
    {
        return $this->idconteudodigitalcategoria;
    }

    /**
     * 
     * @param int $idconteudodigitalcategoria
     */
    function setIdconteudodigitalcategoria($idconteudodigitalcategoria) 
    {
        $this->idconteudodigitalcategoria = $idconteudodigitalcategoria;
    }

    /**
     * nao implementado
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     */
    public function selectComentariosRelacionados($num = 0, $offset = 0, Aew_Model_Bo_Usuario $usuarioAutor = null)
    {
        
    }

    /**
     * 
     * @param Aew_Model_Bo_ItemPerfil $usuario
     * @return boolean|string
     */
    public function getUrlApagar(Aew_Model_Bo_ItemPerfil $usuario=null) 
    {
        if($usuario)
        if($usuario->isAdmin())
        {
            return '/conteudos-digitais/comentario/apagar/id/'.$this->getId() ;
        }
            
        return false;
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ConteudoDigitalComentario
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_ConteudoDigitalComentario();
        return $dao;
    }

}