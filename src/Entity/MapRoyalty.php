<?php
namespace App\Entity;

use App\Traits\EntityTimeBlameTrait;
use Doctrine\ORM\Mapping as ORM;
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

    use EntityTimeBlameTrait;

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
}
