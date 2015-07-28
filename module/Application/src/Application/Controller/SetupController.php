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
use Zend\Console\Request as ConsoleRequest;
use Zend\Http\Client;
use DOMDocument;
use DOMXpath;
use SplFileObject;

use Application\Entity\BusStop;
use Application\Entity\Track;

/**
 * SetupController
 *
 * @category Application
 * @package  Application\Controller
 * @author   Henri de Jong <henri.dejong@regiecentrale.nl>
 * @license  http://www.regiecentrale.nl/license Commercial License
 * @link     http://www.regiecentrale.nl
 *
 * @method \Application\Controller\Plugin\Authoriser Authoriser()
 */
class SetupController extends AbstractActionController
{
    /**
     * The entity manager
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     *
     * @return Zend\View\Model\ModelInterface
     */
    public function indexAction()
    {
        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$this->getRequest() instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }
        $this->deleteStops();
        echo 'Running setup: loadStops.' . PHP_EOL;
        echo 'Importing Arriva Stops.' . PHP_EOL;
        $this->loadStops('data/setup/arriva/stops.txt');
        echo 'Importing Connexxion Stops.' . PHP_EOL;
        $this->loadStops('data/setup/connexxion/stops.txt');
        echo 'Importing EBS Stops.' . PHP_EOL;
        $this->loadStops('data/setup/ebs/stops.txt');
        echo 'Importing GVB Stops.' . PHP_EOL;
        $this->loadStops('data/setup/gvb/stops.txt');
        echo 'Importing HTM Stops.' . PHP_EOL;
        $this->loadStops('data/setup/htm/stops.txt');
        echo 'Importing QBUZZ Stops.' . PHP_EOL;
        $this->loadStops('data/setup/qbuzz/stops.txt');
        echo 'Importing RET Stops.' . PHP_EOL;
        $this->loadStops('data/setup/ret/stops.txt');
        echo 'Importing Syntus Stops.' . PHP_EOL;
        $this->loadStops('data/setup/syntus/stops.txt');
        echo 'Importing Veolia Stops.' . PHP_EOL;
        $this->loadStops('data/setup/veolia/stops.txt');
        echo 'Running setup: completed loadStops.' . PHP_EOL;

        echo 'Running setup: loadTracks.' . PHP_EOL;
        $this->loadTracks();
        echo 'Running setup: completed loadTracks.' . PHP_EOL;
    }

    /**
     * Get the entity manager
     * @return Doctrine\ORM\EntityManager
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

    protected function deleteStops()
    {
        echo 'Deleting Stops.' . PHP_EOL;
        $stops = $this->getEntityManager()->getRepository('Application\Entity\BusStop')->findBy(array());
        foreach ($stops as $stop) {
            $this->getEntityManager()->remove($stop);
        }
        $this->getEntityManager()->flush();
    }

    protected function loadStops($file)
    {
        echo 'Loading Stops:';
        $csv = new SplFileObject($file);
        $csv->setFlags(SplFileObject::READ_CSV);
        $counter = 0;
        $first = true;
        foreach ($csv as $row) {
            if (implode('', $row) == '') {
                continue;
            }
            if ($first) {
                $first = false;
                continue;
            }
            $stopcode = $row[1];
            if (!empty($stopcode)) {
                $stopName = explode(',', $row[2]);
                $operator = explode(':', $row[0]);
                $stop = new BusStop();
                $stop
                    ->setCode($row[0])
                    ->setName(trim($stopName[1]))
                    ->setTown(trim($stopName[0]))
                    ->setCountry('NL')
                    ->setLatitude($row[3])
                    ->setLongitude($row[4])
                    ->setType($row[5])
                    ->setParent($row[6])
                    ->setWheelchair($row[8])
                    ->setOperator($operator[0]);

                $this->getEntityManager()->persist($stop);
                //echo "codename:" . $row[0];
                echo '.';
                $counter++;
                if ($counter == 2000) {
                    $counter = 0;
                    $this->getEntityManager()->flush();
                    echo '|';
                }
            }
        }

        $this->getEntityManager()->flush();

        echo PHP_EOL;
        echo 'Loading stops complete' . PHP_EOL;

        return true;
    }

    protected function loadTracks()
    {
        echo 'Deleting Distances.' . PHP_EOL;
        $tracks = $this->getEntityManager()->getRepository('Application\Entity\Track')->findBy(array());
        foreach ($tracks as $track) {
            $this->getEntityManager()->remove($track);
        }
        $this->getEntityManager()->flush();

        echo 'Loading Distances:';
        $counter = 0;
        $csv = new SplFileObject('data/setup/distance.csv');
        $csv->setFlags(SplFileObject::READ_CSV);
        $first = true;
        foreach ($csv as $row) {
            if (implode('', $row) == '') {
                break;
            }
            if ($first) {
                $first = false;
                continue;
            }
            $track = new Track();
            $repository = $this->getEntityManager()->getRepository('Application\Entity\BusStop');
            $leftBusStopElements = explode(':', $row[1]);
            //$rightBusStopElements = explode(':', $row[3]);

            $leftBusStop = $repository->findOneByCode($row[1]);
            $rightBusStop = $repository->findOneByCode($row[3]);
            if (is_null($rightBusStop) || strlen($rightBusStop->getCode()) == 0) {
                echo PHP_EOL . 'Right busstop not found: ' . $row[3] . PHP_EOL;
                continue;
            }
            if (is_null($leftBusStop) || strlen($leftBusStop->getCode()) == 0) {
                echo PHP_EOL . 'Left busstop not found: ' . $row[1] . PHP_EOL;
                continue;
            }
            $track
                ->setLeftBusStop($leftBusStop)
                ->setRightBusStop($rightBusStop)
                ->setPto($leftBusStopElements[0])
                ->setDistance($row[4]);

            if (is_null($track->getLeftBusStop()->getCode())) {
                echo PHP_EOL . 'Left busstop code is null: ' . $row[1] . PHP_EOL;
                continue;
            } elseif (is_null($track->getRightBusStop()->getCode())) {
                echo PHP_EOL . 'Right busstop code is null: ' . $row[3] . PHP_EOL;
                continue;
            } else {
                $this->getEntityManager()->persist($track);
                echo '.';
                $counter++;
                if ($counter == 1000) {
                    $counter = 0;
                    $this->getEntityManager()->flush();
                    echo '|';
                }
            }
        }

        $this->getEntityManager()->flush();

        echo PHP_EOL;
        echo 'Loading distances complete' . PHP_EOL;
    }
}
