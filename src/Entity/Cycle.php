<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Class Cycle
 *
 * @ORM\Table(name="cycle")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CycleRepository")
 *
 */
class Cycle extends Activity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 */
	protected $id;

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
     * @return string
     * @Serializer\VirtualProperty()
     * @Groups({"activity"})
     */
    public function getType() {

        return $this::TYPE_CYCLE;
    }
}