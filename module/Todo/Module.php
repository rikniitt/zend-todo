<?php

namespace Todo;

use Todo\Model\Todo;
use Todo\Model\TodoTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Todo\Model\TodoTable' =>  function($sm) {
                    $tableGateway = $sm->get('TodoTableGateway');
                    $table = new TodoTable($tableGateway);
                    return $table;
                },
                'TodoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Todo());
                    return new TableGateway('todo', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
