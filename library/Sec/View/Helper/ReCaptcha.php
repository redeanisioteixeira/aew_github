<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReCaptcha
 *
 * @author lisandro 
 */
class Sec_View_Helper_ReCaptcha
{
    public $view;
    public $captcha;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function addCaptcha(Sec_Form $form)
    {
        $this->setCaptcha();
        
        $textocaptha  = "<div class='col-lg-12'>";
        $textocaptha .= "   <div class='row'>";
        $textocaptha .= "      <div class='g-recaptcha' data-sitekey='".Sec_Constante::KEYPUBLIC."'></div>";
        $textocaptha .= "      <h5 class='menu-preto'>Este desafio é para nos certificar que você é um visitante humano. Serve para evitar que envios sejam realizados por scripts automatizados de <b>SPAM</b>.</h5>";
        $textocaptha .= "   </div>";
        $textocaptha .= "</div>";

        $recaptcha = $form->createElement('text','recaptcha');
        $recaptcha->setLabel('Código de segurança:')
                    ->setAttrib('class', 'form-control desativado')
                    ->setDescription($textocaptha)
                    ->setRequired(true);
        
        $form->addElement($recaptcha);
        
        $request = Zend_Controller_Front::getInstance();
        $ajax = $request->getRequest()->isXmlHttpRequest();
        
        $script = New Zend_View_Helper_HeadScript();
        if($ajax)
            echo $script->setFile('https://www.google.com/recaptcha/api.js?hl=pt_BR');
        else
            $script->appendFile('https://www.google.com/recaptcha/api.js?hl=pt_BR');

        return $form;
    }
    
    public function setCaptcha()
    {
        $captcha = new Sec_ReCaptcha();
        $this->captcha = $captcha->ReCaptcha(Sec_Constante::KEYPRIVATE);
        return $this->captcha;
    }
    
    public function validar($post)
    {   
        $resp = false;
        
        if(isset($post['g-recaptcha-response']))
        {
            $captcha = new Sec_ReCaptcha();
            $captcha->ReCaptcha(Sec_Constante::KEYPRIVATE);
            $response = $captcha->verifyResponse($_SERVER["REMOTE_ADDR"], $post['g-recaptcha-response']);

            if ($response != null && $response->success)
            {        
                $resp = true;
            }
        }
        
        return $resp;
    }
}