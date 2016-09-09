<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalgshistorikType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aarsag')
            ->add('salgstype')
            ->add('status')
            ->add('resstart', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('resslut', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('tilbudstart', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('tilbudslut', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('accept', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('overtagelse', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('skoederekv', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('beloebanvist', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('auktionstartdato', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('auktionslutdato', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
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
            ->add('createdBy')
            ->add('updatedBy')
            ->add('createdAt', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('updatedAt', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('medkoeberpostbyid')
            ->add('grundid')
            ->add('koeberpostbyid')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Salgshistorik'
        ));
    }
}
