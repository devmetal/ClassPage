<?php
namespace ORMs\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="invitations")
 * @ORM\EntityListeners({"ORMs\Entity\Listener\BaseListener"})
 */
class Invitation {
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="inv_id",type="integer")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="inv_email",type="string",unique=true)
     */
    protected $email;
    
    /**
     * @ORM\Column(name="inv_code",type="string",unique=true)
     */
    protected $code;
    
    /**
     * @ORM\Column(name="inv_edited", type="datetime")
     */
    protected $edited;
    
    /**
     * @ORM\Column(name="inv_created", type="datetime")
     */
    protected $created;
    
    /**
     * @ORM\Column(name="inv_used", type="boolean")
     */
    protected $used = false;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invitations")
     * @ORM\JoinColumn(name="inv_creator_id", referencedColumnName="usr_id")
     */
    protected $creator;
    
    /**
     * @ORM\OneToOne(targetEntity="User", mappedBy="invitation")
     */
    protected $user;
    
    public function assignToUser(User $u) {
        $this->user = $u;
        $u->setInvitation($this);
        return $this;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    public function getEdited() {
        return $this->edited;
    }

    public function setEdited($edited) {
        $this->edited = $edited;
        return $this;
    }

    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

    public function getUsed() {
        return $this->used;
    }

    public function setUsed($used) {
        $this->used = $used;
        return $this;
    }

    public function getCreator() {
        return $this->creator;
    }

    public function setCreator($creator) {
        $this->creator = $creator;
        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }


    
    
}

?>
