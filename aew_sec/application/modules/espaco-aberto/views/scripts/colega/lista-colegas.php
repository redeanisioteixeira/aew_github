<?php
    if(count($this->colegas)):
        foreach ($this->colegas as $colega):
            $this->colega = $colega;
            echo $this->render('colega/colega.php');
        endforeach;
    endif;
?>