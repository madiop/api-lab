<?php

namespace AppBundle\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\Routing\Annotation\Route;

use AppBundle\Entity\Movie;
use AppBundle\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class MovieController extends ApiController
{
    /**
    * @Route("/moviese", methods="GET")
    */
    // public function index(MovieRepository $movieRepository)
    public function index()
    {
        return new JsonResponse([
            [
                'id' => 1,
                'title' => 'Bride the prince',
                'count' => 0
            ],
            [
                'id' => 2,
                'title' => 'The Princess',
                'count' => 0
            ],
            [
                'id' => 3,
                'title' => 'The impolite man',
                'count' => 0
            ],
            [
                'id' => 4,
                'title' => 'Girl of her man',
                'count' => 0
            ]
        ]);
        // $movies = $movieRepository->transformAll();

        // return $this->respond($movies);
    }

    /**
    * @Route("/movies", methods="POST")
    */
    public function create(Request $request, MovieRepository $movieRepository, EntityManagerInterface $em)
    {
        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        // validate the title
        if (! $request->get('title')) {
            return $this->respondValidationError('Please provide a title!');
        }

        // persist the new movie
        $movie = new Movie;
        $movie->setTitle($request->get('title'));
        $movie->setCount(0);
        $em->persist($movie);
        $em->flush();

        return $this->respondCreated($movieRepository->transform($movie));
    }

    /**
    * @Route("/movies/{id}/count", methods="POST")
    */
    public function increaseCount($id, EntityManagerInterface $em, MovieRepository $movieRepository)
    {
        $movie = $movieRepository->find($id);

        if (! $movie) {
            return $this->respondNotFound();
        }

        $movie->setCount($movie->getCount() + 1);
        $em->persist($movie);
        $em->flush();

        return $this->respond([
            'count' => $movie->getCount()
        ]);
    }
}