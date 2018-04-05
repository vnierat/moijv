<?php

namespace App\Form;

use App\DataTransformers\TagTransformer;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    
    private  $tagTransformer;
    
    public function __construct(TagTransformer $tagTransformer) 
    {
        $this->tagTransformer = $tagTransformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('title')
            ->add('description', TextareaType::class)
            ->add('image', FileType::class, [
                'required' => false
                
            ])
            ->add('tags', TextType::class)
            ->get('tags')
                    ->addModelTransformer($this->tagTransformer)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
