<?php foreach($this->fotos as $foto): ?>
<section>
    <figure >
        <img class="thumbnail" src="<?php echo $foto->getUrl() ?>">
        <figcaption>
            <span><?php echo $foto->getLegenda() ?></span>
            <span><?php echo $foto->getDataCriacao(); ?></span>
        </figcaption>
        <figcaption>
            <a class="btn btn-default"
               data-toggle="tooltip"
               data-placement="auto"
               href="#editar">
                <i class="fa fa-edit"></i>
            </a>
        </figcaption>
    </figure>
</section>
<?php endforeach;?>

