<?php

namespace AppBundle\Repository;

/**
 * MovieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MovieRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Devuelve una consulta a partir de todos los filtros obtenidos
     *
     * @param string $fieldFilter
     *          Campo por el que se filtra
     * @param string $nameFilter
     *          Valor a buscar en el campo
     * @param string $starYear
     *          Fecha de inicio a buscar
     * @param string $finishYear
     *          Fecha de fin a buscar
     * @param int $starTime
     *          Duración mínima a buscar
     * @param int $finishTime
     *          Duración máxima a buscar
     * @param null $category
     *          Categoría por la que se busca
     * @param $starIndex
     *          Índice por el que se muestra la consulta
     * @param $numRows
     *          Número de tuplas a mostrar
     * @param null $order
     *          Orden en el que se muestra la consulta
     * @param null $directionOrder
     *          Orden ascendente o descendente a mostrar
     * @return array
     *          Consulta
     */
    public function getFilmByField($fieldFilter = "", $nameFilter = "", $starYear = "1950",
                                   $finishYear = "2050", $starTime = 60, $finishTime = 300, $category = null,
                                    $starIndex, $numRows, $order = null, $directionOrder = null, $view = "TODO"){

        $conn = $this->getEntityManager()->getConnection();

        if($starYear == "") $starYear = "1950";
        if($finishYear == "") $finishYear = "2050";
        if($starTime == "") $starTime = 60;
        if($finishTime == "") $finishTime = 300;
        $direc = "DESC";
        $field = "";

        if($directionOrder == 1) $direc = "ASC";

        switch ($fieldFilter){
            case 1:
                $field = 'ORIGINALTITLE';
                break;
            case 2:
                $field = 'ACTORS';
                break;
            case 3:
                $field = 'DIRECTOR';
                break;
            case 4:
                $field = 'COUNTRY';
                break;
            case 5:
                $field = 'CF_SAGA';
                break;
            case 6:
                $field = 'TODO';
                break;
        }

        switch ($order){
            case 1:
                $order = 'YEAR';
                break;
            case 2:
                $order = 'RATING';
                break;
            case 3:
                $order = 'ORIGINALTITLE';
                if($directionOrder == 1) $direc = "DESC";
                else $direc = "ASC";
                break;
            case 4:
                $order = 'LENGTH';
                break;
            case 5:
                $order = 'COUNTRY';
                if($directionOrder == 1) $direc = "DESC";
                else $direc = "ASC";
                break;
            case 6:
                $order = 'CF_SAGA';
                if($directionOrder == 1) $direc = "DESC";
                else $direc = "ASC";
                break;
            case 7:
                $order = 'NUM';
                break;
            case 8:
                $order = 'DIRECTOR';
                if($directionOrder == 1) $direc = "DESC";
                else $direc = "ASC";
                break;
            case 9:
                $order = 'FILESIZE';
                break;
            case 10:
                $order = 'CF_NOTA';
                break;
        }

        $query = "SELECT
                      NUM AS num, 
                      MEDIA AS media, 
                      ORIGINALTITLE AS originalTitle, 
                      DATEADD AS dateAdd, 
                      RATING AS rating,
                      DIRECTOR AS director, 
                      COUNTRY AS country, 
                      CATEGORY AS category, 
                      YEAR AS year, 
                      LENGTH AS length, 
                      ACTORS AS actors, 
                      CF_SAGA AS saga, 
                      PICTURENAME AS picture,
                      URL AS urlVideo,
                      FILESIZE AS fileSize,
                      VIDEOFORMAT AS videoFormat,
                      DESCRIPTION AS description,
                      CF_VISTO AS visto,
                      CF_NOTA AS miNota
                  FROM 
                      movies AS m";

        $queryEvery = "
                    WHERE
                        (m.ORIGINALTITLE LIKE :nameFilter OR 
                        m.ACTORS LIKE :nameFilter OR 
                        m.DIRECTOR LIKE :nameFilter OR 
                        m.COUNTRY LIKE :nameFilter";

        if($order != 'CF_SAGA'){
            $queryEvery .= " OR 
                        m.CF_SAGA LIKE :nameFilter) ";
        }else{
            $queryEvery .= " ) AND 
                        m.CF_SAGA <> ''";

        }

        if($fieldFilter != ""){
            if($field != "TODO") {
                $query .= "
                    WHERE
                        m.$field LIKE :nameFilter";
            }else{
                $query .= $queryEvery;
            }
        }else{
            $query .= "
                    WHERE
                        m.ORIGINALTITLE LIKE :nameFilter";
        }

        $query .= " AND
                        m.YEAR BETWEEN :starYear AND :finishYear AND
                        m.LENGTH BETWEEN :starTime AND :finishTime";

        if($category != null){
            for($i = 0; $i < count($category); $i++){
                $query .= " AND
                        m.CATEGORY LIKE :category$i";
            }
        }

        if($view != "TODO" || $view == false){
            $query .= " AND
                        m.CF_VISTO = :view";
        }

        $query .= " AND m.trash = 0";

        if($order != null){
            if($order == 'CF_NOTA'){
                $query .= " AND $order <> '' ";
            }
            $query .= " ORDER BY
                            $order $direc";
        }else {
            $query .= "
                ORDER BY 
                    m.YEAR DESC, m.CLICKS DESC";
        }

        if($numRows != null){
            $query .= "
                    LIMIT :starIndex, :numRows";
        }

        $stmt = $conn->prepare($query);
        $name = '%'.$nameFilter.'%';
        $stmt->bindValue(':nameFilter', $name);
        $stmt->bindValue(':starYear', $starYear);
        $stmt->bindValue(':finishYear', $finishYear);
        $stmt->bindValue(':starTime', $starTime, \PDO::PARAM_INT);
        $stmt->bindValue(':finishTime', $finishTime, \PDO::PARAM_INT);
        if($category != null) {
            for ($i = 0; $i < count($category); $i++) {
                $c = ':category'.$i;
                $r = '%'.$category[$i].'%';
                $stmt->bindValue($c, $r);
            }
        }

        if($numRows != null){
            $stmt->bindValue(':starIndex', $starIndex, \PDO::PARAM_INT);
            $stmt->bindValue(':numRows', $numRows, \PDO::PARAM_INT);
        }

        if($view != "TODO" || $view == false){
            $stmt->bindValue(':view', $view, \PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Devuelve los valores mínimo y máximo de los campos año y duración
     * @return mixed
     */
    public function getValuesMinMaxLengthYear(){
        $conn = $this->getEntityManager()->getConnection();

        $query = 'SELECT 
                      min(length) AS min_length, 
                      max(length) AS max_length, 
                      min(year) AS min_year, 
                      max(year) AS max_year 
                  FROM 
                      movies';

        $stmt = $conn->prepare($query);

        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Devuelve todos los valores distinos de un campo de la tabla
     * @return mixed
     */
    public function getFilms(){
        $conn = $this->getEntityManager()->getConnection();

        $query = "SELECT 
                      originaltitle AS label,
                      picturename AS icon,
                      num AS id
                  FROM 
                      movies AS m
                  ORDER BY
                      clicks DESC,
                      rating DESC,
                      originaltitle ASC";

        $stmt = $conn->prepare($query);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Devuelve todos los campos de una película
     * @return mixed
     */
    public function getFilm($title = null, $idFilm = null){
        $conn = $this->getEntityManager()->getConnection();

        $query = "SELECT
                      NUM AS num, 
                      MEDIA AS media, 
                      ORIGINALTITLE AS originalTitle, 
                      DATEADD AS dateAdd, 
                      RATING AS rating,
                      DIRECTOR AS director, 
                      COUNTRY AS country, 
                      CATEGORY AS category, 
                      YEAR AS year, 
                      LENGTH AS length, 
                      ACTORS AS actors, 
                      CF_SAGA AS saga, 
                      PICTURENAME AS picture,
                      URL AS urlVideo,
                      FILESIZE AS fileSize,
                      VIDEOFORMAT AS videoFormat,
                      DESCRIPTION AS description,
                      CF_VISTO AS visto,
                      CF_NOTA AS miNota
                  FROM 
                      movies AS m
                  WHERE ";
        if($title != null) {
            $query .= " ORIGINALTITLE = :title ";
        }
        else if($idFilm != null){
            $query .= " NUM = :num ";
        }

        $stmt = $conn->prepare($query);
        if($title != null) {
            $stmt->bindValue(':title', trim($title));
        }
        else if($idFilm != null){
            $stmt->bindValue(':num', trim($idFilm));
        }

        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Devuelve todos los valores distinos de un campo de la tabla
     * @return mixed
     */
    public function getDistincValuesByField($field){
        $conn = $this->getEntityManager()->getConnection();

        $query = "SELECT 
                      $field AS label
                  FROM 
                      movies
                  WHERE
                      $field <> ''
                  GROUP BY
                      $field";

        $stmt = $conn->prepare($query);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Devuelve el campo vídeo de la película pasada por id
     * @param $idFilm
     * @return mixed
     */
    public function getVideoFilm($idFilm){

        $conn = $this->getEntityManager()->getConnection();

        $query = 'SELECT 
                      m.URL AS url
                  FROM 
                      movies AS m 
                  WHERE 
                      m.NUM = :id_film';

        $stmt = $conn->prepare($query);

        $stmt->bindValue(':id_film', $idFilm, \PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch();
    }

}
