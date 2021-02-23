<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var array
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $etats = $this->loadEtats($manager, [
            "Créée", "Ouverte", "Clôturée", "Activité en cours", "Passée", "Annulée"
        ]);

        $campus = $this->loadCampus($manager, [
            "Campus Nantes", "Campus Rennes", "Campus Niort"
        ]);

        $villes = $this->loadVilles($manager, [
            ["Nantes","44000"], ["Rennes","35000"], ["Niort","79000"]
        ]);

        $lieux = $this->loadLieux($manager, [
            ["Le Jardin des Plantes","Place Charles Leroux",47.2194,-1.5427,$villes[0]],
            ["Le Nid","Place de Bretagne",47.2175,-1.5736,$villes[0]],
            ["Le hangar à bananes","Quai des Antilles",47.2005,-1.5736,$villes[0]],
            ["La Chapelle Saint-Yves","11 rue Saint-Yves",48.1105,-1.6827,$villes[1]],
            ["La Cathédrale Saint-Pierre","Centre",48.1113,-1.6833,$villes[1]],
            ["Les Halles","Place des Halles",46.3255,-0.4638,$villes[2]]
        ]);

        $participants = $this->loadParticipants($manager, [
            ["Jojo","nono12","ROLE_ADMIN","Neymard","Jean","+33612345678","jean@mail.fr",true,true,$campus[0]],
            ["Toto","toto12","ROLE_USER","Lepinsot","Sébastien","+33687654321","seb@mail.fr",false,true,$campus[1]],
            ["Polo","polo12","ROLE_USER","Desfleur","Yvan","+33614253687","yvan@mail.fr",false,true,$campus[2]]
        ]);

        $sorties = $this->loadSorties($manager, [
            ["Nantes Verte","2021-02-01 18:00:00",480,"2021-01-25 18:00:00",10,"S'équiper de bottes",
                $participants[0],$campus[0],$lieux[0],$etats[0]],
            ["Verres en l'air","2021-03-10 16:00:00",360,"2021-02-15 20:00:00",8,"Personnes majeures uniquement",
                $participants[0],$campus[0],$lieux[1],$etats[1]],
            ["Par les deux bouts","2021-01-18 08:00:00",5760,"2021-01-16 16:00:00",20,"Chaussures de marche",
                $participants[0],$campus[0],$lieux[2],$etats[3]],
            ["Chapelles rennaises","2020-12-10 15:00:00",120,"2020-11-20 18:00:00",5,"Sandwiches à prévoir",
                $participants[1],$campus[1],$lieux[3],$etats[4]],
            ["La Cathédrale","2021-04-05 09:00:00",480,"2021-02-01 18:00:00",10,"Dépliant Office du Tourisme",
                $participants[1],$campus[1],$lieux[3],$etats[2]],
            ["Le marché à Niort","2020-11-15 12:00:00",120,"2020-11-10 18:30:00",10,"Couteau et fourchette",
                $participants[2],$campus[2],$lieux[4],$etats[5]]
        ]);

        $this->addParticipants($manager, [
            [ $sorties[1], [$participants[1],$participants[2]] ],
            [ $sorties[2], [$participants[0],$participants[2]] ],
            [ $sorties[3], [$participants[0]] ],
            [ $sorties[4], [$participants[0],$participants[2]] ],
            [ $sorties[5], [$participants[1]] ],
        ]);
    }

    private function loadEtats(ObjectManager $manager, $attributs): array
    {
        for( $i=0; $i<count($attributs); $i++) {
            $etats[] = new Etat();
            $etats[$i]->setLibelle($attributs[$i]);
            $manager->persist($etats[$i]);
        }
        $manager->flush();
        return $etats;
    }

    private function loadCampus(ObjectManager $manager, $attributs): array
    {
        for( $i=0; $i<count($attributs); $i++) {
            $campus[] = new Campus();
            $campus[$i]->setNom($attributs[$i]);
            $manager->persist($campus[$i]);
        }
        $manager->flush();
        return $campus;
    }

    private function loadVilles(ObjectManager $manager, $attributs): array
    {
        for( $i=0; $i<count($attributs); $i++) {
            $villes[] = new Ville();
            $villes[$i]->setNom($attributs[$i][0]);
            $villes[$i]->setCodePostal($attributs[$i][1]);
            $manager->persist($villes[$i]);
        }
        $manager->flush();
        return $villes;
    }

    private function loadLieux(ObjectManager $manager, $attributs): array
    {
        for( $i=0; $i<count($attributs); $i++) {
            $lieux[] = new Lieu();
            $lieux[$i]->setNom($attributs[$i][0]);
            $lieux[$i]->setRue($attributs[$i][1]);
            $lieux[$i]->setLatitude($attributs[$i][2]);
            $lieux[$i]->setLongitude($attributs[$i][3]);
            $lieux[$i]->setVille($attributs[$i][4]);
            $manager->persist($lieux[$i]);
        }
        $manager->flush();
        return $lieux;
    }

    private function loadParticipants(ObjectManager $manager, $attributs): array
    {
        for( $i=0; $i<count($attributs); $i++) {
            $participants[] = new Participant();
            $participants[$i]
                ->setPseudo($attributs[$i][0])
                ->setPassword($this->encoder->encodePassword($participants[$i], $attributs[$i][1]))
                ->setRoles([$attributs[$i][2]])
                ->setNom($attributs[$i][3])
                ->setPrenom($attributs[$i][4])
                ->setTelephone($attributs[$i][5])
                ->setEmail($attributs[$i][6])
                ->setAdministrateur($attributs[$i][7])
                ->setActif($attributs[$i][8])
                ->setCampus($attributs[$i][9]);
            $manager->persist($participants[$i]);
        }

        $manager->flush();
        return $participants;
    }

    private function loadSorties(ObjectManager $manager, $attributs): array
    {
        for( $i=0; $i<count($attributs); $i++) {
            $sorties[] = new Sortie();
            $sorties[$i]
                ->setNom($attributs[$i][0])
                ->setDateHeureDebut(new \DateTime($attributs[$i][1]))
                ->setDuree($attributs[$i][2])
                ->setDateLimiteInscription(new \DateTime($attributs[$i][3]))
                ->setNbInscriptionsMax($attributs[$i][4])
                ->setInfosSortie($attributs[$i][5])
                ->setOrganisateur($attributs[$i][6])
                ->setCampus($attributs[$i][7])
                ->setLieu($attributs[$i][8])
                ->setEtat($attributs[$i][9]);
            $manager->persist($sorties[$i]);
        }
        $manager->flush();
        return $sorties;
    }

    private function addParticipants(ObjectManager $manager, $attributs)
    {
        for( $i=0; $i<count($attributs); $i++) {

            $sortie = $attributs[$i][0];
            $participants = $attributs[$i][1];

            for( $j=0; $j<count($participants); $j++) {
                $sortie->addParticipant($participants[$j]);
            }

            $manager->persist($sortie);
        }
        $manager->flush();
    }

}
