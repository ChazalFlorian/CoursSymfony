<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01/02/2016
 * Time: 11:44
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository{

    public function searchPost($parameters){
        $parameters = explode(' ', $parameters);
        $qb = $this->createQueryBuilder('p');

    }
}