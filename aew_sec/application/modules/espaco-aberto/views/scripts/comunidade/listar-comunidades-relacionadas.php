<div id="comunidades-relacionadas">

    <?php echo $this->paginationControl($this->comunidadesRelacionadas,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#comunidades-relacionadas', 'divUrl' => $this->urlPaginatorRelacionadas));?>

    <ul class="list-unstyled">
        <?php
            foreach ($this->comunidadesRelacionadas as $comunidade):
                $comunidade->selectVotos();
                $this->comunidade = $comunidade;
                echo $this->render('comunidade/comunidade.php');
            endforeach;
        ?>
    </ul>
    
    <?php echo $this->paginationControl($this->comunidadesRelacionadas,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#comunidades-relacionadas', 'divUrl' => $this->urlPaginatorRelacionadas));?>
    
</div>