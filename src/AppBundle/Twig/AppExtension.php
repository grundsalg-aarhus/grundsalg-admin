<?php

namespace AppBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

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
      $type = isset($options['type']) ? $this->getType($options['type']) : NULL;
      unset($options['type']);
      if (isset($options['property'])) {
        $property = $options['property'];
        unset($options['property']);
        $field = $factory->createBuilderForProperty($class, $property, NULL, $options);
        $builder->add($field, $type, $options);
      } elseif (isset($options['name'])) {
        $name = $options['name'];
        unset($options['name']);
        $field = $factory->createNamedBuilder($name, $type, NULL, isset($options['type_options']) ? $options['type_options'] : null);
        $builder->add($field, $type, $options);
      }
    }

    return $builder->getForm()->createView();
  }

  private function getType($type) {
    switch ($type) {
      case 'choice':
        return ChoiceType::class;
    }

    return $type;
  }

  protected function getFormFactory() {
    return $this->container->get('form.factory');
  }
}
