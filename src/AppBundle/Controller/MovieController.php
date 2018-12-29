<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializationContext;

class MovieController extends Controller
{
    /**
     * @Route("/", name="movie_list")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $movies = $this->getDoctrine()->getRepository('AppBundle:Movie')->findAll();
        $data = $this->get('jms_serializer')->serialize($movies, 'json', SerializationContext::create()->setGroups(array('detail')));
        
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
        // return $this->render('AppBundle:Movie:index.html.twig', array(
        //     // ...
        // ));
    }

    /**
     * @Route("/movie/{id}", name="movie_show")
     */
    public function showAction(Movie $movie)
    {
        $data = $this->get('jms_serializer')->serialize($movie, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @Route("/movie", name="movie_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $data = $request->getContent();
        // var_dump($data);
        // exit;
        $movie = $this->get('jms_serializer')->deserialize($data, 'AppBundle\Entity\Movie', 'json');

        $em = $this->getDoctrine()->getManager();

        $em->persist($movie);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);

    }
}
