<?php

// src/AppBundle/Entity/Repository/AnswerEntityRepository.php

namespace AppBundle\Entity\Repository;


use AppBundle\Model\QuestionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class AnswerEntityRepository extends EntityRepository
{
    /**
     * @param   QuestionInterface       $question
     * @return ArrayCollection
     */
    public function findAllForQuestion(QuestionInterface $question)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('an')
            ->from('AppBundle\Entity\Answer', 'an')
            ->join('an.questions', 'q')
            ->where('q.id = :questionId')
            ->setParameter('questionId', $question->getId())
            ->getQuery();

        return new ArrayCollection(
            $query->getResult()
        );
    }
}