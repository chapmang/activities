<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * App\Entity\UserFlag
 * @author Geoff Chapman <geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="user_flag")
 * @ORM\Entity(repositoryClass="App\Repository\UserFlagRepository")
 */
class UserFlag {

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
     * Related activity
     * @var Activity
     * 
	 * @ORM\ManyToOne(targetEntity="Activity", inversedBy="flags")
	 * @ORM\JoinColumn(name="activity", referencedColumnName="id", nullable=false)
	 */
	protected $activity;

    /**
     * Flag type
     * @var Activity
     *
     * @ORM\ManyToOne(targetEntity="FlagType")
     * @ORM\JoinColumn(name="flagType", referencedColumnName="id", nullable=false)
     * @Groups({"activity"})
     */
    protected $flagType;

    /**
     * Flag type
     * @var FlagType
     *
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity"})
     */
    protected $description;


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
     *
     * @return integer 
     */
    public function getId() {

        return $this->id;
    }

    /**
     * Set createdDate
     *
     * @param DateTime $createdDate
     * @return UserFlag
     */
    public function setCreatedDate($createdDate)  {

        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * Get createdDate
     *
     * @return DateTime
     */
    public function getCreatedDate() {

        return $this->createdDate;
    }

    /**
     * Set modifiedDate
     *
     * @param DateTime $modifiedDate
     * @return UserFlag
     */
    public function setModifiedDate($modifiedDate) {

        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    /**
     * Get modifiedDate
     *
     * @return DateTime
     */
    public function getModifiedDate() {

        return $this->modifiedDate;
    }

    /**
     * Set activity
     *
     * @param Activity $activity
     * @return UserFlag
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
     * @return UserFlag
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
