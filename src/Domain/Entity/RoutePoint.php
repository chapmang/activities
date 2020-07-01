<?php
namespace App\Domain\Entity;

use App\Domain\Traits\EntityTimeBlameTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * App\Domain\Entity\RoutePoint
 * @author Geoff Chapman <geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="route_point")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\RoutePointRepository")
 */
class RoutePoint {

    use EntityTimeBlameTrait;

	/**
     * Unique route point identifier
     * @var integer
     * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */	
	protected $id;

	/**
     * Related activity
     * @var Activity
     * 
	 * @ORM\ManyToOne(targetEntity="Activity", inversedBy="routePoints")
	 * @ORM\JoinColumn(name="activity", referencedColumnName="id", nullable=false)
	 */
	protected $activity;

	/**
     * Order of point on route
     * @var integer
     * 
	 * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
	 */
	protected $routeOrder;

	/**
     * Latitude of route point
     * @var float
     * 
	 * @ORM\Column(type="decimal", precision=10, scale=8, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex("/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/", message="This value is not a valid Latitude")
	 */
	protected $latitude;

	/**
     * Longitude of route point
     * @var float
	 * @ORM\Column(type="decimal", precision=11, scale=8, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex("/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/", message="This value is not a valid Longitude")
	 */
	protected $longitude;

	/**
     * Direction associated with the route point
     * @var Direction
     * 
	 * @ORM\OneToOne(targetEntity="Direction")
	 * @ORM\JoinColumn(name="direction", referencedColumnName="id", nullable=true)
	 */
	protected $direction;

    /**
     * Get id
     * @return integer 
     */
    public function getId() {

        return $this->id;
    }

    /**
     * Set routeOrder
     * @param integer $routeOrder
     * @return RoutePoint
     */
    public function setRouteOrder($routeOrder) {

        $this->routeOrder = $routeOrder;
        return $this;
    }

    /**
     * Get routeOrder
     * @return integer 
     */
    public function getRouteOrder() {

        return $this->routeOrder;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return RoutePoint
     */
    public function setLatitude($latitude) {

        $this->latitude = $latitude;
        return $this;
    }

    /**
     * Get latitude
     * @return float
     */
    public function getLatitude() {

        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return RoutePoint
     */
    public function setLongitude($longitude) {

        $this->longitude = $longitude;
        return $this;
    }

    /**
     * Get longitude
     * @return float
     */
    public function getLongitude() {

        return $this->longitude;
    }

    /**
     * Set activity
     * @param Activity $activity
     * @return RoutePoint
     */
    public function setActivity(Activity $activity) {
        
        $this->activity = $activity;
        return $this;
    }

    /**
     * Get activity
     * @return Activity
     */
    public function getActivity() {

        return $this->activity;
    }

    /**
     * Set direction
     * @param Direction $direction
     * @return RoutePoint
     */
    public function setDirection(Direction $direction) {
       
        $this->direction = $direction;
        return $this;
    }

    /**
     * Get direction
     * @return Direction
     */
    public function getDirection() {

        return $this->direction;
    }

}
