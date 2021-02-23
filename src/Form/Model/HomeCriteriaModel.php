<?php


namespace App\Form\Model;


use App\Entity\Campus;
use Doctrine\Persistence\ManagerRegistry;

class HomeCriteriaModel
{
    private $name;

    private $dateMin;

    private $dateFrom;

    private $dateTo;

    private $manager = true;

    private $subscribed = true;

    private $notSubscribed = true;

    private $outdated = false;

    private Campus $campus;


    // SERVICES

    // rafraîchissement des entities internes à partir de leur id
    public function reloadEntities(ManagerRegistry $doctrine): void {
        $this->setCampus(
            $doctrine->getRepository(Campus::class)->find($this->getCampus()->getId())
        );
    }


    // GENERATED

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDateMin()
    {
        return $this->dateMin;
    }

    /**
     * @param mixed $dateMin
     */
    public function setDateMin($dateMin): void
    {
        $this->dateMin = $dateMin;
    }

    /**
     * @return mixed
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @param mixed $dateFrom
     */
    public function setDateFrom($dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * @return mixed
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @param mixed $dateTo
     */
    public function setDateTo($dateTo): void
    {
        $this->dateTo = $dateTo;
    }

    /**
     * @return bool
     */
    public function isManager(): bool
    {
        return $this->manager;
    }

    /**
     * @param bool $manager
     */
    public function setManager(bool $manager): void
    {
        $this->manager = $manager;
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
     * @return bool
     */
    public function isNotSubscribed(): bool
    {
        return $this->notSubscribed;
    }

    /**
     * @param bool $notSubscribed
     */
    public function setNotSubscribed(bool $notSubscribed): void
    {
        $this->notSubscribed = $notSubscribed;
    }

    /**
     * @return bool
     */
    public function isOutdated(): bool
    {
        return $this->outdated;
    }

    /**
     * @param bool $outdated
     */
    public function setOutdated(bool $outdated): void
    {
        $this->outdated = $outdated;
    }

    /**
     * @return Campus
     */
    public function getCampus(): Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus $campus
     */
    public function setCampus(Campus $campus): void
    {
        $this->campus = $campus;
    }

}