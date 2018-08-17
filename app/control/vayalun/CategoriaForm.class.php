<?php
/**
 * CategoriaForm Form
 * @author  <your name here>
 */
class CategoriaForm extends TPage
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
        $this->form = new  BootstrapFormBuilder('form_Categoria');
        $this->form->setFormTitle('Categoria');
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
     

        $this->form->addAction(_('Back'),  new TAction(array('CategoriaList', 'onReload')),  'fa:arrow-circle-o-left blue');
      

        parent::add($this->form);
    }

    public function onSave(){

        try{
               TTransaction::open('vayalun');
               $object = $this->form->getData('Categoria');
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
               $object =  new Categoria($key);
              

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
               $object =  new Categoria($key);
               $object->delete();
               TTransaction::close();
               //new TMessage('info', 'Registro Salvo com sucesso');
           }catch(Exception $e){
                    new TMessage('error', $e->getMessage());
                    TTransaction::rollback();
           }
    }
}