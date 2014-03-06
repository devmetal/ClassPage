<?php
namespace Home\Model;

use Zend\ServiceManager\ServiceManager;

use Doctrine\ORM\EntityManager;

use ORMs\Entity\Item;

/**
 * Description of ItemModel
 *
 * @author adam
 */
class ItemModel {
    
    /**
     *
     * @var EntityManager
     */
    private $_entityManager;
    
    /**
     *
     * @var ServiceManager
     */
    private $_serviceManager;
    
    /**
     * 
     * @param \Doctrine\ORM\EntityManager $manager
     */
    public function __construct(EntityManager $manager, ServiceManager $sm) {
        $this->_entityManager = $manager;
        $this->_serviceManager = $sm;
    }
    
    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getItemRepository() {
        return $this->_entityManager->getRepository("ORMs\Entity\Item");
    }
    
    /**
     * 
     * @return EntityManager
     */
    public function getEntityManager() {
        return $this->_entityManager;
    }
    
    /**
     * 
     * @param array $fields
     * @return Item
     */
    public function findItemBy($fields) {
        return $this->getItemRepository()
                ->findOneBy($fields);
    }
    
    public function removeById($id) {
        $item = $this->getItemRepository()
                ->find($id);
        
        if($item) {
            $this->_entityManager->remove($item);
            $this->_entityManager->flush();
        }
    }
    
    /**
     * 
     * @param int $id
     * @return Item
     */
    public function findItemById($id) {
        return $this->_entityManager
                ->find("ORMs\Entity\Item", (int)$id);
    }
    
    public function getLatestNItem($n) {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT i.title as title,
                   i.id as id,
                   i.created as created,
                   u.nick as nick,
                   i.desc as descr FROM ORMs\Entity\Item i
            JOIN i.uploader u
            ORDER BY created DESC
        ');
        
        $query->setMaxResults($n);
        return $query->getResult();
    }
    
    
    public function createNewEmptyItem($fileDatas) {
        $item = new Item();
        $item->setSrc($fileDatas['tmp_name']);
        $item->setTitle($fileDatas['name']);
        $item->setType($fileDatas['type']);
        
        $userId = $this->_serviceManager->get('Auth\Service\Auth')
                ->getIdentity()
                ->getId();
        
        $user = $this->_entityManager
                ->find("ORMs\Entity\User", $userId);
        
        $category = $this->_entityManager
                ->find("ORMs\Entity\Category", 1);
        
        $item->setCategory($category);
        $item->setUploader($user);
        
        $this->_entityManager->persist($item);
        $this->_entityManager->flush();
        
        return $item->getId();
    }
}

?>
