<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {

        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($u=0; $u<10; $u++){
            $user=new User();

            $passHash = $this->encoder->encodePassword($user, 'password');
            $user->setEmail($faker->email)
                ->setPassword($passHash);

            if($u % 3 ===0){
                $user->setStatus(false)
                ->setAge(23);
            }

            $manager->persist($user);

            for($a=0; $a<random_int(5,15);$a++){
                $article = (new Article())
                    ->setAuthor($user)
                    ->setContent($faker->text(300))
                    ->setName($faker->text(50));
                $manager->persist($article);
            }

        }

        $manager->flush();
    }
}
