<?php
/**
 * ClienteForm Form
 * @author  <your name here>
 */
class ClienteForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form 
        $this->form = new  BootstrapFormBuilder('form_Cliente');
        $this->form->setFormTitle('Cliente');
        $this->form->class = 'tform';

        $id   = new TEntry('id');
        $nome = new TEntry('nome');
        $cpf = new TEntry('cpf');

        //$nome->addValidation('Nome', new TRequiredValidator()); 
        
        $id->setEditable(false);
        $nome->setSize('70%');
        

        $this->form->addFields( [new TLabel('ID')], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$nome] , [new TLabel('CPF')], [$cpf]);
       
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';    
     

        $this->form->addAction(_('Back'),  new TAction(array('ClienteList', 'onReload')),  'fa:arrow-circle-o-left blue');
      

        parent::add($this->form);
    }

    public function onSave(){

        try{
               TTransaction::open('vayalun');
               $object = $this->form->getData('Cliente');
               $object->store();
               $this->form->setData($object);
               TTransaction::close();
               new TMessage('info', 'Registro Salvo com sucesso');
           }catch(Exception $e){
                    new TMessage('error', $e->getMessage());
                    TTransaction::rollback();
           }
    }

    public function onEdit( $param ){

        try{
               TTransaction::open('vayalun');
              
               $key = $param['key'];
               $object =  new Cliente($key);
              

               $this->form->setData($object);
               TTransaction::close();
               //new TMessage('info', 'Registro Salvo com sucesso');
           }catch(Exception $e){
                    new TMessage('error', $e->getMessage());
                    TTransaction::rollback();
           }
    }

        public function onDelete( $param ){

        try{
               TTransaction::open('vayalun');
              
               $key = $param['id'];
               $object =  new Cliente($key);
               $object->delete();
               TTransaction::close();
               //new TMessage('info', 'Registro Salvo com sucesso');
           }catch(Exception $e){
                    new TMessage('error', $e->getMessage());
                    TTransaction::rollback();
           }
    }
}