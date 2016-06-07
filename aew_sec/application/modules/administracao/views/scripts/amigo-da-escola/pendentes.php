<div class="acoes">
    <a class="btn btn-info" href="/administracao/amigo-da-escola/adicionar" title="Adicionar" ><i class="fa fa-plus"></i> Adicionar Amigo da Escola</a>
</div>
<?php if(count($this->pendentes)>0){?>
<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Indicado por</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($this->pendentes as $pendente):?>
        <tr>
            <td>
                <?php echo $pendente->getUsuarioCriado()->getNome(); ?>
            </td>
            <td>
                <?php echo $this->showUsuario($pendente->getUsuarioIndicou()) ?>
            </td>
            <td>
                <!--a class="btn btn-default btn-sm" href="/administracao/amigo-da-escola/editar"><i class="fa fa-edit"></i></a-->
                <?php $urlAprovar = $pendente->getUrlAprovar($this->usuarioLogado);
                      $urlReprovar = $pendente->getUrlReprovar($this->usuarioLogado);
                      if($urlAprovar){
                ?>
                <a class="btn btn-success btn-sm" href="<?php echo $pendente->getUrlAprovar($this->usuarioLogado)?>"><i class="fa fa-check"></i></a>
                      <?php } 
                    if($urlReprovar){ ?>
                    <a class="btn btn-danger btn-sm" href="<?php echo $urlReprovar ?>"><i class="fa fa-close"></i></a>
                <?php } ?>
            </td>
        </tr> 
        <?php endforeach;?>
    </tbody>
</table>
<?php } else{?>
    <div class="conteudo-margin">
    	Nenhum usuário encontrado.
    </div>
<?php } ?>