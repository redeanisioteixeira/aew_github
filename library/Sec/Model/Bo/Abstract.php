<?php

/**
 * Classe abstrata do Business Object
 *
 * @author diegop
 */
abstract class Sec_Model_Bo_Abstract extends Sec_Model_Abstract
{
    /**
     * @var Sec_Model_Dao_Abstract
     */
    protected $id;    
    private  $_dao;
    protected $count;
    
    /**
     * @return Sec_Model_Dao_Abstract
     */
    public function getDao()
    {
        if(!$this->_dao)
        {
            $this->_dao = $this->createDao();
        }
        return $this->_dao;
    }
        
    protected abstract function createDao();
    
    protected function setDao(Sec_Model_Dao_Abstract $dao)
    {
        $this->_dao = $dao;
    }

    /**
     * @param array $elements
     * @param type $page
     * @param type $limit
     * @param type $range
     * @return \Zend_Paginator
     */
    public function getAsPagination($elements, $page = 1, $limit = 10, $range = 15)
    {
        $total = 0;
        if(count($elements) > 0)
        {
            $count = 0;
            if(is_array($elements))
            $count = $elements[0]->getCount();
            else
            $count = $elements->getCount();
            $total = $count>0? $count :  count($elements); 
        }
        $paginator = new Zend_Paginator(new Sec_Paginator_Adapter_Array($elements, $total));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage($limit);
        $paginator->setPageRange($range);
        return $paginator;
    }
    
    /**
     * Pega os elementos prontos para serem usados em um select
     *
     * @param $keyName nome da chave
     * @param $valueName nome do valor a ser apresentado
     * @param $includeAll adiciona o valor Todos
     * @param int $idgroup nome campo pai para criar agrupamento
     * @param $options campo para definir atributos adicionais a query SQL
     * @param $result camṕo de resultado
     * @param $excluirPai excluir o registro que agrupa caso de possuir campo pai
     * @return array
     */
    public function getAllForSelect($keyName, $valueName,  $includeAll = true, $idgroup='', array $options = null, $result = null, $excluirPai = true)
    {
        if(!$result)
        {
            $result = $this->select(0,0,$options);
        }
        $array = array();
        if($includeAll)
        {
            if($includeAll === true)
                $array[''] = '« TODOS »';
            else
            {
                $array[''] = '« '. strtoupper(($includeAll)).' »';
            }
        }
        foreach($result as $elemento)
        {
            $data = $elemento->toArray();
            if(!$idgroup) 
            {
                $array[$data[$keyName]] = $data[$valueName];
            }
            else if($idgroup)
            {
                if(!isset($data[$idgroup]))
                    $array[$data[$keyName]] = $data[$valueName];
                
                $group = array();
                foreach($result as $elemento2) 
                {
                    $data2 = $elemento2->toArray();
                    if($data2[$idgroup]==$data[$keyName])
                    {
                        if($data2[$valueName])
                        {
                            $paiId   = $data2[$idgroup];
                            $paiNome = $array[$data2[$idgroup]];
                            $group[$data2[$keyName]] = $data2[$valueName];
                        }
                    }     
                }
                if($group)
                {
                    if($paiNome)
                        $array[$paiNome] = $group;
                    
                    if($excluirPai)
                        unset($array[$paiId]);
                }
            }
        }
        
        return $array;
    }
    
    /**
     * Salva um objeto
     * @return int 
     */
    public function save()
    {
        if($this->getId())
        {
            return $this->update();
        }
        return $this->insert();
    }
    
    /**
     * insere o objeto no banco de dados
     * @return int
     */
    public function insert()
    {
        $data = $this->toArray();
        $insert = $this->getDao()->insert($data);
        $this->setId($insert);
        return $insert;
    }

    /**
     * delete o objeto
     * @return int
     */
    public function delete()
    {
        if(!$this->getId())
        {
            return ;
        }
        try
        {
            //print_r($this->getIdInArray(array(),true)); die();
            return $this->getDao()->delete($this->getIdInArray(array(),true));
        } 
        catch (Exception $ex)
        {
            return 0;
        }
    }
    

    /**
     * Insere os dados do id no arraydata (chave=nomecampo)
     * @param array $data       array que contara os dados
     * @param string $select    parametro utlizado para indicar que o array data sera ou nao utilizado em uma query
     */
    protected function getIdInArray(array $data, $select = false)
    {
        if(!$this->getDao())
        return $data;
        if($select)
        $select = " = ? ";
        $primaries = $this->getDao()->getPrimary();
        $idmodelBo = $this->getId();
        $nomeTabela = $this->getDao()->getName();
        if( is_array($primaries) && (count($primaries)==1))
        {
            foreach($primaries as $primary)
            {
                $p = $primary;
            }
            if($p)
            $primaries = $p;
        }
        if(is_array($idmodelBo)) 
        {
            $i=0;
            if(!is_array($primaries))
            {
                $data[$primaries.$select] = $idmodelBo; 
            }
            else
            {
                foreach($primaries as $primary)
                {
                    if($idmodelBo[$i])
                    {
                        $data[$primary.$select] = $idmodelBo[$i]; 
                        $this->getDao()->setTableInTableField($primary, $nomeTabela);
                    }
                    $i++;
                }
            }
        }
        else if($idmodelBo)
        {
            if(is_array($primaries))
            {
                foreach ($primaries as $field) 
                {
                    if($field)
                    {
                        $data[$field.$select] = $idmodelBo; 
                        $this->getDao()->setTableInTableField($field, $nomeTabela);
                        break;
                    }
                }
            }
            else
            {
                $data[$primaries.$select] = $idmodelBo;
                $this->getDao()->setTableInTableField($primaries, $nomeTabela);   
            }
        }
        return $data;
    }
    
