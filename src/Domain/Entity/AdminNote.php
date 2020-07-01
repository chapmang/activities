<?php
declare(strict_types=1);
namespace App\Domain\Entity;

use App\Domain\Traits\EntityTimeBlameTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="admin_notes")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\AdminNoteRepository")

 */
class AdminNote {

    use EntityTimeBlameTrait;

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

}