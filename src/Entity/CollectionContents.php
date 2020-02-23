<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * App\Entity\Contents
 * @author Geoff Chapman <geoff.chapman@mac.com>
 * @version 1.0
 * 
 * @ORM\Table(name="collection_contents")
 * @ORM\Entity(repositoryClass="App\Repository\CollectionContentsRepository")
 */
class CollectionContents {

	/**
     * Unique identifier
     * @var integer
     *  
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")
     *
	 */
	protected $id;

	/**
	 * Collection content belongs to
	 * @var Collection
	 *
	 * @ORM\ManyToOne(targetEntity="Collection", inversedBy="contents")
	 * @ORM\JoinColumn(name="collection", referencedColumnName="id", nullable=false)
	 */
	protected $collection;

	/**
	 * Activity in collection
	 * @var Collection
	 *
	 * @ORM\ManyToOne(targetEntity="Activity", inversedBy="collections")
	 * @ORM\JoinColumn(name="activity", referencedColumnName="id", nullable=false)
	 */
	protected $activity;

	/**
	 * Position of activity in collection
	 * @var integer
	 *
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $position;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        
        return $this->id;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return CollectionContents
     */
    public function setPosition($position) {

        $this->position = $position;
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition() {

        return $this->position;
    }

    /**
     * Set collection
     *
     * @param Collection $collection
     * @return CollectionContents
     */
    public function setCollection(Collection $collection) {
        
        $this->collection = $collection;
        return $this;
    }

    /**
     * Get collection
     *
     * @return Collection
     */
    public function getCollection() {

        return $this->collection;
    }

    /**
     * Set activity
     *
     * @param Activity $activity
     * @return CollectionContents
     */
    public function setActivity(Activity $activity) {

        $this->activity = $activity;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getActivity() {
        return $this->activity;
    }

}
