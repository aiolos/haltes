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
use Application\Math\Coordinate;
use Application\Math\Graph;

/**
 * BusstopController
 *
 * @category Application
 * @package  Application\Controller
 * @author   Henri de Jong <henri.dejong@regiecentrale.nl>
 * @license  http://www.mobiliteitsfabriek.nl/license Commercial License
 * @link     http://www.mobiliteitsfabriek.nl
 */
class BusstopController extends AbstractActionController
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
        'Zend\View\Model\JsonModel' => array('text/html'),
        'Zend\View\Model\JsonModel' => array('application/json')
    );

    /**
     *
     * @return Zend\View\Model\ModelInterface
     */
    public function indexAction()
    {
        $viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);
        $params = array();
        if (!is_null($this->params()->fromQuery('maxResults'))) {
            $limit = $this->params()->fromQuery('maxResults');
        } else {
            $limit = 50;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
                ->select('b')
                ->from('Application\Entity\BusStop', 'b');
        if ($this->params()->fromQuery('town')) {
            $params['town'] = $this->params()->fromQuery('town');
            $queryBuilder->andWhere('b.town = :town');
            $queryBuilder->setParameter('town', $params['town']);
        }
        if ($this->params()->fromQuery('code')) {
            $params['code'] = $this->params()->fromQuery('code');
            $queryBuilder->andWhere('b.code = :code');
            $queryBuilder->setParameter('code', $params['code']);
        }
        if ($this->params()->fromQuery('operator')) {
            $params['operator'] = $this->params()->fromQuery('operator');
            $queryBuilder->andWhere('b.operator = :operator');
            $queryBuilder->setParameter('operator', $params['operator']);
        }
        if ($this->params()->fromQuery('name')) {
            $params['name'] = $this->params()->fromQuery('name');
            $queryBuilder->andWhere('b.name = :name');
            $queryBuilder->setParameter('name', $params['name']);
        }
        if ($this->params()->fromQuery('description')) {
            $parameters = explode(',', $this->params()->fromQuery('description'));
            $params['name'] = trim((array_pop($parameters)));
            $params['town'] = (array_pop($parameters));
            $queryBuilder->andWhere('b.name = :name');
            $queryBuilder->andWhere('b.town = :town');
            $queryBuilder->setParameter('name', $params['name']);
            $queryBuilder->setParameter('town', $params['town']);
        }

        $queryBuilder->setMaxResults($limit);
        $busstops = $queryBuilder->getQuery()->getResult();
        $countbs = $queryBuilder->select('count(b)')->setFirstResult(0)->getQuery()->getSingleScalarResult();

        $centerLat = 0;
        $centerLon = 0;
        foreach ($busstops as $busstop) {
            $centerLat += $busstop->getLatitude();
            $centerLon += $busstop->getLongitude();
        }

        $center = new \stdClass();

        if ($busstops) {
            $center->latitude = $centerLat / count($busstops);
            $center->longitude = $centerLon / count($busstops);
        }
        $metadata['pageSize'] = $limit;
        $metadata['parameters'] = $params;
        $metadata['totalResults'] = $countbs;

        return $viewModel->setVariables(
            array(
                'busstops' => $busstops,
                'center' => $center,
                'metadata' => $metadata
            )
        );
    }

    /**
     *
     * @return Zend\View\Model\ModelInterface
     */
    public function viewAction()
    {
        $viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);
        $params = array();
        if (!is_null($this->params('code'))) {
            $params['code'] = $this->params('code');
        }
        if ($this->getRequest()->isPost()) {
            $postVars = $this->getRequest()->getPost();
            $code = $postVars['busstopFaux'];
            $this->redirect()->toUrl("/busstop/" . $code);
        }
        $busstop = $this->getEntityManager()->getRepository('Application\Entity\BusStop')->findOneBy($params);
        $form = new \Application\Form\SearchForm();
        if ($viewModel instanceof \Zend\View\Model\JsonModel) {
            return $viewModel->setVariables(
                array(
                    'busstop' => $busstop
                )
            );
        } else {
            return $viewModel->setVariables(
                array(
                    'busstop' => $busstop,
                    'form' => $form
                )
            );
        }
    }

    /**
     *
     * @return Zend\View\Model\ModelInterface
     */
    public function distanceAction()
    {
        $viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);

        if (!is_null($this->params('codeFrom'))) {
            $params = array();
            $params['code'] = $this->params('codeFrom');
            $busstopFrom = $this->getEntityManager()->getRepository('Application\Entity\BusStop')->findOneBy($params);
            if (is_null($busstopFrom)) {
                throw new \Exception('From busstop not found');
            }
        }
        if (!is_null($this->params('codeTo'))) {
            $params = array();
            $params['code'] = $this->params('codeTo');
            $busstopTo = $this->getEntityManager()->getRepository('Application\Entity\BusStop')->findOneBy($params);
            if (is_null($busstopTo)) {
                throw new \Exception('To busstop not found');
            }
        }
        if ($this->getRequest()->isPost()) {
            $postVars = $this->getRequest()->getPost();
            $codeFrom = $postVars['busstopFrom'];
            $codeTo = $postVars['busstopTo'];
            $this->redirect()->toUrl("/distance/" . $codeFrom . "/" . $codeTo);
        }

        $centerLat = ($busstopFrom->getLatitude() + $busstopTo->getLatitude()) / 2;
        $centerLon = ($busstopFrom->getLongitude() + $busstopTo->getLongitude()) / 2;

        $coordinateCalculator = new Coordinate();
        $distance = $coordinateCalculator->haversineGreatCircleDistance(
            $busstopFrom->getLatitude(),
            $busstopFrom->getLongitude(),
            $busstopTo->getLatitude(),
            $busstopTo->getLongitude()
        );

        $form = new \Application\Form\SearchForm();
        return $viewModel->setVariables(
            array(
                'busstopFrom' => $busstopFrom,
                'busstopTo' => $busstopTo,
                'distance' => $distance,
                'centerLat' => $centerLat,
                'centerLon' => $centerLon,
                'form' => $form
            )
        );
    }

    /**
     *
     * @return type
     * @throws \Exception
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function pathAction()
    {
        $viewModel = $this->acceptableViewModelSelector($this->acceptCriteria);

        if ($this->getRequest()->isPost()) {
            $postVars = $this->getRequest()->getPost();
            $from = $postVars['busstopFrom'];
            $to = $postVars['busstopTo'];
        } else {
            $from = $this->params('from');
            $to = $this->params('to');
        }

        $entityManager = $this->getEntityManager();
        $busstopFrom = $entityManager->getRepository('Application\Entity\BusStop')->findOneBy(array('code' => $from));
        $busstopTo = $entityManager->getRepository('Application\Entity\BusStop')->findOneBy(array('code' => $to));

        $form = new \Application\Form\SearchForm();

        if (!$busstopFrom || !$busstopTo) {
            $message = 'Error:';
            if (!$busstopFrom) {
                $message .= sprintf(' Stop %s not found.', $from);
            }
            if (!$busstopTo) {
                $message .= sprintf(' Stop %s not found.', $to);
            }
            //$this->getResponse()->setStatusCode(\Zend\Http\Response::STATUS_CODE_404);

            return $viewModel->setVariables(
                array(
                    'error' => $message,
                    'path' => null,
                    'distance' => null,
                    'busstopFrom' => null,
                    'busstopTo' => null,
                    'flightDistance' => null,
                    'centerLat' => null,
                    'centerLon' => null,
                    'birdDistance' => null,
                    'form' => $form
                )
            );
        }

        $graph = new Graph();
        $trackRepo = $this->getEntityManager()->getRepository('Application\Entity\Track');
        $trackStarts = $trackRepo->findBy(array('leftBusStop' => $from));
        $trackEnds = $trackRepo->findBy(array('rightBusStop' => $to));
        $ptos = array();
        foreach ($trackStarts as $trackStart) {
            $ptos[] = $trackStart->getPto();
        }
        foreach ($trackEnds as $trackEnd) {
            $ptos[] = $trackEnd->getPto();
        }
        if (count($ptos) == 0) {
            return $viewModel->setVariables(
                array(
                    'error' => 'No pto\'s found.',
                )
            );
        }

        $params = array('pto' => $ptos);
        $tracks = $entityManager->getRepository('Application\Entity\Track')->findBy($params);

        $centerLat = ($busstopFrom->getLatitude() + $busstopTo->getLatitude()) / 2;
        $centerLon = ($busstopFrom->getLongitude() + $busstopTo->getLongitude()) / 2;
        $center = new \stdClass();
        $center->latitude = $centerLat;
        $center->longitude = $centerLon;

        foreach ($tracks as $track) {
            $graph->addEdge(
                $track->getLeftBusStop()->getCode(),
                $track->getRightBusStop()->getCode(),
                $track->getDistance()
            );
        }

        $graph
            ->setStartVertex(strtoupper($from))
            ->setEndVertex(strtoupper($to));

        $path = array();
        $repository = $this->getEntityManager()->getRepository('Application\Entity\BusStop');

        foreach ($graph->getPath()->toArray() as $code) {
            $path[] = $repository->findOneByCode($code);
        }

        $distance = ($graph->getDistance());
        $coordinateCalculator = new Coordinate();
        $flightDistance = null;
        if (is_object($busstopFrom) && is_object($busstopTo)) {
            $flightDistance = $coordinateCalculator->haversineGreatCircleDistance(
                $busstopFrom->getLatitude(),
                $busstopFrom->getLongitude(),
                $busstopTo->getLatitude(),
                $busstopTo->getLongitude()
            );
        }
        $cCalculator = new Coordinate();
        $birdDistance = 0;
        if (!is_null($path[0])) :
            $queue = new \SplQueue;

            foreach ($path as $trainStation) {
                $queue->enqueue($trainStation);
            }
            $from = $queue->dequeue();
            while ($queue->count() > 0) {
                $to = $queue->dequeue();

                $birdDistance += $cCalculator->haversineGreatCircleDistance(
                    $from->getLatitude(),
                    $from->getLongitude(),
                    $to->getLatitude(),
                    $to->getLongitude()
                );

                $from = $to;
            }
        endif;


        if ($viewModel instanceof \Zend\View\Model\JsonModel) {
            return $viewModel->setVariables(
                array(
                    'path' => $path,
                    'distance' => $distance,
                    'busstopFrom' => $busstopFrom,
                    'busstopTo' => $busstopTo,
                    'flightDistance' => $flightDistance,
                    'center' => $center,
                    'birdDistance' => $birdDistance
                )
            );
        } else {
            return $viewModel->setVariables(
                array(
                    'path' => $path,
                    'distance' => $distance,
                    'busstopFrom' => $busstopFrom,
                    'busstopTo' => $busstopTo,
                    'flightDistance' => $flightDistance,
                    'center' => $center,
                    'birdDistance' => $birdDistance,
                    'form' => $form
                )
            );
        }
    }

    /**
     * Get the entity manager
     * @return \Doctrine\ORM\EntityManager
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
