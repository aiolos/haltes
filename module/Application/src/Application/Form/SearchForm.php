<?php
/**
 * Haltes
 *
 * PHP Version 5.3
 *
 * @category  Haltes
 * @package   Application\Form
 * @author    Henri de Jong <henri.dejong@mobiliteitsfabriek.nl>
 * @author    Peter Lammers <peter.lammers@mobiliteitsfabriek.nl>
 * @copyright 2013 Regiecentrale
 * @license   http://www.regiecentrale.nl/license Commercial License
 * @version   SVN: $Id$
 * @link      http://www.regiecentrale.nl
 */

namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SearchForm extends Form implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function __construct()
    {
        // we want to ignore the name passed
        parent::__construct('busstop');

        $this->setAttribute('class', 'formSearch');
        $this->setAttribute('method', 'post');
        $this->setAttribute('name', 'search');

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'key',
            'options' => array(
                'label' => 'Key',
                'value_options' => array(
                    'code' => 'Haltecode',
                    'operator' => 'Operator',
                    'town' => 'Town',
                    'description' => 'Description'
                ),
            ),
            'attributes' => array(
                'id' => 'key',
                'value' => 'code'
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'searchvalue',
            'attributes' => array(
                'id' => 'searchvalue',
                'placeholder' => 'Searchvalue',
                'class' => 'form-control',
                'autocomplete' => false,
                'required' => false
            ),
            'options' => array(
                'label' => 'stad'
            ),
        ));
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Submit',
                'name' => 'submit',
                'attributes' => array(
                    'type'  => 'submit',
                    'value' => 'Zoek',
                    'id' => 'submitbutton',
                ),
            )
        );
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
}
