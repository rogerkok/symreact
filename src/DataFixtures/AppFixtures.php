<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hash;
    public function __construct(UserPasswordHasherInterface $hash){
        $this->hash = $hash;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        $password = 'password';
        for ($u = 0; $u< 10; $u++) {
            $user = new User();
            $chrono = 1;
            $hashedPassword = $this->hash->hashPassword(
                $user, $password
            );
            $user->setFirstName($faker->firstName())
                 ->setLastName($faker->lastName)
                 ->setUsername($faker->userName)
                 ->setEmail($faker->email)
                 ->setPassword($hashedPassword);
            $manager->persist($user);
            for ($c = 0; $c<mt_rand(5, 20); $c++) {
                $customer = new Customer();
                $customer->setFirstName($faker->firstName())
                     ->setLasteName($faker->lastName)
                     ->setCompany($faker->company)
                     ->setEmail($faker->email)
                     ->setUtilisateur($user);

        
                $manager->persist($customer);
                for ($i = 0; $i<mt_rand(3, 10); $i++) {
                    $invoice = new Invoice();
                    $invoice->setAmount($faker->randomFloat(2, 2500, 50000))
                        ->setSentAt($faker->dateTimeBetween('-6 months'))
                        ->setStatus($faker->randomElement(['SENT','PAID','CANCELLED']))
                        ->setCustomer($customer)
                        ->setChrono($chrono);
                    $chrono++;
                    $manager->persist($invoice);
                }
            }
    
        
        
            $manager->flush();
        }
    }
}
