<?php
namespace ORMs\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="group")
 * @ORM\EntityListeners({"ORMs\Entity\Listener\BaseListener"})
 */
class Group {
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="grp_id", type="integer")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="grp_name", type="string")
     */
    protected $name;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    protected $tags;
    
    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="group", fetch="EXTRA_LAZY")
     */
    protected $items;
    
    /**
     * @ORM\Column(name="grp_created", type="datetime")
     */
    protected $created;
    
    /**
     * @ORM\Column(name="grp_created", type="datetime")
     */
    protected $edited;
    
    public function __construct() {
        $this->items = new ArrayCollection();
        $this->tags  = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getTags() {
        return $this->tags;
    }
    
    public function getItems() {
        return $this->items;
    }
    
    public function addItem(Item $i) {
        $this->items[] = $i;
    }
    
    public function addTag(User $u) {
        $this->tags[] = $u;
        $u->addGroup($this);
    }
    
}

?>