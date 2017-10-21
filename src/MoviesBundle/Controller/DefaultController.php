<?php

namespace MoviesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{

    private $em = null;
    private $params = null;
    private $filter = "";
    private $nameFilter = "";
    private $year = "";
    private $sy = null;
    private $fy = null;
    private $st = null;
    private $ft = null;
    private $length = "";
    private $category = [];
    private $order = "";
    private $directionOrder = 0;
    private $view = "TODO";

    /**
     * Obtengo todas las películas paginadas ordenadas primero por fecha y después alfabéticamente
     *
     * @Route("/", name="films")
     */
    public function indexAction(Request $request)
    {
        $this->initialize();

        $result = null;
        $info = null;
        $aux = "";
        if($request->query->has('result')){
            $result = $request->query->get('result');
            $this->order = 7;
            $this->directionOrder = 0;
        }
        if($request->query->has('info')){
            $info = $request->query->get('info');
        }

        $this->params['movies'] = $this->em->getRepository("AppBundle:Movie")
            ->getFilmByField($this->filter, $this->nameFilter, $this->sy, $this->fy,
                intval($this->st), intval($this->ft), null, null, null, $this->order, $this->directionOrder);
        $a = 0;
        $count = count($this->params['movies']);
//        for($i = 0; $i < $count; $i++){
//            if($this->params['movies'][$i]['num'] != $aux){
//                $a = $i;
//                $this->params['movies'][$i]['categories'] = [];
//                array_push($this->params['movies'][$i]['categories'], $this->params['movies'][$i]['category']);
//                $aux = $this->params['movies'][$i]['num'];
//            }else{
//                array_push($this->params['movies'][$a]['categories'], $this->params['movies'][$i]['category']);
//                unset($this->params['movies'][$i]);
//            }
//        }

        $this->params['values'] = $this->em->getRepository("AppBundle:Movie")
            ->getValuesMinMaxLengthYear();

//        $categories = $this->em->getRepository("AppBundle:Category")
//            ->findBy([],[
//                'name' => 'ASC'
//            ]);

        // Añadimos el paginador (En este caso el parámetro "1" es la página actual,
        // y parámetro "6" es el número de peliculas a mostrar)
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->params['movies'],
            $request->query->get('page', 1),12
        );

        //Obtengo todas las películas en json para mostrar las sugerencias en la búsqueda
        $films = json_encode($this->em->getRepository("AppBundle:Movie")->getFilms());

