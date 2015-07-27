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
use Zend\Json\Json;

/**
 * Busstop
 *
 * The busstop entity represents a real world dutch busstop
 *
 * @category Application
 * @package  Application\Entity
 * @author   Henri de Jong <henri.dejong@regiecentrale.nl>
 * @license  http://www.mobiliteitsfabriek.nl/license Commercial License
 * @link     http://www.mobiliteitsfabriek.nl
 *
 * @ORM\Entity
 * @ORM\Table(name="bus_stops")
 */
class BusStop
{
    /**
     *
     * @var string
     * ORM\Id
     * ORM\GeneratedValue
     * ORM\Column(name="id", type="integer")
     */
    //protected $id;

    /**
     *
     * @var string
     * @ORM\Id
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    protected $code;

    /**
     *
     * @var string
     * @ORM\Column(name="operator", type="string", length=255)
     */
    protected $operator;

    /**
     *
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="town", type="string", length=255)
     */
    protected $town;

    /**
     *
     * @var string
     * @ORM\Column(name="country", type="string", length=2)
     */
    protected $country;

    /**
     *
     * @var string
     * @ORM\Column(name="latitude", type="float")
     */
    protected $latitude;

    /**
     *
     * @var string
     * @ORM\Column(name="longitude", type="float")
     */
    protected $longitude;

    /**
     *
     * @var string
     * @ORM\Column(name="type", type="integer")
     */
    protected $type;

    /**
     * ORM\OneToOne(targetEntity="BusStop")
     * ORM\JoinColumn(name="parent_id", referencedColumnName="code", nullable=true)
     * @ORM\Column(name="parent_code", type="string", length=255)
     */
    protected $parent;

    /**
     *
     * @var string
     * @ORM\Column(name="wheelchair", type="integer", nullable=true)
     */
    protected $wheelchair;

    /**
     *
     * @var string
     * @ORM\Column(name="platform_code", type="integer", nullable=true)
     */
    protected $platform_code;

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
    public function getOperator()
    {
        return $this->operator;
    }

    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    public function getPto()
    {
        $values = explode(':', $this->getCode());
        return $values[0];
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getTown()
    {
        return $this->town;
    }

    public function setTown($town)
    {
        $this->town = $town;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getWheelchair()
    {
        return $this->wheelchair;
    }

    public function setWheelchair($wheelchair)
    {
        $this->wheelchair = $wheelchair;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function toJson()
    {
        $array = array(
            "operator" => $this->getOperator(),
            "code" => $this->getCode(),
            "name" => $this->getName(),
            "town" => $this->getTown(),
            "country" => $this->getCountry(),
            "longitude" => $this->getLongitude(),
            "latitude" => $this->getLatitude(),
            "parent_code" => $this->getParent(),
            "wheelchair" => $this->getWheelchair()
        );
        return Json::Encode($array);
    }
}