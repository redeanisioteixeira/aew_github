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
                        <p>O usuário <?php echo $this->faleconosco['nome'];?> entrou em contato realizando uma <b>denuncia</b>.</p>
                    <?php else:?>
                        <p>O usuário <?php echo $this->faleconosco['nome'];?> entrou em contato através do Fale Conosco.</p>
                    <?php endif?>
                        
                    <p>E enviou a seguinte mensagem:</p>
                    
                    <blockquote style="border-left:2px solid #BBBBBB;padding-left:10px;">
                        <b style="color: #2C2C2C;"><?php echo $this->faleconosco['mensagem'];?></b>
                    </blockquote>
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