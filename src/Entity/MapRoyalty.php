<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * App\Entity\MapRoyalty
 * @author Geoff Chapman <geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="map_royalty")
 * @ORM\Entity(repositoryClass="App\Repository\MapRoyaltyRepository")
 */
class MapRoyalty {
	
	/**
     * Unique map royalty identifier
     * @var integer
     * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"activity"})
	 */	
	protected $id;

    /**
     * Related activity
     * @var Activity
     *
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="directions")
     * @ORM\JoinColumn(name="activity", referencedColumnName="id", nullable=false)
     */
    protected $activity;

	/**
     * Map width (mm)
     * @var float
     * 
	 * @ORM\Column(type="float", nullable=true)
     * @Assert\Type(type="float")
     * @Groups({"activity"})
	 */
	protected $width = null;

	/**
     * Map height (mm)
     * @var float
     * 
	 * @ORM\Column(type="float", nullable=true)
     * @Assert\Type(type="float")
     * @Groups({"activity"})
	 */
	protected $height = null;

	/**
     * Final map scale
     * @var integer
     * 
	 * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer")
     * @Groups({"activity"})
	 */
	protected $mapScale = null;

	/**
     * Source map scale
     * @var integer
     * 
	 * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer")
     * @Groups({"activity"})
	 */
	protected $sourceScale = null;

	/**
     * Sea are represented on map (mm square)
     * @var integer
     * 
	 * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer")
     * @Groups({"activity"})
	 */
	protected $seaArea = null;

    /**
     * Creation date of the map royalty
     * @var DateTime
     * 
     * @Gedmo\Timestampable(on="create") 
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdDate;

    /**
     * Last modified date of the map royalty
     * @var DateTime
     * 
     * @Gedmo\Timestampable(on="update") 
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $modifiedDate;

    /**
     * User assigned to last modification of the map royalty
     * @var User
     * 
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="modifiedUser", referencedColumnName="id", nullable=false)
     */
    protected $modifiedUser;
    
    /**
     * Get id
     * @return integer 
     */
    public function getId() {

        return $this->id;
    }

    /**
     * Set activity
     * @param Activity $activity
     * @return MapRoyalty
     */
    public function setActivity(Activity $activity) {

        $this->activity = $activity;
        return $this;
    }

    /**
     * Get activity
     *
     * @return Activity
     */
    public function getActivity() {

        return $this->activity;
    }
    
    /**
     * Set width
     * @param float $width
     * @return MapRoyalty
     */
    public function setWidth($width) {

        $this->width = $width;
        return $this;
    }

    /**
     * Get width
     * @return float
     */
    public function getWidth() {

        return $this->width;
    }

    /**
     * Set height
     * @param float $height
     * @return MapRoyalty
     */
    public function setHeight($height) {

        $this->height = $height;
        return $this;
    }

    /**
     * Get height
     * @return float
     */
    public function getHeight() {

        return $this->height;
    }

    /**
     * Set mapScale
     * @param integer $mapScale
     * @return MapRoyalty
     */
    public function setMapScale($mapScale) {

        $this->mapScale = $mapScale;
        return $this;
    }

    /**
     * Get mapScale
     * @return integer 
     */
    public function getMapScale() {

        return $this->mapScale;
    }

    /**
     * Set sourceScale
     * @param integer $sourceScale
     * @return MapRoyalty
     */
    public function setSourceScale($sourceScale) {

        $this->sourceScale = $sourceScale;
        return $this;
    }

    /**
     * Get sourceScale
     * @return integer 
     */
    public function getSourceScale() {

        return $this->sourceScale;
    }

    /**
     * Set createdDate
     * @param DateTime $createdDate
     * @return MapRoyalty
     */
    public function setCreatedDate($createdDate) {

        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * Get createdDate
     * @return DateTime
     */
    public function getCreatedDate() {

        return $this->createdDate;
    }

    /**
     * Set seaArea
     * @param integer $seaArea
     * @return MapRoyalty
     */
    public function setSeaArea($seaArea) {

        $this->seaArea = $seaArea;
        return $this;
    }

    /**
     * Get seaArea
     * @return integer 
     */
    public function getSeaArea() {

        return $this->seaArea;
    }

    /**
     * Set modifiedDate
     * @param DateTime $modifiedDate
     * @return MapRoyalty
     */
    public function setModifiedDate($modifiedDate) {

        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    /**
     * Get modifiedDate
     * @return DateTime
     */
    public function getModifiedDate() {

        return $this->modifiedDate;
    }

    /**
     * Set modifiedUser
     * @param User $modifiedUser
     * @return MapRoyalty
     */
    public function setModifiedUser(User $modifiedUser) {

        $this->modifiedUser = $modifiedUser;
        return $this;
    }

    /**
     * Get modifiedUser
     * @return User
     */
    public function getModifiedUser() {

        return $this->modifiedUser;
    }
}
