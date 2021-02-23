<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/{id}", name="sortie_show", requirements={"id"="\d+"})
     */
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $sortie = $em->getRepository(Sortie::class)->find($id);

        $form = $this->createForm(SortieType::class, $sortie);

        return $this->render('sortie/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sortie/modify/{id}", name="sortie_modify", requirements={"id"="\d+"})
     */
    public function modify(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $sortie = $em->getRepository(Sortie::class)->find($id);
        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()) {

            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'sortie.success.sortie_updated');
        }
        return $this->render('sortie/modify.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sortie/publish/{id}", name="sortie_publish", requirements={"id"="\d+"})
     */
    public function publish(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $sortie = $em->getRepository(Sortie::class)->find($id);

        if($sortie->getEtat()->getLibelle() == Etat::CREATED) {
            $sortie->setEtat(
                $em->getRepository(Etat::class)->findOneBy(['libelle' => Etat::OPEN])
            );
            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'sortie.success.sortie_published');
        }

        return $this->redirect( $request->headers->get('referer') );
    }

    /**
     * @Route("/sortie/subscribe/{id}", name="sortie_subscribe", requirements={"id"="\d+"})
     */
    public function subscribe(int $id, EntityManagerInterface $em): Response
    {
        $sortie = $em->getRepository(Sortie::class)->find($id);
        $participant = $this->getUser();

        // ajout de l'utilisateur aux participants de la sortie
        $sortie->addParticipant($participant);
        $em->persist($sortie);
        $em->flush();

        $this->addFlash('success','sortie.success.user_subscribed');
        return $this->redirectToRoute('default_home');
    }

    /**
     * @Route("/sortie/withdraw/{id}", name="sortie_withdraw", requirements={"id"="\d+"})
     */
    public function withdraw(int $id, EntityManagerInterface $em): Response
    {
        $sortie = $em->getRepository(Sortie::class)->find($id);
        $participant = $this->getUser();

        // suppression de l'utilisateur de la liste des participants de la sortie
        $sortie->removeParticipant($participant);
        $em->persist($sortie);
        $em->flush();

        $this->addFlash('success','sortie.success.user_withdraw');
        return $this->redirectToRoute('default_home');
    }

    /**
     * @Route("/sortie/cancel/{id}", name="sortie_cancel", requirements={"id"="\d+"})
     */
    public function cancel(int $id, EntityManagerInterface $em): Response
    {
        $sortie = $em->getRepository(Sortie::class)->find($id);

        $form = $this->createForm(SortieType::class, $sortie);

        return $this->render('sortie/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
