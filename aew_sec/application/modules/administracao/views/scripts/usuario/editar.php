<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-lg-3">
            <?php echo $this->usuario->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_160X160,false,0, 200,false, 'thumbnail shadow-center margin-auto');?>
        </div>    
        <div class="col-lg-9">
            <?php echo $this->editar;?>
        </div>
    </div>
</div>
