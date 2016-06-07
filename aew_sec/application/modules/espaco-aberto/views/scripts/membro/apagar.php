<?php ?>

    <div class="box">
        Tem certeza que deseja remover o membro 
        "<b><?php echo $this->escape($this->objeto->getNome()); ?></b>"?
        <br/>
        <?php echo $this->form; ?>
    </div>
</section>