<?php
namespace App\Domain\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Class Ride
 *
 * @ORM\Table(name="ride")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\RideRepository")
 *
 */
class Ride extends Activity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 */
	protected $id;

    /**
     * @return string
     * @Groups({"activity"})
     */
    public function getType() {

        return $this::TYPE_RIDE;
    }
}