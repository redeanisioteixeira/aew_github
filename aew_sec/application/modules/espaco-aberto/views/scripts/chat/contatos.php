<?php
$i = count($this->chats);
foreach ($this->chats as $chat)
{
    $inicio_naoativo = true;
    $gray = ($chat->getUsuarioContato()->selectFeedContagem()->getFlchatativo() == 1 ? false : true);
    $status = ($chat->getUsuarioContato()->getFeedContagem()->getFlchatativo() == 1 ? 'ativo' : 'nao_ativo');
    $comecar = ($chat->getUsuarioContato()->getFeedContagem()->getFlchatativo()==1 || $chat->getFlbloquear() == true ? ' comecar' : '');
    if ($status == 'nao_ativo' && $inicio_naoativo == true)
    {
        if ($i > 0)
        {
            $status .= ' inicio_naoativo';
        }
        $inicio_naoativo = false;
    }
    if ($chat->getFlbloquear() == true)
    {
        $status .= ' bloqueado';
    }
    ?>
    <li id="<?php echo $chat->getUsuarioContato()->getId()?>" class="<?php echo $status ?> contatos">
        <div id="perfil_<?php echo $chat->getUsuarioContato()->getId()?>" class="perfil">
            <div class="quadro">
                <?php echo  $chat->getUsuarioContato()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_90X90)?>
                <h5 id="<?php echo $chat->getUsuarioContato()->getId()?>" class="perfil">
                    <strong><?php echo  $chat->getUsuarioContato()->getPrimeiroNome() ?></strong>
                </h5>
                <table class="opcoes-menu-perfil" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="recado">
                            <a href="<?php echo $chat->getUsuarioContato()->getUrlListaRecados()?>" class="opcao-perfil" title="recados" link="recado"></a>
                        </td>
                        <td class="colega">
                            <a href="<?php echo  $chat->getUsuarioContato()->getUrlListaColegas() ?>" class="opcao-perfil" title="colegas" link="colega"></a>
                        </td>
                        <td class="comunidade">
                            <a href="<?php echo $chat->getUsuarioContato()->getUrlListaComunidades()?>" class="opcao-perfil" title="comunidades" link="comunidade"></a>
                        </td>
                        <td class="album">
                            <a href="<?php echo $chat->getUsuarioContato()->getUrlListaAlbuns()?>" class="opcao-perfil" title="álbuns" link="album"></a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <a class="contato <?php echo  $comecar ?>"  nome="<?php echo  $chat->getUsuarioContato()->getNome()?>" >
            <?php echo $chat->getUsuarioContato()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30, $gray);?>
            <span> <strong><?php echo  $chat->getUsuarioContato()->getPrimeiroNome() ?></strong> </span>
        </a>    
    </li>
<?php } ?>