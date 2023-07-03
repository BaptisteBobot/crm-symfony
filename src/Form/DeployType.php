<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('projectType', ChoiceType::class, [
                'label' => 'Project Type',
                'choices' => [
                    'Symfony' => 'symfony',
                    'React' => 'react',
                    'Java' => 'java',
                    'C#' => 'csharp',
                    'JavaScript' => 'javascript',
                    'PHP' => 'php',
                    'Python' => 'python',
                    'Ruby' => 'ruby',
                    'Go' => 'go',
                    'Swift' => 'swift',
                    'Rust' => 'rust',
                    'Haskell' => 'haskell',
                   
                ],
                'required' => true,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Upload']);
    }
}
