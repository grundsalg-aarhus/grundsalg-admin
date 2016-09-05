<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LokalplanType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nr')
            ->add('lsnr')
            ->add('titel')
            ->add('projektleder')
            ->add('telefon')
            ->add('lokalplanlink')
            ->add('samletareal')
            ->add('salgbartareal')
            ->add('forbrugsandel')
            ->add('createdby')
            ->add('createddate')
            ->add('modifiedby')
            ->add('modifieddate')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Lokalplan'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_lokalplan';
    }
}
