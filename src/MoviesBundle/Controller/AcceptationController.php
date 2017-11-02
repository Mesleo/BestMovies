<?php

namespace MoviesBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\CommentDelete;
use AppBundle\Entity\Movie;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class AcceptationController
 * @Route("/acceptacion")
 */
class AcceptationController extends Controller
{

    private $em = null;
    private $params = null;

    /**
     * Muestra la vista de los comentarios del usuario logeado
     *
     * @Route("/register", name="clause_acceptance")
     */
    public function showFilmAction()
    {
        return $this->render('MoviesBundle:Clauses:clause_register.html.twig');
    }

}
