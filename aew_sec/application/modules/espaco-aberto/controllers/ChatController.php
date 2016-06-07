<?php

class EspacoAberto_ChatController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
	$acl = $this->getHelper('Acl');
	$amigoDaEscolaAction = array('home');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);
    }
    /**
     * Método do CHAT da aplicação
     * @return Zend_View
     */
    public function homeAction()
    {
	$this->getHelper('layout')->disableLayout();
        
        $usuarioLogado = $this->getLoggedUserObject();
        $acao = $this->getParam('acao', '');
        
        switch ($acao):
            
            case 'avisar':
                $para = $this->getParam('para', '');
                $processo = $this->getParam('processo', '');
		$status = ($processo == 'A' ? 'TRUE' : 'FALSE');
                $usuarioContato = new Aew_Model_Bo_Usuario();
                $usuarioContato->setId($para);
                $usuarioLogado->selectChatStatus(1,1,$usuarioContato);
		echo json_encode('');
		die();
		break;

            case 'alertar':
                $ids = $this->getParam('ids', '');
		$retorno = array();
                $chatMensagem = new Aew_Model_Bo_ChatMensagens();
                if ($ids != ''):
                    foreach ($ids as $indice => $id):
                        $contato = new Aew_Model_Bo_Usuario();
                        $contato->setId($id);
                        $alertar = $chatMensagem->obtemAlertaMensagem($id, $usuarioLogado->getId());
                        if(isset($alertar[0]))
			$retorno['alertas'][$id] = ($alertar[0]->getAlerta())?$alertar[0]->getAlerta():'f';
                    endforeach;
		endif;

		echo json_encode($retorno);
		die();
		break;

            case 'inserir':
                $para = $this->getParam('para', '');
                $mensagem = strip_tags($this->getParam('mensagem', ''));
                if ($mensagem != ''):
                    $usuarioContato = new Aew_Model_Bo_Usuario();
                    $usuarioContato->setId($para);
                    $chat = new Aew_Model_Bo_ChatMensagens;
                    $chat->setMensagem($mensagem);
                    $usuarioLogado->insertChatMensagen($chat, $usuarioContato);
		endif;
		echo json_encode('');
		die();
		break;

            case 'bloquear':
                $chatDe = $this->getParam('usuario_de', '');
                $usuarioContato = new Aew_Model_Bo_Usuario();
                $usuarioContato->setId($chatDe);
                $chatStatus = $usuarioContato->selectChatStatus(1,0,$usuarioLogado);
                $status = 'bloqueado';
                
                if(!count($chatStatus)):
                    $chatStatus = new Aew_Model_Bo_ChatMensagensStatus();
                    $chatStatus->setIdDe($chatDe);
                    $chatStatus->setId_para($usuarioLogado->getId());
                    $status = 'inserido';
                endif;
                
                $chatStatus->setFlbloquear(true);
                $chatStatus->save();
                
                echo json_encode($status);
		die();
		break;

            case 'desbloquear':
                $chatDe = $this->getParam('usuario_de', '');
                $usuarioContato = new Aew_Model_Bo_Usuario();
                $usuarioContato->setId($chatDe);
                $chatStatus = $usuarioContato->selectChatStatus(1,0,$usuarioLogado);
                $chatStatus->setFlbloquear('FALSE');
                echo json_encode($chatStatus->update());
		die();
		break;
		
            case 'lidos':
                $usuario_de = $this->getParam('usuario_de','');
                $bo = new Aew_Model_Dao_ChatMensagens;
                $alterar_status = $bo->atualizaStatusMensagem($usuario_de,$usuarioLogado->getId());
                echo json_encode('');
                die();
                break;

            case 'ativar-chat':
                $status = $this->getParam('status', false);

                if($status):
                    $usuarioLogado->onlinechat();
                    $status = 'online-chat';
                else:
                    $usuarioLogado->offlinechat();
                    $status = 'offline-chat';
                endif;
                
                echo json_encode($status);
                die();
                break;

            case 'online':
                $status = $this->getParam('status', false);

                $usuarioLogado = $this->getLoggedUserObject();
                if($status):
                    $usuarioLogado->online();
                    $status = 'online';
                else:
                    $usuarioLogado->offline();
                    $status = 'offline';
                endif;
                
                echo json_encode($status);
                die();
                break;                
                
            case 'verificar':
		$ids = $this->getParam('ids','');
		$idm = $this->getParam('idm','');
                
		$retorno = array();
		$retorno['mensagens'] = '';
		$retorno['nao_lidos'] = '';
		$retorno['online']    = '';
		$retorno['tempo']     = '';
                
		$bo = new Aew_Model_Dao_ChatMensagens;
                
		if($ids != ''):
    
                    foreach($ids as $indice => $id):
                    
                        if(isset($idm[$indice]))
                            $selecionar = $bo->obtemMensagemChat($usuarioLogado->getId(), $id, $idm[$indice]);
                        else 
                            $selecionar = $bo->obtemMensagemChat($usuarioLogado->getId(), $id);
                        
			$i        = 0;
			$mensagem = '';
			$tempo    = 0;
			$id_de    = 0;
			$id_para  = 0;
			$tempo_segundo = 0;
                        
			foreach($selecionar as $ft):
                            $pos = strpos($ft->getUsuarioEscritorMensagem()->getPrimeiroNome(), ' ');
                            if($pos):
				$ft->getUsuarioEscritorMensagem()->setNome(substr($ft->getUsuarioEscritorMensagem()->getPrimeiroNome(),0,$pos));
                            endif;
                            
                            $conteudo = $ft->getMensagem();
                            
                            //--- Converte tags de imagens para emoticons
                            for($j=1; $j<=155; $j++):
				$buscar = ':'.$j.'#';
				$conteudo = str_replace($buscar,' <img src="/assets/img/chat/cleardot.gif" class="emoticon e'.$j.'"/> ', $conteudo);
                            endfor;
                            
                            $conteudo = preg_replace('!(\s|^)(:{1}[0-9]{1,6}#{1})!i',' <img src="/assets/img/cleardot.gif" class="emoticon e2"/> ', $conteudo." ");
                            
                            //--- Converte url em link
                            $conteudo = preg_replace('!(\s|^)((www\.)+[a-z0-9_./?=&-]+)!i',' <a class="link_conteudo_chat" target="_blank" href="http://$2" target="_blank">$2</a> ', $conteudo." ");
                            $conteudo = preg_replace('$(\s|^)(http://[a-z0-9_./?=&-]+)(?![^<>]*>)$i',' <a class="link_conteudo_chat" href="$2" target="_blank">$2</a> ', $conteudo." ");
                            $conteudo = preg_replace('$(\s|^)(https://[a-z0-9_./?=&-]+)(?![^<>]*>)$i',' <a class="link_conteudo_chat" href="$2" target="_blank">$2</a> ', $conteudo." ");
                            
                            //--- Converte url youtube a video
                            $buscar   = '%<a(.*)(?:href="https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v= ))([\w\-]{10,12})(?:)([\w\-]{0})\b%x';						
                            $replace  = '<div id="video_youtube">';
                            $replace .= '   <h5>Vídeo de YouTube</h5>';
                            $replace .= '   <a href="http://www.youtube.com/watch?v=$2" target="_blank">';
                            $replace .= '      <img src="http://i3.ytimg.com/vi/$2/hqdefault.jpg">';
                            $replace .= '   </a>';
                            $replace .= '</div>';
                            
                            $conteudo = preg_replace($buscar, $replace, $conteudo);
                            $formato = 'd/m/y g:i a';
                            $data = new DateTime($ft->getData());
                            if($ft->getTempoMinuto() != $tempo || $ft->getId_de() != $id_de || $ft->getId_para() != $id_para):
                                $origem =  ($ft->getId_de() != $usuarioLogado->getId() ? 'remetente' : 'destinatario');
                                $shadow =  ($ft->getId_de() != $usuarioLogado->getId() ? 'shadow-right' : 'shadow-left');
                                
                                $mensagem .= '<li class="'.$origem.'">';
                                $mensagem .= '<div class="box-mensagem '.$shadow.'">';
                                $mensagem .= '<span class="enviado">'.$data->format($formato).'</span><span class="usuario"><strong>'.$ft->getUsuarioEscritorMensagem()->getPrimeiroNome().'</strong> disse:</span>';
                            endif;
                            
                            $mensagem .= '<p>'.trim($conteudo).'</p>';
                            
                            $tempo   = $ft->getTempoMinuto();
                            $id_de   = $ft->getId_de(); 
                            $id_para = $ft->getId_para();
                            
                            if($ft->getTempoMinuto() != $tempo || $ft->getId_de() != $id_de || $ft->getId_para() != $id_para):
                                $mensagem .= '</div></li>';
                            endif;
                            
                            $i++;
                            $tempo_segundo = $ft->getTempoSegundo();
                        endforeach;
                        
                        $retorno['mensagens'][$id] = $mensagem;
                        $retorno['qtd_mensagens'][$id] = $i;
                        $retorno['tempo'][$id] = $tempo_segundo;

                    endforeach;
                endif;
				
                $verificar = $bo->obtemMensagemPendentes($usuarioLogado->getId());
                if(count($verificar) > 0):
                    foreach($verificar as $chat):
                    {
                        if(!isset($retorno['nao_lidos'][$chat->getId_de()]))
                        $retorno['nao_lidos'][$chat->getId_de()] = 0;
                        $retorno['nao_lidos'][$chat->getId_de()] = 1;
                    }
                    endforeach;
                endif;

                echo json_encode($retorno);
                die();
                break;

            case 'pendentes':
                $retorno = array();
                $retorno['nao_lidos'] = '';

                $bo = new Aew_Model_Dao_ChatMensagens;
                $pendentes = $bo->obtemMensagemPendentes($usuarioLogado->getId());
                if(count($pendentes) > 0):
                    foreach($pendentes as $user):
                        if(!isset($retorno['nao_lidos'][$ft->getUsuarioReceptor()->getId()]))
                            $retorno['nao_lidos'][$ft->getUsuarioReceptor()->getId()] = 0;

                        $retorno['nao_lidos'][$ft->getUsuarioReceptor()->getId()] = 1;
                    endforeach;
                endif;

                echo json_encode($retorno);
                die();
                break;

            case 'contatos':
                $retorno = array();
                $retorno['statuschat'] = '';
                $retorno['online'] = '';
                $retorno['bloqueados'] = '';
                $retorno['quantidade'] = 0;
                $retorno['contatos'] = '';
                $retorno['perfil'] = '';
                $filtro = $this->getParam('filtro','');
                $div = '';
                $div_perfil = '';
                
                //--- Atualiza status para online
                $bo = new Aew_Model_Bo_ChatMensagens();
                
                $usuarioContato = new Aew_Model_Bo_Usuario();
                $usuarioContato->setNome($filtro);
                $selecionar_usuarios = $bo->obtemUsuariosChat($usuarioLogado->getId(), $filtro);
                
                if(count($selecionar_usuarios) > 0):
                    
                    $inicio_naoativo = true;
                    foreach($selecionar_usuarios as $usuariochat):
                        //$gray = ($usuario['status'] == 1 ? false:true);
                        $feedcontagem = $usuariochat->getUsuarioEscritorMensagem()->selectFeedContagem();
                        if($feedcontagem)
                            $gray = ($usuariochat->getStatus() == 1 ? false : true);
                        
                        $link_foto_perfil  = $usuariochat->getUsuarioEscritorMensagem()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_90X90);
                        $link_foto_contato = $usuariochat->getUsuarioEscritorMensagem()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30, $gray, 36, 36, false, 'img-circle img-responsive');

                        $status = ($usuariochat->getStatus() && $usuariochat->getOnline() ? 'ativo':'nao_ativo');
                        if($status == 'nao_ativo' && $inicio_naoativo == true):
                            $status .= ' inicio_naoativo';
                            $inicio_naoativo = false;
                        endif;

                        $nome_usuario = $usuariochat->getUsuarioReceptor()->getNome();
                        if($filtro != ''):
                            $nome_usuario = str_replace($filtro,'<b>'.$filtro.'</b>',$nome_usuario);
                        endif;

                        if($usuariochat->getBloqueado()):
                            $status .= ' bloqueado';
                        endif;

                        $div_perfil  = '<div id="perfil_'.$usuariochat->getUsuarioReceptor()->getId().'" class="perfil shadow-right">';
                        $div_perfil .= '   <div class="quadro" class="desativado">'.$link_foto_perfil;
                        $div_perfil .= '      <h6 id="'.$usuariochat->getUsuarioReceptor()->getId().'" class="perfil cursor-pointer margin-all-05 text-center uppercase link-preto"><b>'.$nome_usuario.'</b></h6>';
                        $div_perfil .= '      <ul class="opcoes-menu-perfil">';
                        $div_perfil .= '         <li id="'.$usuariochat->getUsuarioReceptor()->getId().'" class="opcao-perfil fa fa-envelope cursor-pointer" title="recados" link="recado"></li>';
                        $div_perfil .= '         <li id="'.$usuariochat->getUsuarioReceptor()->getId().'" class="opcao-perfil fa fa-users cursor-pointer" title="colegas" link="colega"></li>';
                        $div_perfil .= '         <li id="'.$usuariochat->getUsuarioReceptor()->getId().'" class="opcao-perfil fa fa-comments-o cursor-pointer" title="comunidades" link="comunidade"></li>';
                        $div_perfil .= '         <li id="'.$usuariochat->getUsuarioReceptor()->getId().'" class="opcao-perfil fa fa-camera cursor-pointer" title="álbuns" link="album"></li>';
                        $div_perfil .= '     </ul>';
                        $div_perfil .= '   </div>';
                        $div_perfil .= '</div>';

                        $div .= '<li id="'.$usuariochat->getUsuarioReceptor()->getId().'" class="'.$status.' contatos">';
                        $div .= '<a class="contato'.(($usuariochat->getStatus() == true && $usuariochat->getOnline() == true) || $usuariochat->getBloqueado() == true ? ' comecar':'').'"  nome="'.$usuariochat->getUsuarioReceptor()->getPrimeiroNome().'" id="'.$usuariochat->getUsuarioReceptor()->getId().'">'.$link_foto_contato;

                        if($usuariochat->getStatus() && $usuariochat->getOnline()):
                            if($usuariochat->getBloqueado() == false):
                                $div .= '<span class="hidden-xs hidden-sd icone-dispositivo fa fa-'.$usuariochat->getNomedispositivo().'"><b>'.$nome_usuario.'</b></span>';
                            else:
                                $div .= '<span class="hidden-xs hidden-sd icone-dispositivo fa fa-ban link-vermelho">'.$nome_usuario.'</span>';
                            endif;
                        else:
                            $div .= '<span class="hidden-xs hidden-sd offline">'.$nome_usuario.'</span>';
                        endif;
                        
                        $div .= '</a></li>';

                        $retorno['perfil'][$usuariochat->getUsuarioReceptor()->getId()] = $div_perfil;
                        
                        if($usuariochat->getStatus() && $usuariochat->getOnline() == true && $filtro == ''):
                            $retorno['online'] .= $usuariochat->getUsuarioReceptor()->getId().'/';
                        endif;

                        if($usuariochat->getBloqueado() == true):
                            $retorno['bloqueados'] .= $usuariochat->getUsuarioReceptor()->getId().'/';
                        endif;
                        
                    endforeach;

                    if($filtro != ''):
                        $usuarios_online = $bo->obtemUsuariosChat($usuarioLogado->getId(), $filtro);
                        if(count($usuarios_online) > 0):
                            foreach($usuarios_online as $usuariochat):
                                if($usuariochat->getStatus()):
                                    $retorno['online'] .= $usuariochat->getUsuarioReceptor()->getId().'/';
                                endif;
                                if($usuariochat->getBloqueado() == true):
                                    $retorno['bloqueados'] .= $usuariochat->getUsuarioReceptor()->getId().'/';
                                endif;
                            endforeach;
                        endif;
                    endif;
                    
                endif;

                $retorno['quantidade'] = count($selecionar_usuarios);
                $retorno['contatos'] = $div;

                echo json_encode($retorno);
                die();
                break;

            case 'emoticons':
                $id = $this->getParam('janela','');
                $icones_incluir = array(51,67,2,3,5,6,8,9,10,11,12,13,18,19,21,23,24,25,27,29,44,48,49,50,54,38,31,93,72,124,33,58,94,78,81,84,88,95,96,97,155,101,98,107,60);

                $icone = 0;
                $contador_row = 1;
                $total_icone = count($icones_incluir);
                $div = '<table id="emoticons_'.$id.'" class="emoticons">';
                while($icone<$total_icone):
                    $j = 1;
                    while($j<=5 && $icone<$total_icone):
                        if($j == 1):
                            $classe = '';
                            if($contador_row == 5):
                                $classe = ' final';
                            endif;
                            if($contador_row == 6):
                                $div .= '<tr colspan="5" class="outros inicio"><td><hr/></td></tr>';
                            endif;
                            $div .= '<tr class="'.($contador_row < 6 ? 'padrao' : 'outros').$classe.'">';
                        endif;
                        $div .= '<td><a id=":'.$icones_incluir[$icone].'#" class="emoticon e'.$icones_incluir[$icone].'"></a></td>';
                        $icone++;
                        $j++;
                    endwhile;
                    $contador_row++;
                endwhile;
                $div .= '</table>';
                $div .= '<a id="mais_icones_'.$id.'" class="mais_icones" class="abrir">mais</a>';
                echo json_encode($div);
                die();
                break;

            case 'alertas':
                $alertas = $this->verificaAlerta($this->getParam('id',0));
                echo json_encode($alertas);
                die();
                break;
                
            default:
                echo json_encode('');
                die();

        endswitch;
    }
    /**
     * Verifica alertas de cada funcionalidade da aplicação
     * @param type $id
     * @return Array de objetos
     */
    private function verificaAlerta($id = 0)
    {   
        $usuario = $this->getLoggedUserObject();
        if(!$usuario)
        {
            return;
        }
        
        $url = array();           
        $url[1] = $this->view->url(array('controller' => 'recado','action' => 'listar','module' => 'espaco-aberto','usuario' => $usuario->getId()), null, true);
        $url[2] = $this->view->url(array('controller' => 'colega','action' => 'listar','module' => 'espaco-aberto','usuario' => $usuario->getId()), null, true);
        $url[3] = $this->view->url(array('controller' => 'comunidade','action' => 'listar','module' => 'espaco-aberto','usuario' => $usuario->getId()), null, true);
        $url[4] = $this->view->url(array('controller' => 'album','action' => 'listar','module' => 'espaco-aberto','usuario' => $usuario->getId()), null, true);
        $url[5] = $this->view->url(array('controller' => 'agenda','action' => 'listar','module' => 'espaco-aberto' , 'usuario' => $usuario->getId()), null, true);
        $url[6] = $this->view->url(array('controller' => 'blog','action' => 'listar','module' => 'espaco-aberto' , 'usuario' => $usuario->getId()), null, true);
        $contador = $usuario->selectFeedContagem();
        if ($id>0)
        {   
            $contador->limparContagem($id);
            $this->_redirect($url[$id]);
            return;
        }
        clearstatcache();
        $response = array();
        $response['recados']     = $contador->getQtdFeedRecados();
        $response['colegas']     = $contador->getQtdFeedColegas();
        $response['comunidades'] = $contador->getQtdFeedComunidades();
        $response['albuns']      = $contador->getQtdFeedAlbuns();
        $response['agenda']      = $contador->getQtdFeedAgenda();
        $response['blog']        = $contador->getQtdFeedBlog();
        if($usuario->isSuperAdmin())
        {
            $online = $contador->obtemUsuariosOnline();
            $response['online'] = $online[0]->conectados;
        }
        return $response;
     }
}
