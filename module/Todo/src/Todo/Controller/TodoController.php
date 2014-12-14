<?php

namespace Todo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Todo\Model\Todo;
use Todo\Form\TodoForm;

class TodoController extends AbstractActionController
{
    protected $todoTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'todos' => $this->getTodoTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new TodoForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $todo = new Todo();
            $form->setInputFilter($todo->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $todo->exchangeArray($form->getData());
                $this->getTodoTable()->saveTodo($todo);

                // Redirect to index
                return $this->redirect()->toRoute('todo');
            }
        }

        // Otherwise display form
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('todo', array(
                'action' => 'add'
            ));
        }
        $todo = $this->getTodoTable()->getTodo($id);

        $form  = new TodoForm();
        $form->bind($todo);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($todo->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getTodoTable()->saveTodo($form->getData());

                // Redirect to index
                return $this->redirect()->toRoute('todo');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('todo');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getTodoTable()->deleteTodo($id);
            }

            // Redirect to index
            return $this->redirect()->toRoute('todo');
        }

        return array(
            'id'    => $id,
            'todo' => $this->getTodoTable()->getTodo($id)
        );
    }


    public function getTodoTable()
    {
        if (!$this->todoTable) {
            $sm = $this->getServiceLocator();
            $this->todoTable = $sm->get('Todo\Model\TodoTable');
        }
        return $this->todoTable;
    }
}