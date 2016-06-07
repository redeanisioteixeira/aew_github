<?php

/**
 * BO da entidade AgendaComentario
 */

class Aew_Model_Bo_BlogComentario extends Aew_Model_Bo_Comentario
{

    protected $idblog; //int(11)
    protected $tipo; //int(11)
    protected $visto; //tinyint(1)
	
    /**
     * 
     * @return int
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * 
     * @return int
     */
    public function getVisto() {
        return $this->visto;
    }

    /**
     * 
     * @param int $tipo
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    /**
     * 
     * @param int $visto
     */
    public function setVisto($visto) {
        $this->visto = $visto;
    }

    /**
     * 
     * @return int
     */
    public function getIdblog() {
        return $this->idblog;
    }

    /**
     * 
     * @param int $idblog
     */
    public function setIdblog($idblog) {
        $this->idblog = $idblog;
    }

    /**
     * 
     * @param Aew_Model_Bo_ItemPerfil $perfilObject
     * @return boolean|string
     */
    public function getUrlApagar(Aew_Model_Bo_ItemPerfil $perfilObject)
    {
        if($this->getUsuarioAutor()->getId()==$perfilObject->getId())
        {
            if($perfilObject instanceof Aew_Model_Bo_Usuario)
            return '/espaco-aberto/blog/apagarcomentario/usuario/'.$perfilObject->getId().'/id/'.$this->getIdblog().'/idcomentario/'.$this->getId();
            else if($perfilObject instanceof Aew_Model_Bo_Comunidade)
            {
                return '/espaco-aberto/blog/apagarcomentario/comunidade/'.$perfilObject->getId().'/id/'.$this->getIdblog().'/idcomentario/'.$this->getId();
            }
        }
        return false;
    }

    /**
     * metodo vazio
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     */
    public function selectComentariosRelacionados($num = 0, $offset = 0, Aew_Model_Bo_Usuario $usuarioAutor = null)
    {
        
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_BlogComentario
     */
    protected function createDao() {
        return new Aew_Model_Dao_BlogComentario();
    }

}
