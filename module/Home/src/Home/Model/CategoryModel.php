<?php
namespace Home\Model;

use Doctrine\ORM\EntityManager;

use Zend\ServiceManager\ServiceManager;

/**
 * Description of CategoryModel
 *
 * @author adam
 */
class CategoryModel {
    
    /**
     *
     * @var EntityManager
     */
    private $_entityManager;
    
    /**
     *
     * @var type ServiceManager
     */
    private $_serviceManager;
    
    public function __construct(EntityManager $em, ServiceManager $sm) {
        $this->_entityManager = $em;
        $this->_serviceManager = $sm;
    }
    
    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getCategoryRepository() {
        return $this->_entityManager->getRepository("ORMs\Entity\Category");
    }
    
    public function findByName($name) {
        return $this->getCategoryRepository()->findOneBy(array(
            'name' => $name
        ));
    }
    
    public function findById($id) {
        return $this->_entityManager
                ->find("ORMs\Entity\Category", $id);
    }
    
    public function findAllOrderByNameAndItems() {
        $em = $this->_entityManager;
        $query = $em->createQuery(
                'SELECT c.id as id, c.name as name, count(c) as items_count 
                    FROM ORMs\Entity\Category c 
                    INNER JOIN c.items it
                    GROUP BY name
                    ORDER BY name ASC'
        );
        
        return $query->getResult();
    }
}

?>
