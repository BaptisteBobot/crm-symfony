<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DeployType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projectFile', FileType::class, [
                'label' => 'Project File',
                'mapped' => false,
                'required' => true,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Upload']);
    }
}
