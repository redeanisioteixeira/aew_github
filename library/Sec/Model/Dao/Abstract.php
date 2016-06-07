<?php

/**
 * Classe abstrata do Data Acess Object
 * @author diegop
 */
abstract class Sec_Model_Dao_Abstract extends Zend_Db_Table
{
    protected $_options = array();
    protected $sql;
    protected $tableField=array();
    protected $aliasTable;
    protected $_countLastQuery;
    
    /**
     * @param string $name nome da tabela
     * @param string $primary nome da chave primaria
     */
    function __construct($name,$primary) 
    {
        $this->_name = $name;
        $this->_primary = $primary;
        $config = $this->getDefaultAdapter();
        parent::__construct($config);
    }
    
    public function getAliasTable()
    {
        return $this->aliasTable;
    }
    
    /**
     * @param type $aliasTable
     */
    public function setAliasTable($aliasTable)
    {
        $this->aliasTable = $aliasTable;
    }
    
   
    public function getName()
    {
        return $this->_name;
    }
    
    public function getPrimary()
    {
        return $this->_primary;
    }
    
    /**
     * @return Zend_Db_Select
     */
    public function getSql() {
        $select = new Zend_Db_Select($this->getDefaultAdapter());
        return $select;
    }

    public function getTableField() {
        return $this->tableField;
    }

    public function setSql($sql) {
        $this->sql = $sql;
    }

    public function setTableField($tableField) {
        $this->tableField = $tableField;
    }

    public function setTableInTableField($campo,$tabela)
    {
        $this->tableField[$campo] = $tabela;
    }
    
    /**
     * Retorna um select do DAO
     *
     * @example
     * Options:
     * where = array('e.nome = ?', 'diego')
     * join = array('e.escola' => 'es', 'e.municipio' => 'm')
     * orderBy = array('e.nome DESC')
     * limit = 10
     * offset = 0
     * page = 1
     *
     * @param array $options
     * @return Doctrine_Query_Abstract
     */
    public function getSelect(array $options = null)
    {
        $options = $this->mergeOptions($this->_options, $options);

        $q = Doctrine_Query::create();
        $q->addSelect('e.*')
          ->addFrom($this->_entityName . ' e');

        if(true === is_array($options))
        {
	    foreach($options as $key => $value) 
            {
	        if($key == 'select')
                {
	            if(is_array($value))
                    {
	                foreach($value as $select)
                        {
	                    $q->addSelect($select);
	                }
	            }
	        }
	        if($key == 'join')
                {
	            if(is_array($value))
                    {
	                foreach($value as $join => $pre)
                        {
	                    $q->addSelect($pre.'.*');
	                    $q->addFrom($join.' '.$pre);
	                }
	            }
	        }
	        if($key == 'whereIn')
                {
	            if(is_array($value))
                    {
	                foreach($value as $where => $valueW)
                        {
	                        if($valueW !== null){
	                            $q->whereIn($where, $valueW);
	                        } else {
	                            $q->whereIn($where);
	                        }
	                    }
	                }
	            }
	        	if($key == 'where'){
	                if(is_array($value)){
	                    foreach($value as $where => $valueW){
	                        if($valueW !== null){
	                            $q->addWhere($where, $valueW);
	                        } else {
	                            $q->addWhere($where);
	                        }
	                    }
	                }
	            }
	            if($key == 'orderBy'){
	                if(is_array($value)){
	                    foreach($value as $order){
	                        $q->addOrderBy($order);
	                    }
	                } elseif(is_string($value)) {
	                    $q->addOrderBy($value);
	                }
	            }
	        	if($key == 'limit'){
	                if(is_numeric($value)) {
	                    $q->limit($value);
	                }
	            }
	            if($key == 'offset'){
	                if(is_numeric($value)) {
	                    $q->offset($value);
	                }
	            }
	            if($key == 'page' && isset($options['limit'])){
	                if(is_numeric($value)) {
	                    $q->offset(($value - 1) * $options['limit']);
	                }
	            }
	        }
        }

        return $q;
    }


    /**
     * Merge options recursively
     *
     * @param  array $array1
     * @param  mixed $array2
     * @return array
     */
    public function mergeOptions(array $array1, $array2 = null)
    {
        if (is_array($array2)) {
            foreach ($array2 as $key => $val) {
                if (is_array($array2[$key])) {
                    $array1[$key] = (array_key_exists($key, $array1) && is_array($array1[$key]))
                                  ? $this->mergeOptions($array1[$key], $array2[$key])
                                  : $array2[$key];
                } else {
                    $array1[$key] = $val;
                }
            }
        }
        return $array1;
    }

