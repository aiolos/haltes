<?php

namespace Application\Form;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-06-30 at 14:24:20.
 */
class SearchFormTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var SearchForm
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SearchForm;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Application\Form\SearchForm::getServiceLocator
     */
    public function testGetServiceLocator()
    {
        $serviceManager = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->assertNull($this->object->getServiceLocator());
        $this->object->setServiceLocator($serviceManager);
        $this->assertEquals($serviceManager, $this->object->getServiceLocator());
    }

    /**
     * @covers Application\Form\SearchForm::setServiceLocator
     */
    public function testSetServiceLocator()
    {
        $serviceManager = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->assertEquals($this->object, $this->object->setServiceLocator($serviceManager));
    }

    /**
     * @covers \Application\Form\SearchForm::__construct
     */
    public function test__construct()
    {
        $this->assertInstanceOf('Application\Form\SearchForm', $this->object);
    }
}
