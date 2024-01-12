<?php declare(strict_types=1);

namespace App\Component\User\Business\Form;

use App\DTO\UserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserDTOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte einen Benutzernamen auswählen!',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Benutzername sollte mindestens 3 Zeichen lang sein!',
                        'max' => 30,
                        'maxMessage' => 'Benutzername sollte maximal 30 Zeichen lang sein!',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte eine Email-Adresse eingeben!',
                    ]),
                    new Email([
                        'message' => 'Ungültige Email-Adresse!',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte ein Passwort eingeben!',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Bitte ein Passwort mit mindestens 6 Zeichen auswählen!',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserDTO::class,
        ]);
    }
}
