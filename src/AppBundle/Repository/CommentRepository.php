<?php

namespace AppBundle\Repository;

/**
 * MovieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Obtiene todos los comentarios del usuario logeado
     * @param $user
     * @param null $limit
     * @return array
     */
    public function getCommentsOrderBy($searchName = null, $search = null, $order = null,
                                           $user, $limit = null){

        $conn = $this->getEntityManager()->getConnection();

        $orden = "";

        switch ($order){
            case "1":
                $orden = " ORDER BY c.dateAdd DESC";
                break;
            case "2":
                $orden = " ORDER BY c.dateAdd ASC";
                break;
            case "3":
                $orden = " ORDER BY score DESC";
                break;
            case "4":
                $orden = " ORDER BY score ASC";
                break;
            case "6":
                $orden = " ORDER BY title DESC";
                break;
            case "5":
                $orden = " ORDER BY title ASC";
                break;
        }

        if($searchName != null) {
            switch ($searchName) {
                case 1:
                    $sN = "m.originaltitle";
                    break;
                case 2:
                    $sN = "c.title";
                    break;
                case 3:
                    $sN = "c.comment";
                    break;
            }
        }

        $query = "SELECT
                      c.id,
                      c.user_id AS user,
                      c.movie_num AS movie,
                      c.title,
                      c.comment,
                      DATE_FORMAT(c.dateAdd, '%d-%m-%Y %H:%i') AS fechaAdd,
                      c.score,
                      m.originaltitle,
                      m.picturename AS picture,
                      m.num AS film
                  FROM 
                      comment AS c
                  LEFT JOIN movies AS m
                      ON c.movie_num = m.num
                  WHERE
                      c.user_id = :user";

        if($searchName != null && $search != null){
            $query .= " AND $sN LIKE '%$search%'";
        }

        if($order == null) {
            $query .= " ORDER BY fechaAdd DESC";
        }else{
            $query .= $orden;
        }

        if($limit != null) {
            $query .= " LIMIT 0, $limit";
        }

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':user', trim($user));

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtiene el numero total de comentarios de una película
     * @param $idFilm
     * @param null $limit
     * @return array
     */
    public function getCommentsCount($user){
        $conn = $this->getEntityManager()->getConnection();

        $query = "SELECT
                      COUNT(*)
                  FROM 
                      comment AS c
                  WHERE
                      c.user_id = :user ";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':user', trim($user));

        $stmt->execute();
        return $stmt->fetch();

    }

    /**
     * Obtiene todos los comentarios de una película
     * @param $idFilm
     * @param null $limit
     * @return array
     */
    public function getCommentsByFilm($idFilm, $limit = 3, $startIndex = 0, $bool){
        $conn = $this->getEntityManager()->getConnection();

        $query = "SELECT
                      c.id,
                      u.username AS user,
                      u.id AS user_id,
                      c.title,
                      c.comment,
                      DATE_FORMAT(c.dateAdd, '%d-%m-%Y') AS fechaAdd,
                      c.score,
                      m.originaltitle,
                      m.num AS film,
                      (SELECT 
                            count(*)
                            FROM likes 
                            WHERE 
                            comment_id = c.id 
                            AND like_not_like = 1) 
                            AS likes,
                      (SELECT 
                            count(*)
                            FROM likes 
                            WHERE 
                            comment_id = c.id 
                            AND like_not_like = 0) 
                            AS no_likes
                  FROM 
                      comment AS c
                  LEFT JOIN movies AS m
                      ON c.movie_num = m.num
                  LEFT JOIN user AS u
                      ON c.user_id = u.id
                  WHERE
                      c.movie_num = :film ";

        if($bool){
            $query .= " AND 
                      c.comment != '' ";
        }

        $query .= " ORDER BY 
                      c.dateadd DESC";

        if($limit != null) {
            $query .= " 
                        LIMIT $startIndex, $limit";
        }

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':film', trim($idFilm));

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Encuentra resultados para el select2 de la búsqueda por AJAX en "Mis comentarios"
     * @param $user
     * @param $searchName
     * @param $pattern
     * @return array
     */
    public function getResultsSearchAJAX($user, $searchName, $pattern){
        $conn = $this->getEntityManager()->getConnection();

        $s = "m.ORIGINALTITLE";
        switch ($searchName){
            case 1:
                $s = "m.ORIGINALTITLE";
                $p = "m.originaltitle AS label";
                break;
            case 2:
                $s = "c.title";
                $p = "c.title AS label";
                break;
        }
        $query = "SELECT
                      c.id AS id,
                      $p
                  FROM 
                      comment AS c
                  LEFT JOIN movies AS m
                      ON c.movie_num = m.num
                  WHERE
                      c.user_id = :user
                      AND $s LIKE '%$pattern%' ";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':user', trim($user));

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Encuentra los resultados del select2
     * @param $user
     * @param $searchName
     * @param $pattern
     * @return array
     */
    public function getResultsSearch($user, $searchName, $pattern){
        $conn = $this->getEntityManager()->getConnection();
        $query = "SELECT
                      c.id,
                      u.username AS user,
                      c.title,
                      c.comment,
                      DATE_FORMAT(c.dateAdd, '%d-%m-%Y') AS fechaAdd,
                      c.score,
                      m.originaltitle,
                      m.num AS film
                  FROM 
                      comment AS c
                  LEFT JOIN movies AS m
                      ON c.movie_num = m.num
                  WHERE
                      c.user_id = :user
                      AND $searchName LIKE '%$pattern%' ";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':user', trim($user));

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Saca la media de los comentarios
     * @param $film
     * @return array
     */
    public function getMediaCommentsFilm($film){
        $conn = $this->getEntityManager()->getConnection();
        $query = "SELECT
                      AVG(score) AS media
                  FROM 
                      comment AS c
                  LEFT JOIN movies AS m
                      ON c.movie_num = m.num
                  WHERE
                      c.movie_num = :film";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':film', trim($film));

        $stmt->execute();
        return $stmt->fetch();
    }

}
