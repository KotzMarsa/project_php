<?php
/**
 * Product type.
 */

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProductType
 */
class ProductType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'product_name',
            TextType::class,
            [
                'label' => 'label.product_name',
                'required' => true,
                'attr' => ['max_length' => 100],
            ]
        );
        $builder->add(
            'calories',
            TextType::class,
            [
                'label' => 'label.calories',
                'required' => true,
                'attr' => ['max_length' => 100],
            ]
        );
        $builder->add(
            'carbohydrate',
            TextType::class,
            [
                'label' => 'label.carbohydrate',
                'required' => true,
                'attr' => ['max_length' => 100],
            ]
        );
        $builder->add(
            'protein',
            TextType::class,
            [
                'label' => 'label.protein',
                'required' => true,
                'attr' => ['max_length' => 3],
            ]
        );
        $builder->add(
            'fat',
            TextType::class,
            [
                'label' => 'label.fat',
                'required' => true,
                'attr' => ['max_length' => 3],
            ]
        );
        $builder->add(
            'is_accepted',
            TextType::class,
            [
                'label' => 'label.is_accepted',
                'required' => false,
                'data' => '0',
                'label' => false,
            ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Product::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'product';
    }
}
