<?php

/**
 * ItemPedido short summary.
 *
 * ItemPedido description.
 *
 * @version 1.0
 * @author valerio
 */
class ItemPedido extends TRecord
{
    const TABLENAME = 'tbitempedido';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    private $tbitemcardapio;
    private $tbpedido;
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('qtd');
        parent::addAttribute('preco');
        parent::addAttribute('tbitemcardapio_id');
        parent::addAttribute('tbpedido_id');
    }


    public function get_tbitemcardapio()
    {
        // loads the associated object
        if (empty($this->tbitemcardapio))
            $this->tbitemcardapio = new ItemCardapio($this->tbitemcardapio_id);
    
        // returns the associated object
        return $this->tbitemcardapio;
    }



    public function get_tbpedido()
    {
        // loads the associated object
        if (empty($this->tbpedido))
            $this->tbpedido = new Pedido($this->tbpedido_id);
    
        // returns the associated object
        return $this->tbpedido;
    }


    public function delete($id = NULL)
    {
        // delete the related System_groupSystem_program objects
        $id = isset($id) ? $id : $this->id;
        $repository = new TRepository('ItemPedido');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id', '=', $id));
        $repository->delete($criteria);
        
        // delete the object itself
        parent::delete($id);
    }
}