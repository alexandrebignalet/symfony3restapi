<?php

// src/AppBundle/Entity/Repository/SurveyEntityRepository.php

namespace AppBundle\Entity\Repository;

use AppBundle\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class SurveyEntityRepository extends EntityRepository
{

    public function fetchAllSurveys(){

        $qb = $this->createQueryBuilder('s');

        $query = $qb->select('s.id, s.title, q.id, q.entitled, a.id, a.entitled, a.value')
            ->leftJoin('s.questions', 'q')
            ->leftJoin('q.answers','a')
            ->getQuery()
        ;

        $surveys = $query->getResult();

        return $surveys;
    }
}