<?php
// src/AppBundle/Entity/Movie.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="movies")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieRepository")
 */
class Movie
{

    /**
     * @ORM\Column(name="NUM", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $num;

    /**
     * @var string
     *
     * @ORM\Column(name="MEDIA", type="string", length=100, nullable=false)
     */
    private $media;

    /**
     * @var string
     *
     * @ORM\Column(name="ORIGINALTITLE", type="string", length=100, nullable=false)
     */
    private $originalTitle;

    /**
     * @var datetime
     *
     * @ORM\Column(name="DATEADD", type="datetime", length=100, nullable=false)
     */
    private $dateAdd;

    /**
     * @var datetime
     *
     * @ORM\Column(name="DATEUPDATE", type="datetime", length=100, nullable=false)
     */
    private $dateUpdate;

    /**
     * @var string
     *
     * @ORM\Column(name="RATING", type="string", length=100, nullable=false)
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="DIRECTOR", type="string", length=255, nullable=false)
     */
    private $director;

    /**
     * @var string
     *
     * @ORM\Column(name="COUNTRY", type="string", length=255, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="YEAR", type="string", length=100, nullable=false)
     */
    private $year;

    /**
     * @var integer
     *
     * @ORM\Column(name="LENGTH", type="integer", length=100, nullable=false)
     */
    private $length;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTORS", type="string", length=1000, nullable=false)
     */
    private $actors;

    /**
     * @var string
     *
     * @ORM\Column(name="CF_SAGA", type="string", length=100, nullable=false)
     */
    private $saga;

    /**
     * @var string
     *
     * @ORM\Column(name="PICTURENAME", type="string", length=255, nullable=false)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="URL", type="string", length=255, nullable=true)
     */
    private $urlVideo;

    /**
     * @var float
     *
     * @ORM\Column(name="FILESIZE", type="float", length=100, nullable=true)
     */
    private $fileSize;

    /**
     * @var string
     *
     * @ORM\Column(name="VIDEOFORMAT", type="string", length=100, nullable=true)
     */
    private $videoFormat;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION", type="string", length=1500, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="CATEGORY", type="string", length=1500, nullable=true)
     */
    private $category;

    /**
     * @var boolean
     *
     * @ORM\Column(name="CF_VISTO", type="boolean", nullable=true)
     */
    private $visto;

    /**
     * @var string
     *
     * @ORM\Column(name="CF_NOTA", type="string", length=100, nullable=true)
     */
    private $miNota;

    /**
     * @var string
     *
     * @ORM\Column(name="URL_TORRENT", type="string", length=1000, nullable=true)
     */
    private $urlTorrent;

    /**
     * Una película puede tener muchos comentarios.
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="movie")
     */
    private $comment;

    /**
     * @var integer
     *
     * @ORM\Column(name="CLICKS", type="integer", nullable=true)
     */
    private $click;

//    /**
//     * @ORM\OneToMany(targetEntity="MovieCategory", mappedBy="movies")
//     */
//    private $hasCategories;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TRASH", type="boolean", length=100, nullable=true)
     */
    private $trash;


    /**
     * Get num
     *
     * @return integer
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set media
     *
     * @param string $media
     *
     * @return Movie
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return string
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set dateAdd
     *
     * @param string $dateAdd
     *
     * @return Movie
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return string
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set director
     *
     * @param string $director
     *
     * @return Movie
     */
    public function setDirector($director)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return string
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Movie
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Movie
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set year
     *
     * @param string $year
     *
     * @return Movie
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set length
     *
     * @param string $length
     *
     * @return Movie
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return string
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set actors
     *
     * @param string $actors
     *
     * @return Movie
     */
    public function setActors($actors)
    {
        $this->actors = $actors;

        return $this;
    }

    /**
     * Get actors
     *
     * @return string
     */
    public function getActors()
    {
        return $this->actors;
    }

    /**
     * Set saga
     *
     * @param string $saga
     *
     * @return Movie
     */
    public function setSaga($saga)
    {
        $this->saga = $saga;

        return $this;
    }

