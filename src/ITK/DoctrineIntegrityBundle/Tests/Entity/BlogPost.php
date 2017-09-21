<?php

namespace ITK\DoctrineIntegrityBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * BlogPost.
 *
 * @ORM\Table(name="blogpost")
 * @ORM\Entity
 */
class BlogPost
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var \Author
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="blogPosts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     * })
     */
    private $author;

    /**
     * @var
     *
     * @OneToMany(targetEntity="Comment", mappedBy="blogPost", cascade={"persist", "remove"})
     */
    private $comments;

    /**
     * @return mixed
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $this->comments->add($comment);
        $comment->setBlogPost($this);
    }
}
