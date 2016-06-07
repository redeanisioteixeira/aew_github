<?php

/**
 * BO da entidade Usuario Tipo
 */
class Aew_Model_Bo_ConteudoDigitalVoto extends Sec_Model_Bo_Abstract
{
    protected $idconteudodigital; //int(11)
    protected $usuario; //Aew_Model_Bo_Usuario
    protected $voto; //int(11)
    protected $datacriacao; //timestamp
    
    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    function toArray()
    {
        $data = parent::toArray();
        if($this->getUsuario()->getId())
        {
             $data['idusuario'] = $this->getUsuario()->getId();
        }
        return $data;
    }

    /**
     * 
     * @param array $data
     */
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->getUsuario()->exchangeArray($data);
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     */
    public function setUsuario(Aew_Model_Bo_Usuario $usuario)
    {
        $this->usuario = $usuario;
    }
    /**
     * @return idconteudodigital - int(11)
     */
    public function getIdconteudodigital(){
    	return $this->idconteudodigital;
    }
    /**
     * @return Aew_Model_Bo_Usuario usuario que realizou o voto
     */
    public function getUsuario()
    {
    	return $this->usuario;
    }
    /**
     * @return voto - int(11)
     */
    public function getVoto(){
    	return $this->voto;
    }
    /**
     * @return datacriacao - timestamp
     */
    public function getDatacriacao(){
    	return $this->datacriacao;
    }
    /**
     * @param Type: int(11)
     */
    public function setIdconteudodigital($idconteudodigital){
    	$this->idconteudodigital = $idconteudodigital;
    }
    
    /**
     * @param Type: int(11)
     */
    public function setVoto($voto){
    	$this->voto = $voto;
    }

    /**
     * @param Type: timestamp
     */
    public function setDatacriacao($datacriacao){
    	$this->datacriacao = $datacriacao;
    }
    
    /**
     * Construtor
     */
    public function __construct(Aew_Model_Bo_Usuario $usuario = null)
    {
        if(!$usuario)
            $usuario = new Aew_Model_Bo_Usuario();
        
        $this->setUsuario($usuario);
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ConteudoDigitalVoto
     */
    protected function createDao() {
        return new  Aew_Model_Dao_ConteudoDigitalVoto();
    }
}