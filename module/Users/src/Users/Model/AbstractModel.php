<?php
namespace Users\Model;

/**
 * Description of AbstractModel
 *
 * @author adam
 */
abstract class AbstractModel {
    
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_entityManager;
    
    public function __construct(\Doctrine\ORM\EntityManager $manager) {
        $this->_entityManager = $manager;
    }
    
    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->_entityManager;
    }
    
    /**
     * 
     * @param string $entity
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository($entity) {
        return $this->_entityManager->getRepository($entity);
    }
    
}

?>
