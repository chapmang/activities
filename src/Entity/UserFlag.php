<?php
namespace App\Entity;

use App\Traits\EntityTimeBlameTrait;
use Doctrine\ORM\Mapping as ORM;
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
     * Get id
     *
     * @return integer 
     */
    public function getId() {

        return $this->id;
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
}
