<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $userPasswordEncoder
    )
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln('Vous allez pouvoir créer un administrateur pour vous connecter au site local :');

        $firstname = $io->ask('Prénom ?', 'Dominique');

        $lastname = $io->ask('Nom ?', 'Strauss-Kahn');

        $email = $io->ask('E-mail de connexion', 'dsk@pornhub.com', function(string $email) {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException("Un E-mail ... C'est le truc avec le a bizarre ...
                ");
            }

            $exists = $this->userRepository->findOneByEmail($email);

            return $email;
        });

        $password = $io->askHidden('Mot de passe ?', function($password) {
            if(!preg_match(User::PASSWORD_REGEX, $password)) {
                throw new \RuntimeException('Franchement ... au moins 8 caractères, un chiffre et une lettre ... Fais un effort ...');
            }

            return $password;
        });

        $user = new User();
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setRoles([
            User::ROLE_ADMIN,
            User::ROLE_USER
        ]);

        $user->setPassword(
            $this->userPasswordEncoder->encodePassword($user, $password)
        );

        $io->success("C'est tout bon ! Tu peux maintenant te connecter.");

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
