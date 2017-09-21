<?php
/**
 * Created by PhpStorm.
 * User: turegjorup
 * Date: 06/09/2017
 * Time: 22.05.
 */

namespace ITK\DoctrineIntegrityBundle\Tests;

use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use ITK\DoctrineIntegrityBundle\Service\IntegrityManager;
use ITK\DoctrineIntegrityBundle\Tests\Entity\Author;
use ITK\DoctrineIntegrityBundle\Tests\Entity\BlogPost;
use ITK\DoctrineIntegrityBundle\Tests\Entity\Comment;
use ITK\DoctrineIntegrityBundle\Tests\Entity\User;

/**
 * @coversNothing
 */
class IntegrityManagerTest extends \PHPUnit\Framework\TestCase
{
    private $em;
    private $integrityManager;

    public function setUp()
    {
        $paths = [realpath(__DIR__.'/Entity')];
        $config = Setup::createAnnotationMetadataConfiguration($paths, true, null, null, false);
        $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache());

        $this->em = \Doctrine\ORM\EntityManager::create(
            [
                'driver' => 'pdo_sqlite',
                'memory' => true,
            ],
            $config
        );

        // In SQLite, foreign key constraints are disabled by default.
        // http://www.sqlite.org/foreignkeys.html#fk_enable
        $sql = 'PRAGMA foreign_keys = ON';
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();

        $this->integrityManager = new IntegrityManager($this->em);

        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema(
            [
                $this->em->getClassMetadata('ITK\DoctrineIntegrityBundle\Tests\Entity\BlogPost'),
                $this->em->getClassMetadata('ITK\DoctrineIntegrityBundle\Tests\Entity\Comment'),
                $this->em->getClassMetadata('ITK\DoctrineIntegrityBundle\Tests\Entity\User'),
            ]
        );
    }

    public function testOneToMany()
    {
        $author1 = new User();
        $author2 = new User();
        $blogpost1 = new BlogPost();
        $blogpost2 = new BlogPost();

        $author1->addBlogPost($blogpost1);
        $author1->addBlogPost($blogpost2);

        $this->em->persist($author1);
        $this->em->persist($author2);
        $this->em->persist($blogpost1);
        $this->em->persist($blogpost2);
        $this->em->flush();

        $canDelete = $this->integrityManager->canDelete($author1);
        $expected = [
            'total' => 2,
            'references' => [
                'ITK\DoctrineIntegrityBundle\Tests\Entity\BlogPost' => [
                    'author' => 2,
                ],
            ],
        ];
        $this->assertSame($expected, $canDelete, '@OneToMany: Author with two blogPosts should not be deleteable');

        $canDelete = $this->integrityManager->canDelete($author2);
        $expected = true;
        $this->assertSame($expected, $canDelete, '@OneToMany: Author without any blogPosts should be deleteable');

        $this->em->remove($author2);
        $this->em->flush();

        $this->expectException(DriverException::class);
        $this->em->remove($author1);
        $this->em->flush();
    }

    public function testOneToManyWithCascadeRemove()
    {
        $blogPost = new BlogPost();
        $comment1 = new Comment();
        $comment2 = new Comment();

        $blogPost->addComment($comment1);
        $blogPost->addComment($comment2);

        $this->em->persist($blogPost);
        $this->em->flush();

        $canDelete = $this->integrityManager->canDelete($blogPost);
        $expected = true;
        $this->assertSame($expected, $canDelete, '@OneToMany: BlogPost with comments should be deleteable because of cascade={remove} on comments');

        $this->em->remove($blogPost);
        $this->em->flush();
    }

    public function testManyToOne()
    {
        $author1 = new User();
        $blogPost1 = new BlogPost();
        $blogPost2 = new BlogPost();

        $author1->addBlogPost($blogPost1);
        $author1->addBlogPost($blogPost2);

        $this->em->persist($author1);
        $this->em->persist($blogPost1);
        $this->em->persist($blogPost2);
        $this->em->flush();

        $canDelete = $this->integrityManager->canDelete($blogPost1);
        $expected = true;

        $this->assertSame($expected, $canDelete, '@ManyToOne: BlogPost should always be deleteable');

        $this->em->remove($blogPost1);
        $this->em->flush();
    }

    public function testManyToOneWithCascadeRemove()
    {
        $blogPost = new BlogPost();
        $comment1 = new Comment();
        $comment2 = new Comment();

        $blogPost->addComment($comment1);
        $blogPost->addComment($comment2);

        $this->em->persist($blogPost);
        $this->em->flush();

        $canDelete = $this->integrityManager->canDelete($comment1);
        $expected = true;
        $this->assertSame($expected, $canDelete, '@ManyToOne: Comment should always be deleteable');

        $this->em->remove($comment1);
        $this->em->flush();
    }

    public function testManyToManyBidirectional()
    {
        $user = new User();
        $comment1 = new Comment();
        $comment2 = new Comment();

        $user->addFavorite($comment1);
        $user->addFavorite($comment2);

        $this->em->persist($user);
        $this->em->persist($comment1);
        $this->em->persist($comment2);
        $this->em->flush();

        $canDelete = $this->integrityManager->canDelete($user);
        $expected = true;
        $this->assertSame($expected, $canDelete, '@ManyToMany/Bidirectional/OwningSide: User should always be deleteable');

        $canDelete = $this->integrityManager->canDelete($comment1);
        $expected = true;
        $this->assertSame($expected, $canDelete, '@ManyToMany/Bidirectional/InverseSide: Comment should always be deleteable');

        $this->em->remove($user);
        $this->em->flush();

        $this->em->remove($comment1);
        $this->em->flush();
    }

    public function testManyToManyUnidirectional()
    {
        $user = new User();
        $comment1 = new Comment();
        $comment2 = new Comment();

        $user->addCommentsRead($comment1);
        $user->addCommentsRead($comment2);

        $this->em->persist($user);
        $this->em->persist($comment1);
        $this->em->persist($comment2);
        $this->em->flush();

        $canDelete = $this->integrityManager->canDelete($user);
        $expected = true;
        $this->assertSame($expected, $canDelete, '@ManyToMany/Unidirectional/OwningSide: User should always be deleteable');

        $canDelete = $this->integrityManager->canDelete($comment1);
        $expected = true;
        $this->assertSame($expected, $canDelete, '@ManyToMany/Unidirectional/InverseSide: Comment should always be deleteable');

        $this->em->remove($user);
        $this->em->flush();

        $this->em->remove($comment1);
        $this->em->flush();
    }
}
