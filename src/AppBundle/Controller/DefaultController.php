<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Pagerfanta\Adapter\ArrayAdapter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pagerfanta\Pagerfanta;

class DefaultController extends Controller
{

    public function addAction(Request $request)
    {
        $persister = $this->getDoctrine()->getManager();
        $post =  new Post();
        $form = $this->get('form.factory')->createBuilder('form', $post)
            ->add('title', 'text')
            ->add('body', 'textarea')
            ->add('category', 'entity', array(
                'class' => 'AppBundle:Category'
            ))
            ->add('isPublished', 'checkbox')
            ->add("Publier", "submit")
            ->getForm();
        $form->handleRequest($request);

        if($form->isValid()){
            $persister->persist($post);
            $persister->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Article Publié!');
            return $this->redirectToRoute('blog_show_post', array(), 301);
        }

        return $this->render('AppBundle:default:addPost.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $emRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post');

        $post = $emRepo->findOneById($id);
        $post->setTitle("Modification du Titre");
        $em->persist($post);
        $em->flush();

        return $this->render('AppBundle:default:editPost.html.twig', array(
            'id' => $id,
            'post' => $post
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

    public function showAction(){
        $emRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post');
        $posts = $emRepo->findAll();
        $adapter = new ArrayAdapter($posts);
        $pagerFanta = new Pagerfanta($adapter);
        $pagerFanta->setMaxPerPage(2);
        $pagerFanta->hasNextPage();
        $pagerFanta->hasPreviousPage();

        return $this->render('AppBundle:default:showPost.html.twig', array(
            'posts' => $posts,
            'pager' => $pagerFanta
        ));
    }

    public function searchAction(Request $request){
        $postRepo = $this->getDoctrine()->getManager()->getRepository("AppBundle:Post");
        $form = $this->get('form.factory')->createBuilder('form')
            ->add('title', 'text')
            ->add('category', 'entity', array(
                'class' => 'AppBundle:Category'
            ))
            ->add('isPublished', 'checkbox')
            ->getForm();

        $form->handleRequest($request);
        if($form->isValid()){
            $posts = $postRepo->searchPost($form->getData());

            $content = $this->get('templating')->render('AppBundle:Default:searchPost.html.twig', array(
                'posts' => $posts
            ));
            return new Response($content);
        }
        $content = $this->get('templating')->render('AppBundle:Default:searchPost.html.twig', array(
            'form' => $form
        ));
        return new Response($content);
    }


}
