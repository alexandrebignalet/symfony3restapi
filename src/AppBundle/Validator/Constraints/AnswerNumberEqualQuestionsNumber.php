<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class AnswerNumberEqualQuestionsNumber extends Constraint
{
    public $message_number = 'Vous devez donner une reponse a chacune des questions.';

    public $message_hack_answer = 'Cette reponse n\'est pas disponible pour cette question';

    public $message_hack_question = 'Cette question n\'est pas disponible pour cette reponse';

    public function validatedBy()
    {
        return 'survey_result_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function questionValidationErrorMessage($question)
    {
        return 'La question '.$question.' n\'est pas disponible pour ce sondage.';
    }

    public function answerValidationErrorMessage($question, $answer)
    {
        return 'La reponse '.$answer.' n\'est pas disponible pour la question '.$question.'.';
    }

    public function multipleAnswersToASameQuestionValidationErrorMessage($question)
    {
        return 'Veuillez repondre une seule fois aux questions. '.$question.' have multiple answers.';
    }
}