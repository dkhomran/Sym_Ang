<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/personne/list', name: 'app_personne_list', methods: ['GET'])]
    public function index(PersonneRepository $personneRepository)
    {
        $users = $personneRepository->findAll();
        return $this->json($users);
    }

    #[Route('/personne/store', name: 'app_personne_store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        // Récupérez les données de la requête POST
        $data = json_decode($request->getContent(), true);

        // Créez une nouvelle instance de Personne
        $personne = new Personne();
        $personne->setFirstname($data['firstname']);
        $personne->setLastname($data['lastname']);
        $personne->setEmail($data['email']);
        $personne->setAge($data['age']);

        // Persister l'entité dans la base de données
        $this->entityManager->persist($personne);
        $this->entityManager->flush();

        // Répondez avec une réponse JSON de succès
        return new JsonResponse(['message' => 'Personne ajoutée avec succès'], Response::HTTP_CREATED);
    }
}
