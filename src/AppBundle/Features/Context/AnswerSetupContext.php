<?php

namespace AppBundle\Features\Context;

use AppBundle\Factory\AnswerFactoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\ORM\EntityManagerInterface;

class AnswerSetupContext implements Context, SnippetAcceptingContext
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var AnswerFactoryInterface
     */
    protected $answerFactory;

    /**
     * UserContext constructor.
     * @param EntityManagerInterface $em
     * @param AnswerFactoryInterface $answerFactory
     */
    public function __construct(EntityManagerInterface $em, AnswerFactoryInterface $answerFactory)
    {
        $this->em = $em;
        $this->answerFactory = $answerFactory;
    }

    /**
     * @Given there are Answers with the following details:
     * @param TableNode $answers
     */
    public function thereAreAnswersWithTheFollowingDetails(TableNode $answers)
    {

        foreach ($answers->getColumnsHash() as $key => $val) {

            $answer = $this->answerFactory->create($val['entitled'], $val['value']);

            $this->em->persist($answer);
            $this->em->flush();

            $this->fixIdForAnswerEntitledAndWithValue($val['uid'], $val['entitled'], $val['value']);
        }
        $this->em->flush();
    }

    private function fixIdForAnswerEntitledAndWithValue($id, $answerEntitled, $answerValue)
    {
        $qb = $this->em->createQueryBuilder();

        $query = $qb->update('AppBundle:Answer', 'an')
            ->set('an.id', $qb->expr()->literal($id))
            ->where('an.entitled = :answerEntitled')
            ->andWhere('an.value = :answerValue')
            ->setParameters([
                'answerEntitled' => $answerEntitled,
                'answerValue' => $answerValue
            ])
            ->getQuery()
        ;

        $query->execute();
    }
}