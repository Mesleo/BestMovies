<?php

namespace MoviesBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\CommentDelete;
use AppBundle\Entity\Likes;
use AppBundle\Entity\Movie;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class LikeController
 * @Route("/like")
 */
class LikeController extends Controller
{

    private $em = null;
    private $params = null;

    /**
     * @Route("/", name="like_no_like")
     */
    public function setLikeAction(Request $request)
    {
        $this->initialize();
        if($this->getUserLogged() != null){
            $response = null;
            if($request->request->has('like') && $request->request->has('comment')){
                $comment = $this->em->getRepository('AppBundle:Comment')
                    ->findOneById(trim($request->request->get('comment')));
                $like = $this->em->getRepository('AppBundle:Likes')
                    ->findOneBy([
                        'comment' => $comment,
                        'user' => $this->getUserLogged()
                    ]);
                if($like == null){
                    $like = new \AppBundle\Entity\Likes();
                    $like->setComment($comment);
                    $like->setUser($this->getUserLogged());
                }

                $like->setLikeNotLike(trim($request->request->get('like')));
                $this->em->persist($like);
                $this->em->flush();
            }
            $response['like'] = $this->em->getRepository('AppBundle:Likes')
                ->getLikesComment(trim($request->request->get('comment')), 1);
            $response['no_like'] = $this->em->getRepository('AppBundle:Likes')
                ->getLikesComment(trim($request->request->get('comment')), 0);
            return new JsonResponse($response);
        }else{
            return $this->redirectToRoute('films');
        }
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
