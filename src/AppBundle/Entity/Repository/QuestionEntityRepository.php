<?php

// src/AppBundle/Entity/Repository/QuestionEntityRepository.php

namespace AppBundle\Entity\Repository;


use AppBundle\Model\SurveyInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class QuestionEntityRepository extends EntityRepository
{
    /**
     * @param   SurveyInterface       $survey
     * @return ArrayCollection
     */
    public function findAllForSurvey(SurveyInterface $survey)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('q')
            ->from('AppBundle\Entity\Question', 'q')
            ->join('q.surveys', 's')
            ->where('s.id = :surveyId')
            ->setParameter('surveyId', $survey->getId())
            ->getQuery();

        return new ArrayCollection(
            $query->getResult()
        );
    }
}