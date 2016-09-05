<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('createddate')
            ->add('modifiedby')
            ->add('modifieddate')
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
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Salgsomraade'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_salgsomraade';
    }
}
