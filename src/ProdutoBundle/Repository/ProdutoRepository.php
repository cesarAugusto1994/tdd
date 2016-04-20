<?php

namespace ProdutoBundle\Repository;

use ProdutoBundle\Entity\Produto;

/**
 * ProdutoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProdutoRepository extends \Doctrine\ORM\EntityRepository
{

    public function save(Produto $produto)
    {
        $this->getEntityManager()->persist($produto);
        $this->getEntityManager()->flush($produto);
    }

    public function search($search)
    {
        return $this->getEntityManager()->getRepository('ProdutoBundle:Produto')
            ->createQueryBuilder('p')
            ->where('p.nome LIKE :nome')
            ->setParameter(':nome', $search.'%')
            ->getQuery()->getResult();
    }
}