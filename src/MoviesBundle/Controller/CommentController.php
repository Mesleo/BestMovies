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
 * Class CommentController
 * @Route("/comment")
 */
class CommentController extends Controller
{

    private $em = null;
    private $params = null;

    /**
     * Displays comments of the logged in user
     *
     * @Route("/", name="show_comments")
     */
    public function showFilmAction()
    {
        $this->initialize();
        if($this->getUserLogged() != null){
            $this->params['comments'] = $this->em->getRepository('AppBundle:Comment')
                ->getCommentsOrderByDate(null, null, 1, $this->getUserLogged()->getId(), 3);
            $this->params['comments_length'] = $this->em->getRepository('AppBundle:Comment')
                ->getCommentsCount($this->getUserLogged()->getId());
            return $this->render('MoviesBundle:Comment:comments.html.twig', $this->params);
        }else{
            return $this->redirectToRoute('films');
        }
    }

    /**
     * Displays comment of a movie
     *
     * @Route("/view", name="comment_view" )
     */
    public function viewCommentAction(Request $request)
    {
        $this->initialize();
        if($request->query->has('m')){
            $this->params['comment'] = $this->em->getRepository('AppBundle:Comment')
            ->findOneById($request->query->get('m'));
            return $this->render('MoviesBundle:Comment:comment.html.twig', $this->params);
        }
        return false;
    }

    /**
     * Create form for add comment of a movie
     * if form is submit, it is validated
     *
     * @Route("/create", name="comment_add" )
     */
    public function createCommentAction(Request $request)
    {
        $this->initialize();

        if($this->getUserLogged()) {
            $session = $request->getSession();
            $info = null;
            $idFilm = null;
            if ($request->query->has('film')) {
                $idFilm = $request->query->get('film');
            } elseif ($request->request->has('film')) {
                $idFilm = $request->request->get('film');
            } elseif ($session->get('film') != null) {
                $idFilm = $session->get('film');
            }

            $film = $this->em->getRepository("AppBundle:Movie")
                ->findOneBy([
                    'num' => $idFilm
                ]);
            $user = $this->em->getRepository("AppBundle:User")
                ->findOneById($this->getUserLogged()->getId());

            $comment = $this->em->getRepository('AppBundle:Comment')
                ->findOneBy([
                    'movie' => $film->getNum(),
                    'user' => $user->getId()
                ]);
            if ($comment == null) {
                $comment = new Comment();
            }

            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            $this->params['active'] = 't';
            if ($request->query->has('info')) {
                $info = $request->query->get('info');
            }

            if ($form->isSubmitted() && $form->isValid() && $film != null
                && $user != null
            ) {

                if ($film != null) {
                    $comment->setMovie($film);
                    $comment->setUser($this->getUserLogged());
                }

                $comment->setScore($request->request->get('appbundle_comment')['score']);
                $com = nl2br($request->request->get('appbundle_comment')['comment']);
                for($o = 0; $o < strlen($com); $o++){
                    if($com[$o] == '<' && $com[$o+4] == '/' && $com[$o+5] == '>'){
                        if($com[$o+8] == '<' && $com[$o+12] == '/' && $com[$o+13] == '>'){
                            $com = str_replace('<br />  <br />', '<br />', nl2br($com));
                        }
                    }
                }
                $comment->setDateAdd(new \DateTime());

                if ($comment->getMovie() != null && $comment->getUser() != null
                    && $comment->getScore() != null
                ) {
                    $this->em->persist($comment);
                    $this->em->flush();

                    return $this->redirectToRoute('comment_add', array(
                        'info' => ' ',
                        'film' => $film->getNum()
                    ));
                }
            }

            return $this->render('MoviesBundle:Comment:create_comment.html.twig', array(
                'form' => $form->createView(),
                'comment' => $comment,
                'film' => $film,
                'info' => $info
            ));

        }
        if(
            $request->query->has('film')
            && $request->query->has('p') && $request->query->get('p') == 1){
            $this->params['p'] = $request->query->get('p');
            $this->params['film'] = trim($request->query->get('film'));
        }
        return $this->redirectToRoute('fos_user_security_login', $this->params);
    }

    /**
     * Deletes comment but it is clone to other table for not to lose it altogether
     * @Route("/delete", name="comment_del" )
     */
    public function deleteCommentAction(Request $request){
        $this->initialize();
        if($request->request->has('comment')){
            $comment = $this->em->getRepository('AppBundle:Comment')
                ->findOneById($request->request->get('comment'));
            $commentDel = new CommentDelete();
            $commentDel->setTitle($comment->getTitle());
            $commentDel->setComment($comment->getComment());
            $commentDel->setScore($comment->getScore());
            $commentDel->setMovie($comment->getMovie());
            $commentDel->setUser($comment->getUser());
            $commentDel->setDateAdd($comment->getDateAdd());
            $commentDel->setDateTrash(new \DateTime());

            $this->em->persist($commentDel);
            $this->em->remove($comment);
            $this->em->flush();
            return $this->redirectToRoute('comment_add', array(
                'info' => 'del',
                'film' => $comment->getMovie()->getNum()
            ));
        }
        return false;
    }

    /**
     * Loads comments by a movie
     * @Route("/film", name="comments_film" )
     */
    public function loadCommentsFilm(Request $request){
        $this->initialize();
        if($request->request->has('scroll')
            && $request->request->has('film')){
            $more = $request->request->get('scroll');
            $film = $request->request->get('film');
            $comments = $this->em->getRepository('AppBundle:Comment')
                ->getCommentsByFilm($film, $more);
            return new JsonResponse($comments);
        }
        return null;
    }

    /**
     * Loads average score by a movie
     * @Route("/mediaScore", name="media_comments_film" )
     */
    public function getMediaCommentsFilm(Request $request){
        $this->initialize();
        if($request->request->has('film')){
            $film = $request->request->get('film');
            $comments = $this->em->getRepository('AppBundle:Comment')
                ->getMediaCommentsFilm($film);
            return new JsonResponse($comments);
        }
        return null;
    }

    /**
     * Filter comments for AJAX
     * @Route("/filterComments", name="filter_comments" )
     */
    public function filterCommentsJson(Request $request){
        $this->initialize();
        if($request->query->has('s')
            && $request->query->has('q')){
            $searchName = $request->query->get('s');
            $search = $request->query->get('q');
            $comments = $this->em->getRepository('AppBundle:Comment')
                ->getResultsSearchAJAX($this->getUserLogged()->getId(), $searchName, $search);
            return new JsonResponse($comments);
        }
        return null;
    }

    /**
     * Loads comments for AJAX requests
     * @Route("/moreComments", name="more_comments" )
     */
    public function loadMoreCommentsAjax(Request $request){
        $this->initialize();
        $searchName = null;
        $search = null;
        if($request->request->has('scroll')
            && $request->request->has('order')){
            $more = $request->request->get('scroll');
            $order = $request->request->get('order');
            if($request->request->has('sN')
                && $request->request->has('s')){
                $searchName = $request->request->get('sN');
                $search = $request->request->get('s');
            }
            $comments = $this->em->getRepository('AppBundle:Comment')
                ->getCommentsOrderByDate($searchName, $search, $order, $this->getUserLogged()->getId(), $more);
            return new JsonResponse($comments);
        }
        return null;
    }

    /**
     * Gets user logged in
     * @return user
     */
    private function getUserLogged(){
        return $this->em->getRepository('AppBundle:User')
        ->findOneById($this->getUser());
    }

    /**
     * Initializes database handler and params variable
     */
    private function initialize(){
        $this->em = $this->getDoctrine()->getManager();
        $this->params = [];
    }

}
