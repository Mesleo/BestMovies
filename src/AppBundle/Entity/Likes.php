<?php
// src/AppBundle/Entity/Likes.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Table(name="likes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LikesRepository")
 */

class Likes
{


    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="like_not_like", type="boolean", nullable=true)
     */
    private $likeNotLike;

    /**
     * @var boolean
     *
     * @ORM\Column(name="useful", type="boolean", nullable=true)
     */
    private $useful;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * Many likes can have one comment
     * @ORM\ManyToOne(targetEntity="Comment")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=false)
     */
    private $comment;

    /**
     * Many likes can put one user
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->created = new \DateTime();
    }
   

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
     * Set likeNotLike
     *
     * @param boolean $likeNotLike
     *
     * @return Likes
     */
    public function setLikeNotLike($likeNotLike)
    {
        $this->likeNotLike = $likeNotLike;

        return $this;
    }

    /**
     * Get likeNotLike
     *
     * @return boolean
     */
    public function getLikeNotLike()
    {
        return $this->likeNotLike;
    }

    /**
     * Set useful
     *
     * @param boolean $useful
     *
     * @return Likes
     */
    public function setUseful($useful)
    {
        $this->useful = $useful;

        return $this;
    }

    /**
     * Get useful
     *
     * @return boolean
     */
    public function getUseful()
    {
        return $this->useful;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Likes
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Likes
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Likes
     */
    public function setComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \AppBundle\Entity\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Likes
     */
    public function setUser(\AppBundle\Entity\User $user)
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
}
