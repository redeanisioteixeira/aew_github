<?php
$meses = array("JAN","FEV","MAR","ABR","MAI","JUN","JUL","AGO","SET","OUT","NOV","DEZ");
$imagens = array();
$fonte = "img/home/bg";
$i=0;

$imagens = glob($fonte."/$mes_atual/*.jpg");
if(count($pasta) == 0):
    $imagens = glob($fonte."/Geral/*.jpg");
endif;
die();
$total_pasta = count($imagens);
shuffle($imagens);
?>

<?php if($total_pasta):?>
    <section class='slides-fotos'>
        <div id="maximage">
            <?php
                for($i = 0; $i < $total_pasta; $i++):
                    echo $imagens[$i]; die();
                    $credito_foto = new Aew_Model_Bo_Foto($imagens[$i]);
                    $credito_foto->selectMetaDados();
                    print_r($credito_foto);
                    $texto_credito = "";	
                    if($credito_foto->getTitulo() != "" || $credito_foto->getArtista() != "" || $credito_foto->getComentarios() != ""):
                        $texto_credito .= ($credito_foto['title']    == "" ? "" : ucfirst($credito_foto->getTitulo())." ");
                        $texto_credito .= ($credito_foto['comments'] == "" ? "" : "<br/>".ucfirst($credito_foto->getComentarios())." ");
                        $texto_credito .= ($credito_foto['artist']   == "" ? "" : "por <b>".$credito_foto->getArtista()."</b>");
                    endif;

                    if($texto_credito != ""):
                        echo "<img src='/".$imagens[$i]."' tooltip='".$texto_credito."' />";
                    else:
                        echo "<img src='/".$imagens[$i]."'/>";
                    endif;
                endfor;
            ?>
        </div>

        <div id="maximage-buttom">
            <a href="" id="arrow_left"><div id="img_arrow_left"></div></a>
            <a href="" id="arrow_right"><div id="img_arrow_right"></div></a>
        </div>
    </section>
<?php endif;?>
