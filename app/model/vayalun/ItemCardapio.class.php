<?php

/**
 * ItemCardapio short summary.
 *
 * ItemCardapio description.
 *
 * @version 1.0
 * @author valerio
 */
class ItemCardapio extends TRecord
{
    const TABLENAME = 'tbitemcardapio';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    private $tbcategoria;
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('img');
        parent::addAttribute('preco');
        parent::addAttribute('tbcategoria_id');
    }


    public function get_tbcategoria_name()
    {
        // loads the associated object
        if (empty($this->tbcategoria))
            $this->tbcategoria = new Categoria($this->tbcategoria_id);
    
        // returns the associated object
        return $this->tbcategoria->nome;
    }

    public function delete($id = NULL)
    {
        // delete the related System_groupSystem_program objects
        $id = isset($id) ? $id : $this->id;
        $repository = new TRepository('ItemCardapio');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id', '=', $id));
        $repository->delete($criteria);
        
        // delete the object itself
        parent::delete($id);
    }
}