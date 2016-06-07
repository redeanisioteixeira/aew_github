<?php
class Sec_View_Helper_JsUsuarioAdicionar
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function jsUsuarioAdicionar()
    {
        $this->view->Jquery()->onLoadCaptureStart();
        ?>
            $('#idestado').chainSelect('#idmunicipio',
                    '<?php echo $this->view->url(array('module' => 'administracao',
                                                  'controller' => 'usuario',
                                                  'action' => 'municipios'), null, true); ?>',
            {
                    before:function (target) //before request hide the target combobox and display the loading message
                    {
                            $(target).attr("disabled", "true");
                            $(target).html("<option value=\"\">Carregando...</option>");
                    },
                    after:function (target) //after request show the target combobox and hide the loading message
                    {
                                    $(target).removeAttr("disabled");
                    }
            });
        <?php
        $this->view->Jquery()->onLoadCaptureEnd();
    }
}
