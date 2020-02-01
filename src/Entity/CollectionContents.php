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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CollectionContentsRepository")
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
     * Creation date of the activity
     * @var \DateTime 
     * 
     * @Gedmo\Timestampable(on="create") 
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdDate;

    /**
     * Last modified date of the activity
     * @var \DateTime
     * 
     * @Gedmo\Timestampable(on="update") 
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $modifiedDate;

    /**
     * User assigned to last modification of the activity
     * @var User
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="modifiedUser", referencedColumnName="id", nullable=false)
     */
    protected $modifiedUser;

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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return CollectionContents
     */
    public function setCreatedDate($createdDate) {

        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime 
     */
    public function getCreatedDate() {

        return $this->createdDate;
    }

    /**
     * Set modifiedDate
     *
     * @param \DateTime $modifiedDate
     * @return CollectionContents
     */
    public function setModifiedDate($modifiedDate) {

        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    /**
     * Get modifiedDate
     *
     * @return \DateTime 
     */
    public function getModifiedDate() {

        return $this->modifiedDate;
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


    /**
     * Set modifiedUser
     *
     * @param User $user
     * @return CollectionContents
     */
    public function setModifiedUser(User $user) {

        $this->modifiedUser = $user;
        return $this;
    }

    /**
     * Get modifiedUser
     *
     * @return User
     */
    public function getModifiedUser() {

        return $this->modifiedUser;
    }
}
