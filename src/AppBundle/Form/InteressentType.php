<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('createddate', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('modifiedby')
            ->add('modifieddate', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
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
            ->add('koeberpostbyid')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Interessent'
        ));
    }
}
