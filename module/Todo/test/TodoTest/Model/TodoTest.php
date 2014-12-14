<?php

namespace TodoTest\Model;

use Todo\Model\Todo;
use PHPUnit_Framework_TestCase;

class TodoTest extends PHPUnit_Framework_TestCase
{
    public function testTodoInitialState()
    {
        $todo = new Todo();

        $this->assertNull($todo->id, '"id" should initially be null');
        $this->assertNull($todo->name, '"name" should initially be null');
        $this->assertNull($todo->description, '"description" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $todo = new Todo();
        $data  = array('id'           => 123,
                       'name'         => 'some name',
                       'description'  => 'some description');

        $todo->exchangeArray($data);

        $this->assertSame($data['id'], $todo->id, '"id" was not set correctly');
        $this->assertSame($data['name'], $todo->name, '"name" was not set correctly');
        $this->assertSame($data['description'], $todo->description, '"description" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $todo = new Todo();

        $todo->exchangeArray(array('id'           => 123,
                                   'name'         => 'some name',
                                   'description'  => 'some description'));
        $todo->exchangeArray(array());

        $this->assertNull($todo->id, '"id" should have defaulted to null');
        $this->assertNull($todo->name, '"name" should have defaulted to null');
        $this->assertNull($todo->description, '"description" should have defaulted to null');
    }
}