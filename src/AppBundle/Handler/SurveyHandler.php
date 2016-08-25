<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 11/08/2016
 * Time: 09:22
 */

namespace AppBundle\Handler;


use AppBundle\DataTransformer\SurveyDataTransformer;
use AppBundle\DTO\SurveyDTO;
use AppBundle\Factory\SurveyFactoryInterface;
use AppBundle\Form\Handler\FormHandlerInterface;
use AppBundle\Model\SurveyInterface;
use AppBundle\Repository\SurveyRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SurveyHandler implements HandlerInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $formHandler;


    /**
     * @var SurveyRepositoryInterface
     */
    private $repository;

    /**
     * @var SurveyFactoryInterface
     */
    private $factory;

    /**
     * @var SurveyDataTransformer
     */
    private $dataTransformer;

    public function __construct(
        FormHandlerInterface $formHandler,
        SurveyRepositoryInterface $surveyRepository,
        SurveyFactoryInterface $surveyFactory,
        SurveyDataTransformer $surveyDataTransformer
    )
    {
        $this->formHandler = $formHandler;
        $this->repository = $surveyRepository;
        $this->factory = $surveyFactory;
        $this->dataTransformer = $surveyDataTransformer;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        if ($id === null) {
            throw new BadRequestHttpException('An survey ID was not specified.');
        }

        return $this->repository->findOneById($id);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return mixed
     */
    public function all($limit, $offset)
    {
        return $this->repository->findBy($limit, $offset);
    }

    /**
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function post(array $parameters, array $options = [])
    {
        $surveyDTO = $this->formHandler->handle(
            new SurveyDTO(),
            $parameters,
            Request::METHOD_POST,
            $options
        );

        $survey = $this->factory->createFromDTO($surveyDTO);

        $this->repository->save($survey);

        return $survey;
    }

    /**
     * @param SurveyInterface $survey
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function put($survey, array $parameters, array $options = [])
    {
        $this->guardSurveyImplementsInterface($survey);

        /** @var SurveyInterface $survey */
        $surveyDTO = $this->dataTransformer->convertToDTO($survey);

        $surveyDTO = $this->formHandler->handle(
            $surveyDTO,
            $parameters,
            Request::METHOD_PUT,
            $options
        );

        $this->repository->refresh($survey);

        $survey = $this->dataTransformer->updateFromDTO($survey, $surveyDTO);

        $this->repository->save($survey);

        return $survey;
    }

    /**
     * @param SurveyInterface $survey
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function patch($survey, array $parameters, array $options = [])
    {
        $this->guardSurveyImplementsInterface($survey);

        /** @var SurveyInterface $survey */
        $surveyDTO = $this->dataTransformer->convertToDTO($survey);
        
        $surveyDTO = $this->formHandler->handle(
            $surveyDTO,
            $parameters,
            Request::METHOD_PATCH,
            $options
        );

        $this->repository->refresh($survey);

        $survey = $this->dataTransformer->updateFromDTO($survey, $surveyDTO);

        $this->repository->save($survey);

        return $survey;
    }

    /**
     * @param mixed $resource
     * @return mixed
     */
    public function delete($resource)
    {
        $this->guardSurveyImplementsInterface($resource);

        $this->repository->delete($resource);

        return true;
    }

    /**
     * @param SurveyInterface $survey
     */
    private function guardSurveyImplementsInterface($survey)
    {
        if (!$survey instanceof SurveyInterface) {
            throw new \InvalidArgumentException('Expected passed Survey to implement SurveyInterface');
        }
    }
}