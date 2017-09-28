<?php

namespace Itk\DoctrineIntegrityBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var
     *
     * @OneToMany(targetEntity="BlogPost", mappedBy="author")
     */
    private $blogPosts;

    /**
     * Bidirectional - Many users have Many favorite comments (OWNING SIDE)
     *
     * @ManyToMany(targetEntity="Comment", inversedBy="userFavorites")
     * @JoinTable(name="user_favorite_comments")
     */
    private $favorites;

    /**
     * Unidirectional - Many users have marked many comments as read
     *
     * @ManyToMany(targetEntity="Comment")
     * @JoinTable(name="user_read_comments")
     */
    private $commentsRead;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @OneToMany(targetEntity="Comment", mappedBy="author")
     */
    private $commentsAuthored;

    /**
     * Unidirectional - Many-To-One
     *
     * @ManyToOne(targetEntity="Comment")
     */
    private $firstComment;

    /**
     * @return mixed
     */
    public function __construct()
    {
        $this->blogPosts = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->commentsRead = new ArrayCollection();
        $this->commentsAuthored = new ArrayCollection();
    }

    /**
     * @param BlogPost $blogPost
     */
    public function addBlogPost(BlogPost $blogPost)
    {
        $this->blogPosts->add($blogPost);
        $blogPost->setAuthor($this);
    }

    /**
     * @param Comment $comment
     */
    public function addFavorite(Comment $comment) {
        $this->favorites->add($comment);
    }

    /**
     * @param Comment $comment
     */
    public function addCommentsRead(Comment $comment) {
        $this->commentsRead->add($comment);
    }

    /**
     * @param Comment $comment
     */
    public function addCommentsAuthored(Comment $comment) {
        $this->commentsAuthored->add($comment);
    }

    /**
     * @param mixed $firstComment
     */
    public function setFirstComment(Comment $firstComment)
    {
        $this->firstComment = $firstComment;
    }

}
