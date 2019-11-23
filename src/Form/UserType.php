<?php
// src/FormUserType.php
namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\IntegerType;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label' => 'Adresse mail*'))
            ->add('username', TextType::class, array('label' => 'Login*'))
            ->add('confirmPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe*'),
                'second_options' => array('label' => 'Répéter le mot de passe*'),
            ))
            ->add('codePostal', TextType::class, array('label' => 'Code postal', 'required'=>false) )
            ->add('ville', TextType::class, array('label' => 'Ville', 'required'=>false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}