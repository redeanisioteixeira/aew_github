<?php

class Sec_View_Helper_ShowBreadCrumb
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function ShowBreadCrumb($pagina = "", $paginaPai = "")
    {
        $arrTitle = array();
        $arrTituloModulos =  array(
                                    "conteudos-digitais" => "Conteúdos Digitais",
                                    "sites-tematicos" => "Sites Temáticos",
                                    "ambientes-de-apoio" =>"Apoio a Produção e Colaboração",
                                    "aew" => "",
                                    "usuario" => "Usuários",
                                    "administracao" => "Área de Administração",
                                    "espaco-aberto" => "Espaço Aberto",
                                );

        $arrTituloControl =  array(
                                    "home" => "",
                                    "conteudos" => "",
                                    "conteudo" => "",
                                    "licenca" => "Licenças",
                                    "formato" => "Formatos de arquivos",
                                    "tag" => "Glosário",
                                    "programas" => "Programas da TV Anísio Teixeira",
                                    "perfil" => "",
                                    "feed" => "",
                                    "colega" => "Colegas",
                                    "comunidade" =>"Comunidades",
                                    "blog" => "Artigos",
                                    "membro" => ""
                                );
        
        $arrTituloAcao =  array(
                                    "esqueci-a-senha" => "",
                                    "listar" => "",
                                    "categoria-conteudo" => "Categorização dos conteúdos digitais",
                                );
        
        $request = Zend_Controller_Front::getInstance();	
        $argument = $request->getRequest()->getParams();

        $path = array();
        foreach($argument as $key=>$value):
            if($key == "module"):
                $path[] = $value;
            elseif($key == "controller"):
                    $path[] = $value;
                elseif($key == "action" && $pagina == ""):
                        $path[] =  $value;
                endif;
        endforeach;

        $base = "";
        
        $breadcrumbs = array("<li><a class='link-cinza-escuro' href='/'><i class='fa fa-home'></i></a></li>");
        
        foreach ($path as $key => $value):
            $base .= "/$value";
            if(array_key_exists($value, $arrTituloModulos)):
                $title = $arrTituloModulos[$value];
            elseif(array_key_exists($value, $arrTituloControl)):
                    $title = $arrTituloControl[$value];
                elseif(array_key_exists($value, $arrTituloAcao)):
                        $title = $arrTituloAcao[$value];
                    else:
                        $title = ucwords(str_replace(array('.php', '_', '-', '#'), array('',' ',' ',''), $value));
                    endif;

            $arrTitle[$title] = $title;
            
            if($title):
                if(!array_search($title, $breadcrumbs)):
                    $breadcrumbs[] = "<li class='fa fa-angle-double-right'><a class='link-cinza-escuro' href='$base'><small class='breadcrumb-text'>$title</small></a></li>";
                endif;
            endif;
            
        endforeach;

        $isFilho = false;
        if(isset($paginaPai)):
            foreach($paginaPai as $key=>$value):
                $href = $value['url'];
                //$titulo = strtolower($value['titulo']);
                $titulo = $value['titulo'];
                if($href != ""):
                    $href = "<a href='$href'>";
                endif;
                
                $html = "<li class='fa fa-angle-double-right'>$href<small class='link-cinza-escuro breadcrumb-text'>$titulo</small>".($href ? "</a>" : "")."</li>";
                
                if(isset($value['filho'])):
                    $isFilho = true;
                    $breadcrumbsFilho[] = $html;
                else:
                    $breadcrumbs[] = $html;
                endif;
            endforeach;
        endif;
        
        if(!array_search($pagina, $arrTitle) && $pagina):
            $breadcrumbs[] = "<li class='hidden-sm hidden-xs fa fa-angle-double-right'><b class='link-cinza-escuro'><small class='breadcrumb-text'>$pagina</small></b></li>";
        endif;

        if($isFilho):
            $breadcrumbs = array_merge($breadcrumbs,$breadcrumbsFilho);
        endif;
        
        $result = "<ul class='breadcrumb padding-none margin-none'>".implode($breadcrumbs)."</ul>";

        return $result;
    }
}