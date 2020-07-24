<?php
declare(strict_types=1);
namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="poi")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\PoiRepository")
 */
class Poi extends Activity {

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

        return $this::TYPE_POI;
    }
}