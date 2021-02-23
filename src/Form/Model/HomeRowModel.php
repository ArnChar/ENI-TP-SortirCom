<?php


namespace App\Form\Model;


use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;


class HomeRowModel
{
    const SHOW = 'show';
    const MODIFY = 'modify';
    const PUBLISH = 'publish';
    const CANCEL = 'cancel';
    const SUBSCRIBE = 'subscribe';
    const WITHDRAW = 'withdraw';

    public function getCANCEL(): string { return self::CANCEL; }

    private int $id;

    private string $name;

    private \DateTimeInterface $date;

    private \DateTimeInterface $closure;

    private string $vacancy;

    private Etat $status;

    private bool $subscribed;

    private Participant $manager;

    private array $actions = [];


    // SERVICES

    public function load(Sortie $sortie, Participant $user) {
        $this->setId($sortie->getId());
        $this->setName($sortie->getNom());
        $this->setDate($sortie->getDateHeureDebut());
        $this->setClosure($sortie->getDateLimiteInscription());
        $this->setVacancy(count($sortie->getParticipants()).'/'.$sortie->getNbInscriptionsMax());
        $this->setStatus($sortie->getEtat());
        $this->setSubscribed($this->defineSubscribed($user, $sortie));
        $this->setManager($sortie->getOrganisateur());

        $this->defineActions($user, $sortie);
    }

    private function defineSubscribed(Participant $user, Sortie $sortie): bool
    {
        foreach ($sortie->getParticipants() as $inscrit) {
            if($user->getId() === $inscrit->getId()) {
                return true;
            }
        }
        return false;
    }

    private function defineActions(Participant $user, Sortie $sortie)
    {
        // Status : "Créée", "Ouverte", "Clôturée", "Activité en cours", "Passée", "Annulée"
        $actions = [];
        // Si le user est l'organisateur
        if( $user->getId() == $sortie->getOrganisateur()->getId() ) {
            if( $sortie->getEtat()->getLibelle() == Etat::CREATED ) {
                $actions[] = self::MODIFY;
                $actions[] = self::PUBLISH;
            } elseif( $sortie->getEtat()->getLibelle() == Etat::OPEN ) {
                $actions[] = self::SHOW;
                $actions = $this->manageSubscription($actions, $sortie);
                $actions[] = self::CANCEL;
            } else {
                $actions[] = self::SHOW;
            }

        } else {
            // Si le user n'est pas l'organisateur
            $actions[] = self::SHOW;
            if( $sortie->getEtat()->getLibelle() == Etat::OPEN ) {
                $actions = $this->manageSubscription($actions, $sortie);
            }
        }
        $this->setActions($actions);
    }

    // Ajoute les actions "S'inscrire" et "Se désister" en fonction des places
    private function manageSubscription($actions, Sortie $sortie): array
    {
        if( $this->isSubscribed() == true ) {
            $actions[] = self::WITHDRAW;
        } elseif( $sortie->getParticipants()->count() < $sortie->getNbInscriptionsMax() ) {
            $actions[] = self::SUBSCRIBE;
        }
        return $actions;
    }

    // GENERATED

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     */
    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getClosure(): \DateTimeInterface
    {
        return $this->closure;
    }

    /**
     * @param \DateTimeInterface $closure
     */
    public function setClosure(\DateTimeInterface $closure): void
    {
        $this->closure = $closure;
    }

    /**
     * @return string
     */
    public function getVacancy(): string
    {
        return $this->vacancy;
    }

    /**
     * @param string $vacancy
     */
    public function setVacancy(string $vacancy): void
    {
        $this->vacancy = $vacancy;
    }

    /**
     * @return Etat
     */
    public function getStatus(): Etat
    {
        return $this->status;
    }

    /**
     * @param Etat $status
     */
    public function setStatus(Etat $status): void
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isSubscribed(): bool
    {
        return $this->subscribed;
    }

    /**
     * @param bool $subscribed
     */
    public function setSubscribed(bool $subscribed): void
    {
        $this->subscribed = $subscribed;
    }

    /**
     * @return Participant
     */
    public function getManager(): Participant
    {
        return $this->manager;
    }

    /**
     * @param Participant $manager
     */
    public function setManager(Participant $manager): void
    {
        $this->manager = $manager;
    }

    /**
     * @return array
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param array $actions
     */
    public function setActions(array $actions): void
    {
        $this->actions = $actions;
    }
}