<div class="col-lg-12 box overflow-visible padding-all-02">
    <div class="input-group">
        <span class="input-group-addon menu-cinza">
            <i id="iconBusca" class="fa fa-search"></i>
        </span>
        <input id="filtrar-busca" class="form-control search-input" placeholder="buscar colegas, comunidades, blogs e outros..." maxlength="200" rel="/espaco-aberto/buscar/listar" idloadcontainer="resultado-busca" value='<?php echo $this->filtro;?>' icon="#iconBusca">
    </div>
    
    <ul id="resultado-busca" class="notifications-menu desativado absolute list-unstyled box margin-top-05 shadow"></ul>
</div> 