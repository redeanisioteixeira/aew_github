<?php
if(!count($this->redesSociais)):
    return;
endif;
?>

<hr>
<div class="clearfix pull-right text-center link-cinza-escuro">
    <b class="clearfix">Siga-me</b>
    <?php foreach($this->redesSociais as $rede):?>
        <?php if($rede->getRede() != 'orkut'):?>
            <a class="padding-none" href="<?php echo $rede->getUrl();?>" alt="<?php echo $rede->getRede();?>" target="_blank">
                <span class="fa-stack fa-2x trans40">
                  <i class="link-verde fa fa-square fa-stack-2x"></i>
                  <i class="link-branco fa fa-<?php echo $rede->getRede();?> fa-stack-1x"></i>
                </span>                
            </a>
        <?php endif;?>
    <?php endforeach;?>   
</div>