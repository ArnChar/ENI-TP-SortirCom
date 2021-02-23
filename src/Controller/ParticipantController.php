<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant/{id}", name="participant_show", requirements={"id"="\d+"})
     */
    public function read(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $participant = $em->getRepository(Participant::class)->find($id);

        return $this->render('participant/index.html.twig', [
            'participant' => $participant
        ]);
    }


    /**
     * @Route("/participant/modify", name="participant_modify")
     */
    public function modify(Request $request, EntityManagerInterface $em,
                          UserPasswordEncoderInterface $encoder): Response
    {
        $participant = $this->getUser();
        $form = $this->createForm(ParticipantType::class, $participant);

        $form->handleRequest($request);
        if( $form->isSubmitted() ) {

            if( $form->isValid()) {
                $hashedPassword = $encoder->encodePassword($participant, $participant->getPassword());
                $participant->setPassword($hashedPassword);

                $em->persist($participant);
                $em->flush();

                $this->addFlash('success','participant.success.update');
                $em->refresh($participant);
                return $this->redirectToRoute('default_home');

            } else {
                $em->refresh($participant);
            }
        }

        return $this->render('participant/modify.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
