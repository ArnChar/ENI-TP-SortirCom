<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Form\Model\HomeModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findByHomeModel(HomeModel $model)
    {
        $qb = $this->createQueryBuilder('s');

        $qb ->andWhere('s.dateHeureDebut >= :dateMin')
            ->setParameter('dateMin', $model->getCriterias()->getDateMin());

        $qb ->leftJoin('s.participants', 'p')
            ->addSelect('p');
        $qb ->leftJoin('s.campus', 'c')
            ->addSelect('c');
        $qb ->leftJoin('s.etat', 'e')
            ->addSelect('e');

        $qb ->andWhere('s.campus = :campus')
            ->setParameter('campus', $model->getCriterias()->getCampus());

        if (!is_null($model->getCriterias()->getName()) and !empty($model->getCriterias()->getName())) {
            $qb ->andWhere('s.nom like :name')
                ->setParameter('name', '%'.$model->getCriterias()->getName().'%');
        }

        if(!is_null($model->getCriterias()->getDateFrom())) {
            $qb ->andWhere('s.dateHeureDebut >= :dateFrom')
                ->setParameter('dateFrom', $model->getCriterias()->getDateFrom());
        }

        if(!is_null($model->getCriterias()->getDateTo())) {
            $qb ->andWhere('s.dateHeureDebut <= :dateTo')
                ->setParameter('dateTo', $model->getCriterias()->getDateTo());
        }

        if($model->getCriterias()->isManager()) {
            $qb ->andWhere('s.organisateur = :participant')
                ->setParameter('participant', $model->getParticipant());
        }

        if($model->getCriterias()->isSubscribed() and !$model->getCriterias()->isNotSubscribed()) {
            $qb ->andWhere(':participant MEMBER OF s.participants')
                ->setParameter('participant', $model->getParticipant());
        }

        if(!$model->getCriterias()->isSubscribed() and $model->getCriterias()->isNotSubscribed()) {
            $qb ->andWhere(':participant NOT MEMBER OF s.participants')
                ->setParameter('participant', $model->getParticipant());
        }

        if($model->getCriterias()->isOutdated()) {
            $qb ->andWhere('s.dateLimiteInscription < :now')
                ->setParameter('now', $model->getNow());
        }

        return $qb
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
        ;
    }

}
