<?php
$url_portal = str_replace('local.','',$this->baseUrl());
$url_portal = str_replace('desenv.','',$url_portal);
?>


<div style="background-color:#C9C9C9">
    
    <table align="center" border="0" cellpadding="20" cellspacing="0" height="auto" width="80%" style="background-color:#FAFAFA">

        <?php echo $this->render('_componentes/_email.cabecalho.php');?>
        
        <tr>
            <td colspan="4" style="background-image: url(<?php echo $url_portal;?>/assets/img/email/img_users.png);background-repeat:no-repeat;background-position:50% 50%;">

                <span style="font-family:Arial;font-size:16px;color:#777">
                    
                    <?php if($this->denunciar):?>
                        <p>Recebemos sua mensagem. Assim que verificarmos as informações relacionadas a sua denuncia, entraremos em contato através do e-mail fornecido.</p>
                    <?php else:?>
                        <p>Recebemos sua mensagem no Fale Conosco. Assim que verificarmos as informações que você enviou, entraremos em contato através do e-mail fornecido.</p>
                    <?php endif;?>
                        
                    <p>Agradecemos o seu contato.</p>
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