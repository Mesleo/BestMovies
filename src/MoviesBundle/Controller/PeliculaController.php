<?php

namespace MoviesBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Movie;
use AppBundle\Entity\MovieCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class PeliculaController
 * @Route("/film")
 */
class PeliculaController extends Controller
{

    private $em = null;
    private $params = null;

    /**
     * Muestra la vista para añadir nueva película
     *
     * @Route("/new", name="show_new_films")
     */
    public function showFilmAction()
    {

        $this->initialize();
        $this->getDifferentValues();
        return $this->render('MoviesBundle:Film:film.html.twig', $this->params);
    }


    /**
     * Añade una nueva pelicula
     *
     * @Route("/add", name="add_new_film")
     */
    public function addFilmAction(Request $request)
    {
        $this->initialize();
        if($request->request->has('title')) {
            $ok = 0;
            $arrCategories = [];
            if($request->request->get('id-film')){
                $movie = $this->em->getRepository("AppBundle:Movie")
                    ->findOneBy(["num" => $request->request->get('id-film')]);
            }else{
                $movie = new Movie();
                $movie->setDateAdd(new \DateTime());
            }
            $movie->setOriginalTitle(trim($request->request->get('title')));
            $movie->setSaga(trim($request->request->get('saga')));
            $movie->setYear(trim($request->request->get('year')));
            $movie->setLength(trim($request->request->get('time')));
            $movie->setDirector(trim($request->request->get('director')));
            $movie->setCountry(trim($request->request->get('country')));
            $movie->setRating(trim($request->request->get('rating')));
            $movie->setDateUpdate(new \DateTime());
            $arrCategories = explode("/", trim($request->request->get('categories')));

            $a = 1;
            foreach($arrCategories as $category){
                $categ = $this->em->getRepository("AppBundle:Category")
                    ->findOneBy(['name' => $category]);
                if($categ==null){
                    $categ = new Category();
                    $categ->setName($category);
                    $this->em->persist($categ);
                    $this->em->flush();
                }
                $movieCateg = $this->em->getRepository("AppBundle:MovieCategory")
                    ->findOneBy(
                        ['movies' => $movie->getNum(),
                            'categories' => $categ->getId()
                        ]
                    );
                if($movieCateg == null){
                    $movieCateg = new MovieCategory();
                }
                $movieCateg->setCategories($categ);
                $movieCateg->setMovies($movie);
                $movieCateg->setSequence($a);
                $movie->addHasCategory($movieCateg);
                $this->em->persist($movieCateg);
                $a++;
            }
            $movie->setActors(trim($request->request->get('actors')));
            $movie->setDescription(trim($request->request->get('description')));
            $movie->setUrlVideo(trim($request->request->get('video')));

            $movie->setFileSize(trim($request->request->get('filesize')));
            $movie->setVideoFormat(trim($request->request->get('format')));
            if($request->request->has('view')){
                $movie->setVisto(true);
            }else{
                $movie->setVisto(false);
            }
            $movie->setMiNota(trim($request->request->get('my-calification')));
            $movie->setMedia(trim($request->request->get('ubication')));
            $movie->setTrash(0);
            if($this->em->persist($movie)){
                $ok = 3;
            }
            $this->em->flush();

            if ($_FILES['upl'] && $_FILES['upl']['name'] != "" && $_FILES['upl']['type'] != ""
                && $_FILES['upl']['size'] > 0) {
                if(!$this->uploadFileFilm($movie)){
                    $ok = 1;
                }else{
                    $ok = 3;
                }
            }else{
                if($movie->getPicture() == "" || $movie->getPicture() == null) {
                    $ok = 2;
                }
            }
            if($ok != 0){
                switch ($ok){
                    case 1:
                        $this->params['result'] = "Película añadida correctamente pero error al añadir la carátula";
                        break;
                    case 2:
                        $this->params['result'] = "No ha seleccionado carátula para la película '" . $movie->getOriginalTitle() . "'";
                        break;
                    case 3:
                        $this->params['result'] = "Película añadida correctamente";
                        break;
                }
            }
        }
        return $this->redirectToRoute('films', $this->params);
    }