    /**
     * Get saga
     *
     * @return string
     */
    public function getSaga()
    {
        return $this->saga;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Movie
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set originalTitle
     *
     * @param string $originalTitle
     *
     * @return Movie
     */
    public function setOriginalTitle($originalTitle)
    {
        $this->originalTitle = $originalTitle;

        return $this;
    }

    /**
     * Get originalTitle
     *
     * @return string
     */
    public function getOriginalTitle()
    {
        return $this->originalTitle;
    }

    /**
     * Set rating
     *
     * @param string $rating
     *
     * @return Movie
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set urlVideo
     *
     * @param string $urlVideo
     *
     * @return Movie
     */
    public function setUrlVideo($urlVideo)
    {
        $this->urlVideo = $urlVideo;

        return $this;
    }

    /**
     * Get urlVideo
     *
     * @return string
     */
    public function getUrlVideo()
    {
        return $this->urlVideo;
    }

    /**
     * Set fileSize
     *
     * @param float $fileSize
     *
     * @return Movie
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize
     *
     * @return float
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set videoFormat
     *
     * @param string $videoFormat
     *
     * @return Movie
     */
    public function setVideoFormat($videoFormat)
    {
        $this->videoFormat = $videoFormat;

        return $this;
    }

    /**
     * Get videoFormat
     *
     * @return string
     */
    public function getVideoFormat()
    {
        return $this->videoFormat;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Movie
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set visto
     *
     * @param boolean $visto
     *
     * @return Movie
     */
    public function setVisto($visto)
    {
        $this->visto = $visto;

        return $this;
    }

    /**
     * Get visto
     *
     * @return boolean
     */
    public function getVisto()
    {
        return $this->visto;
    }

    /**
     * Set miNota
     *
     * @param string $miNota
     *
     * @return Movie
     */
    public function setMiNota($miNota)
    {
        $this->miNota = $miNota;

        return $this;
    }

    /**
     * Get miNota
     *
     * @return string
     */
    public function getMiNota()
    {
        return $this->miNota;
    }

    /**
     * Set dateUpdate
     *
     * @param string $dateUpdate
     *
     * @return Movie
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return string
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set trash
     *
     * @param boolean $trash
     *
     * @return Movie
     */
    public function setTrash($trash)
    {
        $this->trash = $trash;

        return $this;
    }

    /**
     * Get trash
     *
     * @return boolean
     */
    public function getTrash()
    {
        return $this->trash;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comment = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Movie
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comment[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comment->removeElement($comment);
    }

    /**
     * Get comment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set click
     *
     * @param integer $click
     *
     * @return Movie
     */
    public function setClick($click)
    {
        $this->click = $click;

        return $this;
    }

    /**
     * Get click
     *
     * @return integer
     */
    public function getClick()
    {
        return $this->click;
    }

//    /**
//     * Add category
//     *
//     * @param \AppBundle\Entity\Category $category
//     *
//     * @return Movie
//     */
//    public function addCategory(\AppBundle\Entity\Category $category, $order)
//    {
//        $this->categories[] = [$category, $order];
//
//        return $this;
//    }
//
//    /**
//     * Remove category
//     *
//     * @param \AppBundle\Entity\Category $category
//     */
//    public function removeCategory(\AppBundle\Entity\Category $category)
//    {
//        $this->categories->removeElement($category);
//    }
//
//    /**
//     * Get categories
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getCategories()
//    {
//        return $this->categories;
//    }
//
//    /**
//     * Set categories
//     *
//     * @param integer $categories
//     *
//     * @return Movie
//     */
//    public function setCategories($categories)
//    {
//        $this->categories = $categories;
//
//        return $this;
//    }
//
//    /**
//     * Add hasCategory
//     *
//     * @param \AppBundle\Entity\MovieCategory $hasCategory
//     *
//     * @return Movie
//     */
//    public function addHasCategory(\AppBundle\Entity\MovieCategory $hasCategory)
//    {
//        $this->hasCategories[] = $hasCategory;
//
//        return $this;
//    }
//
//    /**
//     * Remove hasCategory
//     *
//     * @param \AppBundle\Entity\MovieCategory $hasCategory
//     */
//    public function removeHasCategory(\AppBundle\Entity\MovieCategory $hasCategory)
//    {
//        $this->hasCategories->removeElement($hasCategory);
//    }
//
//    /**
//     * Get hasCategories
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getHasCategories()
//    {
//        return $this->hasCategories;
//    }

    /**
     * Set urlTorrent
     *
     * @param string $urlTorrent
     *
     * @return Movie
     */
    public function setUrlTorrent($urlTorrent)
    {
        $this->urlTorrent = $urlTorrent;

        return $this;
    }

    /**
     * Get urlTorrent
     *
     * @return string
     */
    public function getUrlTorrent()
    {
        return $this->urlTorrent;
    }
}
