<?php


namespace App\Form\Model;


use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Persistence\ManagerRegistry;

class HomeModel
{
    private $now;

    private $participant;

    private HomeCriteriaModel $criterias;

    private array $rows = [];

    // SERVICES

    public function getRow(int $i) {
        return $this->rows[$i];
    }

    public function setRow(int $i, HomeRowModel $row) {
        return $this->rows[$i] = $row;
    }

    public function init(Participant $user) {
        $this->setNow(new \DateTime());
        $this->setParticipant($user);

        $this->setCriterias(new HomeCriteriaModel());
        $this->getCriterias()->setCampus($user->getCampus());

        $firstOfMonth = $this->getNow()->format('Y-m-01');
        $dateMin = new \DateTime($firstOfMonth);
        $dateMin->modify('-6 month');  // au lieu de 1 mois
        $this->getCriterias()->setDateMin($dateMin);
        $this->getCriterias()->setDateFrom($dateMin);
        $dateTo = new \DateTime($firstOfMonth);
        $dateTo->modify('+7 month')->modify("-1 second");
        $this->getCriterias()->setDateTo($dateTo);
    }

    public function loadRows(Participant $user, ManagerRegistry $doctrine): void
    {
        $resultset = $doctrine->getRepository(Sortie::class)->findByHomeModel($this);

        $this->setRows([]);
        for($i=0; $i<count($resultset); $i++) {

            $row = new HomeRowModel();
            $row->load($resultset[$i], $user);

            $this->setRow($i,$row);
        }
    }

    // GENERATED

    /**
     * @return mixed
     */
    public function getNow()
    {
        return $this->now;
    }

    /**
     * @param mixed $now
     */
    public function setNow($now): void
    {
        $this->now = $now;
    }

    /**
     * @return mixed
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * @param mixed $participant
     */
    public function setParticipant($participant): void
    {
        $this->participant = $participant;
    }

    /**
     * @return HomeCriteriaModel
     */
    public function getCriterias(): HomeCriteriaModel
    {
        return $this->criterias;
    }

    /**
     * @param HomeCriteriaModel $criterias
     */
    public function setCriterias(HomeCriteriaModel $criterias): void
    {
        $this->criterias = $criterias;
    }

    /**
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @param array $rows
     */
    public function setRows(array $rows): void
    {
        $this->rows = $rows;
    }

}