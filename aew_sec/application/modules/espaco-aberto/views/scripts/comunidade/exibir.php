<section class="col-lg-12">
    <?php echo $this->render('_componentes/_sobremim.php');?>
</section>

<section class="col-lg-12">
    <?php echo $this->render('perfil/listar-favoritos.php');?>
</section>

<section class="col-lg-12 col-md-12">
    <div class="margin-bottom-10">
        <?php echo $this->render('comunidade/sugerir.php');?>
    </div>
    
    <div class="row">
        
        <div class="itens-isotope">

            <?php if(count($this->membros)):?>
                <!-- MEMBROS -->
                <div class="col-lg-6 margin-bottom-10">

                    <?php if(count($this->membros)>0):?>
                        <span class="box-badge absolute absolute-right margin-top-05-">
                            <span class="fa fa-users badge badge-bottom"> <?php echo count($this->membros);?></span>
                        </span>
                    <?php endif;?>

                    <header>
                        <a href="/espaco-aberto/membro/listar/comunidade/<?php echo $this->usuarioPerfil->getId();?>" title="Ver todos membros">
                            <h5 class="headline-ea link-verde"><b><i class="fa fa-users"></i> Membros da comunidade</b></h5>
                        </a>
                    </header>    

                    <div class="box">
                        <ul class="list-unstyled">
                            <?php $i = 1;?>
                            <?php foreach($this->membros as $membro):?>
                                <?php $membro->selectFotoPerfil();?>
                            
                                <li class="padding-bottom-05 padding-top-05 border-bottom" data-user-active="<?php echo $membro->getFlativo();?>">
                                    <a class="media" href="<?php echo $membro->getLinkPerfil();?>">
                                        <figure class="media-left">
                                            <img class="img-circle shadow-center" src="<?php echo $membro->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_90X90,false,90,90,true);?>" width="30" height="30">
                                        </figure>

                                        <span class="media-body middle text-capitalize" alt ="Exibir perfil de <?php echo $membro->getNome()?>" title ="Exibir perfil de <?php echo $membro->getNome();?>">
                                            <?php echo strtolower($membro->getNome());?>
                                        </span>
                                    </a>                        
                                </li>

                                <?php if($i++ > 9) break;?>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            <?php endif;?>

            <?php if(count($this->topicos)):?>
                <!-- FÓRUM -->
                <div class="col-lg-6 margin-bottom-10">
                    <?php if(count($this->topicos)>6):?>
                        <span class="box-badge absolute absolute-right margin-top-05-">
                            <span class="fa fa-commenting badge badge-bottom"> <?php echo count($this->topicos);?></span>
                        </span>
                    <?php endif;?>

                    <header>
                        <a href="<?php echo $this->comunidade->getUrlListaForum();?>" title="Ver todos tópicos">
                            <h5 class="headline-ea link-verde"><b><i class="fa fa-commenting"></i> Tópicos do Fórum</b></h5>
                        </a>
                    </header>

                    <div class="box">
                        <ul class="simbols">
                            <?php $i = 1;?>
                            <?php foreach($this->topicos as $topico): ?>
                                <li class="simbols padding-bottom-05 padding-top-05 border-bottom">
                                    <a href="<?php echo $topico->getUrl();?>"><?php echo $topico->getTitulo();?></a>
                                </li>

                                <?php if($i++ > 9) break;?>

                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            <?php endif;?>

            <?php if(count($this->blogs)):?>
                <!-- BLOG -->
                <div class="col-lg-6 margin-bottom-10"> 

                    <?php if(count($this->blogs)>6):?>
                        <span class="box-badge absolute absolute-right margin-top-05-">
                            <span class="fa fa-rss-square badge badge-bottom"> <?php echo count($this->blogs);?></span>
                        </span>
                    <?php endif;?>

                    <header>
                        <a href="/espaco-aberto/blog/listar/comunidade/<?php echo $this->usuarioPerfil->getId() ?>" title="Ver todos os artigos">
                            <h5 class="headline-ea link-verde"><b><i class="fa fa-rss-square"></i> Blogs</b></h5>
                        </a> 
                    </header>

                    <div class="box">
                        <ul class="simbols">
                            <?php $i = 1;?>
                            <?php foreach($this->blogs as $blog):?>
                                <li class="simbols padding-bottom-05 padding-top-05 border-bottom">
                                    <a href="<?php echo  $blog->getLinkPerfil();?>"><?php echo $blog->getTitulo();?></a>
                                </li>

                                <?php if($i++ > 9) break;?>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            <?php endif;?>
                
        </div>
        
        <?php if(count($this->comunidadesRelacionadas)):?>
            <!-- COMUNIDADES RELACIONADAS -->
            <div class="col-lg-12">

                <?php if(count($this->comunidadesRelacionadas)>10):?>
                    <span class="box-badge absolute absolute-right margin-top-05-">
                        <span class="fa fa-comments-o badge badge-bottom"> <?php echo count($this->comunidadesRelacionadas);?></span>
                    </span>
                <?php endif;?>

                <header>
                    <a href="<?php echo '/espaco-aberto/comunidade/listar-comunidades-relacionadas/comunidade/'.$this->usuarioPerfil->getId();?>" title="Ver todas comunidades relacionadas">
                        <h5 class="headline-ea link-verde"><b><i class="fa fa-comments-o"></i> Comunidades relacionadas</b></h5>
                    </a> 
                </header>

                <div class="col-lg-12 box">
                    <ul class="list-unstyled itens-isotope">
                        <?php $i = 1;?>
                        <?php foreach ($this->comunidadesRelacionadas as $relacionada): ?>

                            <li class="col-lg-6 padding-bottom-05 padding-top-05 border-bottom">
                                <div class="row"> 
                                    <figure class="col-lg-3">
                                        <a  href="<?php echo $relacionada->getLinkPerfil();?>">
                                            <img class="img-responsive img-rounded shadow-center" src="<?php echo $relacionada->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_90X90,false,90,90,true);?>">
                                        </a>
                                    </figure>

                                    <div class="col-lg-9">
                                        <div class="row">
                                            <h6 class="page-header"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($relacionada->getDataCriacao());?> por <b><?php echo $this->ShowUsuario($relacionada->getUsuario());?> </b></small></h6>
                                            <h5 class="link-verde"><b><?php echo $relacionada->getNome();?></b></h5>
                                            <p><?php echo $this->readMore($relacionada->getDescricao(), 200, null, null, $relacionada->getLinkPerfil(),'success');?></p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                            <?php if($i++ > 9) break;?>

                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        <?php endif;?>
                
        <?php if(count($this->comunidadesRelacionadasTag)):?>   
            <!-- COMUNIDADES RELACIONADAS -->
            <div class="col-lg-12">

                <?php if(count($this->comunidadesRelacionadasTag)>10):?>
                    <span class="box-badge absolute absolute-right margin-top-05-">
                        <span class="fa fa-comments-o badge badge-bottom"> <?php echo count($this->comunidade->selectComunidadesRelacionadasTag());?></span>
                    </span>
                <?php endif;?>

                <header>
                    <h5 class="headline-ea link-verde"><b><i class="fa fa-comments-o"></i> Outras Comunidades relacionadas</b></h5>
                </header>

                <div class="col-lg-12 box">
                    <ul class="list-unstyled itens-isotope">
                        <?php $i = 1;?>
                        <?php foreach ($this->comunidadesRelacionadasTag as $relacionada): ?>

                            <li class="col-lg-6 padding-bottom-05 padding-top-05 border-bottom">
                                <div class="row">
                                    <figure class="col-lg-3">
                                        <a href="<?php echo $relacionada->getLinkPerfil();?>">
                                            <img class="img-responsive img-rounded shadow-center" src="<?php echo $relacionada->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_90X90,false,90,90,true);?>" width="48" height="48">
                                        </a>
                                    </figure>

                                    <div class="col-lg-9">
                                        <div class="row">
                                            <h6 class="page-header"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($relacionada->getDataCriacao());?> por <b><?php echo $this->ShowUsuario($relacionada->getUsuario());?> </b></small></h6>
                                            <h5 class="link-verde"><b><?php echo $relacionada->getNome();?></b></h5>
                                            <p><?php echo $this->readMore($relacionada->getDescricao(), 200, null, null, $relacionada->getLinkPerfil(),'success');?></p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <?php if($i++ > 9) break;?>

                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        <?php endif;?>
        
    </div>
</section>        
