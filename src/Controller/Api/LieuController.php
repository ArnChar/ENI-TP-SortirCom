<?php


namespace App\Controller\Api;


use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/api", name="api_")
 * Class LieuController
 * @package App\Controller\Api
 */
class LieuController extends AbstractController
{
    /**
     * Retourne le Lieu pour l'id fourni en paramètre
     * @Route("/lieu/{id}", requirements={"id"="\d+"}, name="lieu_index", methods={"GET"})
     * @param LieuRepository $repo
     * @return Response Lieu au format Json
     */
    public function index(int $id, LieuRepository $repo, SerializerInterface $serializer): Response
    {
        $lieu = $repo->findJoinVille($id);
        $json = $serializer->serialize($lieu, 'json', ['groups' => 'api_lieu_index']);
        return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

}