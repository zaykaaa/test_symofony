<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlayerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;

class PlayerController extends AbstractController
{
    #[Route ('/player',name : 'app_player')]
    public function showPlayer(PlayerRepository $br,EntityManagerInterface $em)
    {
        $playersBD=$br->findAll();
            return $this->render('player/index.html.twig', [
                
                'players' => $playersBD,
            ]);
    }
    #[Route('/player/add',name: 'app_addplayer')]
    public function newplayer(Request $req,EntityManagerInterface $em)
    {
        $player=new Player();
        $form=$this->createForm(PlayerType::class,$player);
        $form->handleRequest($req);
        if ($form->isSubmitted())
        {  
            $em->persist($player);
            $em->flush();
            return $this->redirectToRoute('app_player');
        }
        return $this->renderForm('player/form.html.twig',["formPlayer"=>$form,"title"=>"Add Player"]);
    }
    #[Route('/player/edit/{id}',name: 'app_editplayer')]
    public function editPlayer($id,Request $req,EntityManagerInterface $em,PlayerRepository $br ) : Response
    {
        $player=$br->find($id);
        $form=$this->createForm(PlayerType::class,$player);
        $form->handleRequest($req);
        if ($form->isSubmitted())
        {
            $em->persist($player);
            $em->flush();
            return $this->redirectToRoute('app_player');
        }
        return $this->renderForm('player/form.html.twig',["formPlayer"=>$form]);
    }
    #[Route('/player/delete/{id}',name:'app_deletePlayer')]
    public function delete($id,EntityManagerInterface $em, PlayerRepository $ar)
    {
        $player=$ar->find($id);
        $em->remove($player);
        $em->flush();
        return $this->redirectToRoute('app_player');
    }
}
