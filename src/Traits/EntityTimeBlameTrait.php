<?php
declare(strict_types=1);
namespace App\Traits;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait EntityTimeBlameTrait
{
    /**
     * Creation date of the object
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdDate;

    /**
     * User assigned to creation of the object
     * @var User
     *
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="createdUser", referencedColumnName="id", nullable=false)
     */
    protected $createdUser;

    /**
     * Last modified date of the object
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $modifiedDate;

    /**
     * User assigned to last modification of the object
     * @var User
     *
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="modifiedUser", referencedColumnName="id", nullable=false)
     */
    protected $modifiedUser;

    public function setCreatedDate(DateTime $createdDate) :self {

        $this->createdDate = $createdDate;
        return $this;
    }

    public function getCreatedDate() : Datetime{

        return $this->createdDate;
    }

    public function getCreatedUser(): User
    {
        return $this->createdUser;
    }

    public function setCreatedUser(User $createdUser): void
    {
        $this->createdUser = $createdUser;
    }

    public function setModifiedDate(DateTime $modifiedDate) :self {

        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    public function getModifiedDate() : DateTime {

        return $this->modifiedDate;
    }

    public function setModifiedUser(User $modifiedUser) :self {

        $this->modifiedUser = $modifiedUser;
        return $this;
    }

    public function getModifiedUser() :User {

        return $this->modifiedUser;
    }

}