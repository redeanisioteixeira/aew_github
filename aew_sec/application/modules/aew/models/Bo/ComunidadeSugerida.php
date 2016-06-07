<?php

/**
 * BO da entidade ComunidadeSugerida
 */

class Aew_Model_Bo_ComunidadeSugerida extends Aew_Model_Bo_Comunidade
{
    protected $usuarioConvite;
    protected $idusuario; //int(11)
    protected $idcomunidade; //int(11)
    protected $idusuarioconvite; //int(11)
    protected $visto; //tinyint(1)
    protected $dataconvite; //timestamp

    /**
     * retorna usuario que realizou convite
     * @return Aew_Model_Bo_Usuario
     */
    function getUsuarioConvite() {
        return $this->usuarioConvite;
    }

    function getLinkPerfil() {
         return '/espaco-aberto/comunidade/exibir/comunidade/'.$this->getIdcomunidade();
    }
    /**
     * seta usuario convite do objeto
     * @param Aew_Model_Bo_Usuario $usuarioConvite
     */
    function setUsuarioConvite(Aew_Model_Bo_Usuario $usuarioConvite) {
        $this->usuarioConvite = $usuarioConvite;
    }

    /**
     * 
     * @return int
     */
    public function getIdusuario() {
        return $this->idusuario;
    }

    /**
     * 
     * @return int
     */
    public function getIdcomunidade() {
        return $this->idcomunidade;
    }

    /**
     * id do usuario que sugeriu comunidade
     * @return int
     */
    public function getIdusuarioconvite() {
        return $this->idusuarioconvite;
    }

    /**
     * se o convite foi visualizado ou nao
     * @return boolean
     */
    public function getVisto() {
        return $this->visto;
    }

    /**
     * 
     * @return string
     */
    public function getDataconvite() {
        return $this->dataconvite;
    }

    /**
     * 
     * @param int $idusuario
     */
    public function setIdusuario($idusuario) {
        $this->idusuario = $idusuario;
    }

    /**
     * 
     * @param int $idcomunidade
     */
    public function setIdcomunidade($idcomunidade) {
        $this->idcomunidade = $idcomunidade;
    }

    /**
     * 
     * @param int $idusuarioconvite
     */
    public function setIdusuarioconvite($idusuarioconvite) {
        $this->idusuarioconvite = $idusuarioconvite;
    }

    /**
     * 
     * @param boolean $visto
     */
    public function setVisto($visto) {
        $this->visto = $visto;
    }

    /**
     * 
     * @param string $dataconvite
     */
    public function setDataconvite($dataconvite) {
        $this->dataconvite = $dataconvite;
    }

    /**
     * insere o objeto no banco de dados
     * @return int
     */
    public function insert()
    {
        $data = $this->toArray();
        $insert = $this->getDao()->insert($data);
        $this->setId($insert);
        return $insert;
    }
    /**
     * Construtor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setUsuarioConvite(new Aew_Model_Bo_Usuario);
    }

    /**
     * 
     * @param int $idUsuario
     * @param int $idAgenda
     * @return boolean
     */
    public function valorExistente($idUsuario, $idAgenda)
    {
        $ret = $this->getDao()->obtemtodos($idUsuario, $idAgenda);
       	if (count($ret)>0) return true;
        return false;
    }
    
    /**
     * 
     * @param type $data
     */
    function exchangeArray($data) {
        parent::exchangeArray($data);
        $this->getUsuarioConvite()->exchangeArray($data);
        $this->getFotoPerfil()->setExtensao(isset($data['ext_foto_comunidade']) ? $data['ext_foto_comunidade']:null);
    }
    
    /**
     * 
     * @param type $id
     */
    public function setaVistos($id)
    {
    	$id = $this->getDao()->obtemPorUsuario($id, null);
    	for ($i = 0; $i < count($id); $i++)
        {
	   $objeto = $id[$i];
           $objeto['visto'] = true;
	   $this->save($objeto);
    	}
    }
    
    public function aceitaMarcacao($idUsuario, $idAgenda, array $options = null)
    {
       //apagar o registro e entrar na comunidade
       $obj = $this->obtemPorId($idUsuario, $idAgenda, $options);
       $objeto = $obj[0];
       $objeto['idComunidade'] = $idAgenda;
       $objeto['idUsuario'] = $idUsuario;
       $objeto['aceito'] = true;
       $this->save($objeto);
       return $objeto;
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ComunidadeSugerida
     */
    public function createDao() {
        return new Aew_Model_Dao_ComunidadeSugerida();
    }
}