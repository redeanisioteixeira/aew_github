<?php
$url_portal = str_replace('local.','',$this->baseUrl());
$url_portal = str_replace('desenv.','',$url_portal);
?>


<div style="background-color:#C9C9C9">
    
    <table align="center" border="0" cellpadding="20" cellspacing="0" height="auto" width="80%" style="background-color:#FAFAFA">

        <?php echo $this->render('_componentes/_email.cabecalho.php');?>

        <tr>
            <td colspan="4" style="background-image: url(<?php echo $url_portal;?>/assets/img/email/img_lock.png);background-repeat:no-repeat;background-position:50% 50%;">    

                <span style="font-family:Arial;font-size:14px;color:#777">
                    <p>Olá <b style="text-transform:capitalize"><?php echo $this->usuario->getNome();?></b>,</p>
                    <p>A senha da sua conta no <b>Ambiente Educacional Web</b> foi redefinida e você recebeu uma senha provisória.</p>
                    <p>Seus dados atuais para realizar login são agora:</p>
                    
                    <ul>
                        <li><label>Nome usuário : </label><b><?php echo $this->usuario->getUsername();?></b></li>
                        <li><label>Senha : </label><b><?php echo $this->senha;?></b></li>
                    </ul>
                    
                    <p>Após realizar o login, sugerimos alterar a su senha para uma mais adequada e de fácil memorização para você.</p>
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