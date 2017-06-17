<?php

namespace AppBundle\Form;

use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\EventListener\IncludeFieldsForm;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                    'label' => 'Título',
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('comment', TextareaType::class, array(
                'label' => 'Comentario',
                'attr' => array('class' => 'form-control')
            ))
            ->add('score', ChoiceType::class, array(
                'label' => 'Puntuación',
                'choices'  => array(
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10,
                ),
                'expanded' => true,
                'multiple' => false,
                'attr' => array(
                    'class' => 'optradio'
                )
            ))
            ->add('save', SubmitType::class, array(
                    'label' => 'Confirmar',
                    'attr' => array(
                        'class' => 'btn btn-primary-search'
                    )
                )
            )
            ->add('cancel', SubmitType::class, array(
                    'label' => 'Cancelar',
                    'attr' => array(
                        'class' => 'btn btn-primary-reset '
                    )
                )
            )
            ->add('delete', SubmitType::class, array(
                    'label' => 'Eliminar',
                    'attr' => array(
                        'class' => 'btn btn-primary-danger '
                    )
                )
            )->addEventSubscriber(new IncludeFieldsForm())
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Comment'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_comment';
    }


}
