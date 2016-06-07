<?php if (APPLICATION_ENV == "production"): ?>
<div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="text-center"><?php echo $this->message ?></h1>
            
        </div>
        <div class="panel-body">
            <img src="/assets/img/erro.png">
         
        <ul class="list-inline padding-all-30" >
            <li class="margin-all-10">
                <h3>
                    <a class="voltar" href="#" title="Clique aqui para voltar">
                        <i class="fa fa-chevron-circle-left"></i> 
                        Clique aqui para voltar
                    </a>
                </h3>    
            </li>
            <li class="margin-all-10">
                <h3>
                    <a href="/" title="Clique aqui para ir para a página inicial">
                        <i class="fa fa-home"></i> 
                        Clique aqui para ir para a página inicial (Home Page)
                    </a>
                </h3>    
            </li>
        </ul>
    </div>
</div>
<?php endif; ?>    
  

<?php if (APPLICATION_ENV == "development"):?>
<?php if(isset($this->exception)):?>

<div class="panel panel-danger">
    <div class="panel-heading">
        <h3 class="panel-title">Informações sobre a exceção:</h3>
    </div>
    <div class="panel-body">
        <ul class="list-unstyled">
            <li>
                <b>Tipo: </b>
                <code><?php echo get_class($this->exception); ?></code>
            </li>
            <li>
                <b>Código:</b> 
                <code><?php echo $this->exception->getCode() ?></code> 
            </li>
            <li>
                <b>Mensagem:</b> 
                <code><?php echo $this->exception->getMessage() ?></code>
            </li>
        </ul>

    </div>
</div>  
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Rastreamento da pilha:</h3></div>
    <div class="panel-body">
        <pre> 
            <code><?php echo $this->exception->getTraceAsString() ?></code>
        </pre>
    </div>
</div>
<?php endif; ?>
<?php if(isset($this->request)):?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Parâmetros do Request:</h3>
    </div>
    <div class="panel-body">
        <pre>
            <code><?php var_dump($this->request->getParams());?> </code>
        </pre>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>
    


