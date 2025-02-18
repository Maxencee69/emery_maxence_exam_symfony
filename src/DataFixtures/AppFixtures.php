<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Camera;
use App\Entity\Brand;
use App\Entity\Photo;
use App\Entity\Manual;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $brands = [];
        $cameraPhotoMap = [
            'Leica' => [
                ['modelName' => 'M6', 'photo' => 'leicam6.webp', 'manual' => 'leicam6manual.pdf', 'filmFormat' => '35mm']
            ],
            'Canon' => [
                ['modelName' => 'AE-1', 'photo' => 'canonae1.webp', 'manual' => 'canonae1manual.pdf', 'filmFormat' => '35mm']
            ],
            'Nikon' => [
                ['modelName' => 'FM2', 'photo' => 'nikonfm2.webp', 'manual' => 'nikonFm2manual.pdf', 'filmFormat' => '35mm']
            ],
            'Pentax' => [
                ['modelName' => 'K1000', 'photo' => 'pentaxk1000.webp', 'manual' => 'pentaxK1000manual.pdf', 'filmFormat' => '35mm']
            ],
            'Minolta' => [
                ['modelName' => 'X-300', 'photo' => 'minoltax300.webp', 'manual' => 'minoltax300manual.pdf', 'filmFormat' => '35mm']
            ],
            'Yashica' => [
                ['modelName' => 'T4', 'photo' => 'yashicat4.webp', 'manual' => 'yashicat4manual.pdf', 'filmFormat' => '35mm']
            ],
            'Rollei' => [
                ['modelName' => '35', 'photo' => 'rollei35.webp', 'manual' => 'rollei35manual.pdf', 'filmFormat' => '35mm']
            ],
            'Hasselblad' => [
                ['modelName' => '503CW', 'photo' => 'hasselblad503CW.webp', 'manual' => 'Hasselbladmanual.pdf', 'filmFormat' => '35mm']
            ],
            'Olympus' => [
                ['modelName' => 'XA2', 'photo' => 'olympusxa2.webp', 'manual' => 'olympusxa2manual.pdf', 'filmFormat' => '35mm']
            ],
            'Mamiya' => [
                ['modelName' => '7', 'photo' => 'mamiya7.webp', 'manual' => 'mamiya7manual.pdf', 'filmFormat' => '120']
            ],
            'Contax' => [
                ['modelName' => 'T2', 'photo' => 'contaxt2.webp', 'manual' => 'contaxt2manual.pdf', 'filmFormat' => '35mm']
            ],
        ];

        foreach ($cameraPhotoMap as $brandName => $cameras) {
            $brand = new Brand();
            $brand->setName($brandName);
            $manager->persist($brand);
            $brands[$brandName] = $brand;
        }

        $regularUser = new User();
        $regularUser
            ->setEmail('bobby@bob.com')
            ->setPassword($this->hasher->hashPassword($regularUser, 'test'));

        $manager->persist($regularUser);

        $adminUser = new User();
        $adminUser
            ->setEmail('admin@mycorp.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($adminUser, 'test'));

        $manager->persist($adminUser);

        foreach ($cameraPhotoMap as $brandName => $cameras) {
            foreach ($cameras as $cameraDetails) {
                $camera = new Camera();
                $camera->setModelName($cameraDetails['modelName']);
                $camera->setYear($faker->year);
                $camera->setDescription($faker->paragraph(3) . "\n\n" . $faker->paragraph(3));
                $camera->setFilmFormat($cameraDetails['filmFormat']);
                $camera->setBrand($brands[$brandName]);
                $camera->setOwner($regularUser); 

                $manager->persist($camera);

                
                $photo = new Photo();
                $photo->setCamera($camera);
                $photo->setPhotoPath('/camera_photos/' . $cameraDetails['photo']);
                $manager->persist($photo);

                
                $manual = new Manual();
                $manual->setCamera($camera);
                $manual->setManualPath('/camera_manuels/' . $cameraDetails['manual']);
                $manager->persist($manual);
            }
        }

        $manager->flush();
    }
}
