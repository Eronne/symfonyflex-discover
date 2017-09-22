<?php
/**
 * Created by IntelliJ IDEA.
 * User: eletue
 * Date: 22/09/2017
 * Time: 16:42
 */

namespace App\Repository;


class GifRepository
{
    /**
    x    * @return array\News[]
     */
    public function findAllOrderByCreatedDesc(): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->execute();
    }
}