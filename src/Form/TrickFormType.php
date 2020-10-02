<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class TrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class)
            ->add('description', TextareaType::class)
            ->add('picture', FileType::class, [
              'data_class' => null,
							'constraints' => [
								new File([
									'maxSize' => '5000000',
									'mimeTypes' => [
										'image/jpeg',
										'image/jpg',
										'image/png'
									]
								])
							]
            ])
            ->add('category', EntityType::class, [
              'class' => Category::class,
              'choice_label' => 'name'
            ])
            ->add('videos', CollectionType::class, [
              'entry_type' => VideoFormType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'label' => false,
              'by_reference' => false
            ])
            ->add('pictures', FileType::class, [
              'multiple' => true,
              'mapped' => false,
							'constraints' => [
								new All(
									new File([
										'maxSize' => '5000000',
										'mimeTypes' => [
											'image/jpeg',
											'image/jpg',
											'image/png'
										]
									])
								)
							]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
