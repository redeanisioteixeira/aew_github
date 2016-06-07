<?php


class Aew_Model_Bo_RedeSocial extends Sec_Model_Bo_Abstract
{

    protected $rede,$site;
    
    /**
     * @return String nome
     */
    public function getRede() {
        return $this->rede;
    }
    
    /**
     * @return String path da imagem/icone da rede
     */
    public function linkImg() 
    {
        return "/img/espaco-aberto/redes/".$this->getId().".png"; 
    }

    /**
     * endereco relacionado ao site
     * @return string
     */
    public function getSite() {
        return $this->site;
    }

    /**
     * nome da rede social
     * @param string $rede
     */
    public function setRede($rede) 
    {
        $this->rede = str_replace('.com','',str_replace('www', '', $rede));
    }

    /**
     * 
     * @param string $site
     */
    public function setSite($site) {
        $this->site = $site;
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_RedeSocial
     */
    protected function createDao() {
        return new Aew_Model_Dao_RedeSocial();
    }
}