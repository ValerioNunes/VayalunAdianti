<?php

/**
 * Funcionario short summary.
 *
 * Funcionario description.
 *
 * @version 1.0
 * @author valerio
 */
class Funcionario extends TRecord
{
    const TABLENAME = 'tbfuncionario';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    private $tbcargo;
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
        parent::addAttribute('cpf');
        parent::addAttribute('senha');
        parent::addAttribute('tbcargo_id');
    }


    public function get_tbcargo_name()
    {
        // loads the associated object
        if (empty($this->tbcargo))
            $this->tbcargo = new Cargo($this->tbcargo_id);
    
        // returns the associated object
        return $this->tbcargo->nome;
    }

    public function delete($id = NULL)
    {
        // delete the related System_groupSystem_program objects
        $id = isset($id) ? $id : $this->id;
        $repository = new TRepository('Funcionario');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id', '=', $id));
        $repository->delete($criteria);
        
        // delete the object itself
        parent::delete($id);
    }
}