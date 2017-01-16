<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalgsomraadeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nr')
            ->add('titel')
            ->add('type')
            ->add('matrikkel1')
            ->add('matrikkel2')
            ->add('ejerlav')
            ->add('vej')
            ->add('gisurl')
            ->add('tilsluttet')
            ->add('sagsnr')
            ->add('lploebenummer')
            ->add('createdBy')
            ->add('updatedBy')
            ->add('createdAt', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('updatedAt', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('landinspektoer')
            ->add('delomraade')
            ->add('lokalplan')
            ->add('postby')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Salgsomraade'
        ));
    }
}
