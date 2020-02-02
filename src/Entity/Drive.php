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

    public function getType() {

        return $this::TYPE_DRIVE;
    }
}