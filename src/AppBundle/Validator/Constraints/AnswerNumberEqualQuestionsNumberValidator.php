<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Model\SurveyResultInterface;
use AppBundle\Repository\QuestionRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class AnswerNumberEqualQuestionsNumberValidator extends ConstraintValidator
{
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;

    public function __construct(QuestionRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param SurveyResultInterface $surveyResultDTO The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($surveyResultDTO, Constraint $constraint)
    {
        if( sizeof($surveyResultDTO->getSurvey()->getQuestions()) !== sizeof($surveyResultDTO->getAnswerResult()) )
        {
            $this->context->addViolation($constraint->message_number);
        }

        $surveyQuestionIds = [];

        foreach ( $surveyResultDTO->getSurvey()->getQuestions() as $question )
        {
            $surveyQuestionIds[] = $question->getId();
        }

        $paramQuestionIds = [];

        foreach ( $surveyResultDTO->getAnswerResult() as $answerResult )
        {
            if( array_search($answerResult['question'], $surveyQuestionIds) === false )
            {
                $this->context->addViolation($constraint->questionValidationErrorMessage($answerResult['question']));
            }

            $answerIds = $this->findAnswersToQuestion($answerResult['question']);

            if( array_search($answerResult['answer'], $answerIds) === false ) {

                $this->context->addViolation($constraint->answerValidationErrorMessage($answerResult['question'], $answerResult['answer']));
            }

            if( array_search($answerResult['question'], $paramQuestionIds) !== false )
            {
                $this->context->addViolation($constraint->multipleAnswersToASameQuestionValidationErrorMessage($answerResult['question']));
            }
            $paramQuestionIds[] = $answerResult['question'];
        }
    }

    /**
     * @param integer $questionId
     * @return array
     */
    private function findAnswersToQuestion($questionId)
    {
        $answerIds = [];

        $question = $this->questionRepository->findOneById($questionId);

        foreach ($question->getAnswers() as $answer) {
            $answerIds[] = $answer->getId();
        }

        return $answerIds;
    }
}