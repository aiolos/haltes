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
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
