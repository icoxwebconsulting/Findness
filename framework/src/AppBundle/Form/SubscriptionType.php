<?php

namespace AppBundle\Form;

use Finance\Finance\Subscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class SubscriptionType
 *
 * @package AppBundle\Form
 */
class SubscriptionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('balance', NumberType::class)
            ->add('operator', NumberType::class)
            ->add('transactionId', TextType::class)
            ->add('cardId', TextType::class)
            ->add(
                'lapse',
                TextType::class,
                array(
                    'constraints' => array(
                        new NotBlank(),
                        new Choice(
                            array(
                                'choices' => array_keys(Subscription::LAPSES),
                                'message' => 'Invalid Lapse.',
                            )
                        ),
                    ),
                )
            )
            ->add(
                'startDate',
                DateTimeType::class,
                array(
                    'constraints' => array(
                        new NotBlank(),
                    ),
                    'widget' => 'single_text',
                    'format' => 'yyyy/MM/dd',
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => false,
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
