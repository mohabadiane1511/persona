<?php

namespace App\Controller;

use DateTime;
use App\Entity\Personne;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonneController extends AbstractController
{
    #[Route('/api/personnes', name: 'app_personne', methods: ['POST'])]
    public function sauvegarderPersonne(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManagerInterface)
    {
        // Récupérer les données JSON envoyées dans la requête
        $data = json_decode($request->getContent(), true);

        //Verification si tous les  champs sont vides 
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['birth_date'])) {
            return $this->json(['message' => 'Veuillez remplir tous les champs'], 400);
        }


        // Valider le format de la date de naissance
        $dateNaissance = $data['birth_date'];
        if (!DateTime::createFromFormat('Y-m-d', $dateNaissance)) {
            return $this->json(['message' => 'Le format de la date de naissance doit être YYYY-MM-DD'], 400);
        }

        // Créer une nouvelle instance de Personne
        $personne = new Personne();
        $personne
            ->setNom($data['nom'])
            ->setPrenom($data['prenom'])
            ->setBirthDate(new \DateTime($data['birth_date']));

        // Valider l'entité
        $errors = $validator->validate($personne);

        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], 400);
        }

        // Vérifier si la personne a moins de 150 ans
        $age = date_diff(new \DateTime(), $personne->getBirthDate())->y;
        if ($age >= 150) {
            return $this->json(['message' => 'La personne doit avoir moins de 150 ans pour être enregistrée'], 400);
        }

        // Enregistrer la Personne en base de données
        $entityManagerInterface->persist($personne);
        $entityManagerInterface->flush();

        return $this->json(['message' => 'Personne enregistrée avec succès'], 201);
    }

    #[Route('/api/listPersonnes', name: 'app_personne_add', methods: ['GET'])]
    public function listerPersonnes(EntityManagerInterface $entityManagerInterface)
    {
        $personnes = $entityManagerInterface->getRepository(Personne::class)->findBy([], ['nom' => 'ASC']);

        $personnesData = [];
        foreach ($personnes as $personne) {
            $age = date_diff(new \DateTime(), $personne->getBirthDate())->y;
            $personnesData[] = [
                'nom' => $personne->getNom(),
                'prenom' => $personne->getPrenom(),
                'age' => $age,
            ];
        }

        return $this->json($personnesData);
    }
}
