<?php

namespace AppBundle\EventListener;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IncludeFieldsForm implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if ($data) {
            $form->add('score', ChoiceType::class, array(
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
            ));
            $form->add('title', TextType::class, array(
                'label' => 'Título',
                'attr' => array('class' => 'form-control')
            ));
            $form->add('comment', TextareaType::class, array(
                'label' => 'Comentario',
                'attr' => array('class' => 'form-control')
            ));
        }
    }
}