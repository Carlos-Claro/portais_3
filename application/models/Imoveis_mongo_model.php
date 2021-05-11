<?php

class Imoveis_mongo_model extends MY_Mongo {

    public function __construct() {
        parent::__construct();
    }

    public function adicionar($data = array())
    {
        return $this->insert_('imoveis', $data);
    }

    public function adicionar_multi($data = array())
    {
        return $this->adicionar_multi_('imoveis', $data);
    }

    public function editar($data = array(), $filtro = array())
    {
        return $this->editar_('imoveis', $data, $filtro);
    }

    public function excluir($filtro)
    {
        return $this->excluir_('imoveis', $filtro);
    }

    public function get_item($id = '')
    {
        $data['tabela'] = 'imoveis';
        $data['filtro'][] = array('tipo' => 'where', 'campo' => '_id', 'valor' => intval($id));
        $retorno = $this->get_item_($data);
        return $retorno;
    }

    public function get_item_por_filtro($filtro, $coluna = '', $ordem = -1)
    {
        $data['tabela'] = 'imoveis';
        $data['filtro'] = $filtro;
        $data['ordem'] = array($coluna => $ordem);
        $retorno = $this->get_item_($data);
        return $retorno;
    }

    public function get_item_destaque_por_filtro($filtro, $coluna = '', $ordem = -1, $off_set = 0)
    {
        $data['tabela'] = 'imoveis';
        $data['filtro'] = $filtro;
        $data['ordem'] = array($coluna => $ordem);
        // $data['off_set'] = $off_set;
        $data['qtde_itens'] = 1;
        $retorno = $this->get_itens_($data);
        if ( $retorno['qtde'] > 0 )
        {
            foreach($retorno['itens'] as $item)
            {
                return $item;
            }
        }
        return FALSE;
    }

    public function get_total_itens( $filtro = array() )
    {
        $data['tabela'] = 'imoveis';
        $data['filtro'] = $filtro;
        $retorno = $this->get_count_($data);
        return isset($retorno) ? $retorno : NULL;
    }

    public function get_itens( $filtro = array(), $coluna = 'ordem', $ordem = -1, $off_set = 0, $qtde_itens = 12 )
    {
        $data['tabela'] = 'imoveis';
        $data['off_set'] = $off_set;
        $data['qtde_itens'] = $qtde_itens;
        $data['filtro'] = $filtro;
        $data['ordem'] = array($coluna => $ordem);
        $tag = 'getImoveis';
        $retorno = $this->get_itens_($data);
        return isset($retorno) ? $retorno : NULL;
    }

}
