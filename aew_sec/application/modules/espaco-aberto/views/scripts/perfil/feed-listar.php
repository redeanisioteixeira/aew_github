<?php foreach($this->feeds as $feed):?>
    <li class="media box feedBusca margin-none margin-bottom-05" id="feed<?php echo $feed->getId() ?>" name="feed<?php echo $feed->getId() ?>" >
        <figure class="media-left">
            <a href="<?php echo $feed->getUsuarioremetente()->getLinkPerfil() ?>">
                <img class="img-circle shadow-center media-object" src="<?php echo $feed->getUsuarioremetente()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30,false,30,30,true);?>" data-toggle="tooltip" data-placement="top" title="ver perfil" width="48" height="48"> 
            </a>
        </figure>    

        <div class="media-body">
            <p><?php echo mb_strtolower( $feed->getMensagem(),'UTF-8');?></p>
        </div>
        
        <hr>
        
        <h6 class="margin-none text-right"><small><i class="fa fa-calendar"></i> <?php echo $this->SetupDate($feed->getDataCriacao());?></small></h6>
    </li>
<?php endforeach; ?>
