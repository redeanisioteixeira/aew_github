<?php

/**
 * BO da entidade Conteudo Licenca
 */

class Aew_Model_Bo_ConteudoLicenca extends Sec_Model_Bo_Abstract
{
    protected $nomeconteudolicenca; //text
    protected $descricaoconteudolicenca; //text
    protected $idconteudolicencapai; //int(11)
    protected $siteconteudolicenca; //text
        
    /**
     * 
     * @return string
     */
    public function getNome()
    {
        return $this->nomeconteudolicenca;
    }

    /**
     * 
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricaoconteudolicenca;
    }

    /**
     * 
     * @return int
     */
    public function getIdconteudolicencapai()
    {
        return $this->idconteudolicencapai;
    }

    /**
     * 
     * @return string
     */
    public function getSiteconteudolicenca()
    {
        return $this->siteconteudolicenca;
    }

    /**
     * 
     * @param string $nomeconteudolicenca
     */
    public function setNome($nomeconteudolicenca)
    {
        $this->nomeconteudolicenca = $nomeconteudolicenca;
    }

    /**
     * 
     * @param string $descricaoconteudolicenca
     */
    public function setDescricao($descricaoconteudolicenca)
    {
        $this->descricaoconteudolicenca = $descricaoconteudolicenca;
    }

    /**
     * 
     * @param type $idconteudolicencapai
     */
    public function setIdconteudolicencapai($idconteudolicencapai)
    {
        $this->idconteudolicencapai = $idconteudolicencapai;
    }

    /**
     * 
     * @param string $siteconteudolicenca
     */
    public function setSiteconteudolicenca($siteconteudolicenca)
    {
        $this->siteconteudolicenca = $siteconteudolicenca;
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ConteudoLicenca
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_ConteudoLicenca();
        return $dao;
    }

    /**
     * Retorna a Url da imagem associada para o conteudo
     * @param $baseUrl
     */
    public function getImagemAssociada()
    {   
        if(CONTEUDO_PATH):
            $path = DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'imagem-associada'.DS.'licencas'.DS.$this->getId().'.png';
        else:
            $path = DS.'conteudos'.DS.'imagem-associada'.DS.'licencas'.DS.$this->getId().'.png';
        endif;
        return $path;
        
    }
    
    /**
     * retorna o diretorio para os icones
     * @return string Description
     */
    static function getIconeDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'imagem-associada'.DS.'licencas';
        ELSE:
            $path = DS.'conteudos-digitais'.DS.'imagem-associada'.DS.'licencas';
        endif;
        
        return $path;
    }

    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    function toArray() {
        $data = parent::toArray();
        if($this->getIdconteudolicencapai()===NULL)
            $data['idconteudolicencapai'] = null;
        return $data;
    }
    
    /**
     * realiza uploda da imagem para icone
     * @param Sec_Form $form
     * @return boolean
     */
    public function uploadIcon(Sec_Form $form)
    {
        return $this->upload($form->icone, $this->getIconeDirectory());
    }
    
    /**
     * deleta registro da licenca do banco e remove file icone do conteudo
     * @return boolean
     */
    public function delete(){
        if(parent::delete()):
            $icone = $this->getIconeDirectory().DS.$this->getId().'.png';
            if(file_exists($icone)):
                unlink($icone);
            endif;
            return true;
        endif;
    }
}