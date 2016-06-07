<?php
$url_portal = str_replace('local.','',$this->baseUrl());
$url_portal = str_replace('desenv.','',$url_portal);
?>

<div style="background-color:#C9C9C9">
    
    <table align="center" border="0" cellpadding="20" cellspacing="0" height="auto" width="80%" style="background-color:#FAFAFA">

        <?php echo $this->render('_componentes/_email.cabecalho.php');?>

        <tr>
            <td colspan="4" style="background-image: url(<?php echo $url_portal;?>/assets/img/email/img_check.png);background-repeat:no-repeat;background-position:50% 50%;padding:30px">

                <span style="font-family:Arial;font-size:14px;color:#777">
                    <p>Olá <b style="text-transform:capitalize"><?php echo $this->usuario->getNome();?></b>,</p>
                    
                    <?php if($this->aprovar):?>
                        <p>O seu conteúdo digital <b><?php echo $this->escape($this->conteudo->getTitulo());?></b> cadastrado no <b>Ambiente Educacional Web</b> foi aprovado por nossos administradores. Para acessar a este clique <a href="<?php echo $this->conteudo->getLinkPerfil(true);?>"><b>aqui</b></a>.</p>
                    <?php else:?>
                        <p>O seu conteúdo digital <b><?php echo $this->escape($this->conteudo->getTitulo());?></b> cadastrado no <b>Ambiente Educacional Web</b> não foi aprovado pelos administradores do site por acharem ele fora do contexto do ambiente.</p>
                    <?php endif;?>    
                </span>

                <span style="font-family:Arial;font-size:14px;color:#333">
                    Saudações Colaborativ@s,<br>
                    <b>Comitê Gestor do Ambiente Educacional Web</b>
                </span>
            </td>
        </tr>

        <?php echo $this->render('_componentes/_email.rodape.php');?>
        
    </table>
    
</div>