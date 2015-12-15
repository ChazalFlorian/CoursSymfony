<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    public function addAction()
    {
        $em = $this->getDoctrine()->getManager();

        $post = new Post();
        $post->setTitle("Coucou les coupaings!");
        $post->setBody("Ceci est un article de test dans le cadre du cours Symfony a Sup\'Internet. <br> Il ne contient rien");
        $post->setIsPublished(true);
        $em->persist($post);
        $em->flush();

        return $this->render('AppBundle:default:addPost.html.twig');
    }

    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $emRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post');
        //$post = new Post();
        $post = $emRepo->findOneById($id);
        $post->setTitle("Modification du Titre");
        $em->persist($post);
        $em->flush();

        return $this->render('AppBundle:default:editPost.html.twig', array(
            'id' => $id
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $emRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post');
        $post = $emRepo->findOneById($id);
        $em->remove($post);
        $em->flush();

        return $this->render('AppBundle:default:deletePost.html.twig', array(
            'id' => $id
        ));
    }
}