    /**
     * 
     * @param Sec_Model_Bo_Abstract $bo
     */
    function querySelect(array $data,$num=0,$offset = 0,$options=null,$globalCountRecords=false) 
    {
        $q = $this->buildQuery($data ,$num,$offset,$options);
        if($globalCountRecords)
        {
            $this->countRecords($q);
        }
        $result = $q->query();
        $objs = $this->createObjects($result);
        if(($num==1) && (count($objs)>0))
        {
            return $objs[0];
        }
        return $objs;
    }
    
    /**
     * cra objetos a partir do resultado 
     * da consulta ao banco de dados
     * @param type $result
     * @return array
     */
    protected function createObjects($result)
    {
        $objs = array();
        $i=0;
        foreach ($result as $data)
        {
            $obj = $this->createModelBo();
            $obj->setCount($this->getCountLastQuery());
            $obj->exchangeArray($data);
            array_push($objs, $obj);
            $i++;
        }
        return $objs;
    }
    
    /**
     * constroi o sql da consulta
     * @param array $data
     * @param int $num
     * @param int $offset
     * @param array|string $options
     * @return Zend_Db_Select
     */
    public function buildQuery(array $data,$num=0,$offset=0,$options=null)
    {
        $q = $this->getSql(); 
        
        $excecoes = array('username' => '', 'nometag' => '');
        $naoIncluir = array('texto' => '', 'datacriacao' => '');
        
        if($this->getAliasTable())
        {
            $q->from($this->getAliasTable());
        }
        else
        {
            $q->from($this->getName());
        }
        
        foreach ($data as $key => $value) 
        {
            $table = '';
            if($this->isColumnName($key))
            {
                $table = $this->getName().'.';
            }
            
            if(isset($this->tableField[$key]))
            {
                $table = $this->tableField[$key].'.';
            }
            
            if(!array_key_exists($key, $naoIncluir))
            {        
                if(!is_array($value))
                {
                    if(is_string($value) && $this->getColumnDatatype($key)=='varchar')
                    {
                        if(array_key_exists($key,$excecoes))
                        {
                            $q->where("(lower($table$key) = lower(?))", $value);
                        }
                        else
                        {
                            $q->where("(lower(sem_acentos($table$key)) like lower(sem_acentos(?)))", '%'.$value.'%');
                        }
                    }
                    else if($value===FALSE && $this->getColumnDatatype($key)==='bool')
                    {
                        $q->where($table.$key." =  ? ", 'f');
                    }
                    else if(($value===0) && $this->getColumnDatatype($key)==='int4' && ($this->isColumnNull($key)))
                    {
                        $q->where($table.$key." =  ? ", null);
                    }
                    else
                    {
                        if(!($this->getColumnDatatype($key)==='int4' && !intval($value)))
                        {
                            $q->where($table.$key." =  ? ", $value); 
                        }    
                    }
                }
                else
                {
                    $values = array();
                    foreach($value as $v)
                    {
                        if(is_int($v))
                        {
                            array_push($values, $v);
                        }
                    }

                    if(count($values)>0)
                    {
                        $q->where($table.$key." IN (?)", $values);
                    }
                }
            }
        }
        
        if($num)
        {
            $q->limitPage($offset, $num);
        }
        
        if(is_array($options))
        {
            $this->getOptionsSql($options,$q);
        }
        else if(is_string($options))
        {
            $q->where($options);
        }

        return $q;
    }
    
    /**
     * 
     * @param type $column
     * @return boolean
     */
    public function isColumnName($column)
    {
       $columns = $this->info(Zend_Db_Table_Abstract::COLS);
       foreach ($columns as $col)
       {
           if($col==$column)
           return true;
       }
       return false;
    }
    
