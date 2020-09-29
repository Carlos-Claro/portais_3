<?php

class Log_pesquisa_mongo_model extends MY_Mongo {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function adicionar($data = array())
    {
        return $this->adicionar_('log_pesquisa', $data);
    }
    
    public function adicionar_multi($data = array())
    {
        return $this->adicionar_multi_('log_pesquisa', $data);
    }

    public function editar($data = array(), $filtro = array())
    {
        return $this->editar_('log_pesquisa', $data, $filtro);
    }

    public function excluir($filtro)
    {
        return $this->excluir_('log_pesquisa', $filtro);
    }

    public function set_date($time = NULL)
    {
        $retorno = $this->date();
        return $retorno;
    }
    
}