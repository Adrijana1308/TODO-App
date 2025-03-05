<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, [
                'label' => 'Task Description',
                'attr' => [
                    'placeholder' => 'Enter task description here...',
                    'class' => 'new-task-text'
                ],
            ])
            ->add('dueDate', DateTimeType::class, [
                'label' => false,
                'widget' => 'choice', 
                'input_format' => 'Y-m-d H:i',
                'html5' => false, 
                'attr' => [
                    'placeholder' => '2024-06-15 14:15',
                    'class' => 'datetime-local', 
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => '<img src="' . $options['asset_path'] . '/images/plus.png" alt="plus">',
                'label_html' => true, 
                'attr' => ['class' => 'btn-create plus'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'asset_path' => '', 
        ]);
    }
}
?>