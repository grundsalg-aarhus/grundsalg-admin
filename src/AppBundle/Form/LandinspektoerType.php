<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LandinspektoerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse')
            ->add('email')
            ->add('mobil')
            ->add('navn')
            ->add('notat')
            ->add('telefon')
            ->add('postnrid')
            ->add('createdby')
            ->add('createddate', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('modifiedby')
            ->add('modifieddate', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('active')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Landinspektoer'
        ));
    }
}
