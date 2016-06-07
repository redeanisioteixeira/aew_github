<?php

/**
 * BO da entidade Formato
 */
class Aew_Model_Bo_Formato extends Sec_Model_Bo_Abstract
{
    protected $nomeformato,$conteudoTipo;
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->setConteudoTipo(new Aew_Model_Bo_ConteudoTipo());
    }
    
    public function toArray() 
    {
        $data = parent::toArray();
        
        if($this->getConteudoTipo()->getId())
        {
            $data['idconteudotipo'] = $this->getConteudoTipo()->getId();
            $this->getDao()->setTableInTableField('idconteudotipo', $this->getDao()->getName());
        }
        return $data;
    }
    
    /**
     * 
     * @param Zend_Form_Element_File $fileField
     */
    function uploadArquivoConteudo(Zend_Form_Element_File $fileField, $id)
    {
        $isUpload = false;
        if($fileField->isUploaded())
        {   
            $formato = Sec_File::getExtension($fileField->getFileName());

            $this->setNome($formato);
            
            $target = $fileField->getDestination().DS."$id.$formato";
            
            $rename = new Zend_Filter_File_Rename(array('target' => $target, 'overwrite' => true));
            $name = $fileField->getfileName();

            $fileField->receive();
            $rename->filter($name);
            $isUpload = true;
        }

        return $isUpload;
    }
    
    /**
     * 
     * @param array $data
     */
    function exchangeArray($data) 
    {
        parent::exchangeArray($data);
        
        $this->getConteudoTipo()->exchangeArray($data);
    }

    /**
     * 
     * @return Aew_Model_Bo_ConteudoTipo
     */
    public function getConteudoTipo() {
        return $this->conteudoTipo;
    }

    /**
     * 
     * @param Aew_Model_Bo_ConteudoTipo $conteudoTipo
     */
    public function setConteudoTipo(Aew_Model_Bo_ConteudoTipo $conteudoTipo) 
    {
        $this->conteudoTipo = $conteudoTipo;
    }

    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nomeformato;
    }
    
    /**
     * 
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nomeformato = $nome;
    }

    /**
     * retorna lista dos formatos existentes
     * @param array $options
     * @return string
     */
    public function getList(array $options = null)
    {
        $objs = $this->select(0, 0, $options);

        foreach($objs as $obj)
        {
            $extensions .= $obj->getNome().', ';
        }
        
        $extensions = substr($extensions, 0, -2);
        return $extensions;
    }

    /**
     * 
     * @return string
     */
    public function getListImagem()
    {
        $extensions = 'png, jpg, gif';
        return $extensions;
    }

    /**
     * 
     * @return \Aew_Model_Dao_Formato
     */
    protected function createDao() {
        return  new Aew_Model_Dao_Formato();
    }
}