<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * App\Entity\Image
 * @author Geoff Chapman <geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image {
	
	/**
     * Unique Image identifier
     * @var integer
     * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"activity"})
	 */	
	protected $id;

	/**
     * Related Activity
     * @var Activity
     * 
	 * @ORM\ManyToOne(targetEntity="Activity", inversedBy="images")
	 * @ORM\JoinColumn(name="activity", referencedColumnName="id", nullable=false)
	 */
	protected $activity;

	/**
     * Name of the image
     * @var string
     * 
	 * @ORM\Column(type="string", length=190, nullable=false)
     * @Assert\NotBlank()
     * @Groups({"activity"})
	 */
	protected $name;

    /**
     * Type of image (photo or map)
     * @var string
     *
     * @ORM\Column(type="string", length=45, nullable=false)
     * @Assert\NotBlank()
     * @Groups({"activity"})
     */
    protected $type;

    /**
     * Creation date of the image
     * @var DateTime
     * 
     * @Gedmo\Timestampable(on="create") 
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdDate;

    /**
     * Last modified date of the image
     * @var DateTime
     * 
     * @Gedmo\Timestampable(on="update") 
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $modifiedDate;

    /**
     * User assigned to last modification of the image
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
     * @return Image
     */
    public function setActivity(Activity $activity) {
        
        $this->activity = $activity;
        return $this;
    }

    /**
     * Get activity
     * @return Activity
     */
    public function getActivity() {

        return $this->activity;
    }

    /**
     * Set name
     * @param string $name
     * @return Image
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
     * Set type
     * @param string $type
     * @return Image
     */
    public function setType($type) {

        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     * @return string 
     */
    public function getType() {

        return $this->type;
    }

    /**
     * Set createdDate
     * @param DateTime $createdDate
     * @return Image
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
     *
     * @param DateTime $modifiedDate
     * @return Image
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
     * @return Image
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
