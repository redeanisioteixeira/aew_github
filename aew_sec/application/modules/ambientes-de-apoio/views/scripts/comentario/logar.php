<?php
echo $this->showMessages();
if($this->usuarioLogado): 
    echo $this->render('/usuario/box-list-comentarios.php');
endif;
?>
