<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Lignedecommande;
use App\Entity\Oeuvre;
use App\Entity\User;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\OeuvreRepository;
use App\Service\mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;





class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, OeuvreRepository $oeuvreRepository)
    {
        $panier = $session->get("panier", []);

        $dataPanier = [];
        $total = 0;
        $totalcommande =0;

        foreach($panier as $id => $quantite){
            $oeuvre = $oeuvreRepository->find($id);


            $dataPanier[] = [
                "oeuvre" => $oeuvre,
                "quantite" => $quantite
            ];
            $total = intval($oeuvre->getPrix()) * $quantite + $total;
        }


        return $this->render('cart/index.html.twig', compact("dataPanier", "total" , "totalcommande"));
    }
    #[Route("/add/{id}", name:"add")]
    public function add(Oeuvre $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_cart");
    }
    #[Route("/remove/{id}", name:"remove")]
    public function remove(Oeuvre $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_cart");
    }
    #[Route("/delete/{id}", name:"delete")]
    public function delete(Oeuvre $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_cart");
    }
    #[Route('/new', name: 'commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandeRepository $commandeRepository, SessionInterface $session, OeuvreRepository $oeuvreRepository, PersistenceManagerRegistry $doctrine, MailerInterface $mailer ): Response
    {
        
        $commande = new Commande();

        $panier = $session->get("panier", []);
        $commande->setNom("hamed");
        $commande->setPrenom("mohamed");
        $commande->setAdresse("ben arous");
        $commande->setTelephone(58533654);
        $commande->setPrixTotal(0);

             foreach($panier as $id => $quantite){
                 $product = $oeuvreRepository->find($id);
                 $lignedecommende=new Lignedecommande();


                 $total = intval($product->getPrix()) * $quantite;
                 $lignedecommende->setOeuvre($product);
                 $lignedecommende->setQuantite($quantite);
                 $em = $doctrine->getManager();
                 $em->persist($lignedecommende);
                 $em->flush();
                 $commande->addLignedecommande($lignedecommende);
                 $commande->setPrixTotal($commande->getPrixTotal()+$total);
                 unset($panier[$id]);
             }
             $em = $doctrine->getManager();
             $em->persist($commande);
             $em->flush();
             $session->set("panier", $panier);
             $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);






        return $this->redirectToRoute("app_cart");

    }

}
