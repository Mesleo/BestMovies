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
 * Class UserController
 * @Route("/user")
 */
class UserController extends Controller
{

    private $em = null;
    private $params = null;

    /**
     * @Route("/{email}/check", name="enabled_user")
     *
     * @param  [type] $email [description]
     * @return [type]           [description]
     */
    public function checkUser($email)
    {
        $this->initialize();

        $usuario = $this->em->getRepository("AppBundle:User")
            ->findOneBy([
                'email' => $email
            ]);

        $usuario->setEnabled(true);
        $this->em->flush();
        return $this->redirectToRoute('fos_user_security_login');
    }

    private function getUserLogged(){
        return $this->em->getRepository('AppBundle:User')
        ->findOneById($this->getUser());
    }

    private function initialize(){
        $this->em = $this->getDoctrine()->getManager();
        $this->params = [];
    }

}
