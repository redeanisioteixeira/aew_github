<?php foreach($this->rssPw as $entrada):?>
    <div class="slide" color="menu-roxo">
        <img class="img-rounded img-responsive shadow" src="<?php echo $entrada["img"];?>" data-position="20,-60" data-in="right" data-delay="50" width="50%">
        <div data-position="60,380" data-in="top" data-step="1">
            <h2 class="menu-roxo text-shadow"><b><?php echo $entrada["title"];?></b></h2>
        </div>
        <div data-position="180,380" data-in="bottom" data-step="2" data-delay="100"><p class="link-branco" style="font-size: 18px"><?php echo $this->readMore($entrada["description"], 400);?></p></div>
        <div data-position="340,380" data-in="bottom" data-step="2" data-delay="150"><a class="btn btn-lg menu-roxo" href="<?php echo $entrada["link"]?>" role="button" style="width: 100%;">Leia mais</a></div>
    </div>
    <?php break;?>
<?php endforeach;?>
