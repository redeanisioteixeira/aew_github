<?php
/**
 * BO da entidade UsuarioFoto
 */
class Aew_Model_Bo_UsuarioFoto extends Aew_Model_Bo_Foto
{
    protected $idusuario;
   
    /**
     * @return int
     */
    public function getIdusuario()
    {
        return $this->idusuario;
    }

    /**
     * @param int $idusuario
     */
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }

    /**
     * 
     * @return array
     */
    function toArray() {
        $data = parent::toArray();
        $this->getDao()->setTableInTableField('idusuario', $this->getDao()->getName());
        return $data;
    }

    /**
     * retorna o diretorio para as fotos
     * @return string string do caminho para o diretorio das fotos de perfil
     */
    public static function getFotoDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'fotos-perfil'.DS.'usuario';
        else:
            $path = MEDIA_PATH.DS.'fotos-perfil'.DS.'usuario';
        endif;
        
        return $path;
    }
    
    /**
     * @return string
     */
    public function uri()
    {
        if(CONTEUDO_PATH):
            $path = DS.CONTEUDO_PATH.DS.'fotos-perfil'.DS.'usuario';
        else:
            $path = DS.'fotos-perfil'.DS.'usuario';
        endif;
        
        return $path;
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_UsuarioFoto
     */
    function createDao() {
        return new Aew_Model_Dao_UsuarioFoto();
    }
}