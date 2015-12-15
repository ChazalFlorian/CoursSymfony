<?php

use Doctrine\Common\DataFixtures\FixtureInterface as FixtureInterface;
use \Doctrine\Common\Persistence\ObjectManager as ObjectManager;
use \AppBundle\Entity\Post as Post;

class LoadPostFixtures implements FixtureInterface
{

    function load(ObjectManager $manager){
        $i = 1;

        while($i <+ 100)
        {
            $post = new Post();
            $post->setTitle('Titre du post n°'.$i);
            $post->setBody('Corps du post');
            $post->setIsPublished($i%2);
            $manager->persist($post);
            $i++;
        }
        $manager->flush();
    }

}