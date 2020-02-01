<?php
declare(strict_types=1);
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * App\Entity\Walk
 * extends Activity
 * @author Geoff Chapman <geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="walk")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WalkRepository")
 */
class Walk extends Activity {

	/**
     * Unique walk identifier
     * @var Activity
     * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
     * Short description of the walk
     * @var string
     * 
	 * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity"})
	 */
	protected $shortDescription = null;
	
	/**
     * Descriptive location of the walk
     * @var string
     * 
	 * @ORM\Column(type="text",nullable=true)
     * @Groups({"activity"})
	 */
	protected $location = null;

	/**
     * Distance of the walk in kilometres
     * @var float
     * 
	 * @ORM\Column(type="float", precision=5, scale=2, nullable=true)
     * @Assert\Type(type="float")
     * @Groups({"activity"})
	 */
	protected $distance = null;

    /**
     * Distance of walk in miles
     * Not persisted, generated from distance
     * @var float
     * @Groups({"activity"})
     */
    protected $distanceMiles;

	/**
     * Minimum time of the walk (hours)
     * @var integer
     * 
	 * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer")
     * @Groups({"activity"})
	 */
	protected $minimumTimeHours = null;

    /**
     * Minimum time of the walk (minutes)
     * @var integer
     * 
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer")
     * @Groups({"activity"})
     */
    protected $minimumTimeMinutes = null;

	/**
     * Total ascent of the walk (metres)
     * @var integer
     * 
	 * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer")
     * @Groups({"activity"})
	 */
	protected $ascent = null;

    /**
     * Total ascent of the walk (feet)
     * NB: Not persisted, generated from ascent
     * @var integer
     */
    protected $ascentFeet;
    
	/**
     * Gradient of the walk (0,1,2,3)
     * @var integer
     * 
	 * @ORM\Column(type="integer", nullable=true)
     * @Assert\Choice({0,1,2,3})
     * @Groups({"activity"})
	 */
	protected $gradient = null;

	/**
     * Degree of difficulty of the walk (1,2,3)
     * @var integer
     * 
	 * @ORM\Column(type="integer", nullable=true)
     * @Assert\Choice({1,2,3})
     * @Groups({"activity"})
	 */
	protected $difficulty = null;

	/**
     * Description of paths on the walk
     * @var string
     * 
	 * @ORM\Column(type="string", length=500, nullable=true)
     * @Groups({"activity"})
	 */
	protected $paths = null;

	/**
     * Description of landscape on the walk
     * @var string
     * 
	 * @ORM\Column(type="string", length=500, nullable=true)
     * @Groups({"activity"})
	 */
	protected $landscape = null;

	/**
     * Grid reference of the walk finish point (if different from start)
     * @var string
     * 
	 * @ORM\Column(type="string", length=45, nullable=true)
     * @Assert\Regex("/^([STNHOstnho][A-Za-z]\s?)(\d{5}\s?\d{5}|\d{4}\s?\d{4}|\d{3}\s?\d{3}|\d{2}\s?\d{2}|\d{1}\s?\d{1})$/", message="This value is not a valid grid reference")
     * @Groups({"activity"})
	 */
	protected $finishGridRef = null;

	/**
     * Description of dog friendliness of the walk
     * @var string
     * 
	 * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity"})
	 */
	protected $dogFriendliness  = null;

	/**
     * Description of parking on the walk
     * @var string
     * 
	 * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity"})
	 */
	protected $parking  = null;

	/**
     * Description of public toilets on the walk
     * @var string
     * 
	 * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity"})
	 */
	protected $publicToilet = null;

	/**
     * Notes about the walk
     * @var string
     *
	 * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity"})
	 */
	protected $notes = null;

	/**
     * What to look out for information on the walk
     * @var string
     * 
	 * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity"})
	 */
	protected $whatToLookOutFor = null;

	/**
     * Where to eat and drink information on the walk
     * @var string
     * 
	 * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity"})
	 */
	protected $whereToEatAndDrink = null;

	/**
     * While you are there information on the walk
     * @var string
     * 
	 * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity"})
	 */
	protected $whileYouAreThere = null;

    /**
     * Extension associated to the walk
     * @var string
     *
     * @ORM\Column(name="extension", nullable=true)
     * @Groups({"activity"})
     */
    protected $extension;


    public function setShortDescription(string $shortDescription = null) :self {

        $this->shortDescription = $shortDescription;
        return $this;
    }

    public function getShortDescription() :?string {

        return $this->shortDescription;
    }

