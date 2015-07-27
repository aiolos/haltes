<?php

namespace Application\Math;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2014-12-15 at 12:34:53.
 */
class GraphTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Graph
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Graph;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Application\Math\Graph::__construct
     */
    public function test__Construct()
    {
        $this->object = new Graph();
    }

    /**
     * @covers Application\Math\Graph::addEdge
     */
    public function testAddEdge()
    {
        $this->object->addEdge( 2100, 2101, 10);
    }

    /**
     * @covers Application\Math\Graph::getPath
     * @covers Application\Math\Graph::solve
     */
    public function testGetPath()
    {
        $this->object->addEdge(2100, 2101, 10);
        $this->object->addEdge(2101, 2102, 5);
        $this->object->addEdge(2101, 2100, 10);
        $this->object->addEdge(2102, 2101, 5);
        $this->object
            ->setStartVertex(strtoupper(2100))
            ->setEndVertex(strtoupper(2102));
        $this->assertInstanceOf('\Zend\Stdlib\SplStack', $this->object->getPath());
        $this->assertInstanceOf('\Zend\Stdlib\SplStack', $this->object->getPath());
    }

    /**
     * @covers Application\Math\Graph::getDistance
     * @covers Application\Math\Graph::solve
     */
    public function testGetDistance()
    {
        $this->object->addEdge(2100, 2101, 10);
        $this->object->addEdge(2101, 2102, 5);
        $this->object->addEdge(2101, 2100, 10);
        $this->object->addEdge(2102, 2101, 5);
        $this->object
            ->setStartVertex(strtoupper(2100))
            ->setEndVertex(strtoupper(2102));
        $this->assertSame(15, $this->object->getDistance());
    }

    /**
     * @covers Application\Math\Graph::setStartVertex
     */
    public function testSetStartVertex()
    {
        $this->assertSame($this->object, $this->object->setStartVertex(2100));
    }

    /**
     * @covers Application\Math\Graph::setEndVertex
     */
    public function testSetEndVertex()
    {
        $this->assertSame($this->object, $this->object->setEndVertex(2102));
    }
}