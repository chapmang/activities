<?php
namespace App\Entity;

use App\Traits\EntityTimeBlameTrait;
use Beelab\TagBundle\Tag\TagInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * App\Entity\Tag
 * @author Geoff Chapman <geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @UniqueEntity("name")
 */

class Tag  implements TagInterface {

    use EntityTimeBlameTrait;

	/**
     * Unique tag identifier 
     * @var integer
     * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"activity"})
	 */
	protected $id;

	/**
     * Name of the tag
     * @var string
     * 
	 * @ORM\Column(type="string", length=190, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Groups({"activity"})
	 */
	protected $name;

    /**
     * Collection of activities related to the tag
     * @var ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Activity", mappedBy="tags")
     */
    protected $activities;

    /**
     * Collection of collection related to the tag
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Collection", mappedBy="tags")
     */
    protected $collections;


    /**
     * Parent tag
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="tag_parent",
     *     joinColumns={@ORM\JoinColumn(name="tag_a_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_b_id", referencedColumnName="id")}
     * )
     */
    protected $parent;


    /**
     * Constructor
     */
    public function __construct() {

        $this->activities = new ArrayCollection();

        $this->collections = new ArrayCollection();
    }

    /**
     * Get id
     * @return integer 
     */
    public function getId() {

        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     * @return void
     */
    public function setName(?string $name): void {

        $this->name = $name;
    }

    /**
     * Get name
     * @return string 
     */
    public function getName(): ?string {

        return $this->name;
    }
    
    /**
     * Add activities
     * @param Activity $activity
     * @return Tag
     */
    public function addActivity(Activity $activity) {

        $this->activities[] = $activity;
        return $this;
    }

    /**
     * Remove activities
     * @param Activity $activity
     */
    public function removeActivity(Activity $activity) {

        $this->activities->removeElement($activity);
    }

    /**
     * Get activities
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivities() {

        return $this->activities;
    }

    /**
     * Add collection
     * @param Collection $collection
     * @return Tag
     */
    public function addCollection(Collection $collection) {

        $this->collections[] = $collection;
        return $this;
    }

    /**
     * Remove collection
     * @param Collection $collection
     */
    public function removeCollection(Collection $collection) {

        $this->collections->removeElement($collection);
    }

    /**
     * Get collections
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollections() {

        return $this->collections;
    }

    public function __toString()
    {
        return $this->getName();
    }

}
