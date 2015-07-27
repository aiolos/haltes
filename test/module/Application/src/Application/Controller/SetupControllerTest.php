<?php

namespace Application\Controller;

use Test\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Stdlib\Parameters;
use PHPUnit_Framework_TestCase;


class SetupControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CostCenterController
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SetupController;

        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new SetupController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'Setup'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Application\Controller\SetupController::getEntityManager
     */
    public function testGetEntityManager()
    {
        $mock = $this->getMock('\Zend\ServiceManager\ServiceManager');
        $mock
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValue('EntityManager'));
        $this->object->setServiceLocator($mock);
        $this->assertEquals('EntityManager', $this->object->getEntityManager());

        $value = 'entityManager';
        $this->object->setEntityManager($value);
        $this->assertSame($value, $this->object->getEntityManager());
    }

    /**
     * @covers Application\Controller\SetupController::setEntityManager
     */
    public function testSetEntityManager()
    {
        $value = 'entityManager';
        $this->assertSame($this->object, $this->object->setEntityManager($value));
    }
}
