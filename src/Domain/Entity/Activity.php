<?php
declare(strict_types=1);
namespace App\Domain\Entity;

use App\Domain\DataTypes\Spatial\Types\Geography\Point;
use App\Domain\Traits\EntityTimeBlameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * App\Domain\Entity\Activity
 * @author Geoff Chapman <geoff.chapman@mac.com>
 * @version 1.0
 *
 * @ORM\Table(name="activity")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=45)
 * @ORM\DiscriminatorMap( {Activity::TYPE_ACTIVITY="Activity", Activity::TYPE_WALK="Walk", Activity::TYPE_DRIVE="Drive", Activity::TYPE_CYCLE="Cycle", Activity::TYPE_POI="Poi"})
 * @ORM\Entity(repositoryClass="App\Domain\Repository\ActivityRepository")
 * @UniqueEntity(fields={"name"}, message="This name is already in use")
 */
class Activity implements ActivityInterface {

    use EntityTimeBlameTrait;

    const TYPE_ACTIVITY = 'activity';
    const TYPE_WALK = 'walk';
    const TYPE_DRIVE = 'drive';
    const TYPE_CYCLE = 'cycle';
    const TYPE_POI = 'poi';

	/**
     * Unique activity identifier
     *  
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"activity", "admin", "carto", "route"})
	 */
	protected $id;

	/**
     * Name of the activity
     * 
	 * @ORM\Column(type="string", length=190, unique=true, nullable=false)
     * @Assert\NotBlank(message = "Name is a required element of all activities")
     * @Groups({"activity", "admin", "carto", "route"})
	 */
	protected $name;

	/**
     * Grid reference of the activity start location
     *  
	 * @ORM\Column(type="string", length=45, nullable=true)
     * @Assert\Regex("/^([STNHOstnho][A-Za-z]\s?)(\d{5}\s?\d{5}|\d{4}\s?\d{4}|\d{3}\s?\d{3}|\d{2}\s?\d{2}|\d{1}\s?\d{1})$/", message="This value is not a valid grid reference")
     * @Groups({"activity", "admin", "carto"})
	 */
	protected $startGridRef = null;

	/**
     * Latitude of the activity start location
     *  
	 * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     * @Assert\Regex("/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/", message="This value is not a valid Latitude")
     * @Groups({"activity", "admin", "carto"})
	 */
//	protected $latitude = null;

	/**
     * Longitude of the activity start location
     * 
	 * @ORM\Column(type="decimal", precision=11, scale=8, nullable=true)
     * @Assert\Regex("/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/", message="This value is not a valid Longitude")
     * @Groups({"activity", "admin", "carto"})
	 */
//	protected $longitude = null;

    /**
     * @ORM\Column(type="point", nullable=true)
     * @Groups({"activity", "admin", "carto"})
     * @var Point
     */
	protected $point = null;

    /**
     * Short description of the walk
     *
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity", "admin"})
     */
    protected $shortDescription = null;

	/**
     * Description of the activity
     * 
	 * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity", "admin"})
	 */
	protected $description = null;

    /**
     * Searchable description of the activity
     *
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"activity", "admin"})
     */
    protected $searchableDescription = null;

	/**
     * Status of the activity (0 = Open, 1 = Locked by modifedUser)
     *
	 * @ORM\Column(type="integer", nullable=false, options={"default" : 0})
     * @Groups({"admin"})
	 */
	protected $status = 0;

    /**
     * Is the Activity suitable for online
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @Groups({"admin"})
     */
    protected $onlineFriendly = false;

    /**
     * Url friendly activity name
     *
     * @ORM\Column(type="string", length=190, nullable=true, unique=true)
     * @Gedmo\Slug(fields={"name"}, updatable=true)
     * @Groups({"activity", "admin"})
     */
    protected $slug;

    // Related Entities

    /**
     * Map Royalty details of activity
     * 
     * @ORM\OneToOne(targetEntity="MapRoyalty", cascade={"persist"})
     * @ORM\JoinColumn(name="maproyalty", referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"admin"})
     */
    protected $mapRoyalty;

    /**
     * Collection of tags associated to the activity
     * 
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="activities")
     * @ORM\JoinTable(name="activity_tag")
     * @ORM\OrderBy({"name" = "DESC"})
     * @Groups({"activity", "admin"})
     */
    protected $tags;

    /**
     * Collection of directions associated to the activity
     * 
     * @ORM\OneToMany(targetEntity="Direction", mappedBy="activity", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\OrderBy({"position" = "ASC"})
     * @Groups({"activity", "admin"})
     */
    protected $directions;

    /**
     * Collection of user flags against the activity
     * 
     * @ORM\OneToMany(targetEntity="UserFlag", mappedBy="activity", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"activity"})
     */
    protected $flags;

    /**
     * Collection of images associated to the activity
     * 
     * @ORM\OneToMany(targetEntity="Image", mappedBy="activity", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"activity"})
     */
    protected $images;

    /**
     * Collection of route points associated to the activity
     * 
     * @ORM\OneToMany(targetEntity="RoutePoint", mappedBy="activity", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"route"})
     */
//    protected $routePoints;

    /**
     * Collection of activities associated to the activity
     * 
     * @ORM\ManyToMany(targetEntity="Activity")
     * @ORM\JoinTable(name="associated_activity",
     *     joinColumns={@ORM\JoinColumn(name="activity_a_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="associated_b_id", referencedColumnName="id")}
     * )
     */
    protected $associatedActivities;

