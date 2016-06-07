<?php

/**
 * BO da entidade ComunidadeFoto
 */

class Aew_Model_Bo_ComunidadeFoto extends Aew_Model_Bo_Foto
{
    protected $idcomunidade; //int(11)
   
    /**
     * @return idcomunidade - int(11)
     */
    public function getIdcomunidade(){
	return $this->idcomunidade;
    }

    /**
     * @return extensao - varchar(255)
     */
    public function getExtensao(){
	return $this->extensao;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdcomunidadefoto($idcomunidadefoto){
    	$this->idcomunidadefoto = $idcomunidadefoto;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdcomunidade($idcomunidade){
	$this->idcomunidade = $idcomunidade;
    }

    /**
     * @param Type: varchar(255)
     */
    public function setExtensao($extensao){
	$this->extensao = $extensao;
    }
    
    /**
     * retorna o diretorio para as fotos
     * @return string string do caminho para o diretorio das fotos de perfil
     */
    public static function getFotoDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'fotos-perfil'.DS.'comunidade';
        else:
            $path = MEDIA_PATH.DS.'fotos-perfil'.DS.'comunidade';
        endif;
        
        return $path;
    }
    /**
     * @return string
     */
    public function uri()
    {
        if(CONTEUDO_PATH):
            $path = DS.CONTEUDO_PATH.DS.'fotos-perfil'.DS.'comunidade';
        else:
            $path = DS.'fotos-perfil'.DS.'comunidade';
        endif;
        return $path;
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ComunidadeFoto
     */
    function createDao() {
        return new Aew_Model_Dao_ComunidadeFoto();
    }
}
