<?php
/**
 * @author Erwann LETUE <erwann.letue@gmail.com>
 * Date: 20/09/2017 at 11:51
 */

namespace App\Form;

use App\Entity\News;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('author', EntityType::class, [
                'class'         => User::class,
                'choice_label'  => 'email'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => News::class
        ]);
    }
}