<?php
	if(!Sec_TipoDispositivo::isDesktop()):
		return;
	endif;

    define("CPATH",dirname(__FILE__)."/");
    $slider = glob(CPATH."slider*.php");
    if(!count($slider)):
        return;
    endif;

    $slider = str_replace(CPATH, "home/slider/", $slider);
    shuffle($slider);
?>

<?php $this->placeholder('slidePrincipal')->captureStart();?>

    <!-- Slider -->
    <section class="slides hidden-sm hidden-xs">
        <div id="slider-main" class="slider-wrapper" bgcolor="default">

            <div class="slider-control _prev"></div>
            <div class="slider-control _next"></div>

            <div class="responisve-container">
                <div class="slider">
                    <?php foreach($slider as $key=>$value):?>
                        <?php echo $this->render($value);?>
                    <?php endforeach;?>
                
                    <div class="fs_loader"></div>
                    
                </div>
            </div>
            
            <div class="go-main hidden-sm hidden-xs absolute">
                <a class="scroll_nag" href="#box-busca-geral"><i class="fa fa-chevron-down fa-3x"></i></a>
            </div>

        </div>
    </section>
    
<?php $this->placeholder('slidePrincipal')->captureEnd();?>
