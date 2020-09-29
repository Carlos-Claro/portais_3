<?php
class Locais_Model extends MY_Model {
	
    public $database = array();
    
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct(array('guiasjp'));
        $this->database = array('db' => 'guiasjp', 'table' => 'locais');
    }
	
    public function excluir($filtro)
    {
        return $this->excluir_($this->database, $filtro);
    }
    
    public function get_item($id = '')
    {
        $data['coluna'] = '*';
        $data['tabela'] = array(
            array('nome' => 'locais'),
        );
        $data['db'] = $this->database['db'];
        $data['filtro'] = 'locais.id = '. $id;
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    
}