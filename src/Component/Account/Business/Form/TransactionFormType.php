<?php declare(strict_types=1);

namespace App\Component\Account\Business\Form;

use App\Entity\TransactionReceiverValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value')
            ->add('receiver')
            ->add('Versenden', SubmitType::class, [
                'attr' => [
                    'class' => 'button',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TransactionReceiverValue::class,
        ]);
    }
}
