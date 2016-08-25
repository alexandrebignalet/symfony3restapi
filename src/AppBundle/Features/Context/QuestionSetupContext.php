<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Question;
use AppBundle\Factory\QuestionFactoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\ORM\EntityManagerInterface;

class QuestionSetupContext implements Context, SnippetAcceptingContext
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var QuestionFactoryInterface
     */
    protected $questionFactory;

    /**
     * UserContext constructor.
     * @param EntityManagerInterface $em
     * @param QuestionFactoryInterface $questionFactory
     */
    public function __construct(EntityManagerInterface $em, QuestionFactoryInterface $questionFactory)
    {
        $this->em = $em;
        $this->questionFactory = $questionFactory;
    }

    /**
     * @Given there are Questions with the following details:
     * @param TableNode $questions
     */
    public function thereAreQuestionsWithTheFollowingDetails(TableNode $questions)
    {

        foreach ($questions->getColumnsHash() as $key => $val) {

            $question = $this->questionFactory->create($val['entitled']);

            $this->em->persist($question);
            $this->em->flush();

            $this->fixIdForQuestionEntitled($val['uid'], $val['entitled']);

            $question = $this->em->getRepository('AppBundle:Question')->find($val['uid']);

            $this->addAnswersToQuestions($val['answers'], $question);
        }
        $this->em->flush();
    }

    private function fixIdForQuestionEntitled($id, $questionEntitled)
    {
        $qb = $this->em->createQueryBuilder();

        $query = $qb->update('AppBundle:Question', 'q')
            ->set('q.id', $qb->expr()->literal($id))
            ->where('q.entitled = :questionEntitled')
            ->setParameters([
                'questionEntitled' => $questionEntitled,
            ])
            ->getQuery()
        ;

        $query->execute();
    }

    /**
     * @param $answersIds
     * @param Question $question
     * @return bool
     */
    private function addAnswersToQuestions($answersIds, Question $question)
    {
        $answersIds = explode(',', $answersIds);

        if (empty($answersIds)) {
            return false;
        }

        foreach ($answersIds as $answerId) {
            /** @var $answer \AppBundle\Entity\Answer */
            $answer = $this->em->getRepository('AppBundle:Answer')->find($answerId);

            if (!$answer) {
                continue;
            }

            $question->addAnswer($answer);
        }

        $this->em->flush();
    }
}