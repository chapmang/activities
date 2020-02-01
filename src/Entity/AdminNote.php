<?php
declare(strict_types=1);
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="admin_notes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdminNoteRepository")

 */
class AdminNote {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="adminNotes")
     * @ORM\JoinColumn(name="collection", referencedColumnName="id")
     */
    protected $collection;

    /**
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="adminNotes")
     * @ORM\JoinColumn(name="activity", referencedColumnName="id")
     */
    protected $activity;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $note;

    /**
     * Creation date of the activity
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdDate;

    /**
     * Last modified date of the activity
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=false)
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
     * @return mixed
     */
    public function getId() :int {

        return $this->id;
    }

    public function getCollection() :Collection {

        return $this->collection;
    }

    public function setCollection(Collection $collection) :self {

        $this->collection = $collection;
        return $this;
    }

    public function getActivity() :Activity {

        return $this->activity;
    }

    public function setActivity(Activity $activity) :self {

        $this->activity = $activity;
        return $this;
    }

    public function getNote() :string {

        return $this->note;
    }

    public function setNote($note) :self {

        $this->note = $note;
        return $this;
    }

    public function getCreatedDate() : DateTime {

        return $this->createdDate;
    }

    public function setCreatedDate(DateTime $createdDate) :self {

        $this->createdDate = $createdDate;
        return $this;
    }

    public function getModifiedDate() : DateTime {

        return $this->modifiedDate;
    }

    public function setModifiedDate(DateTime $modifiedDate) :self {

        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    public function getModifiedUser() :User {

        return $this->modifiedUser;
    }

    public function setModifiedUser(User $modifiedUser) :self {

        $this->modifiedUser = $modifiedUser;
        return $this;
    }
}