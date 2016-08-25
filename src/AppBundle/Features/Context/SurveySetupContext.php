<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Survey;
use AppBundle\Factory\SurveyFactoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\Restricted\RestrictedQuestionRepository;

class SurveySetupContext implements Context, SnippetAcceptingContext
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var SurveyFactoryInterface
     */
    protected $surveyFactory;

    /**
     * SurveyContext constructor.
     * @param EntityManagerInterface $em
     * @param SurveyFactoryInterface $surveyFactory
     */
    public function __construct(EntityManagerInterface $em, SurveyFactoryInterface $surveyFactory)
    {
        $this->em = $em;
        $this->surveyFactory = $surveyFactory;
    }

    /**
     * @Given there are Surveys with the following details:
     * @param TableNode $surveys
     */
    public function thereAreSurveysWithTheFollowingDetails(TableNode $surveys)
    {

        foreach ($surveys->getColumnsHash() as $key => $val) {

            $survey = $this->surveyFactory->create($val['title']);

            $this->em->persist($survey);
            $this->em->flush();

            $this->fixIdForSurveyTitled($val['uid'], $val['title']);

            $survey = $this->em->getRepository('AppBundle:Survey')->find($val['uid']);

            $this->addQuestionsToSurvey($val['questions'], $survey);
        }
        $this->em->flush();
    }

    private function fixIdForSurveyTitled($id, $surveyTitle)
    {
        $qb = $this->em->createQueryBuilder();

        $query = $qb->update('AppBundle:Survey', 's')
            ->set('s.id', $qb->expr()->literal($id))
            ->where('s.title = :surveyTitle')
            ->setParameters([
                'surveyTitle' => $surveyTitle,
            ])
            ->getQuery()
        ;

        $query->execute();
    }

    /**
     * @param $questionIds
     * @param Survey $survey
     * @return bool
     */
    private function addQuestionsToSurvey($questionIds, Survey $survey)
    {
        $questionIds = explode(',', $questionIds);

        if (empty($questionIds)) {
            return false;
        }

        foreach ($questionIds as $questionId) {
            /** @var $question \AppBundle\Entity\Question */
            $question = $this->em->getRepository('AppBundle:Question')->find($questionId);

            if (!$question) {
                continue;
            }

            $survey->addQuestion($question);
        }

        $this->em->flush();
    }
}