<?php
// src/AppBundle/Entity/CommentDelete.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="comment_delete")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class CommentDelete
{

    const STATUS1 = '1';
    const STATUS2 = '2';
    const STATUS3 = '3';
    const STATUS4 = '4';
    const STATUS5 = '5';
    const STATUS6 = '6';
    const STATUS7 = '7';
    const STATUS8 = '8';
    const STATUS9 = '9';
    const STATUS10 = '10';

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=10000, nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="string")
     */
    private $score;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateadd", type="datetime", nullable=false)
     */
    private $dateAdd;

    /**
     * Muchos comentarios puede poner un usuario
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Muchos comentarios tiene una película
     * @ORM\ManyToOne(targetEntity="Movie")
     * @ORM\JoinColumn(name="movie_num", referencedColumnName="NUM")
     */
    private $movie;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date_delete", type="datetime", nullable=false)
     */
    private $dateTrash;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Comment
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set score
     *
     * @param string $score
     *
     * @return Comment
     */
    public function setScore($score)
    {
        if (!in_array($score, array(self::STATUS1, self::STATUS2,self::STATUS3, self::STATUS4,self::STATUS5, self::STATUS6,
            self::STATUS7, self::STATUS8,self::STATUS9, self::STATUS10))) {
            throw new \InvalidArgumentException("Puntuación inválida");
        }
        $this->score = $score;
        return $this;
    }

    /**
     * Get score
     *
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return Comment
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set trash
     *
     * @param boolean $trash
     *
     * @return Comment
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Comment
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return Comment
     */
    public function setMovie(\AppBundle\Entity\Movie $movie = null)
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get movie
     *
     * @return \AppBundle\Entity\Movie
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set dateTrash
     *
     * @param \DateTime $dateTrash
     *
     * @return CommentDelete
     */
    public function setDateTrash($dateTrash)
    {
        $this->dateTrash = $dateTrash;

        return $this;
    }

    /**
     * Get dateTrash
     *
     * @return \DateTime
     */
    public function getDateTrash()
    {
        return $this->dateTrash;
    }
}
