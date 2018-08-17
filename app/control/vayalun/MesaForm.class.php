<?php
/**
 * MesaForm Form
 * @author  <your name here>
 */
class MesaForm extends TPage
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
        $this->form = new  BootstrapFormBuilder('form_Mesa');
        $this->form->setFormTitle('Mesa');
        $this->form->class = 'tform';

        $id   = new TEntry('id');
        $nome = new TEntry('nome');

        //$nome->addValidation('Nome', new TRequiredValidator()); 
        
        $id->setEditable(false);
        $nome->setSize('70%');
        

        $this->form->addFields( [new TLabel('ID')], [$id] );
        $this->form->addFields( [new TLabel(_t('Name'))], [$nome] );
       
        $btn = $this->form->addAction( _t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';    
     

        $this->form->addAction(_('Back'),  new TAction(array('MesaList', 'onReload')),  'fa:arrow-circle-o-left blue');
      

        parent::add($this->form);
    }

    public function onSave(){

        try{
               TTransaction::open('vayalun');
               $object = $this->form->getData('Mesa');
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
               $object =  new Mesa($key);
              

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
               $object =  new Mesa($key);
               $object->delete();
               TTransaction::close();
               //new TMessage('info', 'Registro Salvo com sucesso');
           }catch(Exception $e){
                    new TMessage('error', $e->getMessage());
                    TTransaction::rollback();
           }
    }
}