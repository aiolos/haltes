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

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;

class PathForm extends Form implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct($name);

        $this->setAttribute('class', 'formPath');
        $this->setAttribute('method', 'post');
        $this->setAttribute('name', 'path');

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'busstopFrom',
            'attributes' => array(
                'id' => 'busstopFrom',
                'placeholder' => 'Haltecode',
                'class' => 'form-control',
                'autocomplete' => false,
                'required' => false
            ),
            'options' => array(
                'label' => 'Vanaf station'
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'busstopTo',
            'attributes' => array(
                'id' => 'busstopTo',
                'placeholder' => 'Haltecode',
                'class' => 'form-control',
                'autocomplete' => false,
                'required' => false
            ),
            'options' => array(
                'label' => 'naar halte'
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
