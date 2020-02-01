<?php
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;


/**
 * App\Entity\Drive
 * extends Activity
 *
 * @ORM\Table(name="drive")
 * @ORM\Entity(repositoryClass="App\Repository\DriveRepository")
 *
 */
class Drive extends Activity {

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

    public function getType() {

        return $this::TYPE_DRIVE;
    }
}