<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Form\RechercheType;
use App\Entity\CommandeArticle;
use App\Repository\ClientRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{

#[Route('/commande', name: 'app_commande')]
    public function index(
        Request $request,
        ClientRepository $clientRepository,
        ArticleRepository $articleRepository,
    ): Response {
        $form = $this->createForm(RechercheType::class);
        $form->handleRequest($request);

        $client = null;
        $telephone = null;
        $commandes = [];
        $articles = $articleRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $telephone = $data['telephone'];

            $client = $clientRepository->findOneBy(['telephone' => $telephone]);

          
        }

        return $this->render('commande/index.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
            'telephone' => $telephone,
            'articles' => $articles,
            'commandes' => $commandes,
        ]);
    }

    
    #[Route('/commande/save', name: 'app_save_commande')]
public function saveCommande(Request $request, EntityManagerInterface $entityManager, ArticleRepository $articleRepository): Response
{
    $data = $request->request->all();

    $clientId = $data['client_id']; 
    $articles = json_decode($data['articles'], true);

    $client = $entityManager->getRepository(Client::class)->find($clientId);
    $errors = [];

    foreach ($articles as $articleData) {
        $article = $articleRepository->find($articleData['id']);
        
        if ($articleData['quantite'] > $article->getQteStock()) {
            $errors[] = sprintf(
                "La quantité demandée pour l'article %s (%d) dépasse le stock disponible (%d).",
                $article->getNomArticle(),
                $articleData['quantite'],
                $article->getQteStock()
            );
        }
    }

    if (!empty($errors)) {
        $this->addFlash('error', implode('<br>', $errors));
        return $this->render('commande/index.html.twig', [
            'client' => $client,
            'articles' => $articleRepository->findAll(),
            'errors' => $errors,
        ]);
    }


    $commande = new Commande();
    $commande->setClient($client);
    $commande->setDateAt(new \DateTimeImmutable());
    $commande->setPrixCommande(array_sum(array_column($articles, 'montant')));

   
    $entityManager->persist($commande);

    foreach ($articles as $articleData) {
        $article = $articleRepository->find($articleData['id']);
        $commandeArticle = new CommandeArticle();
        $commandeArticle->setCommande($commande);
        $commandeArticle->setArticle($article);
        $commandeArticle->setQte($articleData['quantite']);
        $commandeArticle->setPrix($articleData['prix']);
        $entityManager->persist($commandeArticle);

        $article->setQteStock($article->getQteStock() - $articleData['quantite']);
    }

    $entityManager->flush();

    return $this->redirectToRoute('app_commande');
}

    
}
