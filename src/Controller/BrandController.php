<?php

namespace App\Controller;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/brand', name: 'app_brand')]
    public function index(): Response
    {
        
        $brands = $this->entityManager->getRepository(Brand::class)->findBy([], ['name' => 'ASC']);

        return $this->render('brand/camera.html.twig', [
            'brands' => $brands,
        ]);
    }
}
