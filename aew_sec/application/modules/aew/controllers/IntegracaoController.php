<?php

/**
 * Realiza autenticação de usuários no AEW
 * @author Thiago Ornellas <tornellas@gmail.com>
 *
 */
class IntegracaoController extends Zend_Controller_Action 
{

    public function init() {
	$this -> _helper -> viewRenderer -> setNoRender(true);
	$this -> _helper -> layout -> disableLayout();
    }

    public function autenticarAction() 
    {
	$login = $this -> getRequest() -> getParam('login');
	$pass = $this -> getRequest() -> getParam('pass');

	//Realizar a busca no banco de dados
	$bo_usuario = new Aew_Model_Bo_Usuario();
	$usuario = $bo_usuario->getUsuarioByUsername($login);
	if ($usuario == NULL)
        {
            $this -> getResponse() -> setHttpResponseCode(403);
            $obj = new stdClass();
            $obj -> http_code = 403;
            $obj->msg = 'Usuário e/ou senha inválido(s).';
    	}
	//Comparar de o e-mail e senha estão corretos
	else if (md5($pass) == $usuario->senha && $usuario->ativo) 
        {
            $this -> getResponse() -> setHttpResponseCode(200);
			// Podem ser colocados quantos atributos forem necessários.
			// O cliente recuperará todos
			$obj = new stdClass();
			$obj -> http_code = 200;
			$obj -> msg = 'Ok';
			$obj -> login = $login;

			//$obj -> pass = $pass;
			$obj->nome = $usuario->nomeconteudodigitalcategoria;
			$obj->email = $usuario->email;
			$obj->municipio = $usuario->municipio->nomeconteudodigitalcategoria;
			$obj->status = $usuario->ativo;
			$obj->tipo_usuario_id = $usuario->usuarioTipo->idUsuarioTipo;
			$obj->tipo_usuario = $usuario->usuarioTipo->nomeconteudodigitalcategoria;
			$obj->escola = $usuario->escola->nomeconteudodigitalcategoria;

			$obj->serie = $usuario->serie->nomeconteudodigitalcategoria;
			$obj->idade = $this->retornaIdade($usuario->dataNascimento);

		} else {
			$this -> getResponse() -> setHttpResponseCode(403);
			$obj = new stdClass();
			$obj -> http_code = 403;
			if($usuario->ativo === NULL) {
				$obj->msg = 'Perfil bloqueado devido à denuncias.';
			} else {
				$obj->msg = 'Usuário e/ou senha inválido(s).';
			}
		}
		$this -> getResponse() -> appendBody(base64_encode(serialize($obj)));
	}

        /**
         * baseado na data passada retorna idade (em anos)
         * @param string $data_nasc
         * @return string
         */    
	private function retornaIdade($data_nasc)
        {
            $data_nasc = explode("-", $data_nasc);
            $data = date("Y-m-d");
            $data = explode("-", $data);
            $anos = $data[0] - $data_nasc[0];
            if ( $data_nasc[1] >= $data[1] )
            {
                if ( $data_nasc[2] <= $data[2] )
                {
                    return $anos;
                }
                else
                {
                    return $anos-1;
                }
            }
            else
            {
                return $anos;
            }
	}

        /**
         * exibe formulario para realizaca de denuncias
         * @return Zend_View 
         */
	public function denunciarAction() 
        {
            $login = $this->getRequest()->getParam('login');
            $motivo = $this->getRequest()->getParam('motivo');

            $usuario = new Aew_Model_Bo_Usuario();
            $usuario->setUsername($login);
            if($usuario->selectAutoDados()) 
            {
		$this->getResponse()->setHttpResponseCode(404);
		// Podem ser colocados quantos atributos forem necessários.
		// O cliente recuperará todos
		$obj = new stdClass();
		$obj -> http_code = 404;
		$obj -> msg = 'Usuário não encontrado.';
            } 
            else 
            {
		$denuncia = new Aew_Model_Bo_Denuncia();
		$parametros =array(
		'idusuario'=>$usuario->getId(),
		'mensagem'=>$motivo . "\nusuário bloqueado: " . $usuario->getNome() . " #".$usuario->getId(),
		'titulo'=>'Denuncia via WebService',
		);
		$denuncia->exchangeArray($parametros);
		$this->getResponse()->setHttpResponseCode(200);
		// Podem ser colocados quantos atributos forem necessários.
		// O cliente recuperará todos
		$obj = new stdClass();
		$obj -> http_code = 200;
		$obj -> msg = 'Ok';
            }
            $this -> getResponse() -> appendBody(base64_encode(serialize($obj)));
	}
}