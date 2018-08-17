<?php

/**
 * ItemCardapioList short summary.
 *
 * ItemCardapioList description.
 *
 * @version 1.0
 * @author valerio
 */
class ItemCardapioList extends TStandardList
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
        parent::setActiveRecord('ItemCardapio');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        parent::addFilterField('descricao', 'like', 'descricao');
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_ItemCardapio');
        $this->form->setFormTitle('ItemCardapio');
        
        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $descricao = new TEntry('descricao');
        
        // add the fields
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$nome] );
        $this->form->addFields( [new TLabel('Descricao')], [$descricao] );

        $id->setSize('30%');
        $nome->setSize('70%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('ItemCardapio') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction(_t('New'),  new TAction(array('ItemCardapioForm', 'onEdit')), 'bs:plus-sign green');

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        
        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_nome = new TDataGridColumn('nome', _t('Name'), 'left');
        $column_descricao = new TDataGridColumn('descricao', 'Descricao', 'left');
        $column_preco = new TDataGridColumn('preco', 'Preco (R$)', 'left');
        // add the columns to the DataGridx'
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_preco);
        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_nome = new TAction(array($this, 'onReload'));
        $order_nome->setParameter('order', 'nome');
        $column_nome->setAction($order_nome);
        

        $order_descricao = new TAction(array($this, 'onReload'));
        $order_descricao->setParameter('order', 'descricao');
        $column_descricao->setAction($order_descricao);


        $order_preco = new TAction(array($this, 'onReload'));
        $order_preco->setParameter('order', 'preco');
        $column_preco->setAction($order_preco);


        // create EDIT action
        $action_edit = new TDataGridAction(array('ItemCardapioForm', 'onEdit'));
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