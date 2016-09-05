<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SalgshistorikType extends AbstractType
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
            ->add('aarsag')
            ->add('salgstype')
            ->add('status')
            ->add('resstart')
            ->add('resslut')
            ->add('tilbudstart')
            ->add('tilbudslut')
            ->add('accept')
            ->add('overtagelse')
            ->add('skoederekv')
            ->add('beloebanvist')
            ->add('auktionstartdato')
            ->add('auktionslutdato')
            ->add('minbud')
            ->add('antagetbud')
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
            ->add('grundid')
            ->add('koeberpostbyid')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Salgshistorik'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_salgshistorik';
    }
}
