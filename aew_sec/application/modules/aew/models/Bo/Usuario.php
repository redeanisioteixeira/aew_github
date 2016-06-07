<?php
/**
 * BO da entidade Usuario
 */
class Aew_Model_Bo_Usuario extends Aew_Model_Bo_ItemPerfil
{
    const STATUS_ATIVO = 1;
    const STATUS_INATIVO = 0;
    const STATUS_BLOQUEADO = 2;
        
    public static $STATUS_ATIVO = 1;
    public static $STATUS_INATIVO = 0;
    public static $STATUS_BLOQUEADO = 2;
    
    protected $senha;
    protected $conteudosDigitaisPublicados = array(),$recadosRecebidos = array();
    protected $colegas = array();
    protected $minhasComunidades = array(),$comunidadesParticipo=array();
    protected $feeds = array();
    protected $blogs = array();
    protected $denuncias = array();
    protected $recadosEnviados = array();
    protected $redesSociais = array();
    protected $usuarioTipo,  $municipio,  $escola,  $serie, $categoria,$username,$matricula,$datacriacao,$flativo;
    protected $datanascimento, $sexo,$telefone, $endereco,$numero,$complemento,$bairro,$cep,$cpf,$rg,$email,$emailpessoal;
    protected $dataatualizacao;
    protected $sobreMim; //Aew_Model_Bo_UsuarioSobreMim
    protected $colegasPendentes = array(); // 
    protected $comunidadesSugeridas=array();//
    protected $chatStatus = array();// um objeto para cada chat com um usuarios
    protected $chatMensagens = array();
    protected $feedContagem;
    protected $componentesCurriculares = array();
    protected $niveisEnsino = array();
    protected $estado; //Aew_Model_Bo_Estado
    protected $nomeusuario;
    
    /**
     * retorna nome do usuario
     * @return string
     */
    function getNome() 
    {
        return $this->nomeusuario;
    }

    function setNome($nomeusuario) {
        $this->nomeusuario = $nomeusuario;
    }

        /**
     * Construtor
     */
    public function __construct()
    {
        $this->setUsuarioTipo(new Aew_Model_Bo_UsuarioTipo());
        $this->setFotoPerfil(new Aew_Model_Bo_UsuarioFoto());
        $this->setMunicipio(new Aew_Model_Bo_Municipio());
        $this->setSerie(new Aew_Model_Bo_Serie());
        $this->setEscola(new Aew_Model_Bo_Escola());
        $this->setSobreMim(new Aew_Model_Bo_UsuarioSobreMimPerfil());
        $this->setFeedContagem(new Aew_Model_Bo_FeedContagem());
        $this->setEstado(new Aew_Model_Bo_Estado());
    }
    
    /**
     * 
     * @return array
     */
    public function getChatStatus()
    {
        return $this->chatStatus;
    }
    
    /**
     * @return Aew_Model_Bo_Estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @param string $chatStatus
     */
    public function setChatStatus($chatStatus)
    {
        $this->chatStatus = $chatStatus;
    }

    /**
     * @return array
     */
    public function getComunidadesSugeridas() {
        return $this->comunidadesSugeridas;
    }

    /**
     * @param array $comunidadesSugeridas
     */
    public function setComunidadesSugeridas($comunidadesSugeridas) {
        $this->comunidadesSugeridas = $comunidadesSugeridas;
    }
    
    /**
     * @return String
     */    
    public function getSenha() {
        return $this->senha;
    }

    /**
     * @param String $senha
     */
    public function setSenha($senha) {
        $this->senha = $senha;
    }

    /**
     * retorna objeto com dados do usuario
     * @return Aew_Model_Bo_UsuarioSobreMim objeto com dados do ussuario
     */
    public function getSobreMim() {
        return $this->sobreMim;
    }

    /**
     * @param  Aew_Model_Bo_UsuarioSobreMim $sobreMim
     */
    public function setSobreMim($sobreMim) {
        $this->sobreMim = $sobreMim;
    }

    /**
     * recupera objetos da classes Aew_Model_Bo_ConteudoDigital que são
     * os conteudos publicados pelo usuario
     * @return array array de objetos Aew_Model_Bo_ConteudoDigital
     */    
    public function getConteudosDigitaisPublicados() 
    {
        return $this->conteudosDigitaisPublicados;
    }
    
    /**
     * @return boolean
     */
    public function getFlativo() {
        return $this->flativo;
    }

    /**
     * @param boolean $flativo
     */
    public function setFlativo($flativo) 
    {
        $this->flativo = $flativo;
    }

    /**
     * @param array $conteudosDigitaisPublicados
     */
    public function setConteudosDigitaisPublicados(array $conteudosDigitaisPublicados) 
    {
        $this->conteudosDigitaisPublicados = $conteudosDigitaisPublicados;
    }

    /**
     * @return array
     */
    public function getRecadosEnviados() {
        return $this->recadosEnviados;
    }

    /**
     * configura os recados recebidos do objeto
     * @param array $recadosEnviados
     */
    public function setRecadosEnviados($recadosEnviados) {
        $this->recadosEnviados = $recadosEnviados;
    }

    /**
     * adiciona um recado à coleção de recados recebidos do usuario
     * @param Aew_Model_Bo_UsuarioRecado $recado
     */
    public function addRecado(Aew_Model_Bo_UsuarioRecado $recado)
    {
        array_push($this->recadosRecebidos, $recado);
    }
    
    /**
     * @return array
     */
    public function getComunidadesParticipo() {
        return $this->comunidadesParticipo;
    }
    
    /**
     * @param Aew_Model_Bo_UsuarioRecado $recado
     */
    public function addRecadoRecebido(Aew_Model_Bo_UsuarioRecado $recado) 
    {
        array_push($this->recadosRecebidos, $recado);
    }

    /**
     * @param array $comunidadesParticipo
     */
    public function setComunidadesParticipo($comunidadesParticipo) {
        $this->comunidadesParticipo = $comunidadesParticipo;
    }

    /**
     * retorna os conteudos digitais favoritos desse ususario
     * @return array
     */
    public function getConteudosDigitaisFavoritos() {
        return $this->conteudosDigitaisFavoritos;
    }

    /**
     * @param array $conteudosDigitaisFavoritos
     */
    public function setConteudosDigitaisFavoritos($conteudosDigitaisFavoritos) {
        $this->conteudosDigitaisFavoritos = $conteudosDigitaisFavoritos;
    }

    /**
     * @return array
     */
    public function getFeeds() {
        return $this->feeds;
    }

    /**
     * @param array $feeds
     */
    public function setFeeds($feeds) {
        $this->feeds = $feeds;
    }

    /**
     * @param Aew_Model_Bo_Feed $feed
     */
    public function addFeed(Aew_Model_Bo_Feed $feed)
    {
        array_push($this->feeds, $feed);
    }
    
    /**
     * adiciona um colega a sua colecao de colegas (memoria local)
     * @param Aew_Model_Bo_Usuario $colega
     */
    public function addColega(Aew_Model_Bo_Usuario $colega)
    {
        array_push($this->colegas,$colega);
    }
    
    /**
     * adiciona um colega a sua colecao de colegas pendentes de aprovacao (memoria local) 
     * @param Aew_Model_Bo_Usuario $colega
     */
    public function addColegaPendente(Aew_Model_Bo_Usuario $colega)
    {
        array_push($this->colegasPendentes,$colega);
    }
    /**
     * @return array
     */
    public function getBlogs() {
        return $this->blogs;
    }

    /**
     * @return array
     */
    public function getColegasPendentes() {
        return $this->colegasPendentes;
    }

    /**
     * @param type $colegasPendentes
     */
    public function setColegasPendentes($colegasPendentes) {
        $this->colegasPendentes = $colegasPendentes;
    }

    /**
     * @param type $blogs
     */
    public function setBlogs($blogs) {
        $this->blogs = $blogs;
    }

    /**
     * @return string
     */
    public function getLinkPerfil()
    {
        return '/espaco-aberto/perfil/feed/usuario/'.$this->getId();
    }
    
    /**
     * 
     * @return array
     */
    public function getRedesSociais() {
        return $this->redesSociais;
    }

    /**
     * 
     * @param array $redesSociais
     */
    public function setRedesSociais($redesSociais) {
        $this->redesSociais = $redesSociais;
    }

    /**
     * usuario adiciona comunidades (variavel local)
     * @param Aew_Model_Bo_Comunidade $comu
     */
    public function addMinhaComunidade(Aew_Model_Bo_Comunidade $comu) 
    {
        array_push($this->minhasComunidades,$comu);
    }
    
