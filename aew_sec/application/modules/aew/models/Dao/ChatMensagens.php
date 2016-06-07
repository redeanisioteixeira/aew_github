<?php

/**
* DAO da entidade chatmensagens
*/

class Aew_Model_Dao_ChatMensagens extends Sec_Model_Dao_Abstract
{

    function __construct()
    {
        parent::__construct("chatmensagens", "id");
    }
    
    public function obtemUsuariosChat($idusuario, $filtro, $status = false)
    {
	$filtro_where = '';
	if($filtro != ''):
            $filtro = strtolower($filtro);	
            $filtro_where = " WHERE LOWER(nome_semacento) LIKE ('%".$filtro."%')";
	endif;

        $query = "SELECT * FROM consultar_usuario_chat(".$idusuario.($status == false ? "" : ",TRUE").")".$filtro_where;
        
        //echo $query; die();
        $resultado = $this->getAdapter()->query($query);

        return $this->createObjects($resultado);
    }

    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        if(isset($data['id_de']))
        {
            $q->orWhere("id_de = ".$data['id_de']." or id_para =  ? ",$data["id_de"]);
        }
        if(isset($data['id_para']))
        {
            $q->orWhere("id_de = ".$data['id_para']." or id_para =  ? ",$data["id_para"]);
        }
        $q->join("usuario", "usuario.idusuario = ".$this->getName().".id_de");
        $q->join('usuariofoto', 'usuario.idusuario = usuariofoto.idusuario');
        $q->order('data desc');
        return $q;
    }
    public function obtemColegaBloqueado($usuario)
    {
	$resultado = $this->getAdapter()->query("SELECT id_de FROM chatmensagensstatus WHERE id_para = ".$usuario." AND flbloquear = TRUE");
	return $this->createObjects($resultado);
    }

    /**
     * @param Aew_Model_Bo_ChatMensagens $chatMensagens
     * @return type
     */
    public function obtemAvisoMensagem(Aew_Model_Bo_ChatMensagens $chatMensagens)
    {
        $query = "SELECT * FROM atualizar_mensagem_chat_status(".$chatMensagens->getId().",".$chatMensagens->getIdPara().",".$chatMensagens->getLido().")";
        $result = $this->getAdapter()->query($query);
	return $this->createObjects($result);
    }
    
    public function atualizaStatusMensagem($usuario_de, $usuario_para)
    {
        $query = "SELECT * FROM atualizar_mensagem_chat_lidos(".$usuario_de.",".$usuario_para.")";
	$resultado = $this->getAdapter()->query($query);
	return $this->createObjects($resultado);
    }

    public function obtemAlertaMensagem($id, $usuario)
    {
        $query = "SELECT * FROM consultar_mensagem_chat_status(".$id.",".$usuario.") AS alerta";
	$resultado = $this->getAdapter()->query($query);
	return $this->createObjects($resultado);
    }

    public function obtemMensagemChat($usuario, $id, $idm=0)
    {
        $query = "SELECT * FROM consultar_mensagem_chat(".$usuario.",".$id.",".$idm.") WHERE data>=NOW()::timestamptz - CAST('1 DAYS' AS interval)";
	$resultado = $this->getAdapter()->query($query);
        return $this->createObjects($resultado);
    }

    public function obtemMensagemPendentes($usuarioId)
    {
        $sql = "SELECT id_de, COUNT(*) AS quantidade FROM chatmensagens AS m INNER JOIN feedcontagem AS fc ON (fc.idusuario = m.id_de AND flacesso = TRUE) WHERE id_para = ".$usuarioId." AND lido = FALSE AND data>=NOW()::timestamptz - CAST('1 DAYS' AS interval) GROUP BY id_de";
	$resultado = $this->getAdapter()->query($sql);
    	return $this->createObjects($resultado);
    }

    public function createModelBo() 
    {
        return new Aew_Model_Bo_ChatMensagens();
    }
}