    /**
     * atualiza dados do objeto no banco
     * @return int
     */
    public function update()
    {
        if(!$this->getId())
        {
            return false;
        }
        return $this->getDao()->update($this->toArray(), $this->getIdInArray(array(),true));
    }
    
    /**
     * Seleciona objetos no banco de acordo com
     * variaveis de instancia do objeto
     * @param type $num
     * @param type $offset
     * @param type $options
     * @param type $globalCountRecords calcula o numero de records da consulta
     * @return type
     * @return array 
     */
    public function select($num=0, $offset=0, $options = null, $globalCountRecords=false)
    {
        $dao = $this->getDao();
        $objs = $dao->querySelect($this->toArray(),$num,$offset,$options,$globalCountRecords);
        return $objs;
    }
    
    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Seleciona os dados do objeto no banco e os insere
     * no objeto que executa o metodo 
     * @return boolean false se o objeto não encontrar um registro
     * referente ao seu id e verdadeiro se encontrar
     */
    function selectAutoDados()
    {
        $result = null;
        if($this->getId())
        {
            $q = $this->getDao()->buildQuery($this->toArray(),1,0);
           
            $result = $q->query();
            
            if($result->rowCount()==0)
                return false;
            
            
            foreach($result as $data)
            {
                $this->exchangeArray($data);
            }
            return $result;
        }
        return false;
    }

    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    public function toArray()
    {
        $data = array();
        $var = $this;
        foreach ($var as $key => $value) 
        {   
            if((!is_object($value)) && ($value || ($value===0) || ($value==='') || ($value===false)) && ($key!="id"))
            {
                if(is_bool($value))
                {
                    $data[$key]= $value ? 't' : 'f';
                }
                else
                {
                    $data[$key]=$value;
                }
                if($this->getDao())
                if($this->getDao()->isColumnName($key))
                {
                    $this->getDao()->setTableInTableField($key, $this->getDao()->getName());
                }
            }
            else if (is_array($value))
            {
                
            }
        }
        return $this->getIdInArray($data);
    }
    
    
    public function toJson() 
    {
        $references = array();
        $var = $this;
        $json = "{";
        // loop over elements/properties
        foreach ($var as $key => $value) 
        {
            // recursively convert objects
            if($key != '_dao')
            if (is_object($value) || is_array($value)) 
            {
                // but prevent cycles
                if (!in_array($value, $references)) 
                {
                   if(is_array($value))
                   {
                       $json .= "'$key':[";
                       foreach($value as $ob)
                       {
                           if($ob instanceof Sec_Model_Bo_Abstract)
                           $json .= $ob->toJson().",";
                       }
                       $json .= "],";
                   }
                   if($value instanceof Sec_Model_Bo_Abstract)
                   {
                       $json .= "'$key':".$value->toJson().',';
                   }
                   $references[] = $value;
                }
            }            
            else 
            {
                $json .= " '$key':'$value',";
            }
        }
        return $json."}";
    }

    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    public function exchangeArray($data)
    {
        foreach ( $data as $var => $value)
        {
            if(($value) || ($value==='') || ($value===0))
            {
                if(property_exists(get_class($this), $var))
                {
                    $this->{$var} = $value; 
                }
            }
        }
        if($this->getDao())
        if(is_array($this->getDao()->getPrimary()))
        {
            $id_array =  array();
            $i=0;
            if(count($this->getDao()->getPrimary())>1)
            {
                foreach($this->getDao()->getPrimary() as $primary)
                {
                    $id_array[$i]=isset($data[$primary])?$data[$primary]:null;
                    $i++;
                }
            }
            else 
            {
                foreach($this->getDao()->getPrimary() as $primary )
                {
                    $id_array= $data[$primary];
                }
            }
            $this->setId($id_array);
        }
        else
        {
            if(isset($data[$this->getDao()->getPrimary()]))
                $this->setId($data[$this->getDao()->getPrimary()]);
        }
    }
    
    /**
     * faz upload de arquivo associado ao conteúdo
     * @param Zend_Form_Element_File $file
     * @param string $path
     * @param boolean $apagar 
     * @return boolean
     */
    function upload(Zend_Form_Element_File $file, $path)
    {
        $resultado = false;
        
        if($file->isUploaded())
        {
            $extensao = Sec_File::getExtension($file->getFileName());
            $arquivo = $path.DS.$this->getId().'.'.$extensao;

            $rename = new Zend_Filter_File_Rename(array('target' => $arquivo, 'overwrite' => true));

            $name = $file->getfileName();
            $rename->filter($name);

            $file->receive();
            $resultado = true;
        }

        return $resultado;
    }
    
    public function getCount()
    {
        return $this->count;
    }

    public function setCount($count)
    {
        $this->count = $count;
    }
}