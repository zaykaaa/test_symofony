<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Team;
use App\Form\TeamType;


class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team')]
    public function index(TeamRepository $TeamRepository): Response
    {   
        $teamBD=$TeamRepository->findAll();
        return $this->render('team/index.html.twig', [
            'controller_name' => 'TeamController',
            'teams' => $teamBD,
        ]);
    }
    #[Route('/team/new', name: 'team_add')]
    public function AddTeam(ManagerRegistry $doctrine, Request $request): Response
    {   $em= $doctrine->getManager();
        $team=new Team();
        $form=$this->createForm(TeamType::class,$team);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            
            $em->persist($team);
            $em->flush();
            return $this-> redirectToRoute('app_team');
        }
        return $this->renderForm('/team/form.html.twig',
        ["formteam"=>$form,"title"=>"Add Team"]);
    }
    #[Route('/team/edit/{id}',name: 'app_editteam')]
    public function editteam(Request $req,EntityManagerInterface $em,TeamRepository $ar,$id)
    {
        $team=$ar->find($id);
        $form=$this->createForm(TeamType::class,$team);
        $form->handleRequest($req);
        if ($form->isSubmitted())
        {
            $em->persist($team);
            $em->flush();
            return $this->redirectToRoute('app_team');
        }
        return $this->renderForm('team/form.html.twig',
        ["formteam"=>$form,"title"=>"Modify Team"]);
    }
    #[Route('/team/delete/{id}',name:'app_deleteteam')]
    public function delete($id,EntityManagerInterface $em, teamRepository $ar)
    {
        $team=$ar->find($id);
        $em->remove($team);
        $em->flush();
        return $this->redirectToRoute('app_team');
    }
 
}