    public function setLocation(string $location = null) :self {

        $this->location = $location;
        return $this;
    }

    public function getLocation() :?string {

        return $this->location;
    }

    public function setDistance(float $distance = null) :self {

        $this->distance = $distance;
        return $this;
    }

    public function getDistance() :?float {

        return $this->distance;
    }

    public function setDistanceMiles(float $distanceMiles = null) :self {

        $this->distanceMiles = $distanceMiles;
        return $this;
    }

    public function getDistanceMiles() :?float {

        return $this->distanceMiles;
    }

    public function setMinimumTimeHours(int $minimumTimeHours = null) :self {

        $this->minimumTimeHours = $minimumTimeHours;
        return $this;
    }

    public function getMinimumTimeHours() :?int {

        return $this->minimumTimeHours;
    }

    public function setMinimumTimeMinutes(int $minimumTimeMinutes = null) :self {

        $this->minimumTimeMinutes = $minimumTimeMinutes;
        return $this;
    }

    public function getMinimumTimeMinutes() :?int {

        return $this->minimumTimeMinutes;
    }

    public function setAscent(int $ascent = null) :self {

        $this->ascent = $ascent;
        return $this;
    }

    public function getAscent() :?int {

        return $this->ascent;
    }

    public function setAscentFeet (int $ascentFeet = null) :self {

        $this->ascentFeet = $ascentFeet;
        return $this;
    }

    public function getAscentFeet() :?int {

        return $this->ascent;
    }

    public function setGradient(int $gradient = null) :self{

        $this->gradient = $gradient;
        return $this;
    }

    public function getGradient() :?int {

        return $this->gradient;
    }

    public static function getGradients() :array {

        return array(0,1,2,3);
    }

    public function setDifficulty(int $difficulty = null) :self {

        $this->difficulty = $difficulty;
        return $this;
    }

    public function getDifficulty() :?int {
        
        return $this->difficulty;
    }

    public static function getDifficulties() :array {

        return array(1,2,3);
    }

    public function setPaths(string $paths = null) :self {

        $this->paths = $paths;
        return $this;
    }

    public function getPaths() :?string {

        return $this->paths;
    }

    public function setLandscape(string $landscape = null) :self {

        $this->landscape = $landscape;
        return $this;
    }

    public function getLandscape() :?string {

        return $this->landscape;
    }

    public function setFinishGridRef(string $finishGridRef = null) :self {

        $this->finishGridRef = $finishGridRef;
        return $this;
    }

    public function getFinishGridRef() :?string {

        return $this->finishGridRef;
    }

    public function setDogFriendliness(string $dogFriendliness = null) :self {

        $this->dogFriendliness = $dogFriendliness;
        return $this;
    }

    public function getDogFriendliness() :?string {

        return $this->dogFriendliness;
    }

    public function setParking(string $parking = null) :self {

        $this->parking = $parking;
        return $this;
    }

    public function getParking() :?string {

        return $this->parking;
    }

    public function setPublicToilet(string $publicToilet = null) :self {

        $this->publicToilet = $publicToilet;
        return $this;
    }

    public function getPublicToilet() :?string {

        return $this->publicToilet;
    }

    public function setNotes(string $notes = null) :self {

        $this->notes = $notes;
        return $this;
    }

    public function getNotes() :?string {

        return $this->notes;
    }

    public function setWhatToLookOutFor(string $whatToLookOutFor = null) :self {

        $this->whatToLookOutFor = $whatToLookOutFor;
        return $this;
    }

    public function getWhatToLookOutFor() :?string {

        return $this->whatToLookOutFor;
    }

    public function setWhereToEatAndDrink(string $whereToEatAndDrink = null) :self {

        $this->whereToEatAndDrink = $whereToEatAndDrink;
        return $this;
    }

    public function getWhereToEatAndDrink() :?string {

        return $this->whereToEatAndDrink;
    }

    public function setWhileYouAreThere(string $whileYouAreThere = null) :self {

        $this->whileYouAreThere = $whileYouAreThere;
        return $this;
    }

    public function getWhileYouAreThere() :?string {

        return $this->whileYouAreThere;
    }

    public function setExtension(string $extension = null) :self {
        
        $this->extension = $extension;
        return $this;
    }

    public function getExtension() :?string {

        return $this->extension;
    }

    /**
     * @return string
     * @Serializer\VirtualProperty()
     * @Groups({"activity"})
     */
    public function getActivityType() :string {

        return $this::TYPE_WALK;
    }
}
