<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Article;
use App\Entity\Commande;
use App\Entity\CommandeArticle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client1 = new Client();
        $client1->setNom('Ndiaye')
            ->setPrenom('Amadou')
            ->setTelephone('778671011')
            ->setAdresse('Dakar | Point E | Villa001');
        $manager->persist($client1);

        $client2 = new Client();
        $client2->setNom('Diallo')
            ->setPrenom('Fatou')
            ->setTelephone('778671022')
            ->setAdresse('Dakar | Mermoz | Villa002');
        $manager->persist($client2);

        $article1 = new Article();
        $article1->setNomArticle('Ordinateur')
            ->setPrix(300000)
            ->setQteStock(10);
        $manager->persist($article1);

        $article2 = new Article();
        $article2->setNomArticle('Téléphone')
            ->setPrix(200000)
            ->setQteStock(20);
        $manager->persist($article2);

        $article3 = new Article();
        $article3->setNomArticle('Imprimante')
            ->setPrix(150000)
            ->setQteStock(5);
        $manager->persist($article3);

        $commande = new Commande();
        $commande->setClient($client1)
            ->setDateAt(new \DateTimeImmutable())
            ->setPrixCommande(500000);
        $manager->persist($commande);

        $commandeArticle1 = new CommandeArticle();
        $commandeArticle1->setCommande($commande)
            ->setArticle($article1)
            ->setQte(1)
            ->setPrix(300000);
        $manager->persist($commandeArticle1);

        $commandeArticle2 = new CommandeArticle();
        $commandeArticle2->setCommande($commande)
            ->setArticle($article2)
            ->setQte(1)
            ->setPrix(200000);
        $manager->persist($commandeArticle2);

        $manager->flush();
    }
}
