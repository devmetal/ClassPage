<?php
namespace ORMs\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 * @ORM\EntityListeners({"ORMs\Entity\Listener\BaseListener"})
 */
class Comment {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="com_id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", name="com_body")
     */
    protected $body;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(name="com_usr_id", referencedColumnName="usr_id")
     */
    protected $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="comments")
     * @ORM\JoinColumn(name="com_itm_id", referencedColumnName="itm_id")
     */
    protected $item;

    /**
     * @ORM\Column(type="datetime", name="com_created")
     */
    protected $created;
    
    /**
     * @ORM\Column(type="datetime", name="com_edited")
     */
    protected $edited;
    
    public function getId() {
        return $this->id;
    }
    
    public function getBody() {
        return $this->body;
    }

    public function setBody($body) {
        $this->body = $body;
        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }
    
    public function setItem($item) {
        $this->item = $item;
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
