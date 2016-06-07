<?php

/**
 * BO da entidade Usuario Tipo
 */
class Aew_Model_Bo_UsuarioTipo extends Sec_Model_Bo_Abstract
{
    const VISITANTE             = 'visitante';
    const AMIGO_DA_ESCOLA       = 'amigo da escola';
    const COLABORADOR           = 'colaborador';
    const EDITOR                = 'editor';
    const COORDENADOR           = 'coordenador';
    const SITES_TEMATICOS       = 'sites tematicos';
    const ADMINISTRADOR         = 'administrador';
    const SUPER_ADMINISTRADOR   = 'super administrador';

    protected $nomeusuariotipo; //varchar(150)
    protected $descricao; //text
    
    /**
     * descricao do tipo de usuario
     * @return string
     */
    public function getDescricao() {
        return $this->descricao;
    }

    /**
     * 
     * @param string $descricao
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    /**
     * @return string
     */
    public function getNome() {
        return $this->nomeusuariotipo;
    }

    /**
     * 
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nomeusuariotipo = $nome;
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_UsuarioTipo
     */
    protected function createDao() 
    {
        $dao =  new  Aew_Model_Dao_UsuarioTipo();
        return $dao;
    }

}