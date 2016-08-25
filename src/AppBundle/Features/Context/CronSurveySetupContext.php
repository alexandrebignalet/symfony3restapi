<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\CronSurvey;
use AppBundle\Factory\CronSurveyFactoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\SnippetAcceptingContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class CronSurveySetupContext implements Context, SnippetAcceptingContext
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var CronSurveyFactoryInterface
     */
    protected $cronSurveyFactory;

    /**
     * CronSurveyContext constructor.
     * @param EntityManagerInterface $em
     * @param CronSurveyFactoryInterface $cronSurveyFactory
     */
    public function __construct(EntityManagerInterface $em, CronSurveyFactoryInterface $cronSurveyFactory)
    {
        $this->em = $em;
        $this->cronSurveyFactory = $cronSurveyFactory;
    }

    /**
     * @Given there are Cron Survey with the following details:
     * @param TableNode $cronSurveys
     */
    public function thereAreCronSurveyWithTheFollowingDetails(TableNode $cronSurveys)
    {

        foreach ($cronSurveys->getColumnsHash() as $key => $val) {

            $survey = $this->em->getRepository('AppBundle:Survey')->find($val['survey']);

            $date = null;
            if($val['date'] !== "null") {
                $date = new \DateTime($val['date']);
            }

            $hourMin = new \DateTime($val['hourMin']);
            $hourMax = new \DateTime($val['hourMax']);

            $cronSurvey = $this->cronSurveyFactory->create($survey, $date, $hourMin, $hourMax, $val['everyday']);

            $this->em->persist($cronSurvey);
            $this->em->flush();

            $this->fixIdForCronSurveySurveyedAndDated($val['uid'], $val['survey'], $val['date']);
        }
        $this->em->flush();
    }

    private function fixIdForCronSurveySurveyedAndDated($id, $surveyId, $date)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->update('AppBundle:CronSurvey', 'cs')
            ->set('cs.id', $qb->expr()->literal($id))
            ->where('cs.survey = :surveyId');
        if($date !== 'null'){
            $qb->andWhere('cs.date = :date')
               ->setParameters([
                   'surveyId' => $surveyId,
                   'date' => $date,
               ]);
        } else{
            $qb->setParameters([
                'surveyId' => $surveyId,
            ]);
        }

        $query = $qb->getQuery();

        $query->execute();
    }
}