<?php
declare(strict_types=1);
namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * App\Entity\Collection
 * @author Geoff Chapman <Geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="collection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CollectionRepository")
 * @UniqueEntity("name")
 *
 */
class Collection {

	/**
     * Unique collection identifier
     * @var integer
     *  
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
     * Name of the collection
     * @var string
     * 
	 * @ORM\Column(type="string", length=190, unique=true, nullable=false)
     * @Assert\NotBlank(message = "Name is a required element of all collections")
	 */
	protected $name;

	/**
     * Description of the activity
     * @var string
     * 
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $description = null;

	/**
     * Status of the activity
     * @var string
     * 
	 * @ORM\Column(type="string", length=45, nullable=false, options={"default": "public"})
	 */
	protected $status = 'public';

    /**
     * Url friendly activity name
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     * @Gedmo\Slug(fields={"name"}, updatable=true)
     */
    protected $slug;

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

    // Related Entities
    
    /**
     * Collection of contents associated to the collection
     * @var \Doctrine\Common\Collections\Collection
     * 
     * @ORM\OneToMany(targetEntity="CollectionContents", mappedBy="collection", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $contents;

    /**
     * Collection of adminNotes associated to the collection
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AdminNote", mappedBy="collection", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $adminNotes = null;

    /**
     * Collection of tags associated to the collection
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="collections")
     * @ORM\JoinTable(name="collection_tag")
     */
    protected $tags;

    /**
     * Constructor
     */
    public function __construct() {
        
        $this->contents = new ArrayCollection();

        $this->adminNotes = new ArrayCollection();

        $this->tags = new ArrayCollection();
    }

    public function getId() :int {

        return $this->id;
    }

    public function setName(string $name) :self {

        $this->name = $name;
        return $this;
    }

    public function getName() :string {

        return $this->name;
    }

    public function setDescription(string $description) :self {

        $this->description = $description;
        return $this;
    }

    public function getDescription() :?string {

        return $this->description;
    }

    public function setStatus(string $status) :self {

        $this->status = ($status === null || $status === '') ? 'public' : $status;
        return $this;
    }

    public function getStatus() :string {

        return $this->status;
    }

    public function setSlug($slug) :self {

        $this->slug = $slug;
        return $this;
    }

    public function getSlug() :string {

        return $this->slug;
    }

    public function setCreatedDate(DateTime $createdDate) :self {

        $this->createdDate = $createdDate;
        return $this;
    }

    public function getCreatedDate() : DateTime {

        return $this->createdDate;
    }

    public function setModifiedDate(DateTime $modifiedDate) :self {

        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    public function getModifiedDate() : DateTime{

        return $this->modifiedDate;
    }

    public function setModifiedUser(User $user) :self {

        $this->modifiedUser = $user;
        return $this;
    }

    public function getModifiedUser() :User {

        return $this->modifiedUser;
    }

    public function addContent(CollectionContents $contents) :self {

        $this->contents[] = $contents;
        return $this;
    }

    public function removeContent(CollectionContents $contents) {

        $this->contents->removeElement($contents);
    }

    public function getContents() :\Doctrine\Common\Collections\Collection {

        return $this->contents;
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
