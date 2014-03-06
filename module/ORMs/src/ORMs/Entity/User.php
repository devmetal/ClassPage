<?php
namespace ORMs\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\EntityListeners({"ORMs\Entity\Listener\BaseListener"})
 */
class User {
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="usr_id")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", name="usr_email")
     */
    protected $email;
    
    /**
     * @ORM\Column(type="string", name="usr_pass")
     */
    protected $pass;

    /**
     * @ORM\Column(type="string", name="usr_nick")
     */
    protected $nick;
    
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    protected $comments;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="uploader")
     */
    protected $items;
    
    /**
     * @ORM\OneToMany(targetEntity="Invitation", mappedBy="creator")
     */
    protected $invitations;
    
    /**
     * @ORM\OneToOne(targetEntity="Invitation", inversedBy="user")
     * @ORM\JoinColumn(name="usr_used_invitation", referencedColumnName="inv_id")
     */
    protected $invitation;


    /**
     *
     * @ORM\Column(type="datetime", name="usr_created")
     */
    protected $created;
    
    /**
     *
     * @ORM\Column(type="datetime", name="usr_edited")
     */
    protected $edited;
    
    /**
     *
     * @ORM\Column(type="boolean", name="usr_active")
     */
    protected $active = false;

    /**
     *
     * @ORM\Column(type="string", name="usr_code")
     */
    protected $code = "";

    private static $_salt = "fnsdjkfsdfdsnfjkdsfksdbnfjksd.-,-,.-,-.,.-54354354.-5,.-34,5.-345,.-5,34";
    
    public function __construct() {
        $this->items = new ArrayCollection();
        $this->comments = new ArrayCollection(); 
        $this->invitations = new ArrayCollection();
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

    public function getPass() {
        return $this->pass;
    }

    public function setPass($pass) {
        $this->pass = sha1(self::$_salt . $pass);
        return $this;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    public function getNick() {
        return $this->nick;
    }

    public function setNick($nick) {
        $this->nick = $nick;
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
    
    public function addItem($item) {
        $this->items[] = $item;
        return $this;
    }
    
    public function getItems() {
        return $this->items;
    }
    
    public function getItemsOrberByNum() {
        $items = $this->getItems();
        
        $criteria = Criteria::create()
                ->orderBy(array("itemNum" => Criteria::DESC))
                ->orderBy(array("category" => Criteria::DESC));
        
        return $items->matching($criteria);
    }
    
    public function addComment($comment) {
        $this->comments[] = $comment;
        return $this;
    }
    
    public function getComments() {
        return $this->comments;
    }
    
    public function addInvitation(Invitation $inv) {
        $inv->setCreator($this);
        $this->invitations[] = $inv;
        return $this;
    }
    
    public function getInvitations() {
        return $this->invitations;
    }
    
    public function setInvitation(Invitation $inv) {
        $this->invitation = $inv;
        return $this;
    }
    
    public function getInvitation() {
        return $this->invitation;
    }
    
    public function genCode() {
        $this->code = sha1(uniqid(self::$_salt, true));
        return $this;
    }
    
    public function getCode() {
        if ($this->code === "") {
            $this->genCode();
        }
        
        return $this->code;
    }
    
    public function isActive() {
        return $this->active === true;
    }
    
    public function activate() {
        $this->active = true;
    }

    public static function checkCode($user, $code) {
        return $user->getCode() === $code;
    }
    
    public static function checkPass($user, $password) {
        return $user->getPass() === sha1(self::$_salt . $password);
    }
    
}

?>
