<?php

class Log_portal_mongo_model extends MY_Mongo {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function adicionar($data = array())
    {
        return $this->adicionar_('log_imoveis', $data);
    }
    
    public function adicionar_multi($data = array())
    {
        return $this->adicionar_multi_('log_imoveis', $data);
    }

    public function editar($data = array(), $filtro = array())
    {
        return $this->editar_('log_imoveis', $data, $filtro);
    }

    public function excluir($filtro)
    {
        return $this->excluir_('log_imoveis', $filtro);
    }

    
    
}