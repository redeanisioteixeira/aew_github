<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <meta name="language" content="pt-BR"/>
        <meta http-equiv="Content-Language" content="pt-BR"/>
        <title>Ambiente Educacional Web - Secretaria da Educação do Estado da Bahia</title>

        <style type="text/css">
            body{
                font-family: Arial, Helvetica, "Trebuchet MS", sans-serif;
                font-size: 12px;
            }

            h1{
                font-size: 14px;
                border-bottom: 1px solid #CCCCCC;
                margin-bottom: 5px;
                background-image: url("<?php echo $url_path?>/img/img_topo_aew.png");
                background-repeat: no-repeat;
                background-position: right top; 
            }

            h2{
                text-align: center;
                font-size: 16px;
                margin: 0;
            }

            h3{
                font-size: 18px;
                color: #6F9FC3;
                margin: 0;
            }

            div.conteudo-digital{
                margin : 15px 0;
            }

            div.pagina-itens{
                float: left;
                width: 100%;
            }

            ul{
                list-style: none;
            }
            
            label{
                float: left;
                font-size: 14px;
                font-weight: bold;
            }

            p{
                float: left;
                font-size: 14px;
                width: 100%;
                text-align: justify;	
                color: #837B7B;
                margin: 0;
                margin-bottom: 5px;
                padding: 10px;
                word-break: break-word;
                background-color: #F3F3F3;
                border-radius: 4px;
            }

            a{
                color: dodgerblue;
                text-decoration: none;
            }

            table{
                position: absolute;
                display: table;
                border: solid 1px #eee;
                width:100%;
            }
            td, th {
                border: 1px solid #999;
                padding: 0.5rem;
            }
            table tr.center-text td{
                padding: 10px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="conteudo-digital">
            <h2>Informações sobre o relatório</h2>
            <div>
                <p><label>Usuario : </label> <?php echo $this->escape($this->usuario->getNome());?></p>
                <p><label>Data do relatório : </label> <?php echo $this->SetupDate($this->datetime(new Sec_Date()));?></p>
                <p><label>Número de conteúdos : </label> <?php echo count($this->conteudos);?></p>
            </div>
        </div>

        <div class='pagina-conteudo'>
            <table>
                <tr style="text-align:center;border-bottom: solid 1px #eee;">
                    <td>ID</td>
                    <td>TÍTULO</td>
                    <td>TAGS</td>
                    <td>COMPONENTES</td>
                    <td>LINK</td>
                </tr>
                <?php foreach($this->conteudos as $conteudo):?>
                <?php // SELECT PARA TAGS E COMPONENTES 
                    $conteudo->selectComponentesCurriculares();
                    $conteudo->selectTags(); ?>
                <tr>
                    <td><?php echo $conteudo->getId() ?></td>
                    <td><?php echo $this->escape($conteudo->getTitulo()) ?></td> 
                    <td><?php echo $this->showTags($conteudo->getTags());?></td>
                    <td><?php echo $this->showComponentes($conteudo->getComponentesCurriculares()) ?></td>
                    <td><a href="<?php echo $conteudo->getLinkPerfil(true) ?>" target="_blank">Link</a></td>
                </tr>
            <?php endforeach;?>
            </table>    
        </div>
    </body>
</html>
