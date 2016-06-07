<?php

/** 
 * Usuarios participantes de uma comunidade
 * dados de relação entre um usuario e uma comunidade
 * BO da entidade ComuUsuario
 */

class Aew_Model_Bo_ComuUsuario extends Aew_Model_Bo_Usuario
{

    protected $idusuario; //int(11)
    protected $datacriacao; //timestamp
    protected $flmoderador; //tinyint(1)
    protected $bloqueado; //tinyint(1)
    protected $flpendente; //tinyint(1)
    private   $comunidade;

    /**
     * @return Aew_Model_Bo_Comunidade
     */
    public function getComunidade()
    {
        return $this->comunidade;
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
     * @param int $idusuario
     */
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }

    /**
     * 
     * @param type $comunidade
     */
    public function setComunidade(Aew_Model_Bo_Comunidade $comunidade)
    {
        $this->comunidade = $comunidade;
    }

    /**
     * Seleciona no banco e retorna os feeds do usuario
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Feed $feed
     * @return type
     */
    function toArray()
    {
        $data = parent::toArray();
        
        if($this->getIdusuario())
        {
            $data['idusuario'] = $this->getIdusuario();
        }

        if($this->getComunidade()->getId())
        {
            $data['idcomunidade'] = $this->getComunidade()->getId();
        }
        
        if($this->getIdFavorito())
        {
            $this->getDao()->setTableInTableField('idfavorito', 'usuario');
        }
        
        return $data;
    }
    
    /**
     * Url Aceita petição de colega 
     * @param Aew_Model_Bo_Usuario $colega
     * @return type
     */
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->getComunidade()->exchangeArray($data);
        $this->getComunidade()->getUsuario()->setId((isset($data['idusuariodono'])) ? $data['idusuariodono'] : null);
    }
    
    /**
     * insere registro do usuario no banco de dados
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
    public function     __construct()
    {
        parent::__construct();
        $this->setComunidade(new Aew_Model_Bo_Comunidade());
    }

    /**
     * @return datacriacao - timestamp
     */
    public function getDatacriacao()
    {
        return $this->datacriacao;
    }

    /**
     * @return flmoderador - tinyint(1)
     */
    public function getFlmoderador()
    {
        return $this->flmoderador;
    }

   
    /**
     * @return bloqueado - tinyint(1)
     */
    public function getBloqueado()
    {
        return $this->bloqueado;
    }

    /**
     * @return flpendente - tinyint(1)
     */
    public function getFlpendente()
    {
        return $this->flpendente;
    }

   
    /**
     * @param Type: timestamp
     */
    public function setDatacriacao($datacriacao)
    {
        $this->datacriacao = $datacriacao;
    }

    /**
     * @param Type: tinyint(1)
     */
    public function setFlmoderador($flmoderador)
    {
        $this->flmoderador = $flmoderador;
    }

    /**
     * @param Type: tinyint(1)
     */
    public function setBloqueado($bloqueado)
    {
        $this->bloqueado = $bloqueado;
    }

    /**
     * @param Type: tinyint(1)
     */
    public function setFlpendente($flpendente)
    {
        $this->flpendente = $flpendente;
    }
    
    /**
     * url para acao de remocao do usuario da comunidade
     * @param Aew_Model_Bo_Usuario $usuario
     * @return type
     */
    public function getUrlRemover(Aew_Model_Bo_Usuario $usuario=null)
    {
        if(!$usuario)
        return ;
    }
    
    /**
     * Seleciona a fotoperfil na base de dados
     * @return UsuarioFoto ou false se objeto não tiver id
     */
    function selectFotoPerfil()
    {
        if(!$this->getId())
        {
            return false;
        }
        
        $fotoPerfil = new Aew_Model_Bo_UsuarioFoto();
        $fotoPerfil->setIdusuario($this->getIdusuario());
        $fotoPerfil = $fotoPerfil->select(1);
        if($fotoPerfil instanceof Aew_Model_Bo_Foto)
            $this->setFotoPerfil($fotoPerfil);
        
        return $this->getFotoPerfil();
    }
    
    /**
     * url para o perfil do membro da comunidade
     * @return string
     */
    
    public function getLinkPerfil()
    {
        return '/espaco-aberto/perfil/feed/usuario/'.$this->getIdusuario();
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ComuUsuario
     */
    function createDao() {
        return new Aew_Model_Dao_ComuUsuario();
    }
}