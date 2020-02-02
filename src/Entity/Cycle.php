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
     * @return string
     * @Serializer\VirtualProperty()
     * @Groups({"activity"})
     */
    public function getType() {

        return $this::TYPE_CYCLE;
    }
}