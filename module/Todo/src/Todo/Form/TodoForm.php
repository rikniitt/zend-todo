<?php
namespace Todo\Form;

use Zend\Form\Form;

class TodoForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('todo');

        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
                'id' => 'name',
            ),
            'options' => array(
                'label' => 'Name',
                'label_attributes' => array(
                    'for' => 'id'
                ),
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type'  => 'textarea',
                'class' => 'form-control',
                'rows' => 7,
                'id' => 'description',
            ),
            'options' => array(
                'label' => 'Description',
                'label_attributes' => array(
                    'for' => 'id'
                ),
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save',
                'id' => 'submitbutton',
                'class' => 'btn btn-success',
            ),
        ));
    }
}