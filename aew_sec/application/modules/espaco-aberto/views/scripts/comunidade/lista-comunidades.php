<?php
    foreach ($this->comunidades as $comunidade):
        $comunidade->selectVotos();
        $this->comunidade = $comunidade;
        echo $this->render('comunidade/comunidade.php');
    endforeach;
?>