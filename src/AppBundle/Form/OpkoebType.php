<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpkoebType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matrik1')
            ->add('matrik2')
            ->add('ejerlav')
            ->add('m2')
            ->add('bemaerkning')
            ->add('opkoebdato', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('pris')
            ->add('procentaflp')
            ->add('createdby')
            ->add('createddate', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('modifiedby')
            ->add('modifieddate', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('createdBy')
            ->add('updatedBy')
            ->add('createdAt', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('updatedAt', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('lpid')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Opkoeb'
        ));
    }
}
