<?php ?>


    <div class="box">
        Tem certeza que deseja apagar o tópico 
        "<b><?php echo $this->escape($this->objeto->getTitulo()); ?></b>"?
        <br/>
        <?php echo $this->form; ?>
    </div>    
</section>