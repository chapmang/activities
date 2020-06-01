<?php
declare(strict_types=1);
namespace App\Entity;

use App\Traits\EntityTimeBlameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * App\Entity\Collection
 * @author Geoff Chapman <Geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="collection")
 * @ORM\Entity(repositoryClass="App\Repository\CollectionRepository")
 * @UniqueEntity("name")
 *
 */
class Collection {

    use EntityTimeBlameTrait;

	/**
     * Unique collection identifier
     *  
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
     * Name of the collection
     * 
	 * @ORM\Column(type="string", length=190, unique=true, nullable=false)
     * @Assert\NotBlank(message = "Name is a required element of all collections")
     * @Groups({"details"})
	 */
	protected $name;

	/**
     * Description of the activity
     * 
	 * @ORM\Column(type="text", nullable=true)
     * @Groups({"details"})
	 */
	protected $description = null;

	/**
     * Status of the activity
     * 
	 * @ORM\Column(type="string", length=45, nullable=false, options={"default": "public"})
     * @Groups({"details"})
	 */
	protected $status = 'public';

    /**
     * Url friendly activity name
     *
     * @ORM\Column(type="string", nullable=false)
     * @Gedmo\Slug(fields={"name"}, updatable=true)
     * @Groups({"details"})
     */
    protected $slug;


    // Related Entities
    
    /**
     * Collection of contents associated to the collection
     * 
     * @ORM\OneToMany(targetEntity="CollectionContents", mappedBy="collection", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\OrderBy({"position" = "DESC"})
     * @Groups({"details"})
     */
    protected $collectionContents;

    /**
     * Collection of adminNotes associated to the collection
     *
     * @ORM\OneToMany(targetEntity="AdminNote", mappedBy="collection", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\OrderBy({"createdDate" = "DESC"})
     */
    protected $adminNotes = null;

    /**
     * Collection of tags associated to the collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="collections")
     * @ORM\JoinTable(name="collection_tag")
     */
    protected $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->collectionContents = new ArrayCollection();
        $this->adminNotes = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId() :int
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName() :?string
    {
        return $this->name;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getDescription() :?string
    {
        return $this->description;
    }

    public function setStatus(string $status)
    {
        $this->status = ($status === null || $status === '') ? 'public' : $status;
    }

    public function getStatus() :?string
    {
        return $this->status;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug() :string
    {
        return $this->slug;
    }

    public function addCollectionContent(Activity $activity)
    {
        if ($this->collectionContents->contains($activity)) {
            return;
        }

        $this->collectionContents[] = $activity;
        $activity->addCollection($this);
    }

    public function removeCollectionContent(Activity $activity)
    {
        if (!$this->collectionContents->contains($activity)) {
            return;
        }

        $this->collectionContents->removeElement($activity);
        $activity->removeCollection($this);
    }

    /**
     * @return ArrayCollection|CollectionContents[]
     */
    public function getCollectionContents()
    {
        return $this->collectionContents;
    }

    public function addAdminNote(AdminNote $adminNote) :self {

        $this->adminNotes[] = $adminNote;
        $adminNote->setCollection($this);
        return $this;
    }

    public function removeAdminNote(AdminNote $adminNote) {

        $this->adminNotes->removeElement($adminNote);
    }

    public function getAdminNotes() :\Doctrine\Common\Collections\Collection {

        return $this->adminNotes;
    }

    public function addTag(Tag $tag) :self {

        $tag->addCollection($this);
        $this->tags[] = $tag;
        return $this;
    }

    public function removeTag(Tag $tag) {

        $this->tags->removeElement($tag);
    }

    public function getTags() :\Doctrine\Common\Collections\Collection {

        return $this->tags;
    }
}
