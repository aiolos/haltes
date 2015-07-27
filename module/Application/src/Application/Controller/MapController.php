<?php
/**
 * Haltes
 *
 * PHP Version 5.5
 *
 * @category  Haltes
 * @package   Application\Form
 * @author    Henri de Jong <henri.dejong@mobiliteitsfabriek.nl>
 * @author    Peter Lammers <peter.lammers@mobiliteitsfabriek.nl>
 * @copyright 2015 Mobiliteitsfabriek
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL
 * @version   SVN: $Id$
 * @link      http://www.mobiliteitsfabriek.nl
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * MapController
 *
 * @category Application
 * @package  Application\Controller
 * @author   Henri de Jong <henri.dejong@regiecentrale.nl>
 * @license  http://www.mobiliteitsfabriek.nl/license Commercial License
 * @link     http://www.mobiliteitsfabriek.nl
 */
class MapController extends AbstractActionController
{
    /**
     * The entity manager
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * The view accept criteria
     * @var mixed
     */
    protected $acceptCriteria = array(
        'Zend\View\Model\ViewModel' => array('text/html')
    );

    /**
     *
     * @return Zend\View\Model\ModelInterface
     */
    public function indexAction()
    {
        $viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);
        $form = $this->getServiceLocator()->get('FormElementManager')->get('SearchForm');
        $pathform = $this->getServiceLocator()->get('FormElementManager')->get('PathForm');
        return $viewModel->setVariables(
            array(
                'form' => $form,
                'pathform' => $pathform
            )
        );
    }

    /**
     * Get the entity manager
     * @return Doctrine\ORM\\EntityManager
     */
    public function getEntityManager()
    {
        if (!$this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }

    /**
     * Get the entity manager
     * @param Doctrine\ORM\EntityManager $entityManager
     * @return \Application\Controller\DepartmentController
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }
}
