<?php
namespace TodoTest\Model;

use Todo\Model\TodoTable;
use Todo\Model\Todo;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class TodoTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllTodos()
    {
        $resultSet        = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $todoTable = new TodoTable($mockTableGateway);

        $this->assertSame($resultSet, $todoTable->fetchAll());
    }

    public function testCanRetrieveAnTodoByItsId()
    {
        $todo = new Todo();
        $todo->exchangeArray(array('id'          => 123,
                                   'name'        => 'Unit test list',
                                   'description' => 'Awesome'));

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Todo());
        $resultSet->initialize(array($todo));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $todoTable = new TodoTable($mockTableGateway);

        $this->assertSame($todo, $todoTable->getTodo(123));
    }

    public function testCanDeleteAnTodoByItsId()
    {
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('delete'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('delete')
                         ->with(array('id' => 123));

        $todoTable = new TodoTable($mockTableGateway);
        $todoTable->deleteTodo(123);
    }

    public function testSaveTodoWillInsertNewTodosIfTheyDontAlreadyHaveAnId()
    {
        $todoData = array('name' => 'Unit test list', 'description' => 'Awesome');
        $todo     = new Todo();
        $todo->exchangeArray($todoData);

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('insert')
                         ->with($todoData);

        $todoTable = new TodoTable($mockTableGateway);
        $todoTable->saveTodo($todo);
    }

    public function testSaveTodoWillUpdateExistingTodosIfTheyAlreadyHaveAnId()
    {
        $todoData = array('id' => 123, 'name' => 'Unit test list', 'description' => 'Awesome');
        $todo     = new Todo();
        $todo->exchangeArray($todoData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Todo());
        $resultSet->initialize(array($todo));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));
        $mockTableGateway->expects($this->once())
                         ->method('update')
                         ->with(array('name' => 'Unit test list', 'description' => 'Awesome'),
                                array('id' => 123));

        $todoTable = new TodoTable($mockTableGateway);
        $todoTable->saveTodo($todo);
    }

    public function testExceptionIsThrownWhenGettingNonexistentTodo()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Todo());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $todoTable = new TodoTable($mockTableGateway);

        try
        {
            $todoTable->getTodo(123);
        }
        catch (\Exception $e)
        {
            $this->assertSame('Could not find row 123', $e->getMessage());
            return;
        }

        $this->fail('Expected exception was not thrown');
    }
}