<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostbyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('postalcode')
            ->add('city')
            ->add('createdBy')
            ->add('updatedBy')
            ->add('createdAt', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('updatedAt', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Postby'
        ));
    }
}
