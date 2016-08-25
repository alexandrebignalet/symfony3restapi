<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 15:58
 */

namespace AppBundle\Factory;


use AppBundle\DTO\SurveyDTO;
use AppBundle\Entity\Survey;

class SurveyFactory implements SurveyFactoryInterface
{
    /**
     * @param  string       $surveyTitle
     * @return Survey
     */
    public function create($surveyTitle)
    {
        return new Survey($surveyTitle);
    }

    /**
     * @param  SurveyDTO   $surveyDTO
     * @return Survey
     */
    public function createFromDTO(SurveyDTO $surveyDTO){

        $survey = self::create($surveyDTO->getTitle());

        foreach ($surveyDTO->getQuestions()as $question){
            $survey->addQuestion($question);
        }

        return $survey;
    }
}