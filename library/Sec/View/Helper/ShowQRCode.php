<?php
class Sec_View_Helper_ShowQRCode
{
    protected $view;

    public function setView($view)
    {
            $this->view = $view;
    }

    public function ShowQRCode($texto_conteudo, $tamanho, $id = null)
    {	
        $alt    = "Código de barras QR";
        $classe = 'qr-code shadow img-rounded';
        $existe = false;

        if($id != null):
            $arquivo = Aew_Model_Bo_ConteudoDigital::getQRCodeDirectory()."/$id.png";
            if(file_exists($arquivo)):
                $existe = true;
                
                $arquivo = Aew_Model_Bo_ConteudoDigital::getQRCodeUrl()."/$id.png";
                $chart = sprintf('<img class="%s" src="%s" alt="%s" width="%spx" height="%spx" />', $classe, $arquivo, $alt, $tamanho['height'], $tamanho['width']);
            endif;
        endif;

        if($existe == false):
            $chart = new Sec_GoogChart();

            $data = array('conteudo' => $texto_conteudo);

            $chart->setChartAttrs(
            array(
                'type'   => 'qr-code',
                'title'  => $alt,
                'data'   => $data,
                'size'   => array($tamanho['height'],$tamanho['width']),
                'classe' => $classe,
                'chld'   => 'M|0',
                'save'   => $id,
                'path'   => Aew_Model_Bo_ConteudoDigital::getQRCodeDirectory()
            ));
        endif;

        return $chart;
    }
}