<?php echo $this->paginationControl($this->comunidadesRelacionar,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#lista-comunidade-relacionar', 'divUrl' => $this->urlPaginator));?>

<ul class="list-unstyled">
    <?php
        foreach ($this->comunidadesRelacionar as $comunidade):
            $comunidade->selectVotos();

            $this->adicionarRelacao = true;
            $this->comunidade = $comunidade;
            echo $this->render('comunidade/comunidade.php');
        endforeach;
    ?>
</ul>

<?php echo $this->paginationControl($this->comunidadesRelacionar,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#lista-comunidade-relacionar', 'divUrl' => $this->urlPaginator));?>