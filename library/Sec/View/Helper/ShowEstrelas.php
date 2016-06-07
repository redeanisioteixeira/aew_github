<?php
class Sec_View_Helper_ShowEstrelas
{
    protected $view;
    protected $id;
    protected $ativar;
    protected $canal;

    public function setView($view)
    {
        $this->view = $view;
    }
    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getCanal() {
        return $this->canal;
    }

    function setCanal($canal) {
        $this->canal = $canal;
    }

    function getAtivar() {
        return $this->ativar;
    }

    function setAtivar($ativar) {
        $this->ativar = $ativar;
    }

    private function getLinkVoto($numeroEstrela)
    {
        $module = "";
        $controller = "";
        switch ($this->getCanal()):
            case "ambientes-de-apio":
                $module = "ambientes-de-apio";
                $controller = "ambiente";
                break;
                
            case "conteudos-digitais":
                $module = "conteudos-digitais";
                $controller = "conteudo";
                break;
            
            case "espaco-aberto":
                $module = 'espaco-aberto';
                $controller = 'comunidade';
                break;

            default:
                $module = "conteudos-digitais";
                $controller = "conteudo"; 
        endswitch;

        $link = $this->view->url(array('module' => $module, 'controller' => $controller, 'action' => 'votar', 'voto' => $numeroEstrela));

        return $link;
    }

    private function getTagEstrela($estado, $numeroEstrela, $nota)
    {
        if(!$nota)
            $nota = 0;

        $usuario = Sec_Controller_Action::getLoggedUserObject();
        if($usuario):
            $this->view->headScript()->appendFile('/assets/js/plugins/estrelas/estrelas.js','text/javascript');
        endif;
        
        $icone = ($estado == "on" ? "star" : "star-o");
        switch ($this->getCanal()):
            case "ambientes-de-apoio":
                $cor = "laranja";
                break;

            case "conteudos-digitais":
                $cor = "azul";
                break;

            case "sites-tematicos":
                $cor = "vermelho";
                break;

            case "tv-anisio-teixeira":
                $cor = "marron";
                break;

            case "espaco-aberto":
                $cor = "verde";
                break;
            
            default:
                $cor = "cinza-escuro";
                break;
            
        endswitch;

        if ($this->getId() && $usuario && $this->getAtivar()):
            $link = $this->getLinkVoto($numeroEstrela);

            $tag  = "<li id-star-rating='".$this->getId()."' class='avaliacao margin-none padding-all-02' avaliacao='$numeroEstrela' nota='$nota'>";            
            $tag .= "<a rel='$link' class='link-action'>";
            $tag .= "   <i avaliacao='$numeroEstrela' nota='$nota' tipo='".$this->getCanal()."' class='avaliacao estrela estrela_nota_$numeroEstrela link-$cor cursor-pointer fa fa-$icone'></i>";            
            $tag .= "</a>";
        else:
            $tag  = "<li class='margin-none padding-all-02'>";
            $tag .= "<i class='link-$cor fa fa-$icone'></i>";
        endif;
        
        $tag .= "</li>";
        
        return $tag;
    }

    /**
     * @param Aew_Model_Bo_ItemPerfil $avaliado
     * @param string $canal
     * @param boolean $ativar
     * @return string
     */
    public function ShowEstrelas($avaliado, $canal = null, $ativo = true)
    {
        $canal = (!$canal ? $this->view->getModule() : $canal);

        if(!is_object($avaliado) && is_numeric($avaliado)):
            $votacao = $avaliado;
            $avaliado = new Aew_Model_Bo_ConteudoDigital();
            $avaliado->setAvaliacao($votacao);
            $ativo = false;
        else:
            if(!$avaliado instanceof Aew_Model_Bo_ConteudoDigitalCategoria):
                $this->setId($avaliado->getId());
            endif;
            
            if($avaliado instanceof Aew_Model_Bo_ConteudoDigital):
                if($avaliado->getFlSiteTematico()):
                    $canal = "sites-tematicos";
                endif;
            endif;
        endif;

        $this->setCanal($canal);
        $this->setAtivar($ativo);
        
        if($this->getId()):
            $estrelas = "<ul id='".$this->getId()."' nota='".$avaliado->getAvaliacao()."' class='avaliacao list-inline list-unstyled text-center'>";
        else:
            $estrelas = "<ul class='avaliacao list-inline list-unstyled text-center'>";
        endif;

        for ($i = 1; $i <= 5; $i++):
            $status = ($i <= $avaliado->getAvaliacao() && $avaliado->getAvaliacao() != 0 ? "on" : "off");
            $estrelas .= $this->getTagEstrela($status, $i, $avaliado->getAvaliacao());
        endfor;

        $estrelas .= "</ul>";
            
        return $estrelas;
    }
}