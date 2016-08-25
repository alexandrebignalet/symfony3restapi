<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 11/08/2016
 * Time: 09:22
 */

namespace AppBundle\Handler;


use AppBundle\DataTransformer\CronSurveyDataTransformer;
use AppBundle\DTO\CronSurveyDTO;
use AppBundle\Factory\CronSurveyFactoryInterface;
use AppBundle\Form\Handler\FormHandlerInterface;
use AppBundle\Model\CronSurveyInterface;
use AppBundle\Repository\CronSurveyRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CronSurveyHandler implements HandlerInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $formHandler;


    /**
     * @var CronSurveyRepositoryInterface
     */
    private $repository;

    /**
     * @var CronSurveyFactoryInterface
     */
    private $factory;

    /**
     * @var CronSurveyDataTransformer
     */
    private $dataTransformer;


    public function __construct(
        FormHandlerInterface $formHandler,
        CronSurveyRepositoryInterface $cronSurveyRepository,
        CronSurveyFactoryInterface $cronSurveyFactory,
        CronSurveyDataTransformer $dataTransformer
    )
    {
        $this->formHandler = $formHandler;
        $this->repository = $cronSurveyRepository;
        $this->factory = $cronSurveyFactory;
        $this->dataTransformer = $dataTransformer;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        if ($id === null) {
            throw new BadRequestHttpException('An cronSurvey ID was not specified.');
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
        $cronSurveyDTO = $this->formHandler->handle(
            new CronSurveyDTO(),
            $parameters,
            Request::METHOD_POST,
            $options
        );

        $cronSurvey = $this->factory->createFromDTO($cronSurveyDTO);

        $this->repository->save($cronSurvey);

        return $cronSurvey;
    }

    /**
     * @param CronSurveyInterface $cronSurvey
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function put($cronSurvey, array $parameters, array $options = [])
    {
        $this->guardCronSurveyImplementsInterface($cronSurvey);

        $cronSurveyDTO = $this->dataTransformer->convertToDTO($cronSurvey);

        $cronSurveyDTO = $this->formHandler->handle(
            $cronSurveyDTO,
            $parameters,
            Request::METHOD_PUT,
            $options
        );

        $this->repository->refresh($cronSurvey);

        $cronSurvey = $this->dataTransformer->updateFromDTO($cronSurvey, $cronSurveyDTO);

        $this->repository->save($cronSurvey);

        return $cronSurvey;
    }

    /**
     * @param CronSurveyInterface $cronSurvey
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function patch($cronSurvey, array $parameters, array $options = [])
    {
        $this->guardCronSurveyImplementsInterface($cronSurvey);

        $cronSurveyDTO = $this->dataTransformer->convertToDTO($cronSurvey);

        $cronSurveyDTO = $this->formHandler->handle(
            $cronSurveyDTO,
            $parameters,
            Request::METHOD_PATCH,
            $options
        );

        $this->repository->refresh($cronSurvey);

        $cronSurvey = $this->dataTransformer->updateFromDTO($cronSurvey, $cronSurveyDTO);

        $this->repository->save($cronSurvey);

        return $cronSurvey;
    }

    /**
     * @param mixed $resource
     * @return mixed
     */
    public function delete($resource)
    {
        $this->guardCronSurveyImplementsInterface($resource);

        $this->repository->delete($resource);

        return true;
    }

    /**
     * @param CronSurveyInterface $cronSurvey
     */
    private function guardCronSurveyImplementsInterface($cronSurvey)
    {
        if (!$cronSurvey instanceof CronSurveyInterface) {
            throw new \InvalidArgumentException('Expected passed CronSurvey to implement CronSurveyInterface');
        }
    }
}