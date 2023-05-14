<?php

namespace App\Form;

use App\Entity\ActivityMember;
use App\Entity\Member;
use App\Entity\Activity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('member', EntityType::class, [
                'class' => Member::class,
                'choice_label' => 'id', // or any property you would like to use as a label
                'required' => true,
            ])
            ->add('activity', EntityType::class, [
                'class' => Activity::class,
                'choice_label' => 'id', // or any property you would like to use as a label
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActivityMember::class,
        ]);
    }
}