    /**
     * Guarda los campos de una película ya existente localizada pos su id pasado por POST
     *
     * @Route("/save", name="save_film")
     */
    public function editFilmAction(Request $request)
    {
        $this->initialize();
        $this->getDifferentValues();
        if ($request->request->has('id_film')) {
            $arrCategories = [];
            $movie = $this->em->getRepository("AppBundle:Movie")
                ->findOneBy(['num' => $request->request->get('id_film')]);

            $ok = 0;
            $view = false;
            if($request->request->get('view') == "on"){
                $view = true;
            }

            $movie->setOriginalTitle(trim($request->request->get('title')));
            $movie->setSaga(trim($request->request->get('saga')));
            $movie->setYear(trim($request->request->get('year')));
            $movie->setLength(trim($request->request->get('time')));
            $movie->setDirector(trim($request->request->get('director')));
            $movie->setCountry(trim($request->request->get('country')));
            $movie->setRating(trim($request->request->get('rating')));
            $movie->setDateUpdate(new \DateTime());
            $arrCategories = explode("/", trim($request->request->get('categories')));
            $this->em->getRepository("AppBundle:MovieCategory")
                ->deleteFromMovieCategory($movie->getNum());

            $a = 1;
            foreach($arrCategories as $category){
                $categ = $this->em->getRepository("AppBundle:Category")
                    ->findOneBy(['name' => $category]);
                if($categ==null){
                    $categ = new Category();
                    $categ->setName($category);
                    $this->em->persist($categ);
//                    $this->em->flush();
                }
                $movieCateg = $this->em->getRepository("AppBundle:MovieCategory")
                    ->findOneBy(
                        ['movies' => $movie->getNum(),
                        'categories' => $categ->getId()
                        ]
                    );
                if($movieCateg == null){
                    $movieCateg = new MovieCategory();
                }
                $movieCateg->setCategories($categ);
                $movieCateg->setMovies($movie);
                $movieCateg->setSequence($a);
                $movie->addHasCategory($movieCateg);
                $this->em->persist($movieCateg);
                $a++;
            }

            $movie->setActors(trim($request->request->get('actors')));
            $movie->setDescription(trim($request->request->get('description')));
            $movie->setUrlVideo(trim($request->request->get('video')));

            $movie->setFileSize(trim($request->request->get('filesize')));
            $movie->setVideoFormat(trim($request->request->get('format')));
            $movie->setVisto($view);
            if($view){
                $movie->setMiNota(trim($request->request->get('my-calification')));
            }else{
                $movie->setMiNota("");
            }
            $movie->setMedia(trim($request->request->get('ubication')));
            $this->em->persist($movie);
            $this->em->flush();
            $this->params['result'] = "Cambios guardados correctamente";

            if ($_FILES['upl'] && $_FILES['upl']['name'] != "" && $_FILES['upl']['type'] != ""
                && $_FILES['upl']['size'] > 0) {
                if($this->uploadFileFilm($movie)){
                    $ok = 1;
                }
            }else{
                if($movie->getPicture() == "" || $movie->getPicture() == null) {
                    $ok = 2;
                }
            }
            if($ok != 0){
                switch ($ok){
                    case 1:
                        $this->params['result'] = "Cambios guardados correctamente pero error al añadir la carátula";
                        break;
                    case 2:
                        $this->params['result'] = "No ha seleccionado carátula para la película '" . $movie->getOriginalTitle() . "'";
                        break;
                }
            }
            $this->params['film'] = $movie;
        }
        return $this->render('MoviesBundle:Film:film.html.twig', $this->params);
    }

    /**
     * Muestra la edición de una película existente pasando su id por GET
     *
     * @Route("/edit/{id_film}", name="show_edit_film")
     */
    public function showEditFilmAction($id_film)
    {
        $this->initialize();
        $this->getDifferentValues();
        if ($id_film != null) {
            $this->params['film'] = $this->em->getRepository("AppBundle:Movie")
                ->findOneBy(['num' => $id_film]);
        }

        return $this->render('MoviesBundle:Film:film.html.twig', $this->params);
    }

    /**
     * Muestra la edición de una película existente pasando su id por GET
     *
     * @Route("/delete", name="delete_film")
     */
    public function deleteFilmAction(Request $request)
    {
        $this->initialize();
        if ($request->query->has('id_film')) {
            $movie = $this->em->getRepository("AppBundle:Movie")
                ->findOneBy(['num' => $request->query->get('id_film')]);
            $movie->setTrash(true);
            $this->em->persist($movie);
            $this->em->flush();
            $this->params['result'] = "Película eliminada correctamente";
        }

        return $this->redirectToRoute('films', $this->params);

    }

    /**
     * Función para subir archivos al servidor
     *
     * @param $m
     * @param $ret
     */
    private function uploadFileFilm($m)
    {
        $output_dir = "upload/img/";
        //myfile es el valor fileName que establecimos en el JS
        $error = $_FILES["upl"]["error"];
        $movie = $this->em->getRepository("AppBundle:Movie")
            ->findOneBy(["num" => $m->getNum()]);
        $image = $_FILES["upl"]["name"];
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        if($_FILES['upl']['size']>0.01){
            $fileName = "peliculashd_" . $movie->getNum() . "." . $ext;
            $movie->setPicture($fileName);
        }else{
            $fileName = "img_not_found.png";
            $movie->setPicture($fileName);
//            return true;
        }
        //You need to handle  both cases
        //If Any browser does not support serializing of multiple files using FormData()
        if (!is_array($_FILES["upl"]["name"])) //single file
        {
            //Cuando subimos un solo archivo
            move_uploaded_file($_FILES["upl"]["tmp_name"], $output_dir . $fileName);
            $this->em->persist($movie);
            $this->em->flush();
            return true;
        } else  //Multiple files, file[]
        {
            //Cuando subimos multiples archivos
            $fileCount = count($_FILES["upl"]["name"]);
            for ($i = 0; $i < $fileCount; $i++) {
                $fileName = $_FILES["upl"]["name"][$i];
                move_uploaded_file($_FILES["upl"]["tmp_name"][$i], $output_dir . $fileName);
            }
            $this->em->persist($movie);
            $this->em->flush();
            return true;
        }
    }

    /**
     * Obtengo todos los valores diferentes de los campos buscados para mostrarlos en sugerencias
     */
    private function getDifferentValues()
    {
        $this->params['sagas'] = json_encode(
            $this->em->getRepository("AppBundle:Movie")
                ->getDistincValuesByField("CF_SAGA")
        );
        $this->params['director'] = json_encode(
            $this->em->getRepository("AppBundle:Movie")
                ->getDistincValuesByField("DIRECTOR")
        );
        $this->params['country'] = json_encode(
            $this->em->getRepository("AppBundle:Movie")
                ->getDistincValuesByField("COUNTRY")
        );
        $this->params['categories'] = json_encode(
            $this->em->getRepository('AppBundle:Category')
                ->findAll()
        );
        $this->params['format'] = json_encode(
            $this->em->getRepository("AppBundle:Movie")
                ->getDistincValuesByField("VIDEOFORMAT")
        );
        $this->params['ubication'] = json_encode(
            $this->em->getRepository("AppBundle:Movie")
                ->getDistincValuesByField("MEDIA")
        );
    }

    private function initialize(){
        $this->em = $this->getDoctrine()->getManager();
        $this->params = [];
    }
}
