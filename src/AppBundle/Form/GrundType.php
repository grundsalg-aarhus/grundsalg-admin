<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrundType extends AbstractType
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
            ->add('status')
            ->add('salgstatus')
            ->add('gid')
            ->add('grundtype')
            ->add('mnr')
            ->add('mnr2')
            ->add('delareal')
            ->add('ejerlav')
            ->add('vej')
            ->add('husnummer')
            ->add('bogstav')
            ->add('postbyid')
            ->add('urlgis')
            ->add('salgstype')
            ->add('auktionstartdato', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('auktionslutdato', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('annonceresej')
            ->add('datoannonce', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('datoannonce1', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('tilsluttet')
            ->add('maxetagem2')
            ->add('areal')
            ->add('arealvej')
            ->add('arealkotelet')
            ->add('bruttoareal')
            ->add('prism2')
            ->add('prisfoerkorrektion')
            ->add('priskorrektion1')
            ->add('antalkorr1')
            ->add('akrkorr1')
            ->add('priskoor1')
            ->add('priskorrektion2')
            ->add('antalkorr2')
            ->add('akrkorr2')
            ->add('priskoor2')
            ->add('priskorrektion3')
            ->add('antalkorr3')
            ->add('akrkorr3')
            ->add('priskoor3')
            ->add('pris')
            ->add('fastpris')
            ->add('minbud')
            ->add('note')
            ->add('landinspektoerid')
            ->add('resstart', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('tilbudstart', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('accept', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('skoederekv', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('beloebanvist', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('resslut', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('tilbudslut', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('overtagelse', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('antagetbud')
            ->add('salgsprisumoms')
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
            ->add('lokalsamfundid')
            ->add('salgsomraadeid')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Grund'
        ));
    }
}
