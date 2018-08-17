<?php
/**
 * PedidoForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class PedidoForm extends TPage
{
    protected $form; // form
    protected $itempedido_list;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Pedido');
        $this->form->setFormTitle( 'Pedidos' );

        // create the form fields
        $id   = new TEntry('id');
        $tbcliente_id = new TDBCombo('tbcliente_id','vayalun','Cliente','id','nome');
        $tbfuncionario_id = new TDBCombo('tbfuncionario_id','vayalun','Funcionario','id','nome');
        $tbmesa_id = new TDBCombo('tbmesa_id','vayalun','Mesa','id','nome');
        
        $status = new TCombo('status');
        $combo_itens['AGUARDANDO'] = 'AGUARDANDO';
        $combo_itens['ENTREGUE'] = 'ENTREGUE';
        $combo_itens['PAGO'] = 'PAGO';
        $status->addItems($combo_itens);
        //$itempedido_id = new TDBSeekButton('itempedido_id', 'vayalun', 'form_Pedido', 'ItemPedido', 'name', 'itempedido_id', 'itempedido_name');
        //$itempedido_name = new TEntry('itempedido_name');
        //$itempedido_id->setSize('50');
        //$itempedido_name->setSize('calc(100% - 200px)');
        //$itempedido_name->setEditable(FALSE);
        
        // define the sizes
        $id->setSize('30%');

        
        // outras propriedades
        $id->setEditable(false);
        
        $this->form->addFields( [new TLabel('ID')], [$id], [new TLabel('Mesa')], [$tbmesa_id]);
        
        $this->form->addFields( [new TLabel('Cliente')], [$tbcliente_id]);
        $this->form->addFields( [new TLabel('Funcionario')], [$tbfuncionario_id],[new TLabel('Status')], [$status] );
        
        $this->itempedido_list = new TQuickGrid();
        $this->itempedido_list->setHeight(200);
        $this->itempedido_list->makeScrollable();
        $this->itempedido_list->style='width: 100%';
        $this->itempedido_list->id = 'itempedido_list';
        $this->itempedido_list->disableDefaultClick();
        $this->itempedido_list->addQuickColumn('', 'delete', 'center', '5%');
        $this->itempedido_list->addQuickColumn('Id', 'id', 'left', '10%');
        $this->itempedido_list->addQuickColumn('Item Pedido', 'itemcardapio', 'left', '60%');
        $this->itempedido_list->addQuickColumn('Quantidade', 'qtd', 'left', '30%');
        $this->itempedido_list->createModel();
        
        $add_button  = TButton::create('add',  array('ItemPedidoForm', 'onEdit'), _t('Add'), 'fa:plus green');
        $hbox = new THBox;
        $hbox->add($add_button);
        $hbox->style = 'margin: 4px';
        
        $vbox = new TVBox;
        $vbox->style='width:100%';
        $vbox->add( $hbox );
        $vbox->add($this->itempedido_list);
        
        $this->form->addFields( [new TFormSeparator('Itens Pedidos')] );
        $this->form->addFields( [$vbox] );
        
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o' );
        $btn->class = 'btn btn-sm btn-primary';

        $this->form->addAction( _t('Clear'), new TAction(array($this, 'onEdit')),  'fa:eraser red' );
        $this->form->addAction( _t('Back'), new TAction(array('PedidoList','onReload')),  'fa:arrow-circle-o-left blue' );

        
        
        $this->form->addField($add_button);
        $container = new TVBox;
        $container->style = 'width:90%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'PedidoList'));
        $container->add($this->form);
        
        // add the form to the page
        parent::add($container);
    }

    /**
     * Remove itempedido from session
     */
    public static function deleteitempedido($param)
    {
        //$itempedidos = TSession::getValue('itempedido_list');
        //unset($itempedidos[ $param['id'] ]);
        //TSession::setValue('itempedido_list', $itempedidos);
        try{
               TTransaction::open('vayalun');
              
               $key = $param['id'];
               $object =  new ItemPedido($param['id']);
               $object->delete();
               TTransaction::close();
               //new TMessage('info', 'Registro Salvo com sucesso');
           }catch(Exception $e){
                    new TMessage('error', $e->getMessage());
                    TTransaction::rollback();
           }
    }
    
    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    public static function onSave($param)
    {
        try
        {
            // open a transaction with database 'vayalun'
            TTransaction::open('vayalun');
            
            // get the form data into an active record Pedido
            $object = new Pedido;
            $object->fromArray( $param );
            $object->store();
            
            $itempedidos = TSession::getValue('itempedido_list');
            if (!empty($itempedidos))
            {
                foreach ($itempedidos as $itempedido)
                {
                   // $object->addSystemitempedido( new Systemitempedido( $itempedido['id'] ) );
                }
            }
            
            $data = new stdClass;
            $data->id = $object->id;
            TForm::sendData('form_Pedido', $data);
            
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
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'vayalun'
                TTransaction::open('vayalun');
                
                // instantiates object Pedido
                $object = new Pedido($key);
                
                $data = array();
                foreach ($object->getItemPedidos() as $itempedido)
                {
                    $data[$itempedido->id] = $itempedido->toArray();
                    
                    $item = new stdClass;
                    $item->id = $itempedido->id;
                    $item->qtd = $itempedido->qtd;
                    $item->itemcardapio = $itempedido->get_tbitemcardapio()->nome;

                    $i = new TElement('i');
                    $i->{'class'} = 'fa fa-trash red';
                    $btn = new TElement('a');
                    $btn->{'onclick'} = "__adianti_ajax_exec('class=PedidoForm&method=deleteitempedido&id={$itempedido->id}');$(this).closest('tr').remove();";
                    $btn->{'class'} = 'btn btn-default btn-sm';
                    $btn->add( $i );
                    
                    $item->delete = $btn;
                    $tr = $this->itempedido_list->addItem($item);
                    $tr->{'style'} = 'width: 100%;display: inline-table;';
                }
                
                // fill the form with the active record data
                $this->form->setData($object);
                
                // close the transaction
                TTransaction::close();
                
                TSession::setValue('itempedido_list', $data);
            }
            else
            {
                $this->form->clear();
                TSession::setValue('itempedido_list', null);
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
    
    /**
     * Add a itempedido
     */
    public function onAdditempedido($param)
    {
            $id['key'] = $param['tbpedido_id'];
            $this->onEdit($id);
    }
}
