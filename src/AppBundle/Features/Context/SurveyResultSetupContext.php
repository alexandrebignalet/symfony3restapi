<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\SurveyResult;
use AppBundle\Factory\SurveyResultFactoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class SurveyResultSetupContext implements Context, SnippetAcceptingContext
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var SurveyResultFactoryInterface
     */
    protected $surveyResultFactory;

    /**
     * SurveyResultContext constructor.
     * @param EntityManagerInterface $em
     * @param SurveyResultFactoryInterface $surveyResultFactory
     */
    public function __construct(EntityManagerInterface $em, SurveyResultFactoryInterface $surveyResultFactory)
    {
        $this->em = $em;
        $this->surveyResultFactory = $surveyResultFactory;
    }

    /**
     * @Given there are Survey Results with the following details:
     * @param TableNode $surveyResults
     */
    public function thereAreSurveyResultsWithTheFollowingDetails(TableNode $surveyResults)
    {

        foreach ($surveyResults->getColumnsHash() as $key => $val) {

            $survey = $this->em->getRepository('AppBundle:Survey')->find($val['survey']);

            $user = $this->em->getRepository('AppBundle:User')->find($val['user']);

            $date = new \DateTime($val['date']);

            $surveyResult = $this->surveyResultFactory->create($user, $survey, $date);

            $surveyResult->setCompleted(true);

            $this->em->persist($surveyResult);
            $this->em->flush();

            $this->fixIdForSurveyResultBySurveyAndUser($val['uid'], $val['survey'], $val['user']);

            $surveyResult = $this->em->getRepository('AppBundle:SurveyResult')->find($val['uid']);

            $this->addAnswerResultsToSurveyResult($val['answerResults'], $surveyResult);
        }
        $this->em->flush();
    }

    private function fixIdForSurveyResultBySurveyAndUser($id, $surveyId, $userId)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->update('AppBundle:SurveyResult', 'sr')
            ->set('sr.id', $qb->expr()->literal($id))
            ->where('sr.survey = :surveyId')
            ->andWhere('sr.user = :userId')
            ->setParameters([
                'surveyId'  => $surveyId,
                'userId'    => $userId,
            ]);

        $query = $qb->getQuery();

        $query->execute();
    }

    /**
     * @param $answerResultIds
     * @param SurveyResult $surveyResult
     * @return bool
     */
    private function addAnswerResultsToSurveyResult($answerResultIds, SurveyResult $surveyResult)
    {
        $answerResultIds = explode(',', $answerResultIds);

        if (empty($answerResultIds)) {
            return false;
        }

        foreach ($answerResultIds as $answerResultId) {
            /** @var $question \AppBundle\Entity\AnswerResult */
            $answerResult = $this->em->getRepository('AppBundle:AnswerResult')->find($answerResultId);

            if (!$answerResult) {
                continue;
            }

            $surveyResult->addAnswerResult($answerResult);
        }

        $this->em->flush();
    }
}