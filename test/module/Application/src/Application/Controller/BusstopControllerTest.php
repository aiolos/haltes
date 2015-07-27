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
use Application\Entity\BusStop;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2014-08-07 at 18:02:02.
 */
class BusstopControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var BusstopController
     */
    protected $object;

    /**
     *
     * @var Request
     */
    protected $request;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new BusstopController;

        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new BusstopController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'Busstop'));
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
     * @covers Application\Controller\BusstopController::getEntityManager
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
        //$this->assertNull($this->object->getEntityManager());
        $this->object->setEntityManager($value);
        $this->assertSame($value, $this->object->getEntityManager());
    }

    /**
     * @covers Application\Controller\BusstopController::setEntityManager
     */
    public function testSetEntityManager()
    {
        $value = 'entityManager';
        $this->assertSame($this->object, $this->object->setEntityManager($value));
    }

    /**
     * @covers Application\Controller\BusstopController::indexAction
     * @dataProvider indexValueProvider
     */
    public function testIndexAction($paramArray, $count, $totalCount)
    {
        $queryBuilder = $this->getMock('Doctrine\ORM\QueryBuilder', array(), array(), '', false);
        $entityManager = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $entityManager->expects($this->any())->method('createQueryBuilder')->will($this->returnValue($queryBuilder));
        $this->controller->setEntityManager($entityManager);

        $queryBuilder->expects($this->any())->method('select')->will($this->returnSelf());
        $queryBuilder->expects($this->any())->method('from')->will($this->returnSelf());
        $queryBuilder->expects($this->any())->method('andWhere')->will($this->returnSelf());
        $queryBuilder->expects($this->any())->method('setParameter')->will($this->returnSelf());
        $queryBuilder->expects($this->any())->method('setMaxResults')->will($this->returnSelf());
        $queryBuilder->expects($this->any())->method('setFirstResult')->will($this->returnSelf());

        $query = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')
                ->setMethods(array('getResult', 'getSingleScalarResult'))
                ->disableOriginalConstructor()
                ->getMockForAbstractClass();
        $queryBuilder->expects($this->any())->method('getQuery')->will($this->returnValue($query));

        $busstop = new BusStop();
        $busstop->setLatitude(52)->setLongitude(5);
        $results = array();
        if ($count) {
            $results = array_fill(0, $count, $busstop);
        }
        $query->expects($this->once())->method('getResult')->will($this->returnValue($results));
        $query->expects($this->once())->method('getSingleScalarResult')->will($this->returnValue($totalCount));



        $this->request->setQuery(new Parameters($paramArray));
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    /**
     * @covers Application\Controller\BusstopController::viewAction
     */
    public function testViewAction()
    {
        $entityManager = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $entityRepository = $this->getMock('Doctrine\ORM\EntityRepository', array(), array(), '', false);
        $entityManager
                ->expects($this->once())
                ->method('getRepository')
                ->with('Application\Entity\BusStop')
                ->will($this->returnValue($entityRepository));
        $entityRepository
                ->expects($this->once())
                ->method('findOneBy')
                ->will($this->returnValue(new BusStop));

        $this->controller->setEntityManager($entityManager);
        $params = new Parameters(array('code'=>'HTM:2719'));
        $this->request->setPost($params);
        $this->request->setMethod("POST");
        $this->routeMatch->setParam('action', 'view');
        $this->routeMatch->setParam('code', 'HTM:2719');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    /**
     * @covers Application\Controller\BusstopController::distanceAction
     */
    public function testDistanceAction()
    {
        $entityManager = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $entityRepository = $this->getMock('Doctrine\ORM\EntityRepository', array(), array(), '', false);
        $entityManager
                ->expects($this->any())
                ->method('getRepository')
                ->with('Application\Entity\BusStop')
                ->will($this->returnValue($entityRepository));
        $entityRepository
                ->expects($this->any())
                ->method('findOneBy')
                ->will($this->returnValue(new BusStop));

        $params = new Parameters(array('busstopFrom'=>'HTM:2719','busstopTo'=>'HTM:2901'));
        $this->request->setMethod("POST");
        $this->request->setPost($params);
        $this->controller->setEntityManager($entityManager);
        $this->routeMatch->setParam('action', 'distance');
        $this->routeMatch->setParam('codeFrom', 'HTM:2719');
        $this->routeMatch->setParam('codeTo', 'HTM:2901');


        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(302, $response->getStatusCode());

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    /**
     * @covers Application\Controller\BusstopController::pathAction
     */
    public function testPathActionWithoutBusstop()
    {
        $entityManager = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $entityRepository = $this->getMock('Doctrine\ORM\EntityRepository', array(), array(), '', false);
        $busstopLeft = new BusStop();
        $busstopLeft->setCode("HTM:2719");
        $busstopRight = new BusStop();
        $busstopRight->setCode("HTM:2901");
        $params = new Parameters(array('busstopFrom'=>'HTM:2719','busstopTo'=>'HTM:2901'));
        $this->request->setMethod("POST");
        $this->request->setPost($params);

        //find busstopfrom and busstopto
        $entityManager
                ->expects($this->any())
                ->method('getRepository')
                ->with('Application\Entity\BusStop')
                ->will($this->returnValue($entityRepository));
        $entityRepository
                ->expects($this->any())
                ->method('findOneBy')
                ->will($this->returnValue(null));

        $this->controller->setEntityManager($entityManager);
        $this->routeMatch->setParam('action', 'path');
        $this->routeMatch->setParam('from', 'HTM:2719');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    /**
     * @covers Application\Controller\BusstopController::pathAction
     */
    public function testPathAction()
    {
        $entityManager = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $entityRepository = $this->getMock('Doctrine\ORM\EntityRepository', array(), array(), '', false);
        $busstopRepository = $this->getMock('Doctrine\ORM\EntityRepository', array(), array(), '', false);
        $busstopLeft = new BusStop();
        $busstopLeft->setCode("HTM:2719");
        $busstopRight = new BusStop();
        $busstopRight->setCode("HTM:2901");
        $busstopRight->setCountry("NL");
        $busstopRight->setLatitude(5.1245);
        $busstopRight->setLongitude(42.853);
        $busstopRight->setName("testname");
        $busstopRight->setOperator("ARR");
        $busstopRight->setTown("Amsersfoort");
        $busstopRight->setType("type");
        $busstopRight->setWheelchair(1);
        $trackTo = new \Application\Entity\Track;
        $trackTo->setLeftBusStop($busstopLeft);
        $trackTo->setRightBusStop($busstopRight);
        $trackTo->setPto("ARR");
        $trackTo->setDistance(57649);
        $trackReturn = new \Application\Entity\Track;
        $trackReturn->setLeftBusStop($busstopRight);
        $trackReturn->setRightBusStop($busstopLeft);
        $trackReturn->setPto("ARR");
        $trackReturn->setDistance(57649);

        //find busstopfrom and busstopto
        $entityManager
                ->expects($this->at(0))
                ->method('getRepository')
                ->with('Application\Entity\BusStop')
                ->will($this->returnValue($entityRepository));
        $entityManager
                ->expects($this->at(1))
                ->method('getRepository')
                ->with('Application\Entity\BusStop')
                ->will($this->returnValue($entityRepository));
        $entityRepository
                ->expects($this->any())
                ->method('findOneBy')
                ->will($this->returnValue($busstopLeft));

        //find tracks for busstopfrom and busstopto
        $entityManager
                ->expects($this->at(2))
                ->method('getRepository')
                ->with('Application\Entity\Track')
                ->will($this->returnValue($entityRepository));
        $entityRepository
                ->expects($this->at(2))
                ->method('findBy')
                ->will($this->returnValue(array($trackTo)));
        $entityRepository
                ->expects($this->at(3))
                ->method('findBy')
                ->will($this->returnValue(array($trackReturn)));

        $entityManager
                ->expects($this->at(3))
                ->method('getRepository')
                ->with('Application\Entity\Track')
                ->will($this->returnValue($entityRepository));
        $entityRepository
                ->expects($this->at(4))
                ->method('findBy')
                ->will($this->returnValue(array($trackReturn)));
        $entityManager
                ->expects($this->at(4))
                ->method('getRepository')
                ->with('Application\Entity\BusStop')
                ->will($this->returnValue($entityRepository));

        $this->controller->setEntityManager($entityManager);
        $this->routeMatch->setParam('action', 'path');
        $this->routeMatch->setParam('from', 'HTM:2719');
        $this->routeMatch->setParam('to', 'HTM:2901');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    /**
     * @return array($params, $count, $totalCount)
     */
    public function indexValueProvider()
    {
        return array(
            array(
                array(
                    'maxResults' => 10,
                    'operator'=> 'HTM',
                ),
                0,
                0,
            ),
            array(
                array(
                    'operator'=> 'HTM',
                    'town' => 'den haag',
                ),
                10,
                100,
            ),
            array(
                array(
                    'code'=> 'ARR:10001000',
                ),
                10,
                100,
            ),
            array(
                array(
                    'name'=> 'Polkastraat',
                    'town' => 'Nijmegen',
                ),
                1,
                1,
            ),
            array(
                array(
                    'description'=> ' Merelstraat, Amsterdam',
                    'operator' => 'GVB',
                ),
                5,
                10,
            ),
        );
    }
}
