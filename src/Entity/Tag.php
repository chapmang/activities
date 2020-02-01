<?php
namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * App\Entity\Tag
 * @author Geoff Chapman <geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 * @UniqueEntity("name")
 */

class Tag {
	
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
     * Creation date of the tag
     * @var DateTime
     * 
     * @Gedmo\Timestampable(on="create") 
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdDate;

    /**
     * Last modified date of the tag
     * @var DateTime
     * 
     * @Gedmo\Timestampable(on="update") 
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $modifiedDate;

    /**
     * User assigned to last modification of the tag
     * @var User
     * 
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="modifiedUser", referencedColumnName="id", nullable=false)
     */
    protected $modifiedUser;

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
     * @return Tag
     */
    public function setName($name) {

        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * @return string 
     */
    public function getName() {

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

    /**
     * Set createdDate
     * @param DateTime $createdDate
     * @return Tag
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
     * Set modifiedDate
     * @param DateTime $modifiedDate
     * @return Tag
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
     * @return Tag
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
