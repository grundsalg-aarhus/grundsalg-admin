<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DelomraadeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('kpl1')
            ->add('kpl2')
            ->add('kpl3')
            ->add('kpl4')
            ->add('o1')
            ->add('o2')
            ->add('o3')
            ->add('anvendelse')
            ->add('mulighedfor')
            ->add('createdby')
            ->add('createddate')
            ->add('modifiedby')
            ->add('modifieddate')
            ->add('lokalplanid')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Delomraade'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_delomraade';
    }
}
