<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 11/08/2016
 * Time: 09:22
 */

namespace AppBundle\Handler;


use AppBundle\DataTransformer\SurveyResultDataTransformer;
use AppBundle\DTO\SurveyResultDTO;
use AppBundle\Factory\SurveyResultFactoryInterface;
use AppBundle\Form\Handler\FormHandlerInterface;
use AppBundle\Model\SurveyResultInterface;
use AppBundle\Repository\SurveyResultRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SurveyResultHandler implements HandlerInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $formHandler;


    /**
     * @var SurveyResultRepositoryInterface
     */
    private $repository;

    /**
     * @var SurveyResultFactoryInterface
     */
    private $factory;

    /**
     * @var SurveyResultDataTransformer
     */
    private $dataTransformer;

    public function __construct(
        FormHandlerInterface $formHandler,
        SurveyResultRepositoryInterface $surveyResultRepository,
        SurveyResultFactoryInterface $surveyResultFactory,
        SurveyResultDataTransformer $surveyResultDataTransformer
    )
    {
        $this->formHandler = $formHandler;
        $this->repository = $surveyResultRepository;
        $this->factory = $surveyResultFactory;
        $this->dataTransformer = $surveyResultDataTransformer;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        if ($id === null) {
            throw new BadRequestHttpException('An surveyResult ID was not specified.');
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
        $surveyResultDTO = $this->formHandler->handle(
            new SurveyResultDTO(),
            $parameters,
            Request::METHOD_POST,
            $options
        );

        $surveyResult = $this->factory->createFromDTO($surveyResultDTO);

        $this->repository->save($surveyResult);

        return $surveyResult;
    }

    /**
     * @param SurveyResultInterface $surveyResult
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function put($surveyResult, array $parameters, array $options = [])
    {
        throw new \DomainException('The method is not implemented');
    }

    /**
     * @param SurveyResultInterface $surveyResult
     * @param array $parameters
     * @param array $options+
     * @return mixed
     */
    public function patch($surveyResult, array $parameters, array $options = [])
    {
        throw new \DomainException('The method is not implemented');
    }

    /**
     * @param mixed $resource
     * @return mixed
     */
    public function delete($resource)
    {
        $this->guardSurveyResultImplementsInterface($resource);

        $this->repository->delete($resource);

        return true;
    }

    /**
     * @param SurveyResultInterface $surveyResult
     */
    private function guardSurveyResultImplementsInterface($surveyResult)
    {
        if (!$surveyResult instanceof SurveyResultInterface) {
            throw new \InvalidArgumentException('Expected passed SurveyResult to implement SurveyResultInterface');
        }
    }
}