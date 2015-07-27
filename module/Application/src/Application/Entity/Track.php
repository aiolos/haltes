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

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 *
 * @ORM\Entity
 * @ORM\Table(name="tracks")
 */
class Track
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="BusStop")
     * @ORM\JoinColumn(name="left_bus_stop", referencedColumnName="code", nullable=false)
     */
    protected $leftBusStop;

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="BusStop")
     * @ORM\JoinColumn(name="right_bus_stop", referencedColumnName="code", nullable=false)
     * @var BusStop
     */
    protected $rightBusStop;

    /**
     * @ORM\Column(name="distance", type="integer")
     */
    protected $distance;

    /**
     * @ORM\Id
     * @ORM\Column(name="pto", type="string", length=20)
     */
    protected $pto;

    public function getId()
    {
        return $this->id;
    }

    public function getLeftBusStop()
    {
        return $this->leftBusStop;
    }

    public function setLeftBusStop($leftBusStop)
    {
        $this->leftBusStop = $leftBusStop;
        return $this;
    }

    public function getRightBusStop()
    {
        return $this->rightBusStop;
    }

    public function setRightBusStop($rightBusStop)
    {
        $this->rightBusStop = $rightBusStop;
        return $this;
    }

    public function getDistance()
    {
        return $this->distance;
    }

    public function setDistance($distance)
    {
        $this->distance = $distance;
        return $this;
    }

    public function getPto()
    {
        return $this->pto;
    }

    public function setPto($pto)
    {
        $this->pto = $pto;
        return $this;
    }
}
