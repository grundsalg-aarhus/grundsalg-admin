<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InteressentType extends AbstractType
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
            ->add('navn')
            ->add('adresse')
            ->add('land')
            ->add('telefon')
            ->add('mobil')
            ->add('koeberemail')
            ->add('navn1')
            ->add('adresse1')
            ->add('land1')
            ->add('telefon1')
            ->add('mobil1')
            ->add('medkoeberemail')
            ->add('notat')
            ->add('medkoeberpostbyid')
            ->add('koeberpostbyid')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Interessent'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_interessent';
    }
}
