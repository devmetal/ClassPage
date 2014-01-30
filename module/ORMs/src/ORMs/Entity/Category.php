<?php
namespace ORMs\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @ORM\EntityListeners({"ORMs\Entity\Listener\BaseListener"})
 */
class Category {
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="cat_id")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", name="cat_name")
     */
    protected $name;
    
    /**
     * @ORM\Column(type="datetime", name="cat_created")
     */
    protected $created;
    
    /**
     * @ORM\Column(type="datetime", name="cat_edited")
     */
    protected $edited;
    
    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="category", fetch="EXTRA_LAZY")
     */
    protected $items;
    
    
    public function __construct() {
        $this->items = new ArrayCollection();
    }
    
    public function getItems() {
        return $this->items;
    }
    
    public function getItemsOrderByNum() {
        $items = $this->getItems();
        
        $criteria = Criteria::create()
                ->orderBy(array("itemNum" => Criteria::DESC));
        
        return $items->matching($criteria);
    }
    
    public function addItem($i) {
        $this->items[] = $i;
        return $this;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
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
