<?php
/**
 * ItemPedidosForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class ItemPedidoForm extends TPage
{
    protected $form; // form
    protected $itemItemPedidos_list;
    protected $tbpedido_id;
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_ItemPedidos');
        $this->form->setFormTitle( 'Item Pedido' );

        // create the form fields
        $id    = new TEntry('id');
        $qtd   = new TEntry('qtd');
        $preco  = new TEntry('preco');
        $tbpedido_id  = new TEntry('tbpedido_id');
       

        $tbitemcardapio_id = new TDBCombo('tbitemcardapio_id','vayalun','itemcardapio','id','nome');

        // define the sizes
        $id->setSize('30%');

        
        // outras propriedades
        $id->setEditable(false);
        $tbpedido_id->setEditable(false);
        $preco->setEditable(false);

        $this->form->addFields( [new TLabel('ID')], [$id] ,[new TLabel('Pedido')], [$tbpedido_id] );
        $this->form->addFields( [new TLabel('Cardapio')], [$tbitemcardapio_id]);
        $this->form->addFields( [new TLabel('Preco')], [$preco], [new TLabel('Quantidade')], [$qtd]);
        //$hbox->style = 'margin: 4px';

        
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o' );
        $btn->class = 'btn btn-sm btn-primary';
        
        $this->form->addAction( _t('Back'), new TAction(array('PedidoForm','onAdditempedido')),  'fa:arrow-circle-o-left blue' );
        
        $container = new TVBox;
        $container->style = 'width:90%';
        $container->add($this->form);
        
        // add the form to the page
        parent::add($container);
    }


    
    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    public function onSave($param)
    {
        try
        {
            // open a transaction with database 'vayalun'
            $object = $this->form->getData('ItemPedido');
            TTransaction::open('vayalun');
            if (!empty($object->tbitemcardapio_id) && !empty($object->tbpedido_id)){
                    $itemcadapio =  new ItemCardapio( $object->tbitemcardapio_id);
                    $object->preco =   $object->qtd * (float) $itemcadapio->preco;
                    $object->store();
                    $this->form->setData($object);
                }
            TTransaction::close(); // close the transaction
            new TMessage('info', _t('Record saved')); // shows the success message
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
       
            if (($param['id']))
            {
                // get the parameter $key
                $key=$param['id'];

                $object =  new ItemPedido;
                $object->tbpedido_id = $key;
               
                $this->form->setData($object);
            }
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
   }