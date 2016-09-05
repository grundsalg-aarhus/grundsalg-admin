<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('opkoebdato')
            ->add('pris')
            ->add('procentaflp')
            ->add('createdby')
            ->add('createddate')
            ->add('modifiedby')
            ->add('modifieddate')
            ->add('lpid')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Opkoeb'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_opkoeb';
    }
}