    /**
     * Collection of collections containing the activity
     *
     * @ORM\OneToMany(targetEntity="CollectionContents", mappedBy="activity")
     * @Groups({"activity"})
     */
    protected $collections;

    /**
     * Collection of adminNotes associated to the activity
     *
     * @ORM\OneToMany(targetEntity="AdminNote", mappedBy="activity", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"activity"})
     */
    protected $adminNotes = null;



    /*
     * Define type for collection properties
     */ 
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->directions = new ArrayCollection();
        $this->flags = new ArrayCollection();
        $this->images = new ArrayCollection();
//        $this->routePoints = new ArrayCollection();
        $this->associatedActivities = new ArrayCollection();
        $this->collections = new ArrayCollection();
        $this->adminNotes = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setStartGridRef(string $startGridRef = null)
    {
        $this->startGridRef = $startGridRef;

    }

    public function getStartGridRef(): ?string
    {
        return $this->startGridRef;
    }

    public function setLatitude(float $latitude = null)
    {
        $this->latitude = $latitude;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLongitude(float $longitude = null)
    {
        $this->longitude = $longitude;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setShortDescription(string $shortDescription = null)
    {
        $this->shortDescription = $shortDescription;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setDescription(string $description = null)
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setSearchableDescription(string $searchableDescription = null)
    {
        $this->searchableDescription = $searchableDescription;
    }

    public function getSearchableDescription(): ?string
    {
        return $this->searchableDescription;
    }

    public function setStatus(int $status = 0)
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setOnlineFriendly(bool $onlineFriendly = false)
    {
        $this->onlineFriendly = $onlineFriendly;
    }

    public function getOnlineFriendly(): bool
    {
        return $this->onlineFriendly;
    }

    public function setSlug(string $slug = null)
    {
        $this->slug = $slug;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    // Related Entities

    public function setMapRoyalty(MapRoyalty $mapRoyalty = null)
    {
        $this->mapRoyalty = $mapRoyalty;
        $this->mapRoyalty->setActivity($this);
    }

    public function getMapRoyalty() :?MapRoyalty
    {
        return $this->mapRoyalty;
    }

    public function addTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function removeTag(Tag $tag)
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }

    public function hasTag(Tag $tag): bool
    {
        return $this->tags->contains($tag);
    }

    public function getTags() :iterable
    {
        return $this->tags;
    }

    public function addDirection(Direction $direction)
    {
        $this->directions[] = $direction;
        $direction->setActivity($this);

    }

    public function removeDirection(Direction $direction)
    {
        $this->directions->removeElement($direction);
    }

    public function getDirections() :iterable
    {
        return $this->directions;
    }

    public function addFlag(UserFlag $flag)
    {
        $this->flags[] = $flag;
        $flag->setActivity($this);
    }

    public function removeFlag(UserFlag $flag)
    {
        $this->flags->removeElement($flag);
    }

    public function getFlags() :iterable
    {
        return $this->flags;
    }

    public function addImage(Image $image)
    {
        $this->images[] = $image;
        $image->setActivity($this);
    }

    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }

    public function getImages() :iterable
    {
        return $this->images;
    }

//    public function addRoutePoint(RoutePoint $routePoint) :self {
//
//        $this->routePoints[] = $routePoint;
//        $routePoint->setActivity($this);
//        return $this;
//    }
//
//    public function removeRoutePoint(RoutePoint $routePoint) {
//
//        $this->routePoints->removeElement($routePoint);
//    }
//
//    public function getRoutePoints() :?\Doctrine\Common\Collections\Collection {
//
//        return $this->routePoints;
//    }

    public function addAssociatedActivity(Activity $activity) :self {

        if (!$this->associatedActivities->contains($activity)) {
                $this->associatedActivities->add($activity);
                $activity->addAssociatedActivity($this);
        }
        return $this;
    }

    public function removeAssociatedActivity(Activity $activity) {

        if($this->associatedActivities->contains($activity)) {
            $this->associatedActivities->removeElement($activity);
            $activity->removeAssociatedActivity($this);
        }
    }

    public function getAssociatedActivities() :?\Doctrine\Common\Collections\Collection {

        return $this->associatedActivities;
    }

    public function addCollection(Collection $collection) {

        if ($this->collections->contains($collection)) {
            return;
        }

        $this->collections[] = $collection;
        $collection->addCollectionContent($this);
    }

    public function removeCollection(Collection $collection) {

        if (!$this->collections->contains($collection)) {
            return;
        }

        $this->collections->removeElement($collection);
        $collection->removeCollectionContent($this);
    }

    /**
     * @return ArrayCollection|CollectionContents[]
     */
    public function getCollections(){

        return $this->collections;
    }

    public function addAdminNote(AdminNote $adminNote) :self {

        $this->adminNotes[] = $adminNote;
        $adminNote->setActivity($this);
        return $this;
    }

    public function removeAdminNote(AdminNote $adminNote) {

        $this->adminNotes->removeElement($adminNote);
    }

    public function getAdminNotes() :?\Doctrine\Common\Collections\Collection {

        return $this->adminNotes;
    }

    /**
     * @return string
     * @Groups({"activity"})
     */
    public function getActivityType() :string {

        return $this::TYPE_ACTIVITY;
    }

    /**
     * @return Point
     */
    public function getPoint(): Point
    {
        return $this->point;
    }

    /**
     * @param Point $point
     */
    public function setPoint(Point $point): void
    {
        $this->point = $point;
    }


}