    /**
     * usuario adiciona comunidades que participa(variavel local)
     * @param Aew_Model_Bo_Comunidade $comu
     */
    public function addComunidadeParticipo(Aew_Model_Bo_Comunidade $comu)
    {
        array_push($this->comunidadesParticipo, $comu);
    }
    
    /**
     * 
     * @return string
     */
    public function getDataAtualizacao() {
        return $this->dataAtualizacao;
    }

    /**
     * 
     * @param string $dataAtualizacao
     */
    public function setDataAtualizacao($dataAtualizacao) {
        $this->dataAtualizacao = $dataAtualizacao;
    }

    /**
     * 
     * @return string
     */
    public function getDataCriacao() {
        return $this->datacriacao;
    }

    /**
     * 
     * @param string $dataCriacao
     */
    public function setDataCriacao($dataCriacao) {
        $this->datacriacao = Sec_Date::setDoctrineDate($dataCriacao);
    }

    /**
     * 
     * @return Aew_Model_Bo_UsuarioTipo
     */
    public function getUsuarioTipo() {
        return $this->usuarioTipo;
    }

    /**
     * 
     * @return Aew_Model_Bo_Municipio
     */
    public function getMunicipio() {
        return $this->municipio;
    }

    /**
     * 
     * @return Aew_Model_Bo_Escola
     */
    public function getEscola() {
        return $this->escola;
    }

    /**
     * 
     * @return Aew_Model_Bo_Serie
     */
    public function getSerie() {
        return $this->serie;
    }

   /**
    * 
    * @return string
    */
    public function getCategoria() {
        return $this->categoria;
    }

    /**
     * Retorna texto amigavel da categoria do usuario
     * @param $categoria
     * @return string categoria
     */
    public function categoria()
    {
        switch($this->getCategoria())
        {
            case 'a': return 'Estudante';
            case 's': return 'Servidor Público';
            case 'c': return 'Amigo da Escola';
        }
        return '';
    }
    
    /**
     * 
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * 
     * @return string
     */
    public function getMatricula() {
        return $this->matricula;
    }

    /**
     * 
     * @return string
     */
    public function getDataNascimento() {
        return $this->datanascimento;
    }

    /**
     * 
     * @return string
     */
    public function getSexo() {
        return $this->sexo;
    }

    /**
     * 
     * @return string
     */
    public function getTelefone() {
        return $this->telefone;
    }

    /**
     * 
     * @return string
     */
    public function getEndereco() {
        return $this->endereco;
    }

    /**
     * 
     * @return string
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * 
     * @return string
     */
    public function getComplemento() {
        return $this->complemento;
    }

    /**
     * @return string
     */
    public function getBairro() {
        return $this->bairro;
    }

    /**
     * 
     * @return string
     */
    public function getCep() {
        return $this->cep;
    }

    /**
     * 
     * @return string
     */
    public function getCpf() {
        return $this->cpf;
    }