        return $this->render('MoviesBundle:Film:films.html.twig', array(
            'pagination' => $pagination,
//            'categories' => $categories,
            'films' => $films,
            'values' => $this->params['values'],
            'result' => $result,
            'info' => $info,
            'vfOne' => false,
            'page' => null
        ));
    }

    /**
     * Obtengo las películas buscadas por un filtro paginadas ordenadas primero
     * por fecha y después alfabéticamente
     *
     * @Route("/films" , name="search_films")
     */
    public function searchFilms(Request $request){
        $this->initialize();

        $session = $request->getSession();
        $page = 0;
        $aux = "";
        if(
            $request->request->has('filter')
            && $request->request->has('anio')
            && $request->request->has('duracion')
        ) {
            $this->filter = $request->request->get("filter");
            $this->nameFilter = $request->request->get("name-filter");
            $this->year = $request->request->get("anio");
            $this->length = $request->request->get("duracion");
            $this->directionOrder = 0;
            $session->set('name_filter', $this->nameFilter);
            $session->set('filter', $this->filter);
            $session->set('year', $this->year);
            $session->set('length', $this->length);
        }

        if($request->request->has('order')){
            $this->order = $request->request->get("order");
            if($request->request->has('inv-order')){
                $this->directionOrder = 1;
            }
            $session->set('order', $this->order);
            $session->set('direction_order', $this->directionOrder);
        }

        if($request->query->has('page')) $page = $request->query->get('page');

        if($session->get('filter')){
            $this->nameFilter = $session->get('name_filter');
            $this->filter = $session->get('filter');
            $this->year = $session->get('year');
            $this->length = $session->get('length');
            $this->order = $session->get('order');
            $this->directionOrder = $session->get('direction_order');
        }

        $this->sy = substr($this->year, 0, strpos($this->year, ","));
        $this->fy = str_replace(",", "", substr($this->year, strpos($this->year, ","), strlen($this->year)));
        $this->st = substr($this->length, 0, strpos($this->length, ","));
        $this->ft = str_replace(",", "", substr($this->length, strpos($this->length, ","), strlen($this->length)));


        $this->params['movies'] = $this->em->getRepository("AppBundle:Movie")
            ->getFilmByField($this->filter, $this->nameFilter, $this->sy, $this->fy,
                    intval($this->st), intval($this->ft), null, null, null, $this->order, $this->directionOrder);

        $a = 0;
        $count = count($this->params['movies']);
        for($i = 0; $i < $count; $i++){
            if($this->params['movies'][$i]['num'] != $aux){
                $a = $i;
                $this->params['movies'][$i]['categories'] = [];
                array_push($this->params['movies'][$i]['categories'], $this->params['movies'][$i]['category']);
                $aux = $this->params['movies'][$i]['num'];
            }else{
                array_push($this->params['movies'][$a]['categories'], $this->params['movies'][$i]['category']);
                unset($this->params['movies'][$i]);
            }
        }

        $this->params['values'] = $this->em->getRepository("AppBundle:Movie")
            ->getValuesMinMaxLengthYear();

        // Añadimos el paginador (En este caso el parámetro "1" es la página actual,
        // y parámetro "6" es el número de peliculas a mostrar)
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->params['movies'],
            $request->query->get('page', 1),12
        );

        $field = "";
        switch ($this->filter){
            case 1:
                $field = 'Título';
                break;
            case 2:
                $field = 'Intérpretes';
                break;
            case 3:
                $field = 'Director';
                break;
            case 4:
                $field = 'País';
                break;
            case 5:
                $field = 'Saga';
                break;
            case 6:
                $field = 'Todo';
                break;

        }

        return $this->render('MoviesBundle:Film:films.html.twig', array(
            'pagination' => $pagination,
            'movies' => $this->params['movies'],
            'films' => json_encode($this->em->getRepository("AppBundle:Movie")->getFilms()),
            'values' => $this->params['values'],
            'page' => $page,
            'filter' => $this->filter,
            'field' => $field,
            'order' => $this->order,
            'direction_order' => $this->directionOrder,
            'num_films' => count($this->params['movies']),
            'name_filter' => $this->nameFilter,
            'year' => $this->year,
            'time' => $this->length,
            'vfOne' => false
        ));
    }

    /**
     * Obtengo las películas según la categoría en formato JSON por una llamada de Ajax paginadas
     * ordenadas primero por fecha y después alfabéticamente
     *
     * @Route("/films/category" , name="filter_films_category")
     */
    public function filterFilmsCategory(Request $request){
        $this->initialize();

        $session = $request->getSession();

        if($request->request->has('categories')) {
            $this->category = [];
            $this->category = $request->request->get("categories");
            $session->set('categories', $this->category);
        }

        if ($request->request->has('view')){
            $this->setViewFilter($request);
            $session->set('view', $this->view);
        }else{
            $this->view = "TODO";
        }

        if (isset($_GET['page'])) {
            $this->category = $session->get('categories');
            $this->view = $session->get('view');
        }else{
            $session->set('view', $this->view);
        }


        if($session->get('filter')){
            $this->nameFilter = $session->get('name_filter');
            $this->filter = $session->get('filter');
            $this->year = $session->get('year');
            $this->length = $session->get('length');
            $this->order = $session->get('order');
            $this->directionOrder = $session->get('direction_order');
        }

        $sy = substr($this->year, 0, strpos($this->year, ","));
        $fy = str_replace(",", "", substr($this->year, strpos($this->year, ","), strlen($this->year)));
        $st = substr($this->length, 0, strpos($this->length, ","));
        $ft = str_replace(",", "", substr($this->length, strpos($this->length, ","), strlen($this->length)));

        $this->params['movies'] = $this->em->getRepository("AppBundle:Movie")
            ->getFilmByField($this->filter, $this->nameFilter, $sy, $fy, intval($st), intval($ft), $this->category,
                0, null, $this->order, $this->directionOrder, $this->view);

        $pageNum = 1;
        $total_paginas = 1;
        $total_peliculas = count($this->params['movies']);

        //Si hay registros
        if ($total_peliculas > 0) {
            //numero de registros por página
            $rowsPerPage = 12;

            //por defecto mostramos la página 0
            $pageNum = 0;

            // si $_GET['page'] esta definido, usamos este número de página
            if (isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            //contando el desplazamiento
            $offset = $pageNum * $rowsPerPage;
            $total_paginas = ceil($total_peliculas / $rowsPerPage);

            $this->params['movies'] = $this->em->getRepository("AppBundle:Movie")
                ->getFilmByField($this->filter, $this->nameFilter, $sy, $fy, intval($st), intval($ft), $this->category,
                    intval($offset), intval($rowsPerPage), $this->order, $this->directionOrder, $this->view);
        }


        $array = array(
            'total_paginas' => $total_paginas ,
            'page_num' => intval($pageNum),
            'total_pelis' => $total_peliculas,
            'movies' => $this->params['movies'],
            'name_filter' => $this->filter,
            'vfOne' => false
        );


        return new JsonResponse($array);
    }

    /**
     * Obtengo una película por el titulo
     *
     * @Route("/films/film" , name="film_view")
     * @Method({"GET"})
     */
    public function getFilm(Request $request){
        $this->initialize();
        if($request->query->has('m') || $request->query->has('idF')){
            $url = null;
            $movie = null;
            if($request->query->has('m')) {
                $url = trim($request->query->get('m'));
                $url = str_replace('%5O', ' ', $url);
                $url = str_replace('%3A', ':', $url);
                $url = str_replace(']', '-', $url);
                $url = str_replace('[', '+', $url);
                $movie = $this->em->getRepository("AppBundle:Movie")
                    ->getFilm($url, null);
            }else if($request->query->has('idF')){
                $url = trim($request->query->get('idF'));
                $movie = $this->em->getRepository("AppBundle:Movie")
                    ->getFilm(null, $url);
            }
            if($movie){
                $film = $this->em->getRepository("AppBundle:Movie")
                    ->findOneByNum($movie['num']);
                $clicks = $film->getClick();
                if($clicks == null){
                    $clicks = 1;
                }
                $clicks = $clicks+1;
                $film->setClick($clicks);
                $this->em->persist($film);
                $this->em->flush();
                $this->params['values'] = $this->em->getRepository("AppBundle:Movie")
                    ->getValuesMinMaxLengthYear();

                $this->params['pagination'][] = $movie;

                $films = json_encode($this->em->getRepository("AppBundle:Movie")->getFilms());

                return $this->render('MoviesBundle:Film:films.html.twig', array(
                    'pagination' =>  $this->params['pagination'],
                    'films' => $films,
                    'vfOne' => true,
                    'film' => $film,
                    'url' => trim($request->query->get('m')),
                    'values' => $this->params['values']
                ));
            }
        }
        return $this->redirectToRoute('films');
    }

    /**
     * Modifico el campo visto de la pelicula y el campo mi nota
     *
     * @Route("/films/film/view" , name="set_view_film")
     * @Method({"POST"})
     */
    public function setViewFilm(Request $request){
        $this->initialize();
        $ok = ['ok' => false];
        if($request->request->has('idFilm') && $request->request->has('view')){
            $idFilm = $request->request->get('idFilm');
            $v = $request->request->get('view');
            $film = $this->em->getRepository("AppBundle:Movie")
                ->findOneBy(['num' => $idFilm]);
            if(!$v){
                $film->setVisto($v);
                $film->setMiNota("");
            }
            if(is_numeric($request->request->get('punt'))) {
                if ($request->request->get('punt') >= 0 && $request->request->get('punt') <= 10) {
                    $film->setVisto($v);
                    $film->setMiNota($request->request->get('punt'));
                } else {
                    $ok = ['ok' => 'La nota debe ser un número entre 0 y 10'];
                }
            }else{
                $ok = ['ok' => 'La puntuación debe ser un número'];
            }
            $this->em->persist($film);
            $this->em->flush();
        }
        return new JsonResponse($ok);
    }

    /**
     * Obtengo el video de la pelicula pasado por id
     *
     * @Route("/films/film/video" , name="get_video_film")
     */
    public function getVideoFilm(Request $request){
        $this->initialize();
        if($request->query->has('num')){
            $idFilm = $request->query->get('num');
            $this->params['movie'] = $this->em->getRepository("AppBundle:Movie")
                ->getVideoFilm($idFilm);
        }
        return $this->render("MoviesBundle:Film:video.html.twig", $this->params);
    }

    /**
     * Limpio todas las sesiones y filtros
     *
     * @Route("/filters/clean" , name="clean_filters")
     */
    public function cleanFilters(Request $request){
        $session = $request->getSession();
        $session->set('name_filter', "");
        $session->set('filter', "");
        $session->set('year', "");
        $session->set('length', "");
        $session->set('order', "");
        $session->set('direction_order', "");
        $session->set('categories', "");
        $session->set('view', "");
        return $this->redirectToRoute("films");
    }

    /**
     * Genera una url corta mediante bitly
     * @Route("/link" , name="link_bitly")
     * @Method({"GET"})
     */
    public function acortarUrl(Request $request) {
        if($request->query->has('idF')) {
            $idF = trim($request->query->get('idF'));
            $usuario = "o_3km6es2rq5";
            $url = 'http://bestmovies.hol.es/films/film?idF='.$idF;
            $apikey = "R_fade2190453f4169b9c7803dd42c05cc";
            $temp = "http://api.bit.ly/v3/shorten?login=" . $usuario . "&apiKey=" . $apikey . "&uri=" . $url . "&format=txt";
            return new JsonResponse(file_get_contents($temp));
        }
        return false;
    }

    private function initialize(){
        $this->em = $this->getDoctrine()->getManager();
        $this->params = [];
    }

    /**
     * @param Request $request
     * @param $session
     */
    private function setViewFilter(Request $request)
    {
        if (count($request->request->get('view')) > 1 || !$request->request->has('view')) {
            $this->view = "TODO";
        } else if ($request->request->get('view')[0] == 1) {
            $this->view = 1;
        } else if ($request->request->get('view')[0] == 0) {
            $this->view = 0;
        }
    }

}
