<?php

/**
 * Mesa short summary.
 *
 * Mesa description.
 *
 * @version 1.0
 * @author valerio
 */
class Mesa extends TRecord
{
    const TABLENAME = 'tbmesa';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
    }


    public function delete($id = NULL)
    {
        // delete the related System_groupSystem_program objects
        $id = isset($id) ? $id : $this->id;
        $repository = new TRepository('Mesa');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id', '=', $id));
        $repository->delete($criteria);
        
        // delete the object itself
        parent::delete($id);
    }
}