<?php

namespace AppBundle\Features\Context;

use AppBundle\Factory\AnswerResultFactoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\ORM\EntityManagerInterface;

class AnswerResultSetupContext implements Context, SnippetAcceptingContext
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var AnswerResultFactoryInterface
     */
    protected $answerResultFactory;

    /**
     * AnswerResultContext constructor.
     * @param EntityManagerInterface $em
     * @param AnswerResultFactoryInterface $answerResultFactory
     */
    public function __construct(EntityManagerInterface $em, AnswerResultFactoryInterface $answerResultFactory)
    {
        $this->em = $em;
        $this->answerResultFactory = $answerResultFactory;
    }

    /**
     * @Given there are Answer Results with the following details:
     * @param TableNode $answerResults
     */
    public function thereAreAnswerResultsWithTheFollowingDetails(TableNode $answerResults)
    {

        foreach ($answerResults->getColumnsHash() as $key => $val) {

            $question = $this->em->getRepository('AppBundle:Question')->find($val['question']);

            $answer = $this->em->getRepository('AppBundle:Answer')->find($val['answer']);

            $answerResult = $this->answerResultFactory->create($question, $answer);

            $this->em->persist($answerResult);

            $this->em->flush();

            $this->fixIdForAnswerResultByQuestionAndAnswer($val['uid'], $val['question'], $val['answer']);
        }
        $this->em->flush();
    }

    private function fixIdForAnswerResultByQuestionAndAnswer($id, $questionId, $answerId)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->update('AppBundle:AnswerResult', 'ar')
            ->set('ar.id', $qb->expr()->literal($id))
            ->where('ar.answer = :answerId')
            ->andWhere('ar.question = :questionId')
            ->setParameters([
                'answerId'      => $answerId,
                'questionId'    => $questionId
            ]);

        $query = $qb->getQuery();

        $query->execute();
    }
}