<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('poster', UrlType::class, [
                'label' => 'Image',
                'attr' => [
                    'placeholder' => 'par ex. https://...',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'description',
            ])
            ->add('matiere', TextType::class, [
                'label' => 'Matière',
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix',
                'help' => 'Un nombre strictement positif.',
                'empty_data' => 0,
            ])
            ->add('news', ChoiceType::class, [
                'choices' => [
                    'oui' => true,
                    'non' => false,
                ],
                'multiple' => false,
                'expanded' => true,
                'label' => 'Nouveauté',
                'label_attr' => [
                    'class' => 'checkbox-inline',
                ],
            ])
            ->add('stock', IntegerType::class,[
                'label' => 'stock',
                'help' => 'Un nombre strictement positif',
                'empty_data' => 0,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'mapped' => true,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'checkbox-inline',
                ],
            ])
            ->add('subcategory', EntityType::class, [
                'class' => SubCategory::class,
                'mapped' => true,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'checkbox-inline',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
