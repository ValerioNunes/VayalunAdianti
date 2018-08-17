
<?php

/**
 * Pedido short summary.
 *
 * Pedido description.
 *
 * @version 1.0
 * @author valerio
 */
class Pedido extends TRecord
{
    const TABLENAME = 'tbpedido';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    private $tbcliente;
    private $tbmesa;
    private $tbfuncionario;

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('datapedidofeito');
        parent::addAttribute('datapedidopronto');
        parent::addAttribute('total');
        parent::addAttribute('status');
        parent::addAttribute('tbcliente_id');
        parent::addAttribute('tbfuncionario_id');
        parent::addAttribute('tbmesa_id');
    }




    public function getItemPedidos()
    {
        $system_programs = array();
        
        // load the related System_program objects
        $repository = new TRepository('ItemPedido');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('tbpedido_id', '=', $this->id));
        $itempedidos = $repository->load($criteria);
        if ($itempedidos)
        {
            foreach ($itempedidos as $item)
            {
                $system_programs[] = new ItemPedido( $item->id );
            }
        }
        
        return $system_programs;
    }

    public function get_tbcliente_name()
    {
        // loads the associated object
        if (empty($this->tbcliente))
            $this->tbcliente = new Cliente($this->tbcliente_id);
    
        // returns the associated object
        return $this->tbcliente->nome;
    }


    public function get_tbfuncionario_id_name()
    {
        // loads the associated object
        if (empty($this->tbfuncionario))
            $this->tbfuncionario = new Funcionario($this->tbfuncionario_id);
         var_dump($this->tbfuncionario);
        // returns the associated object
        return $this->tbfuncionario->nome;
    }


    public function get_tbmesa()
    {
        // loads the associated object
        if (empty($this->tbmesa))
            $this->tbmesa = new Mesa($this->tbmesa_id);
        // returns the associated object
        return $this->tbmesa->nome;
    }


    public function delete($id = NULL)
    {
        // delete the related System_groupSystem_program objects
        $id = isset($id) ? $id : $this->id;
        $repository = new TRepository('Pedido');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id', '=', $id));
        $repository->delete($criteria);
        
        // delete the object itself
        parent::delete($id);
    }
}