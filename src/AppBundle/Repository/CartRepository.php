<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 18.12.7
 * Time: 12.22
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CartRepository extends EntityRepository
{
    public function findByUserAndProduct($userId, $productId)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT p
            FROM AppBundle:Cart p
            WHERE p.user = :userId AND p.product = :productId AND p.order IS NULL'
        )->setParameters(array('userId' => $userId, 'productId' => $productId));

        return $query->getResult();
    }

    public function findByUser($userId)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT p
            FROM AppBundle:Cart p
            WHERE p.user = :userId AND p.order IS NULL'
        )->setParameter('userId', $userId);

        return $query->getResult();
    }

}