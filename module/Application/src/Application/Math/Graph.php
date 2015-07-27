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
namespace Application\Math;

use \SplPriorityQueue;
use SplStack;

class Graph
{
    protected $vertices = array();

    protected $solved = false;

    protected $startVertex;

    protected $endVertex;

    protected $distance = INF;

    protected $path = array();

    public function __construct($vertices = array())
    {
        $this->vertices = $vertices;
    }

    public function addEdge($start, $end, $weight = 0)
    {
        $this->solved = false;
        if (!isset($this->vertices[$start])) {
            $this->vertices[$start] = array();
        }
        $this->vertices[$start][$end] = $weight;
    }

    protected function solve()
    {
        if ($this->solved) {
            return;
        }

        $distances = array();
        $visited = array();
        $previous = array();

        foreach ($this->vertices as $vertex => $edges) {
            $distances[$vertex] = INF;
        }
        $distances[$this->startVertex] = 0;

        $queue = new \Zend\Stdlib\SplPriorityQueue();

        $queue->insert($this->startVertex, 0);

        while (!$queue->isEmpty()) {
            $vertex = $queue->extract();
            if (!key_exists($vertex, $this->vertices)) {
                continue;
            }
            foreach ($this->vertices[$vertex] as $adjecentVertex => $cost) {
                if (isset($visited[$adjecentVertex])) {
                    continue;
                }
                $alt = $distances[$vertex] + $cost;

                if (array_key_exists($adjecentVertex, $distances)
                    && $alt < $distances[$adjecentVertex]
                ) {
                    $distances[$adjecentVertex] = $alt;
                    $previous[$adjecentVertex] = $vertex;
                }

                $queue->insert($adjecentVertex, 0 - $alt);
            }
            $visited[$vertex] = true;
        }

        $stack = new \Zend\Stdlib\SplStack();

        $vertex = $this->endVertex;
        while (isset($previous[$vertex])) {
            $stack->push($vertex);
            $vertex = $previous[$vertex];
        }
        $stack->push($this->startVertex);

        $this->distance = $distances[$this->endVertex];
        $this->path = $stack;
        $this->solved = true;
    }

    public function getPath()
    {
        $this->solve();
        return $this->path;
    }

    public function getDistance()
    {
        $this->solve();
        return $this->distance;
    }

    public function setStartVertex($startVertex)
    {
        $this->startVertex = $startVertex;
        return $this;
    }

    public function setEndVertex($endVertex)
    {
        $this->endVertex = $endVertex;
        return $this;
    }
}
