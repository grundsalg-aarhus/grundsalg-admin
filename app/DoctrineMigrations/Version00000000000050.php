<?php

namespace Application\Migrations;

use AppBundle\Entity\User;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Migration:
 * - Migrate User tabel to FOSUserBundle
 */
class Version00000000000050 extends AbstractMigration implements ContainerAwareInterface
{
  private $container;

  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $users = $this->getLegacyUsers();

    $userManager = $this->container->get('fos_user.user_manager');

    foreach ($users as $user) {
      $newUser = $userManager->createUser();

      $created = \DateTime::createFromFormat('Y-m-d H:i:s', $user['createdDate'].' 00:00:00');
      $modified = \DateTime::createFromFormat('Y-m-d H:i:s', $user['modifiedDate'].' 00:00:00');

      $newUser->setUsername($user['userName']);
      $newUser->setEmail('notset_' . $user['userName'] . '@aarhus.dk');
      $newUser->setName($user['name']);
      $newUser->setCreatedAt($created);
      $newUser->setUpdatedAt($modified);
      $newUser->setCreatedBy($user['createdBy']);
      $newUser->setUpdatedBy($user['modifiedBy']);
      $newUser->setPlainPassword($user['password']);
      $newUser->setEnabled(true);

      $roles = explode('#', $user['roles']);

      foreach ($roles as $role) {
        $newUser->addRole("ROLE_" . $role);
      }

      $userManager->updateUser($newUser, true);
    }

    $this->addSql('DROP TABLE Users');

  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema)
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('TRUNCATE TABLE fos_user');

    $this->addSql('
      CREATE TABLE Users (
        id bigint(20) NOT NULL,
        name longtext NOT NULL,
        userName longtext NOT NULL,
        password longtext NOT NULL,
        roles longtext NOT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        PRIMARY KEY (id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');
  }

  /**
   * Implements container aware interface.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface|NULL $container
   *    The container that is injected.
   */
  public function setContainer(ContainerInterface $container = NULL)
  {
    $this->container = $container;
  }

  private function getLegacyUsers()
  {
    $sql = "SELECT * FROM Users";

    $em = $this->container->get('doctrine')->getManager();
    $stmt = $em->getConnection()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

}
