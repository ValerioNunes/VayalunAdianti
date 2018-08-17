<?php
/**
 * CargoForm Form
 * @author  <your name here>
 */
class CargoForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct()
    {
        parent::__construct();
       // parent::setDatabase('vayalun');
       // parent::setActiveRecord('Cargo');
        
        // creates the form 
        $this->form = new TQuickForm('form_Cargo');
        $this->form->setFormTitle('Cargo');
        $this->form->class = 'tform';

        $id   = new TEntry('id');
        $nome = new TEntry('nome');
        //$nome->addValidation('Nome', new TRequiredValidator()); 
        
        $id->setEditable(false);
        $nome->setSize('70%');
        

        $this->form->addQuickField('ID',$id,100);
        $this->form->addQuickField('Nome',$nome,140);

        $save = new TAction(array($this, 'onSave'));
        $this->form->addQuickAction('Salvar', $save, 'fa:floppy-o green');      
     

        $this->form->addQuickAction(_('Back'),  new TAction(array('CargoList', 'onReload')),  'fa:arrow-circle-o-left blue');
      

        parent::add($this->form);
    }

    public function onSave(){

        try{
               TTransaction::open('vayalun');
               $object = $this->form->getData('Cargo');
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
               $object =  new Cargo($key);
              

               $this->form->setData($object);
               TTransaction::close();
               //new TMessage('info', 'Registro Salvo com sucesso');
           }catch(Exception $e){
                    new TMessage('error', $e->getMessage());
                    TTransaction::rollback();
           }
    }

        public function onCreator( $param ){

        try{


           }catch(Exception $e){
                    new TMessage('error', $e->getMessage());
           }
        }
        public function onDelete( $param ){

        try{
               TTransaction::open('vayalun');
              
               $key = $param['id'];
               $object =  new Cargo($key);
               $object->delete();
               TTransaction::close();
               //new TMessage('info', 'Registro Salvo com sucesso');
           }catch(Exception $e){
                    new TMessage('error', $e->getMessage());
                    TTransaction::rollback();
           }
    }
}