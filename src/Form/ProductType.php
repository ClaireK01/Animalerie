<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('image', FileType::class, [
                'label' => 'Image (jpg, png):',
                'mapped'=>false
            ])
            ->add('label', TextType::class, [
                'label'=>'Nom:'
            ])
            ->add('description', TextareaType::class, [
                'label'=>'Description:'
            ])
            ->add('isActif', CheckboxType::class, [
                'label'=>'Actif',
                'required'=>false
            ])
            ->add('stock', IntegerType::class, [
                'label'=>'Stock:'
            ])
            ->add('price', IntegerType::class, [
                'label'=> 'Prix (en centimes):',
                'attr'=>[
                    'value'=>'0'
                ]
            ])
            ->add('brand', EntityType::class, [
                'label'=>'Marque:',
                'class'=>Brand::class,
                'choice_label'=>'label',
                'expanded'=>true
            ])
            ->add('categories', EntityType::class, [
                'label'=>'Catégories:',
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                            ->where('c.categoryParent IS NOT NULL');
                },
                'choice_label'=>'label',
                'expanded'=>true,
                'multiple'=>true,
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
