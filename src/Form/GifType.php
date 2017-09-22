<?php
/**
 * Created by IntelliJ IDEA.
 * User: eletue
 * Date: 22/09/2017
 * Time: 17:38
 */

namespace App\Form;


use App\Entity\Gif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags')
            ->add('url')
            ->add('album')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Gif::class
        ]);
    }
}