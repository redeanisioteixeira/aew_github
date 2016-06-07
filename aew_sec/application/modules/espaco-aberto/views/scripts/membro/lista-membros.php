<?php
    foreach($this->membros as $membro):
        $colega = new Aew_Model_Bo_Usuario();
        $colega->setId($membro->getIdusuario());
        $this->colega = $colega->select(1);
        
        $this->membro = $membro;
        $this->removerMembro = true;
        echo $this->render('colega/colega.php');
    endforeach;
?>