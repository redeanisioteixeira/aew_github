<?php
$url_portal = str_replace('local.','',$this->baseUrl());
$url_portal = str_replace('desenv.','',$url_portal);
?>


<div style="background-color:#C9C9C9">
    
    <table align="center" border="0" cellpadding="20" cellspacing="0" height="auto" width="80%" style="background-color:#FAFAFA">

        <?php echo $this->render('_componentes/_email.cabecalho.php');?>
        
        <tr>
            <td colspan="4" style="background-image: url(<?php echo $url_portal;?>/assets/img/email/img_users.png);background-repeat:no-repeat;background-position:50% 50%;">

                <span style="font-family:Arial;font-size:14px;color:#777">
                    <p>Olá <b style="text-transform:capitalize"><?php echo $this->nomeUsuario;?></b>,</p>
                    <p>Você acaba de solicitar uma nova senha de acesso ao <b>Ambiente Educacional Web</b>.</p>
                    <p>Caso você não tenha feito esta solicitação, por favor ignore este e-mail.</p>
                    <p>Para confirmar a solicitação, clique no link abaixo ou copie no seu navegador preferido, e uma nova senha será gerada e enviada para seu e-mail:</p>
                    <p><a href="<?php echo $this->urlRecuperar;?>" style="font-size:14px;font-weight:bold;color:#0087DA;"><?php echo $this->urlRecuperar;?></a></p>
                    <p>Sempre que precisar entre em contato conosco, estaremos a sua disposição!.</p>
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