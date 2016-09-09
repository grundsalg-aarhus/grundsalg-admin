<?php

namespace Application\Migrations;

use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160907220755 extends AbstractMigration implements ContainerAwareInterface
{
  private $container;

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

  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $users = $this->container->get('doctrine')
      ->getRepository('AppBundle:Users')
      ->findAll();

    $userManager = $this->container->get('fos_user.user_manager');

    foreach ($users as $user) {
      $newUser = $userManager->createUser();

      $newUser->setUsername($user->getUsername());
      $newUser->setName($user->getName());
      $newUser->setCreatedAt($user->getCreateddate());
      $newUser->setUpdatedAt($user->getModifieddate());
      $newUser->setCreatedBy($user->getCreatedby());
      $newUser->setUpdatedBy($user->getModifiedby());
      $newUser->setEmail('notset_' . $user->getUsername() . '@aarhus.dk');
      $newUser->setPlainPassword($user->getPassword());
      $newUser->setEnabled(true);

      $userManager->updateUser($newUser, true);
    }
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema)
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
  }
}
