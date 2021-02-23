<?php

namespace App\Controller;

use App\Form\HomeType;
use App\Form\Model\HomeModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="default_home")
     */
    public function home(Request $request): Response
    {
        $homeModel = new HomeModel();
        $homeModel->init($this->getUser());

        // Récuperation des critères de recherche depuis la session
        $criterias = $request->getSession()->get('homeCriterias');
        if($criterias != null) {
            // rafraîchissement des entities à partir de leur id
            $criterias->reloadEntities($this->getDoctrine());
            $homeModel->setCriterias($criterias);
            // recherche des sorties après retour sur la page
            $homeModel->loadRows($this->getUser(), $this->getDoctrine());
        }

        $form = $this->createForm(HomeType::class, $homeModel->getCriterias());

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()) {
            // recherche des sorties après soumission
            $homeModel->loadRows($this->getUser(), $this->getDoctrine());
        }

        $request->getSession()->set('homeCriterias',$homeModel->getCriterias());

        return $this->render('default/home.html.twig', [
            'model' => $homeModel,
            'form' => $form->createView()
        ]);
    }

}
