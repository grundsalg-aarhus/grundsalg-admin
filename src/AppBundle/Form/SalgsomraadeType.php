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
            ->add('createdby')
            ->add('createddate', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('modifiedby')
            ->add('modifieddate', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
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
            ->add('landinspektorid')
            ->add('delomraadeid')
            ->add('lokalplanid')
            ->add('postbyid')
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