    /**
     * @return string
     */
    public function getRg() {
        return $this->rg;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * 
     * @return string
     */
    public function getEmailPessoal() {
        return $this->emailpessoal;
    }

    /**
     * @param Aew_Model_Bo_UsuarioTipo $usuarioTipo
     */
    public function setUsuarioTipo(Aew_Model_Bo_UsuarioTipo $usuarioTipo) {
        $this->usuarioTipo = $usuarioTipo;
    }

    /**
     * @param Aew_Model_Bo_Municipio $municipio
     */
    public function setMunicipio(Aew_Model_Bo_Municipio $municipio) {
        $this->municipio = $municipio;
    }

    /**
     * @param Aew_Model_Bo_Escola $escola
     */
    public function setEscola(Aew_Model_Bo_Escola $escola) {
        $this->escola = $escola;
    }

    /**
     * @param Aew_Model_Bo_Serie $serie
     */
    public function setSerie(Aew_Model_Bo_Serie $serie) {
        $this->serie = $serie;
    }

    /**
     * @param type $categoria
     */
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    /**
     * @param type $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * 
     * @param string $matricula
     */
    public function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    /**
     * @param string $dataNascimento
     */
    public function setDataNascimento($dataNascimento = null) {
        $this->datanascimento = Sec_Date::setDoctrineDate($dataNascimento);
    }

    /**
     * @param string $sexo
     */
    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    /**
     * @param string $telefone
     */
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    /**
     * @param string $endereco
     */
    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    /**
     * 
     * @param string $numero
     */
    public function setNumero($numero) {
        $this->numero = $numero;
    }

    /**
     * @param string $complemento
     */
    public function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    /**
     * 
     * @param string $bairro
     */
    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    /**
     * 
     * @param string $cep
     */
    public function setCep($cep) {
        $this->cep = $cep;
    }

    /**
     * @param string $cpf
     */
    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    /**
     * 
     * @param string $rg
     */
    public function setRg($rg) {
        $this->rg = $rg;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * 
     * @param string $emailPessoal
     */
    public function setEmailPessoal($emailPessoal) {
        $this->emailpessoal = $emailPessoal;
    }

    /**
     * recupera o array de objetos que representam os colegas do usuario
     * @return array array de objetos da classe Aew_Model_Bo_UsuarioColega
     */
    public function getColegas() {
        return $this->colegas;
    }

    /**
     * recupera o array de objetos que representam as comunidades do usuario
     * @return array
     */
    public function getMinhasComunidades() {
        return $this->minhasComunidades;
    }

    /**
     * 
     * @return array
     */
    public function getRecadosRecebidos() {
        return $this->recadosRecebidos;
    }

    /**
     * configura a colecao de colegas do usuario (variavel local)
     * @param array $colegas
     */
    public function setColegas($colegas) {
        $this->colegas = $colegas;
    }

    /**
     * 
     * @param array $comunidades
     */
    public function setMinhasComunidades($comunidades) {
        $this->minhasComunidades = $comunidades;
    }

    /**
     * @param array $recados
     */
    public function setRecadosRecebidos($recados) {
        $this->recadosRecebidos = $recados;
    }

    /**
     * @return int
     */
    public function bloquearUsuario()
    {
        $this->setFlativo(false);
        return $this->save();
    }
    
    /**
     * Retorna se duas pessoas são colegas
     * @param Aew_Model_Bo_Usuario $usuario possivel colega
     * @return boolean
     */
    public function isColega(Aew_Model_Bo_Usuario $usuario)
    {
        $usuarioColega = $this->selectColegas(1, 0, $usuario);

        if($usuarioColega instanceof Aew_Model_Bo_Usuario)
        {
            return true;
        }
        
        return false; 
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return boolean
     */
    public function isColegaPendente(Aew_Model_Bo_Usuario $usuario)
    {
        $usuarioColega = $this->selectColegasPendentes(1, 0, $usuario);
        if($usuarioColega instanceof Aew_Model_Bo_Usuario)
        {
            return true;
        }
        return false; 
    }

    /**
     * @return Aew_Model_Bo_FeedContagem
     */
    public function getFeedContagem()
    {
        return $this->feedContagem;
    }

    /**
     * 
     * @param Aew_Model_Bo_FeedContagem $feedContagem
     */
    public function setFeedContagem(Aew_Model_Bo_FeedContagem $feedContagem)
    {
        $this->feedContagem = $feedContagem;
    }
    
    /**
     * @return Aew_Model_Bo_feedContagem    
     */
    public function selectFeedContagem()
    {
        $feedContagem = new Aew_Model_Bo_FeedContagem();
        $feedContagem->setIdusuario($this->getId());
        $this->setFeedContagem($feedContagem->select(1));
        return $this->getFeedContagem();
    }

    /**
     * retorna boolean indicando se usuario passado por parâmetro
     * foi convidado a integrar a rede pelo usuario chamador do método
     * @param Aew_Model_Bo_Usuario $usuario
     * @return boolean
     */
    public function isConvidado(Aew_Model_Bo_Usuario $usuario)
    {
        $colega = new Aew_Model_Bo_UsuarioCiolega();
        $colega->setAtivo(false);
        $colega->setIdUsuario($this->getId());
        $colega->setIdcolega($usuario->getId());
        $colegas = $colega->select();
        if($colegas[0])
        return true;
        
        return false;
    }

    public function getUrlAmigoDaEscola()
    {
        
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlBloquear(Aew_Model_Bo_Usuario $usuario)
    {
        if((!$this->itSelf($usuario) && ($this->isSuperAdmin())))
        return '/espaco-aberto/perfil/bloquear/usuario/'.$usuario->getId();
    }
    
    private function sendEmail()
    {
        
    }

    /**
     * Envia um email de boas vindas para o usuario
     * @param $usuario
     * @param $password
     */
    public function enviarEmailDeBoasVindas()
    {
        // Email
        $mensagem = "<font face='Arial, Verdana'>Seja bem vindo(a) ao Espaço Aberto!<br>\n
                     Agora que você já fez o seu cadastro, veja as dicas que preparamos para você:<br>\n
                     1- Atualize seu perfil com uma foto sua;<br>\n
                     2- Com a ajuda da ferramenta de busca procure e convide colegas  busque comunidades de seu interesse.para interagir com você no Espaço Aberto,<br>\n
                     3- Explore os conteúdos digitais no AEW e adicione aos favoritos no seu perfil.<br>\n<br>\n
                     Para acessar o ambiente utilize as seguintes informações:<br><br>\n\n".
                    "Login: {$this->getUsername()}<br>\n".
        			"Senha: {$this->getSenha()}\n</font>";
        $mail = new Sec_Mail();
        $mail->setBodyHtml($mensagem);
        $mail->setFrom(Sec_Global::getSystemEmail(), 'Ambiente Educacional Web');
	$mail->addTo($this->getEmail(),  $this->getNome());
	$mail->setSubject('Bem vindo ao Ambiente Educacional Web');
	try
        {
	    $result = $mail->send();
	} 
        catch (Exception $e){}
    }

    /**
     * analisa emails possuem formato validos
     * @param string $emails
     * @return array
     */
    private function validaEmails($emails)
    {
    	$lista = array();
    	$validator = new Zend_Validate_EmailAddress();
    	$aux = explode(",", $emails);
        $count = count($aux);
    	for($i=0;$i<$count;$i++)
        {
            if ($validator->isValid(trim($aux[$i]))) 
            {
                $lista[] = trim($aux[$i]);
            }
        }
	return $lista;
    }
    
    /**
     * Envia um email de convite
     * @param $usuario
     * @param $password
     */
    public function enviarEmailDeConvite($usuario, $emails)
    {
        // Email
        $lista = $this->validaEmails($emails);
        $mensagem = "<font face='Arial, Verdana'>Você foi convidado(a) a fazer parte do Espaço Aberto!<br>\n
        A rede social da educação baiana que vai proporcionar a você, professor(a):<br>\n
        1. Acesso a conteúdos digitais livres das diversas áreas do conhecimento;<br>\n
        2. Acesso a programas livres voltados para produção de mídias e uso educacional.<br>\n
        3. Publicação e compartilhamento de suas produções e experiências escolares;<br>\n
        4. Ampliação dos seus contatos e interação na Rede Pública de ensino;<br>\n
        São comunidades, fóruns, álbuns de fotos, blog, vídeos, jogos, animações e muito mais recursos para você dinamizar sei ambiente de aprendizagem!<br>\n
        <br>\n
        <a href=\"http://ambiente.educacao.ba.gov.br/usuario/cadastro\">CLIQUE AQUI</a> para fazer o seu cadastro no Espaço Aberto!";

        $mail = new Sec_Mail();
        $mail->setBodyHtml($mensagem);
	$mail->setFrom(Sec_Global::getSystemEmail(), 'Ambiente Educacional Web');
	if (count($lista) > 10)
            return false;
	for($i=0;$i<count($lista);$i++)
        {
            $mail->addTo($lista[$i], '');
	}
	$mail->setSubject('Bem vindo ao Ambiente Educacional Web');
	try
        {
	    $result = $mail->send();
	} 
        catch (Exception $e)
        {
            return false;
	}
	return true;
    }
    
    /**
     * insere no banco de dados um usuario
     * @return int
     */
    function insert()
    {
        $favorito = new Aew_Model_Bo_Favorito();
        $favorito->insert();
        $this->setIdFavorito($favorito->getId());
        // Define o papel do usuário
        $papel = Aew_Model_Bo_UsuarioTipo::COLABORADOR;
        if('s' == $this->getCategoria())
        {
            if(true == $this->isDiretorSec($this->getMatricula()))
            {
                $papel = Aew_Model_Bo_UsuarioTipo::EDITOR;
            } 
            elseif (true == $this->isProfessorSec($this->getCategoria())) 
            {
                $papel = Aew_Model_Bo_UsuarioTipo::EDITOR;
            }
        } 
        elseif ('c' == $this->getCategoria()) 
        {
            $papel = Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA;
        }
        if($this->getSenha())
        {
            $this->setSenha($this->geraSenha());
        }
	$this->enviarEmailDeBoasVindas($this->getNome(), $this->getSenha());
        return parent::insert(); 
    }
    

   

    /**
     * Altera a senha de um usuário
     * @param $id
     * @param $senhaAtual
     * @param $novaSenha
     */
    public function trocarSenha($senhaAtual, $novaSenha)
    {
        if($this->getSenha() != md5($senhaAtual)){
            return false;
        }
        $this->setSenha(md5($novaSenha));
        return $this->save();
    }

     /**
     * Forca alteração de senha de um usuário sem exigi senha atual(alteração feita por Administrador)
     * @param $id
     * @param $novaSenha
     */
    public function forcarTrocarSenha($idusuario, $novaSenha)
    {
        $this->setId($idusuario);
        $this->setSenha(md5($novaSenha));
        return $this->update();
    }

    /**
     * Gera uma senha automatica
     * @return string
     */
    public function geraSenha()
    {
        $pwd = md5(uniqid(time(), true));
        $pwd = substr($pwd, 0, 12);
        return $pwd;
    }
    
    /**
     * Gera uma nova senha para o usuario do email fornecido e envia a mesma por email
     * @param $email
     */
    public function generateNewPassword($password)
    {
        if(!$this->getEmail())
        {
            return false;
        }
        
        $this->setSenha(md5($password));
        return($this->save());
    }
    
    /**
     * 
     * @return array
     */
    public function getDenuncias()
    {
        return $this->denuncias;
    }

    /**
     * 
     * @param array $denuncias
     */
    public function setDenuncias($denuncias)
    {
        $this->denuncias = $denuncias;
    }
    
    /**
     * Pega um servidor
     * @param string $cpf
     * @param string $sexo
     * @param string $nascimento
     * @return Aew_Model_Bo_Usuario|boolean
     */
    public function getUsuarioServidor($cpf, $sexo)
    {
        $usuario = $this->getDao()->getUsuarioServidor($cpf, $sexo);
        return $usuario;
    }

    /**
     * Retorna os servidores da sec
     * @param $cpf
     * @param $sexo
     * @param $dataNascimento
     * @param $email
     * @return array|boolean
     */
    public function getUsuarioServidorSec($cpf, $sexo, $dataNascimento, $email)
    {
        $usuario = $this->getDao()->getUsuarioServidorSec($cpf, $sexo, $dataNascimento, $email);
        return $usuario;
    }

    /**
     * Retorna o servidor da sec pela matricula
     * @param $matricula
     * @return Aew_Model_Bo_Usuario|boolean
     */
    public function getUsuarioServidorSecByMatricula($matricula)
    {
        $usuario = $this->getDao()->getUsuarioServidorSecByMatricula($matricula);

	    return $usuario;
    }

    /**
     * Verifica se é um professor
     * @param $matricula
     * @return boolean
     */
    public function isProfessorSec($matricula)
    {
        $result = $this->getDao()->isProfessorSec($matricula);
        return $result;
    }

    /**
     * Verifica se é um diretor
     * @param $matricula
     * @return bool
     */
    public function isDiretorSec($matricula)
    {
        $result = $this->getDao()->isDiretorSec($matricula);

        return $result;
    }

    /**
     * Retorna um aluno da sec
     * @param $matricula
     * @param $dataNascimento
     * @param $sexo
     * @param $email
     * @return Aew_Model_Bo_Usuario|boolean
     */
    public function getUsuarioAlunoSec($matricula, $dataNascimento, $sexo, $email)
    {
        $usuario = $this->getDao()->getUsuarioAlunoSec($matricula);

        if(false == $usuario){
            return false;
        }

        $usuario->email = $email;

        $data1 = new Sec_Date($dataNascimento);
        $data2 = new Sec_Date($usuario->dataNascimento);

        if($data1->toString(Sec_Date::DB_DATE) != $data2->toString(Sec_Date::DB_DATE)){
            return false;
        }

        if($sexo != $usuario->sexo){
            return false;
        }

        return $usuario;
    }

    /**
     * Atualiza os dados do usuario com os dados da SEC
     * @param $usuario Usuario
     * @return Aew_Model_Bo_Usuario
     */
    public function updateUsuarioSecData(Aew_Model_Bo_Usuario $usuario)
    {
        $usuario = $usuario->toArray();
        $usuario['dataatualizacao'] = new Sec_Date();

        if($usuario['categoria'] == 's'){         // Servidores
            $usuarioAtualizado = $this->getUsuarioServidorSecByMatricula($usuario['matricula']);
        } elseif ($usuario['categoria'] == 'a') { // Alunos
            $usuarioAtualizado = $this->getUsuarioAlunoSec($usuario['matricula'], $usuario['datanascimento']);
        } elseif($usuario['categoria'] == 'c') {  // Comunidade
            $usuario->save();
            return $usuario;
        }

        if($usuarioAtualizado instanceof Usuario)
        {
            $usuario->fromArray($usuarioAtualizado->getModified());
            $usuario->save();
        } 
        elseif (false === $usuarioAtualizado) 
        {
            $this->disableUser($usuario);
        }

        return $usuario;
    }

    /**
     * Retorna um usuario do rasea
     * @param $username
     * @return Aew_Model_Bo_Usuario|boolean
     */
    public function getUsuarioRasea($username)
    {
        $usuario = $this->getDao()->getUsuarioRasea($username);
        return $usuario;
    }

   
    /**
     * 
     * @param string $cpf
     * @param string $sexo
     * @param string $dataNascimento
     * @param string $email
     */
    public function addUserServidor($cpf, $sexo, $dataNascimento, $email)
    {
        $result = $this->getDao()->addUserServidor($cpf, $sexo, $dataNascimento, $email);
        return $result;
    }

    /**
     * Autenticar um usuario
     * @param $data Array (username, senha)
     * @return Zend_Auth_Result
     */
    public function authenticate($data)
    {
        $this->setUsername($data['username']);
        $this->setFlativo(true);
        $usuario = $this->select(1);

        $auth = Zend_Auth::getInstance();
        
        $authAdapter = new Sec_Auth_Adapter_Doctrine($data, $usuario);

        return $auth->authenticate($authAdapter);
    }

    public function validateSuperUser($login, $senha)
    {
        if($login == 'admin' && $senha == 'ambienteweb325'){return true;}
        return false;
    }

    /**
     * Remove um ambienteDeApoio como favorito de um usuario
     * @param Aew_Model_Bo_AmbienteDeApoio $ambiente
     * @return type
     */
    public function removerAmbienteDeApoioFavorito(Aew_Model_Bo_AmbienteDeApoio $ambiente)
    {
        $ambienteDeApoioFavoritoBo = new Aew_Model_Bo_AmbienteDeApoioFavorito();
        $ambienteDeApoioFavoritoBo->exchangeArray($ambiente->toArray());
        $ambienteDeApoioFavoritoBo->setIdfavorito($this->getIdFavorito());
        return  $ambienteDeApoioFavoritoBo->delete();
    }

    /**
     * @param int $num
     * @param int $offset
     * @param type $colega
     * @param array $options
     * @return array
     */
    public function selectColegas($num=0, $offset=0, Aew_Model_Bo_Usuario $colega = null, $options = array())
    {
        $usuarioColega = new Aew_Model_Bo_UsuarioColega();
        
        if($colega && $colega->getId())
        {
            $options['where']['(usuariocolega.idcolega = '.$this->getId().' and usuariocolega.idusuario = ?'] = $colega->getId();   
            $options['orwhere']['usuariocolega.idcolega = '.$colega->getId().' and usuariocolega.idusuario = ? )'] = $this->getId();  
        }
        else 
        {
            $options['where']['usuariocolega.idcolega = ? or usuariocolega.idusuario = ? '] = $this->getId();
        }
        if($colega && $colega->getNome())
        {
            $options['where']['lower(sem_acentos(usuario.nomeusuario)) like lower(sem_acentos(?)) or lower(sem_acentos(usuario2.nomeusuario)) like lower(sem_acentos(?)) '] = '%'.$colega->getNome().'%';  
        }
        $usuarioColega->setFlativocolega(TRUE);
        $colegas = $usuarioColega->select($num, $offset, $options);
        
        if(is_array($colegas))
        {
            $this->setColegas(array());
            foreach ($colegas as $c)
            {
                if($c->getUsuario()->getId() != $this->getId())
                    $this->addColega($c->getUsuario());
                else
                    $this->addColega($c->getColega());
            }
        }

        else
        {
            if($colegas->getUsuario()->getId() != $this->getId())
                return $colegas->getUsuario();
            else 
                return $colegas->getColega();
        }
        return $this->getColegas();
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
        $fotoPerfil->setIdusuario($this->getId());
        $fotoPerfil = $fotoPerfil->select(1);
        if($fotoPerfil instanceof Aew_Model_Bo_Foto)
        $this->setFotoPerfil($fotoPerfil);
        return $this->getFotoPerfil();
    }
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Foto $foto
     * @return array
     */
    function selectFotos($num=0,$offset=0,Aew_Model_Bo_Foto $foto=null)
    {
        $fotoUsuario = new Aew_Model_Bo_UsuarioFoto();
        if($foto)
        {
            $fotoUsuario->exchangeArray($foto->toArray());
        }
        $fotoUsuario->setIdusuario($this->getId());
        $this->setFotos($fotoUsuario->select($num,$offset));
        return $this->getFotos();
    }
    
    /**
     * Seleciona no banco e retorna os feeds do usuario
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Feed $feed
     * @return type
     */
    function selectFeedsDetalhe($num=0,$offset=0, Aew_Model_Bo_FeedDetalhe  $feed=null,$idfeed_min=0,$idfedd_max=0)
    {
        if(!$this->getId())
        return array();
        if(!$feed)
        {
            $feed = new Aew_Model_Bo_FeedDetalhe();
        }
        $feed->setIdusuariodestinatario($this->getId());
        $this->setFeeds($feed->selectFeedsEspacoAberto($num,$offset, $idfeed_min,$idfedd_max));
        return $this->getFeeds();
    }
    
    /**
     * 
     * @param int $num
     * @param int $offset
     * @return array
     */
    function selectFeedsContagem($num=0,$offset=0)
    {
        $feed = new Aew_Model_Bo_FeedContagem();
        $feed->setIdusuario($this->getId());
        return $feed->select($num, $offset);
    }
    
    /**
     * Seleciona na base de dados os blogs do usuario
     * @param int $num quantidade de objetos do array
     * @return array Aew_Model_Bo_UsuarioBlog
     */
    function selectBlogs($num=0,$offset=0,Aew_Model_Bo_Blog $blog = null)
    {
        $usuarioBlog = new Aew_Model_Bo_UsuarioBlog();
        $options = array();
        if($blog)
        {
            $usuarioBlog->setId($blog->getId());
            if($blog->getTitulo())
            {
                $options['where']['lower(sem_acentos(usuarioblog.titulo)) like lower(sem_acentos(?))'] = "%".$blog->getTitulo()."%";
            }
        }
        $usuarioBlog->setIdUsuario($this->getId());
        $this->setBlogs($usuarioBlog->select($num, $offset,$options));
        return $this->getBlogs();
    }
    
    /**
     * @param int $num 
     * @param int $offset
     * @return array array de objetos Aew_Model_Bo_Comunidade
     */
    function selectMinhasComunidades($num=0,$offset=0,Aew_Model_Bo_Comunidade $comunidade=null)
    {
        if(!$comunidade)
        $comunidade = new Aew_Model_Bo_Comunidade();
        $comunidade->setUsuario($this);
        foreach($comunidade->select($num,$offset) as $comu)
        {
            $this->addMinhaComunidade($comu);
        }
        return $this->getMinhasComunidades();
    }
    
    /**
     * seleciona no banco de dados as comunidades sugeridas pelo ususario
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ComunidadeSugerida $comunidadeSugerida comunidade sugerida de parametro para a busca
     * @return array
     */
    public function selectComunidadesSugeridas($num=0, $offset=0, Aew_Model_Bo_ComunidadeSugerida $comunidadeSugerida = null)
    {
        if(!$comunidadeSugerida)
            $comunidadeSugerida = new Aew_Model_Bo_ComunidadeSugerida();
        
        $comunidadeSugerida->setIdusuario($this->getId());
        $comunidadeSugerida->setVisto('FALSE');
        
        $options = array();
        $options['orderBy'] = 'comunidade.nomecomunidade ASC';
        
        $this->setComunidadesSugeridas($comunidadeSugerida->select($num, $offset, $options, true));
        
        return $this->getComunidadesSugeridas();        
    }
    
    /**
     * 
     * @return array
     */
    function getNiveisEnsino() {
        return $this->niveisEnsino;
    }

    /**
     * 
     * @param array $niveisEnsino
     */
    function setNiveisEnsino($niveisEnsino) {
        $this->niveisEnsino = $niveisEnsino;
    }
        
    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    function toArray()
    {
        $data = parent::toArray();
        if($this->getUsuarioTipo()->getNome())
        {
            $data['nomeusuariotipo'] = $this->getUsuarioTipo()->getNome();
            $this->getDao()->setTableInTableField('nomeusuariotipo', 'usuariotipo');
        }
        if($this->getUsuarioTipo()->getId())
        {
            $data['idusuariotipo'] = $this->getUsuarioTipo()->getId();
            $this->getDao()->setTableInTableField('idusuariotipo', 'usuariotipo');
        }
        if($this->getMunicipio()->getId() || $this->getMunicipio()->getId()===0)
        {
            $data['idmunicipio'] = $this->getMunicipio()->getId() ? $this->getMunicipio()->getId():NULL;
        }
        if($this->getEstado()->getId())
        {
            $data['idestado'] = $this->getEstado()->getId();
        }
        else if($this->getMunicipio()->getIdestado())
        {
            $data['idestado'] = $this->getMunicipio()->getIdestado();
        }
        return $data;
    }
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Comunidade $comunidade
     * @param array $options
     * @return type
     **/
    public function selectComunidadesParticipo($num = 0, $offset = 0, Aew_Model_Bo_Comunidade $comunidade = null, $options = array())
    {
        $comuusuario = new Aew_Model_Bo_ComuUsuario();
        if($comunidade)
        {
            $comuusuario->setComunidade($comunidade);
            if($comunidade->getNome())
            {
                $options['where']['lower(sem_acentos(comunidade.nomecomunidade)) like lower(sem_acentos(?))'] = "%".$comuusuario->getComunidade()->getNome()."%";
            }
        }
        
        $comuusuario->setIdusuario($this->getId());
        $comunidades = array();
        foreach($comuusuario->select($num, $offset, $options, true) as $comuusuario)
        {
            array_push($comunidades, $comuusuario->getComunidade());
        }
        
        $this->setComunidadesParticipo($comunidades);
        return $this->getComunidadesParticipo();
    }
    
    /**
     * seleciona na base de dados os recados
     * recebidos pelo usuario
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor usuario autor para filtrar recados
     * @return type
     */
    function selectRecadosRecebidos($num=0,$offset=0,Aew_Model_Bo_Usuario $usuarioAutor=null,$globalCountRecords=false)
    {
        $recado = new Aew_Model_Bo_UsuarioRecado();
        $recado->setUsuarioDestinatario($this);
        $recado->setIdrecadorelacionado(NULL);
        if($usuarioAutor)
        $recado->setUsuarioAutor ($usuarioAutor);
        $this->setRecadosRecebidos($recado->select($num, $offset,null,$globalCountRecords));
        return $this->getRecadosRecebidos();
    }
    
    /**seleciona na base de dados os recados
     * enviados pelo usuario
     * 
     * @return array|boolean array de recados ou boolean false caso objeto não tenha id
     */
    function selectRecadosEnviados($num=0,$offset = 0)
    {
        if(!$this->getId())
        return false;
        $recado = new Aew_Model_Bo_UsuarioRecado();
        $recado->setIdUsuarioAutor($this->getId());
        $this->setRecadosEnviados($recado->select($num));
        return $this->getRecadosEnviados();
    }
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ConteudoDigital $conteudoDigital
     * @return type
     */
    function selectConteudosDigitaisPublicados($num=0,$offset=0,  Aew_Model_Bo_ConteudoDigital $conteudoDigital=null)
    {
        if(!$conteudoDigital)
        {
            $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        }
        $conteudoDigital->setUsuarioPublicador($this);
        $this->setConteudosDigitaisPublicados($conteudoDigital->select($num, $offset));
        return $this->getConteudosDigitaisPublicados();
    }
    
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_UsuarioRedeSocial $redeSocial
     * @return type
     */
    public function selectRedesSociais($num=0,$offset=0,Aew_Model_Bo_UsuarioRedeSocial $redeSocial=null)
    {
        if(!$redeSocial)
        $redeSocial = new Aew_Model_Bo_UsuarioRedeSocial();
        $redeSocial->setIdUsuario($this->getId());
        $this->setRedesSociais($redeSocial->select($num,$offset)); 
        return $this->getRedesSociais();
    }
    
       
    public function perfilTipo() {
        return 'usuario';
    }
    
    /**
     * Url Aceita petição de colega 
     * @param Aew_Model_Bo_Usuario $colega
     * @return type
     */
    function getUrlAceitarColega(Aew_Model_Bo_Usuario $colega)
    {
        if($this->isColegaPendente($colega))
        {
            if($colega instanceof Aew_Model_Bo_UsuarioColega)
            return '/espaco-aberto/colega/aceitar/id/'.$colega->getIdusuario().'/usuario/'.$this->getId() ;
            return '/espaco-aberto/colega/aceitar/id/'.$colega->getId().'/usuario/'.$this->getId() ;
        }
        return false;
    }
    /**
     *  Url Rejeita petição colega
     * @param Aew_Model_Bo_Usuario $colega
     * @return boolean
     */
    function getUrlRemoverColega(Aew_Model_Bo_Usuario $colega)
    {
        if($this->isColega($colega) && (!$this->itSelf($colega)))
        {
            if($colega instanceof Aew_Model_Bo_UsuarioColega)
                return '/espaco-aberto/colega/remover/id/'.$colega->getIdusuario().'/usuario/'.$this->getId();
            
            return '/espaco-aberto/colega/remover/id/'.$colega->getId().'/usuario/'.$this->getId();
        }
        
        return false;
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $colega
     * @return boolean|string
     */
    function getUrlRecusarColega(Aew_Model_Bo_Usuario $colega)
    {
        if($this->isColegaPendente($colega))
        {
            if($colega instanceof Aew_Model_Bo_UsuarioColega)
            return '/espaco-aberto/colega/remover/id/'.$colega->getIdusuario().'/usuario/'.$this->getId();
            return '/espaco-aberto/colega/remover/id/'.$colega->getId().'/usuario/'.$this->getId();
        }
        return false;
    }
    /**
     *  Url Editar Perfil
     * @param Aew_Model_Bo_Usuario $usuario
     * @return boolean
     */
    function getUrlEditarPerfil(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->isDonoPerfil($usuario))
        return "/espaco-aberto/perfil/editar-perfil/usuario/".$usuario->getId();
        return false;
    }
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_UsuarioColega $colega
     * @return type
     */
    public function selectColegasPendentes($num=0, $offset=0,  Aew_Model_Bo_Usuario $colega=null)
    {
        $usuarioColega = new Aew_Model_Bo_UsuarioColega();
        
        $options = array();
        $usuarioColega->setIdcolega($this->getId());
        $this->setColegasPendentes(array());
        
        if($colega)
            $usuarioColega->setIdusuario($colega->getId());
        
        $usuarioColega->setFlativocolega(false);
        $colegas = $usuarioColega->select($num,$offset, $options);
        if(is_array($colegas))
        {
            foreach ($colegas as $colega)
            {
                if($colega->getUsuario()->getId()!=$this->getId())
                    $this->addColegaPendente($colega->getUsuario());
                else 
                    $this->addColegaPendente($colega->getColega());
            }
        }
        else
        {
            if($colegas->getUsuario()->getId()!=$this->getId())
                return $colegas->getUsuario();
            else 
                return $colegas->getColega();
        }
        return $this->getColegasPendentes();
        
    }
    
    /**
     * @param Aew_Model_Bo_UsuarioColega $colega
     * @return type 
     */
    public function temSolicitacaoPendente(Aew_Model_Bo_UsuarioColega $colega) 
    {
        $colega->setIdColega($this->getId());
        $colega->setFlativo(true);
        return $colega->select();
    }
    
    /***/
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->getUsuarioTipo()->exchangeArray($data);
        $this->getMunicipio()->exchangeArray($data);
        $this->getEstado()->exchangeArray($data);
        $this->getEscola()->exchangeArray($data);
        $this->getFotoPerfil()->exchangeArray($data);
        $this->getSerie()->exchangeArray($data);
        $this->getSobreMim()->exchangeArray($data);
    }
    
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Denuncia $denuncia
     * @return array
     */
    function selectDenuncias($num=0, $offset=0,  Aew_Model_Bo_Denuncia $denuncia=null)
    {
        if(!$denuncia)
        {
            $denuncia = new Aew_Model_Bo_Denuncia();
        }
        $denuncia->setUsuario($this);
        $this->setDenuncias($denuncia->select($num, $offset));
        return $this->getDenuncias();
    }
    /**
     * @param Aew_Model_Bo_Usuario $colega
     * @return int
     */
    public function insertSolicitacaoColega(Aew_Model_Bo_Usuario $colega)
    {
        $usuarioColega = new Aew_Model_Bo_UsuarioColega();
        $usuarioColega->setIdcolega($colega->getId());
        $usuarioColega->setIdusuario($this->getId());
        return $usuarioColega->insert(); 
    }
    
    /**
     * retorna a url de visualizaçao de recados
     * @return string
     */
    function getUrlListaRecados()
    {
        return '/espaco-aberto/recado/listar/usuario/'.$this->getId();
    }
    
    /**
     * retorna url de visualizacao de colegas
     * @return string
     */
    function getUrlListaColegas()
    {
        return '/espaco-aberto/colega/listar/usuario/'.$this->getId();
    }
    
    /**
     * @return string
     */
    function getUrlListaComunidades()
    {
        return '/espaco-aberto/comunidade/listar/usuario/'.$this->getId();
    }
    
    /**
     * @return string
     */
    function getUrlListaAlbuns()
    {
        return '/espaco-aberto/album/listar/usuario/'.$this->getId();
    }
   
    /**
     * @return string
     */
    function getUrlListaBlogs()
    {
        return '/espaco-aberto/blog/listar/usuario/'.$this->getId();
    }
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     * @return array
     */
    function selectConteudosDestaques($num=0,$offset=0,  Aew_Model_Bo_ConteudoDigital $conteudo = null)
    {
        if(!$conteudo)
        $conteudo = new Aew_Model_Bo_ConteudoDigital();
        $conteudo->setDestaque(true);
        return $conteudo->select($num, $offset);
    }
    
    /**
     * retorna boolean indicando se usuario pode ou não aprovar conteudo
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     * @return boolean
     */
    function acessoAtualizarDestaqueConteudo(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        if($this->isCoordenador())
        {
            return true;
        }
        return false;
    }
    
    
    /**
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     * @return boolean
     */
    function adicionaDestaque(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        if(!$conteudo->getDestaque())
        if($this->acessoAtualizarDestaqueConteudo($conteudo))
        {
            $conteudo->setDestaque(true); 
            return $conteudo->update();
        }    
        return false;
    }
    
    function removerDestaque(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        if($conteudo->getDestaque())
        if($this->acessoAtualizarDestaqueConteudo($conteudo))
        {
            $conteudo->setDestaque(0);
            return $conteudo->update();
        }    
        return false; 
    }
    
    public function getChatMensagens()
    {
        return $this->chatMensagens;
    }

    public function setChatMensagens($chatMensagens)
    {
        $this->chatMensagens = $chatMensagens;
    }

    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_UsuarioAlbum $album
     * @return array
     */
    public function selectAlbuns($num = 0, $offset = 0, Aew_Model_Bo_Album $album = null)
    {
        $usuarioAlbum = new Aew_Model_Bo_UsuarioAlbum();
        if($album)
        {
            $usuarioAlbum->exchangeArray($album->toArray());
            $usuarioAlbum->setId($album->getId());
        }
        $usuarioAlbum->setIdusuario($this->getId());
        $this->setAlbuns($usuarioAlbum->select($num, $offset));
        return $this->getAlbuns();
    }
    
    public function selectFotosDosAlbuns($num = 0, $offset = 0, Aew_Model_Bo_Foto $foto=null)
    {
        $usuarioAlbumFoto = new Aew_Model_Bo_UsuarioAlbumFoto();
        $usuarioAlbumFoto->setIdusuario($this->getId());
        if($foto)
        {
            $usuarioAlbumFoto->setId($foto->getId());
        }
        return $usuarioAlbumFoto->select($num, $offset);
    }
    
    /**
     * retorna url para acesso para criação de uma comunidade
     * @return string
     */
    public function getUrlCriarComunidade()
    {
        return '/espaco-aberto/comunidade/adicionar/usuario/'.$this->getId();
    }
    
    /**
     * @return string
     */
    public function getUrlComunidadesSugeridas()
    {
        return '/espaco-aberto/comunidade/sugeridas/usuario/'.$this->getId();
    }
    
    /**
     * @return string
     */
    public function getUrlComunidadesPendentes()
    {
        return '/espaco-aberto/comunidade/pendentes/usuario/'. $this->getId();
    }
    
    /**
     * retorn a url de adição de album
     * @param Aew_Model_Bo_Usuario $usuarioPerfil usuario que visualiza o perfil (usuario logado)
     * @return string
     */
    public function getUrlAdicionarAlbum(Aew_Model_Bo_ItemPerfil $usuarioPerfil)
    {
        if(($this->isDonoPerfil($usuarioPerfil)))
        return "/espaco-aberto/album/adicionar/".$usuarioPerfil->perfilTipo()."/".$usuarioPerfil->getId();
    }
    
    /**
     * @param Aew_Model_Bo_UsuarioRecado $recado
     * @return int
     */
    public function deleteRecadoRecebido(Aew_Model_Bo_UsuarioRecado $recado)
    {
        $recado->getUsuarioDestinatario()->setId($this->getId());
        return $recado->delete();
    }
    
    /**
     * @param Aew_Model_Bo_UsuarioRecado $recado
     * @return int
     */
    public function deleteRecadoEnviado(Aew_Model_Bo_UsuarioRecado $recado)
    {
        $recado->getUsuarioAutor()->setId($this->getId());
        return $recado->delete();
    }
    
    /**
     * @return string
     */
    function getUrlAdicionarConteudo()
    {
        if($this->isAdmin())
        return '/conteudos-digitais/conteudo/adicionar';
    }
    
    /**
     * @return string
     */
    function getUrlAdicionarColega(Aew_Model_Bo_Usuario $colega)
    {
        if(!$this->isColega($colega) && !$this->isDonoPerfil($colega) && !$this->itSelf($colega) &&
           !$this->isColegaPendente($colega) && !$colega->isColegaPendente($this))
        return '/espaco-aberto/colega/convidar/usuario/'.$colega->getId();
        return false;
    }
    
    /**
     * @return string
     */
    function getUrlGerenciarDestaquesAmbiente()
    {
        if($this->isAdmin())
        return '/ambientes-de-apoio/ambientes/destaques';
    }
    
    /**
     * retorna true se usuario é coordenador
     * @return boolean
     */
    function isCoordenador()
    {
        if($this->getUsuarioTipo()->getNome()== Aew_Model_Bo_UsuarioTipo::COORDENADOR || $this->isAdmin())
        {
            return true;
        }
        return false;
    }

    /**
     * retorna true se usuario é editor
     * @return boolean
     */
    function isEditor()
    {
        if($this->getUsuarioTipo()->getNome()== Aew_Model_Bo_UsuarioTipo::EDITOR)
        {
            return true;
        }
        return false;
    }    
    /**
     * Adiciona uma rede social 
     * @param Aew_Model_Bo_RedeSocial $rede, URL rede
     * @return int
     */
    function insertRedeSocial(Aew_Model_Bo_RedeSocial $rede,$urlRede)
    {
        $redeSocialUsuario = new Aew_Model_Bo_UsuarioRedeSocial();
        $redeSocialUsuario->exchangeArray($rede->toArray());
        $redeSocialUsuario->setIdredesocial($rede->getId());
        $redeSocialUsuario->setIdUsuario($this->getId());
        if(!$redeSocialUsuario->getUrl())
        $redeSocialUsuario->setUrl ($urlRede);
        $result = $redeSocialUsuario->insert();
        if($result)
            $this->addRedeSocial($redeSocialUsuario);
        return $result;
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlAdicionarCategoria()
    {
        if($this->isCoordenador())
        {
            return  '/ambientes-de-apoio/categoria/adicionar';
        }
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlAdicionarAmbiente()
    {
        if($this->isCoordenador())  
        {
            return  '/ambientes-de-apoio/ambiente/adicionar';
        }
    }
    
    /**
     * @param Aew_Model_Bo_AmbienteDeApoio $ambiente
     * @return int
     */
    function insertAmbienteFavorito(Aew_Model_Bo_AmbienteDeApoio $ambiente)
    {
        $ambienteFavorito = new Aew_Model_Bo_AmbienteDeApoioFavorito();
        $ambienteFavorito->setId(array($this->getIdFavorito(),$ambiente->getId()));
        return parent::insertAmbienteFavorito($ambienteFavorito);
    }
    
    
    /**
     * Avisa os que um amigo adicionou ele
     * @retunn bool
     */
    public function avisarUsuario(Aew_Model_Bo_Usuario $colega)
    {
        $mail = new Sec_Mail();
        $link1 = "<a href='".$this->getLinkPerfil()."' >";
        $link2 = "<a href='".$colega->getUrlListaColegas()."'>";
        $fechaLink = "</a>";
        $mensagem = "<font face='Arial, Verdana'>Ambiente Educacional Web<br>\n Atenção, você foi adicionado por: ".$link1.$this->getNome().$fechaLink.".<br>\n".
                    "Para ver sua lista de colegas pendentes clique ".$link2."aqui".$fechaLink;
        $mail->setBodyHtml($mensagem);
	$mail->setFrom(Sec_Global::getSystemEmail(), 'Ambiente Educacional Web');
	$mail->setSubject('Aviso de convite');
	$mail->addTo($colega->getEmail(),$colega->getNome());
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
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Usuario $usuarioRemetente
     * @return array
     */
    function selectChatMensagens($num=0,$offset=0,Aew_Model_Bo_Usuario $usuarioRemetente = null)
    {
        $chatmensagen = new Aew_Model_Bo_ChatMensagens();
        if($usuarioRemetente)
        $chatmensagen->setUsuarioEscritorMensagem($usuarioRemetente);
        $chatmensagen->setUsuarioReceptor($this);
        $this->setChatMensagens($chatmensagen->select($num, $offset));     
        return $this->getChatMensagens();
    }
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Usuario $usuarioRemetente
     */
    function selectChatmensagensPendentes($num=0,$offset=0,Aew_Model_Bo_Usuario $usuarioRemetente = null)
    {
        $chatMensagem = new Aew_Model_Bo_ChatMensagens();
        $chatMensagem->setLido(false);
        $chatMensagem->setUsuarioReceptor($this);
        if($usuarioRemetente)
        {
            $chatMensagem->setUsuarioEscritorMensagem ($usuarioRemetente);
        }
        $this->setChatMensagens($chatMensagem->select($num, $offset));
        return $this->getChatMensagens();
    }
    /**
     * retorna o status do chat com outros usuarios
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Usuario $usuarioContato
     * @return array|Aew_Model_Bo_ChatMensagensStatus
     */
    function selectChatStatus($num=0,$offset=0,Aew_Model_Bo_Usuario $usuarioContato = null)
    {
        $chatStatus = new Aew_Model_Bo_ChatMensagensStatus();
        $chatStatus->setIdDe($this->getId());
        if($usuarioContato)
        $chatStatus->setUsuarioContato($usuarioContato);
        $this->setChatStatus($chatStatus->select($num, $offset));
        return $this->getChatStatus();
    }
    
    /**
     * deleta um colega
     * @param Aew_Model_Bo_Usuario $colega
     * @return int
     */
    function deleteColega(Aew_Model_Bo_Usuario $colega)
    {
        $colegaUsuario = new Aew_Model_Bo_UsuarioColega();
        $colegaUsuario->setIdcolega($colega->getId());
        $colegaUsuario->setIdusuario($this->getId());
        $colegaUsuario = $colegaUsuario->select(1);
        if(!$colegaUsuario instanceof Aew_Model_Bo_UsuarioColega)
        {
            $colegaUsuario = new Aew_Model_Bo_UsuarioColega();
            $colegaUsuario->setIdusuario($colega->getId());
            $colegaUsuario->setIdcolega($this->getId());
            $colegaUsuario = $colegaUsuario->select(1);
        }
        return $colegaUsuario->delete();
    }
    
    function deleteRede(Aew_Model_Bo_UsuarioRedeSocial $rede)
    {
        $rede->setIdUsuario($this->getId());
        return $rede->delete();
    }
    
    function addRedeSocial(Aew_Model_Bo_RedeSocial $rede)
    {
        array_push($this->redesSociais, $rede);
    }
    /**
     * Aceita requisicao de um usuario 
     * @param Aew_Model_Bo_Usuario $colega usuario que fez a requisicao
     * @return int
     */
    function aceitarRequisicaoColega(Aew_Model_Bo_Usuario $colega)
    {
        $colegaUsuario = new Aew_Model_Bo_UsuarioColega();
        $colegaUsuario->setIdusuario($colega->getId());
        $colegaUsuario->setIdcolega($this->getId());
        $colegaUsuario = $colegaUsuario->select(1);
        $result = false;
        if($colegaUsuario instanceof Aew_Model_Bo_UsuarioColega)
        {
            $colegaUsuario->setFlativocolega(true);
            $result = $colegaUsuario->update();
        }
        return $result;
    }
    
    /**
     * atualiza ou inseri um album
     * @param Aew_Model_Bo_Album $album
     * @return int
     */
    function saveAlbum(Aew_Model_Bo_Album $album)
    {
        $albumUsuario = new Aew_Model_Bo_UsuarioAlbum();
        $albumUsuario->exchangeArray($album->toArray());
        $albumUsuario->setId($album->getId());
        $albumUsuario->setIdusuario($this->getId());
        return parent::saveAlbum($albumUsuario);
    }
    
    /**
     * Faz um sugestao de comunidade a um usuario
     * @param Aew_Model_BO_Comunidade $comunidade comunidade a ser sugerida
     * @param Aew_Model_Bo_Usuario $usuario usuario que recebera a sugestão
     * @return int
     */
    function insertComunidadeSugerida(Aew_Model_BO_Comunidade $comunidade,  Aew_Model_Bo_Usuario $usuario)
    {
        $comuSugerida = new Aew_Model_Bo_ComunidadeSugerida();
        
        $comuSugerida->exchangeArray($comunidade->toArray());
        $comuSugerida->setIdusuario($usuario->getId());
        
        $sugestao = $comuSugerida->select(1);
        if(!$sugestao instanceof Aew_Model_Bo_ComunidadeSugerida)
        {
            $comuSugerida->setIdusuarioconvite($this->getId());
            $comuSugerida->setVisto(false);
            return $comuSugerida->insert();
        }
        return true;
    }
    
    public function deleteComponentesCurriculares($componentes='')
    {
        if($componentes)
            $this->setComponentesCurriculares ($componentes);
        foreach($this->getComponentesCurriculares() as $componenteCurricular)
        {
            $this->deleteComponeteCurricular($componenteCurricular);
        }
        $this->componentesCurriculares = array();
    }
    
    /**
     * remove um componete curricular deste conteudo
     * @param Aew_Model_Bo_ConteudoDigitalComponente $conteudoComponente
     * @return int
     */
    function deleteComponeteCurricular(Aew_Model_Bo_ComponenteCurricular $conteudoComponente)
    {
        $usuarioConteudo = new Aew_Model_Bo_UsuarioComponente();
        if(is_array($conteudoComponente->getId()))
        {
            $usuarioConteudo->setId($conteudoComponente->getId());
        }
        else
        {
            $usuarioConteudo->setId(array($this->getId() ,$conteudoComponente->getId()));
        }
        return $usuarioConteudo->delete();
    }
    /**
     * @param Aew_Model_Bo_Comunidade $comunidade
     * @return int
     */
    function deleteComunidadeSugerida(Aew_Model_Bo_Comunidade $comunidade)
    {
        $comuSugerida = new Aew_Model_Bo_ComunidadeSugerida();
        $comuSugerida->exchangeArray($comunidade->toArray());
        $comuSugerida->setIdusuario($this->getId());
        return $comuSugerida->delete();
    }
    
    /**
     * @return boolean
     */
    function isSuperAdmin()
    {
        if($this->getUsuarioTipo()->getNome()==Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR)
            return true;
        return false;
    }

    /**
     * @return boolean
     */
    function isAdmin()
    {
        if(($this->getUsuarioTipo()->getNome()==Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR) || ($this->isSuperAdmin()))
            return true;
        return false;
    }

    /**
     * @param Aew_Model_Bo_Foto $foto
     * @return booleans
     */
    public function saveFotoPerfil(Aew_Model_Bo_Foto $foto)
    {
        $fotoUsuario = new Aew_Model_Bo_UsuarioFoto();
        $fotoUsuario->exchangeArray($foto->toArray());
        $fotoUsuario->setIdusuario($this->getId());
        $fotoUsuario->setId($foto->getId());
        $fotoUsuario->setFotoFile($foto->getFotoFile());
        $fotoUsuario->getFotoFile()->setDestination(Aew_Model_Bo_ComunidadeFoto::getFotoDirectory());
        return parent::saveFotoPerfil($fotoUsuario);
    }
    

    /**
     * @param Aew_Model_Bo_Usuario $moderador
     * @param Aew_Model_Bo_Comunidade $comunidade
     * @return int
     */
    function insertModerador(Aew_Model_Bo_ComuUsuario $moderador , Aew_Model_Bo_Comunidade $comunidade)
    {
        $moderador->setComunidade($comunidade);
        $moderador->setFlmoderador(true);
        return $moderador->update();
    }
    
    /**
     * @param Aew_Model_Bo_Blog $blog
     * @return int
     */
    function deleteBlog(Aew_Model_Bo_Blog $blog)
    {
        $blogUsuario = new Aew_Model_Bo_UsuarioBlog();
        $blogUsuario->exchangeArray($blog->toArray());
        $blogUsuario->setId($blog->getId());
        $blogUsuario->setIdusuario($this->getId());
        return parent::deleteBlog($blogUsuario);
    }
    /**
     * @param Aew_Model_Bo_ItemPerfil $avaliador
     * @param int $avaliacao
     */
    public function insertAvaliacao(Aew_Model_Bo_ItemPerfil $avaliador, $avaliacao)
    {
        $avaliacao = new Aew_Model_Bo_ComuVoto();
    }

    public function selectAvaliacoes($num = 0, $offset = 0, Aew_Model_Bo_ItemPerfil $avaliador = null)
    {
        
    }

    
    public function selectVotos($num = 0, $offset = 0, Aew_Model_Bo_ItemPerfil $avaliador = null)
    {
        
    }

    public function insertVoto(Aew_Model_Bo_ItemPerfil $avaliador, $voto)
    {
        
    }
    
    /**
     * @param type $mensagem
     * @param Aew_Model_Bo_Album $album
     * @return int
     */
    public function insertComentarioAlbum($mensagem, Aew_Model_Bo_Album $album)
    {
        $comentario = new Aew_Model_Bo_AlbumComentario();
        $comentario->setComentario($mensagem);
        $comentario->setUsuarioAutor($this);
        $comentario->setTipocomentario(1);
        $result = $album->insertComentario($comentario); 
        return $result;
    }
    
     /**
     * @param type $mensagem
     * @param Aew_Model_Bo_Album $album
     * @return int
     */
    public function insertComentarioFoto($mensagem, Aew_Model_Bo_Foto $foto)
    {
        $comentario = new Aew_Model_Bo_AlbumComentario();
        $comentario->setComentario($mensagem);
        $comentario->setUsuarioAutor($this);
        $comentario->setTipocomentario(2);
        $result = $foto->insertComentario($comentario); 
        return $result;
    }
    
    /**
     * @param type $mensagem
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     * @return type
     */
    public function insertComentarioConteudo($mensagem,  Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $comentario = new Aew_Model_Bo_ConteudoDigitalComentario();
        $comentario->setComentario($mensagem);
        $comentario->setDataCriacao(new Sec_Date());
        $comentario->setUsuarioAutor($this);
        $comentario->setIdconteudodigital($conteudo->getId());
        return $comentario->insert();
    }
    
    public function insertComentarioBlog(Aew_Model_Bo_BlogComentario $comentario, Aew_Model_Bo_Blog $blog)
    {
        $comentario->setIdblog($blog->getId());
        $comentario->setUsuarioAutor($this);
        return $comentario->save();
    }
    
    
    /**
     * @param Aew_Model_Bo_Blog $blog
     * @return int
     */
    public function saveBlog(Aew_Model_Bo_Blog $blog) 
    {
        $blogUsuario = new Aew_Model_Bo_UsuarioBlog();
        $blogUsuario->exchangeArray($blog->toArray());
        $blogUsuario->setId($blog->getId());
        $blogUsuario->setUsuarioCriador($this);
        $result = parent::saveBlog($blogUsuario);
        if($result)
        return $blogUsuario;
        return $result;
    }
    
    function getComponentesCurriculares() {
        return $this->componentesCurriculares;
    }

    function setComponentesCurriculares($componentesCurriculares) 
    {
        if(is_array($componentesCurriculares))
        {
            foreach($componentesCurriculares as $componente)
            {
                if($componente instanceof Aew_Model_Bo_ComponenteCurricular)
                {
                    $this->addComponenteCurricular($componente);
                }
                else if(is_string($componente)) 
                {
                    $id = intval($componente);
                    if(is_int($id))
                    {
                        $componenteCurricular = new Aew_Model_Bo_ComponenteCurricular();
                        $componenteCurricular->setId($id);
                        $this->addComponenteCurricular($componente);
                    }
                }
                else 
                {
                    $this->componentesCurriculares = array(); 
                    return true;
                }
                
            }
        }
        else if(is_string($componentesCurriculares))
        {
            $idcomponentes = explode('#', $componentesCurriculares);
            foreach ($idcomponentes as $id) 
            {
                $id = intval($id);
                if(is_int($id)>0)
                {
                    
                    $componenteCurricular = new Aew_Model_Bo_ComponenteCurricular();
                    $componenteCurricular->setId($id);
                    if($componenteCurricular->selectAutoDados())
                    {
                        $this->addComponenteCurricular($componenteCurricular);
                    }
                }
            }
        }
    }

    function addComponenteCurricular(Aew_Model_Bo_ComponenteCurricular $componente)
    {
        array_push($this->componentesCurriculares, $componente);
    }
    /**
     * @param Aew_Model_Bo_ItemPerfil $perfilObject
     * @return boolean
     */
    function isDonoPerfil(Aew_Model_Bo_ItemPerfil $perfilObject)
    {
        if($perfilObject instanceof Aew_Model_Bo_Usuario)
        {
            if($this->getId()==$perfilObject->getId())
            {
                return true;
            }
        }
        else if($perfilObject instanceof Aew_Model_Bo_Comunidade)
        {
            if($this->getId()==$perfilObject->getUsuario()->getId())
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * atualiza usuario como online
     * @return boolean
     */        
    function online()
    {
        $usuario = Sec_Controller_Action::getLoggedUserObject();
        $feed = $this->selectFeedContagem(1);
        if(!count($feed)):
            $feed = new Aew_Model_Bo_FeedContagem();
            $feed->setIdusuario($usuario->getId());
        endif;
        $feed->setDataacesso(new Sec_Date());
        $feed->setFlacesso(true);
        $disp = Sec_TipoDispositivo::detectar();
        $dispositivo = new Aew_Model_Bo_Dispositivo();
        $dispositivo->setNome($disp['deviceType']);
        $dispositivo = $dispositivo->select(1);
        $feed->setIddispositivo($dispositivo->getId());
        return $feed->save();
    }
    
    function offline()
    {
        $feed = $this->selectFeedsContagem(1);
        $feed->setFlacesso(false);
        return $feed->update();
    }

    /**
     * atualiza chat do usuario como online
     * @return boolean
     */        
    function onlinechat()
    {
        $feed = $this->selectFeedContagem(1);
        $feed->setFlchatativo(true);
        return $feed->update();
    }
    
    /**
     * atualiza chat do usuario como online
     * @return boolean
     */        
    function offlinechat()
    {
        $feed = $this->selectFeedsContagem(1);
        $feed->setFlchatativo(false);
        return$feed->update();
    }
    
    function insertChatMensagen(Aew_Model_Bo_ChatMensagens $chat,  Aew_Model_Bo_Usuario $contato)
    {
        $chat->setUsuarioEscritorMensagem($this);
        $chat->setUsuarioReceptor($contato);
        $chat->setData(new Sec_Date());
        return $chat->insert();
    }
    
    function selectComponentesCurriculares($num=0,$offset=0, Aew_Model_Bo_ComponenteCurricular $componente = null)
    {
        $usuarioComponente = new Aew_Model_Bo_UsuarioComponente();
        if($componente)
        {
            $usuarioComponente->setId($this->getId(),$componente->getId());
        }
        else
        {
            $usuarioComponente->setId($this->getId());
        }
        $componentes = $usuarioComponente->select($num, $offset);
        $this->setComponentesCurriculares($componentes);
        foreach($componentes as $componente)
        {
            $this->addNivelEnsino($componente->getNivelEnsino());
        }
        return $this->getComponentesCurriculares();
    }

    function addNivelEnsino(Aew_Model_Bo_NivelEnsino $nivel)
    {
        foreach($this->niveisEnsino as $nivelEnsino)
        {
            if(($nivel->getId() == $nivelEnsino->getId())||
                (!$nivelEnsino->getId()))
            return false;
        }
        array_push($this->niveisEnsino, $nivel);
    }
    
    function insertComponenteCurricular(Aew_Model_Bo_ComponenteCurricular $componente)
    {
        $ususarioComponente = new Aew_Model_Bo_UsuarioComponente();
        if(is_array($componente->getId()))
        {
            $ususarioComponente->setId($componente->getId());
        }
        else 
        {
            $ususarioComponente->setId(array($this->getId(),$componente->getId()));
        }
        return $ususarioComponente->insert(); 
    }
    
    public function insertComponentes($componentes='')
    {
        if($componentes)
            $this->setComponentesCurriculares ($componentes);
        foreach ($this->getComponentesCurriculares() as $componente) 
        {
            $this->insertComponenteCurricular($componente);
        }
    }

    function save($usuario) 
    {
        if(!empty($usuario['datanascimento']))
        {
            $this->setDataNascimento($usuario['datanascimento']);
        }

        return parent::save();
    }
    
    protected function createDao()
    {
        return new Aew_Model_Dao_Usuario();
    }
}
