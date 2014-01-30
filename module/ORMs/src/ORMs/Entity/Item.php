<?php
namespace ORMs\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="item")
 * @ORM\EntityListeners({"ORMs\Entity\Listener\BaseListener"})
 */
class Item {
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="itm_id")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="itm_title")
     */
    protected $title;
    
    /**
     * @ORM\Column(type="integer", name="itm_num")
     */
    protected $itemNum;
    
    /**
     * @ORM\Column(type="string", name="itm_src")
     */
    protected $src;
    
    /**
     * @ORM\Column(type="string", name="itm_state") 
     */
    protected $state;
    
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="items")
     * @ORM\JoinColumn(name="itm_cat_id", referencedColumnName="cat_id")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="items")
     * @ORM\JoinColumn(name="itm_usr_id", referencedColumnName="usr_id")
     */
    protected $uploader;
    
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="item", fetch="EXTRA_LAZY") 
     */
    protected $comments;

    /**
     * @ORM\Column(type="integer", name="itm_downloads_count")
     */
    protected $downloads;
    
    /**
     * @ORM\Column(type="float", name="itm_rate")
     */
    protected $rate;
    
    /**
     * @ORM\Column(type="integer", name="itm_rating_count")
     */
    protected $ratedCount;
    
    /**
     * @ORM\Column(type="integer", name="itm_rate_sum")
     */
    protected $ratedSum;
    
    /**
     * @ORM\Column(type="string", name="itm_desc", nullable=true)
     */
    protected $desc;

    /**
     * @ORM\Column(type="datetime", name="itm_created")
     */
    protected $created;
    
    /**
     * @ORM\Column(type="datetime", name="itm_edited")
     */
    protected $edited;
    
    public function __construct() {
        $this->comments = new ArrayCollection();
    }
    
    public function addComment($comment) {
        $this->comments[] = $comment;
        return $this;
    }
    
    public function getComments() {
        return $this->comments;
    }
    
    public function isUploaded() {
        return ($this->state === "UPLOADED");
    }
    
    public function isSaved() {
        return ($this->state === "SAVED");
    }
    
    public function setToUploaded() {
        $this->state = "UPLOADED";
        return $this;
    }
    
    public function setToSaved() {
        $this->state = "SAVED";
        return $this;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    public function getDesc() {
        return $this->desc;
    }
    
    public function setDesc($desc) {
        $this->desc = $desc;
        return $this;
    }
    
    public function getItemNum() {
        return $this->itemNum;
    }
    
    public function setItemNum($num) {
        $this->itemNum = $num;
        return $this;
    }

    public function getSrc() {
        return $this->src;
    }

    public function setSrc($src) {
        $this->src = $src;
        return $this;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
        return $this;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
        return $this;
    }

    public function getUploader() {
        return $this->uploader;
    }

    public function setUploader($uploader) {
        $this->uploader = $uploader;
        return $this;
    }

    public function getDownloads() {
        return $this->downloads;
    }

    public function setDownloads($downloads) {
        $this->downloads = $downloads;
        return $this;
    }

    public function getRate() {
        return $this->rate;
    }

    public function setRate($rate) {
        $this->rate = $rate;
        return $this;
    }

    public function getRatedCount() {
        return $this->ratedCount;
    }

    public function setRatedCount($ratedCount) {
        $this->ratedCount = $ratedCount;
        return $this;
    }

    public function getRatedSum() {
        return $this->ratedSum;
    }

    public function setRatedSum($ratedSum) {
        $this->ratedSum = $ratedSum;
        return $this;
    }

    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

    public function getEdited() {
        return $this->edited;
    }

    public function setEdited($edited) {
        $this->edited = $edited;
        return $this;
    }
}

?>
