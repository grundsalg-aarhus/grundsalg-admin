<?php

namespace AppBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormView;

class AppExtension extends \Twig_Extension {
  protected $container;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('form_field', [$this, 'getFormField']),
      new \Twig_SimpleFunction('build_form', [$this, 'buildForm']),
    ];
  }

  public function getFormField($class, $property, $data = null, array $options = []) {
    $factory = $this->getFormFactory();
    $form = $factory->createForProperty($class, $property, $data, $options);

    return $form->createView();
  }

  public function buildForm($class, $name, array $config) {
    $factory = $this->getFormFactory();
    $builder = $factory->createNamedBuilder($name, FormType::class);
    foreach ($config as $options) {
      if (isset($options['property'])) {
        $property = $options['property'];
        unset($options['property']);
        $type = isset($options['type']) ? $options['type'] : NULL;
        unset($options['type']);
        $field = $factory->createBuilderForProperty($class, $property, NULL, $options);
        $builder->add($field, $type, $options);
      }
    }

    return $builder->getForm()->createView();
  }

  protected function getFormFactory() {
    return $this->container->get('form.factory');
  }
}
