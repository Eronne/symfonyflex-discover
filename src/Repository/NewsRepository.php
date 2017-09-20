<?php
/**
 * @author Erwann LETUE <erwann.letue@gmail.com>
 * Date: 20/09/2017 at 10:59
 */

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class NewsRepository extends EntityRepository
{

    /**
x     * @return array\News[]
     */
    public function findAllOrderByCreatedDesc(): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->execute();
    }
}