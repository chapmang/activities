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
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 */
class CollectionContents {

	/**
     * Unique identifier
     *  
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")
     *
	 */
	protected $id;

	/**
	 * Collection content belongs to
     *
     * @Gedmo\SortableGroup()
	 * @ORM\ManyToOne(targetEntity="Collection", inversedBy="collectionContents")
	 * @ORM\JoinColumn(name="collection", referencedColumnName="id", nullable=false)
	 */
	protected $collection;

	/**
	 * Activity in collection

	 * @ORM\ManyToOne(targetEntity="Activity", inversedBy="collections")
	 * @ORM\JoinColumn(name="activity", referencedColumnName="id", nullable=false)
	 */
	protected $activity;

	/**
	 * Position of activity in collection
	 *
     * @Gedmo\SortablePosition()
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $position;


    public function getId()
    {
        return $this->id;
    }


    public function setPosition($position)
    {
        $this->position = $position;
    }


    public function getPosition(): int
    {
        return $this->position;
    }


    public function setCollection(Collection $collection)
    {
        $this->collection = $collection;
    }


    public function getCollection(): Collection
    {
        return $this->collection;
    }


    public function setActivity(Activity $activity)
    {
        $this->activity = $activity;
    }


    public function getActivity(): Activity
    {
        return $this->activity;
    }

}
