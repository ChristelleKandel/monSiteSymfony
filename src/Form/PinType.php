<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('imageFile', VichImageType::class, [
            'label' => 'Image (jpg ou png)',
            'required' => false,
            'allow_delete' => true,
            'delete_label' => 'Effacer',
            // 'download_label' => 'Télécharger',
            'download_uri' => false,
            'image_uri' => true,
            'imagine_pattern' => 'square_small',
            // 'asset_helper' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
