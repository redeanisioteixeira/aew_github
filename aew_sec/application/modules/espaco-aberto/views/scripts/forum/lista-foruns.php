<?php foreach($this->topicos as $topico):
    $this->topico = $topico;
    echo $this->render('forum/forum.php');
endforeach; ?>
