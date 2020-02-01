<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * App\Entity\Direction
 * @author Geoff Chapman <geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="direction")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DirectionRepository")
 */
class Direction {
	
	/**
     * Unique direction identifier
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
     * Position of direction (waypoint number)
     * @var integer
     * 
	 * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Groups({"activity"})
	 */
	protected $position;

	/**
     * Direction description
     * @var string
     * 
	 * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank()
     * @Groups({"activity"})
	 */
	protected $direction;

	/**
     * Creation date of the direction
     * @var DateTime
     * 
     * @Gedmo\Timestampable(on="create") 
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdDate;

    /**
     * Last modified date of the direction
     * @var DateTime
     * 
     * @Gedmo\Timestampable(on="update") 
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $modifiedDate;

    /**
     * User assigned to last modification of the direction
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
     * Set position
     * @param integer $position
     * @return Direction
     */
    public function setPosition($position) {

        $this->position = $position;
        return $this;
    }

    /**
     * Get position
     * @return integer 
     */
    public function getPosition() {

        return $this->position;
    }

    /**
     * Set direction
     * @param string $direction
     * @return Direction
     */
    public function setDirection($direction) {

        $this->direction = $direction;
        return $this;
    }

    /**
     * Get direction
     * @return string 
     */
    public function getDirection() {

        return $this->direction;
    }

    /**
     * Set createdDate
     * @param DateTime $createdDate
     * @return Direction
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
     * @return Direction
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
     * Set activity
     * @param Activity $activity
     * @return Direction
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
     * Set modifiedUser
     *
     * @param User $modifiedUser
     * @return Direction
     */
    public function setModifiedUser(User $modifiedUser) {
        
        $this->modifiedUser = $modifiedUser;
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
