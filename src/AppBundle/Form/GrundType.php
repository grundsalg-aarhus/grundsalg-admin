<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('createddate')
            ->add('modifiedby')
            ->add('modifieddate')
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
            ->add('auktionstartdato')
            ->add('auktionslutdato')
            ->add('annonceresej')
            ->add('datoannonce')
            ->add('datoannonce1')
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
            ->add('resstart')
            ->add('tilbudstart')
            ->add('accept')
            ->add('skoederekv')
            ->add('beloebanvist')
            ->add('resslut')
            ->add('tilbudslut')
            ->add('overtagelse')
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
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Grund'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_grund';
    }
}