    public function getOptionsSql($options,  Zend_Db_Select $q)
    {
        if(true === is_array($options))
        {
            foreach($options as $key => $value) 
            {
                if($key == 'select')
                {
                    if(is_array($value))
                    {
                        foreach($value as $select)
                        $q->where($select);
                    }
                }
	        if($key == 'join')
                {
                    if(is_array($value))
                    {
                        foreach($value as $join => $pre)
                        {
	                    $q->join($join,$pre);
	                }
	            }
	        }
	        if($key == 'whereIn')
                {
	            if(is_array($value))
                    {
                        foreach($value as $where => $valueW)
                        {
                            if($valueW !== null)
                            {
                                $q->where($where, $valueW);
                            } 
                            else 
                            {
                                $q->where($where);
                            }
                        }
	            }
	        }
	        if($key == 'where')
                {
	            if(is_array($value))
                    {
                        foreach($value as $where => $valueW)
                        {
	                    if($valueW !== null)
                            {
	                        $q->where($where, $valueW);
	                    }
                            else 
                            {
	                        $q->where($where);
	                    }
	                }
	            }
                    elseif(is_string($value)) 
                    {
	                $q->where($value);
	            }
	        }
                
                if($key == 'orwhere')
                {
	            if(is_array($value))
                    {
                        foreach($value as $where => $valueW)
                        {
	                    if($valueW !== null)
                            {
	                        $q->orWhere($where, $valueW);
	                    }
                            else 
                            {
	                        $q->orWhere($where);
	                    }
	                }
	            }
                    elseif(is_string($value)) 
                    {
	                $q->orwhere($value);
	            }
                    
	        }
                
	        if($key == 'orderBy')
                {
	            if(is_array($value))
                    {
	                foreach($value as $order)
                        {
	                    $q->order($order);
	                }
	            } 
                    elseif(is_string($value)) 
                    {
	                $q->order($value);
	            }
	        }

	        if($key == 'column')
                {
	            if(is_array($value))
                    {
	                foreach($value as $column)
                        {
	                    $q->columns($column);
	                }
	            } 
                    elseif(is_string($value)) 
                    {
	                $q->columns($column);
	            }
	        }
	    }
        }

        return $q;
    }
    
    /**
     * @param type $data
     * @return type
     */
    function arrayToUpdateInsert($data)
    {
        $dataTable = array();
        foreach ($data as $key => $value) 
        {
            if($this->isColumnName($key))
            {
                if(($value==='' ) && ($this->getColumnDatatype($key)=='timestamp'))
                {
                    continue;
                }
                if(($value==null) && $this->getColumnDatatype($key)==='int4' && ($this->isColumnNull($key)))
                {
                    $dataTable[$key] = NULL;
                    continue;
                }
                if($this->getColumnDatatype($key)==='int4' && !intval($value))
                {
                    continue;
                }
                $dataTable[$key] = $value;
            }
        }
        return $dataTable;
    }
    
    function insert(array $data)
    {
        $dataTable = $this->arrayToUpdateInsert($data);
        return parent::insert($dataTable);
    }
    
    function update(array $data, $where)
    {
        return parent::update($this->arrayToUpdateInsert($data), $where);
    }

    /**
     * @return Sec_Model_Bo_Abstract retorna um objeto do model
     */
    abstract protected function createModelBo();
    
    /**
     * Retorna o valor da contagem da ultima query
     * @return int
     */
    public function getCountLastQuery()
    {
        return $this->_countLastQuery;
    }
    
    public function sumRecordsValues(Zend_Db_Select $q1,$campo)
    {
        //pega somente os dados de filtro da consulta
        $q1->reset(Zend_Db_Select::COLUMNS);
        $q1->reset(Zend_Db_Select::LIMIT_COUNT);
        $q1->reset(Zend_Db_Select::LIMIT_OFFSET);
        $q1->reset(Zend_Db_Select::ORDER);
        $q1->reset(Zend_Db_Select::HAVING);
        $q1->reset(Zend_Db_Select::GROUP);
        $q1->columns("sum($campo)");
        $result = $q1->query();
        return $result; 
    }
    
    public function countRecords(Zend_Db_Select $q)
    {
        $q1 = clone $q;
        //pega somente os dados de filtro da consulta
        $q1->reset(Zend_Db_Select::COLUMNS);
        $q1->reset(Zend_Db_Select::LIMIT_COUNT);
        $q1->reset(Zend_Db_Select::LIMIT_OFFSET);
        $q1->reset(Zend_Db_Select::ORDER);
        $q1->reset(Zend_Db_Select::HAVING);
        $q1->reset(Zend_Db_Select::GROUP);
        $q1->columns("count(*)");
        $result = $q1->query(); 
        foreach ($result as $count) 
        {
            $this->setCountLastQuery($count['count']);
        }
    }
    
    function getColumnDatatype($column)
    {
        $info  = $this->info(Zend_Db_Table_Abstract::METADATA); // get the table metadata, fetches it if it is not yet set
        // get the data type for the "email_address" column
        return $info[$column]['DATA_TYPE'];        
    }
    
    function isColumnNull($column)
    {
        $info  = $this->info(Zend_Db_Table_Abstract::METADATA); // get the table metadata, fetches it if it is not yet set
        return $info[$column]['NULLABLE'];        
    }
    
    function setCountLastQuery($count)
    {
        $this->_countLastQuery = $count;
    }
}