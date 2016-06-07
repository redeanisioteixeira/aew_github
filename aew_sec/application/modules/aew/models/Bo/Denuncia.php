<?php

/**
 * BO da entidade Denuncia
 */

class Aew_Model_Bo_Denuncia extends Sec_Model_Bo_Abstract
{
    
    protected $iddenuncia; //int(11)
    protected $usuario; //int(11)
    protected $url; //varchar(200)
    protected $mensagem; //text
    protected $datacriacao; //timestamp
    protected $titulo; //varchar(150)
    protected $flvisualizada; //tinyint(1)
    
    /***/
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->getUsuario()->exchangeArray($data);
    }

	
	/**
	 * @return Aew_Model_Bo_Usuario $usuario
	 */
	public function getUsuario(){
		return $this->usuario;
	}

	/**
	 * @return url - varchar(200)
	 */
	public function getUrl(){
		return $this->url;
	}

	/**
	 * @return mensagem - text
	 */
	public function getMensagem(){
		return $this->mensagem;
	}

	/**
	 * @return datacriacao - timestamp
	 */
	public function getDatacriacao(){
		return $this->datacriacao;
	}

	/**
	 * @return titulo - varchar(150)
	 */
	public function getTitulo(){
		return $this->titulo;
	}

	/**
	 * @return flvisualizada - tinyint(1)
	 */
	public function getFlvisualizada(){
		return $this->flvisualizada;
	}

        /**
         * 
         * @param Aew_Model_Bo_Usuario $usuario
         */
	public function setUsuario(Aew_Model_Bo_Usuario $usuario){
		$this->usuario = $usuario;
	}

	/**
	 * @param Type: varchar(200)
	 */
	public function setUrl($url){
		$this->url = $url;
	}

	/**
	 * @param Type: text
	 */
	public function setMensagem($mensagem){
		$this->mensagem = $mensagem;
	}

	/**
	 * @param Type: timestamp
	 */
	public function setDatacriacao($datacriacao){
		$this->datacriacao = $datacriacao;
	}

	/**
	 * @param Type: varchar(150)
	 */
	public function setTitulo($titulo){
		$this->titulo = $titulo;
	}

	/**
	 * @param Type: tinyint(1)
	 */
	public function setFlvisualizada($flvisualizada){
		$this->flvisualizada = $flvisualizada;
	}
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->setUsuario(new Aew_Model_Bo_Usuario());
    }


    /**
     * @return string
     */
    public function getLinkPerfil()
    {
        return '/administracao/denuncia/exibir/id/'.$this->getId();
    }
    /**
     * Avisa os administradores sobre a denuncia
     * @param Denuncia $denuncia
     * @retunn bool
     */
    public function avisarAdmins($denuncia, $mensagem)
    {
        $mail = new Sec_Mail();
        $mail->setBodyHtml($mensagem);
        $mail->setFrom(Sec_Global::getSystemEmail(), 'Ambiente Educacional Web');
        $mail->setSubject('Fale Conosco');

        $usuarioBo = new Aew_Model_Bo_Usuario();
        $usuarioBo->getUsuarioTipo()->setNome(Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR);
        $usuarios = $usuarioBo->select();
        
        $mail->addTo("aew@educacao.ba.gov.br", "Administrador");
	if(count($usuarios) > 0)
        {
            foreach($usuarios as $usuario)
            {
		$nome = $usuario->getNome();
		$email = $usuario->getEmail();
		$mail->addTo($email, $nome);
            }
            try 
            {
		$result = $mail->send();
            }
            catch(Zend_Mail_Protocol_Exception $e) 
            {
		$result = null;
            }
	} 
        else 
        {
            return false;
	}
	return $result;
    }

    /**
     * envia email sobre contato fale conosco
     * @return Zend_Mail
     */
    public function retornaFaleConosco()
    {
	$mail = new Sec_Mail();
	$mail->setBodyHtml($this->getMensagem());
	$mail->setFrom(Sec_Global::getSystemEmail(), 'Ambiente Educacional Web - Fale Conosco');
	$mail->setSubject('Ambiente Educacional Web');
	$retorno = $mail->validarMail($this->getUsuario()->getEmail());
	$mail->addTo($this->getUsuario()->getEmail(), $this->getUsuario()->getNome());
	try 
        {
            $result = $mail->send();
	} 
        catch(Zend_Mail_Protocol_Exception $e) 
        {
            $result = null;
	}
	return $result;
    }

    /**
     * 
     * @return \Aew_Model_Dao_Denuncia
     */
    protected function createDao() {
        return new Aew_Model_Dao_Denuncia();
    }

}
