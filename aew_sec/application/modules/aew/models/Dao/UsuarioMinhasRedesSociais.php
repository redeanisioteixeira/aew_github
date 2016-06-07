<?php

/**
 * DAO da entidade UsuarioMinhasRedesSociais
 */

class Aew_Model_Dao_UsuarioMinhasRedesSociais extends Sec_Model_Dao_Abstract
{
    function __construct() {
        parent::__construct('usuarioredesocial','idusuarioredesocial');
    }

    public function createModelBo() {
        return new Aew_Model_Bo_UsuarioMinhasRedesSociais();
    }

}