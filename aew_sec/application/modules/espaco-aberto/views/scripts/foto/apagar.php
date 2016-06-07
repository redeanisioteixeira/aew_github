<?php ?>


    <div class="box">
    Tem certeza que deseja apagar a foto com a legenda 
    "<b><?php echo $this->escape($this->objeto->getLegenda()); ?></b>"?
    <br>
    <?php echo $this->form; ?>
    </div>
</section>