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
     * @ORM\Column(name="like_not_like", type="boolean")
     */
    private $like_not_like;

    /**
     * @var boolean
     *
     * @ORM\Column(name="useful", type="boolean")
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
     * @ORM\Column(name="updated", type="datetime", nullable=false)
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
     * Set like
     *
     * @param boolean $like
     *
     * @return Like
     */
    public function setLike($like)
    {
        $this->like = $like;

        return $this;
    }

    /**
     * Get like
     *
     * @return boolean
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * Set useful
     *
     * @param boolean $useful
     *
     * @return Like
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
     * @return Like
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
     * @return Like
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
     * @return Like
     */
    public function setComment(\AppBundle\Entity\Comment $comment = null)
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
     * @return Like
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
     * Set likeNotLike
     *
     * @param boolean $likeNotLike
     *
     * @return Likes
     */
    public function setLikeNotLike($likeNotLike)
    {
        $this->like_not_like = $likeNotLike;

        return $this;
    }

    /**
     * Get likeNotLike
     *
     * @return boolean
     */
    public function getLikeNotLike()
    {
        return $this->like_not_like;
    }
}
