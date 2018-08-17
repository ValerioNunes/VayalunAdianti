<?php

/**
 * PedidoList short summary.
 *
 * PedidoList description.
 *
 * @version 1.0
 * @author valerio
 */
class PedidoList extends TStandardList
{

    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;
    protected $loaded;
    public function __construct(){
        
        parent::__construct();

        parent::setDatabase('vayalun');            // defines the database
        parent::setActiveRecord('Pedido');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('status', 'like', 'status'); // filterField, operator, formField
        parent::addFilterField('tbmesa_id', '=', 'tbmesa_id');
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Pedido');
        $this->form->setFormTitle('Pedido');
        
        // create the form fields
        $id = new TEntry('id');
        $status = new TEntry('status');
        $tbmesa_id = new TEntry('tbmesa_id');
        
  
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$status] );
        $this->form->addFields( [new TLabel('Mesa')], [$tbmesa_id] );

        $id->setSize('30%');
        $status->setSize('70%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Pedido') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('PedidoForm', 'onEdit')), 'bs:plus-sign green');

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_status = new TDataGridColumn('status', _t('Name'), 'left');
        $column_tbmesa_id = new TDataGridColumn('tbmesa', 'Mesa', 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_status);
        $this->datagrid->addColumn($column_tbmesa_id);

        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_status = new TAction(array($this, 'onReload'));
        $order_status->setParameter('order', 'status');
        $column_status->setAction($order_status);
        

        $order_tbmesa_id = new TAction(array($this, 'onReload'));
        $order_tbmesa_id->setParameter('order', 'tbmesa_id');
        $column_tbmesa_id->setAction($order_tbmesa_id);

        // create EDIT action
        $action_edit = new TDataGridAction(array('PedidoForm', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        $action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);
        

        $this->datagrid->createModel();
         // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);

    }

   
}