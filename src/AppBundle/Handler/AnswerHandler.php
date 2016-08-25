<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 11/08/2016
 * Time: 09:22
 */

namespace AppBundle\Handler;


use AppBundle\DataTransformer\AnswerDataTransformer;
use AppBundle\DTO\AnswerDTO;
use AppBundle\Factory\AnswerFactoryInterface;
use AppBundle\Form\Handler\FormHandlerInterface;
use AppBundle\Model\AnswerInterface;
use AppBundle\Repository\AnswerRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AnswerHandler implements HandlerInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $formHandler;


    /**
     * @var AnswerRepositoryInterface
     */
    private $repository;

    /**
     * @var AnswerFactoryInterface
     */
    private $factory;

    /**
     * @var AnswerDataTransformer
     */
    private $dataTransformer;

    /**
     * AnswerHandler constructor.
     * @param FormHandlerInterface $formHandler
     * @param AnswerRepositoryInterface $answerRepository
     * @param AnswerFactoryInterface $answerFactory
     * @param AnswerDataTransformer $answerDataTransformer
     */
    public function __construct(
        FormHandlerInterface $formHandler,
        AnswerRepositoryInterface $answerRepository,
        AnswerFactoryInterface $answerFactory,
        AnswerDataTransformer $answerDataTransformer
    )
    {
        $this->formHandler = $formHandler;
        $this->repository = $answerRepository;
        $this->factory = $answerFactory;
        $this->dataTransformer = $answerDataTransformer;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        if ($id === null) {
            throw new BadRequestHttpException('An answer ID was not specified.');
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
        $answerDTO = $this->formHandler->handle(
            new AnswerDTO(),
            $parameters,
            Request::METHOD_POST,
            $options
        );

        $answer = $this->factory->createFromDTO($answerDTO);

        $this->repository->save($answer);

        return $answer;
    }

    /**
     * @param AnswerInterface $answer
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function put($answer, array $parameters, array $options = [])
    {
        $this->guardAnswerImplementsInterface($answer);

        /** @var AnswerInterface $answer **/
        $answerDTO = $this->dataTransformer->convertToDTO($answer);

        $answerDTO = $this->formHandler->handle(
            $answerDTO,
            $parameters,
            Request::METHOD_PUT,
            $options
        );

        $this->repository->refresh($answer);

        $answer = $this->dataTransformer->updateFromDTO($answer, $answerDTO);

        $this->repository->save($answer);

        return $answer;
    }

    /**
     * @param AnswerInterface $answer
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function patch($answer, array $parameters, array $options = [])
    {
        $this->guardAnswerImplementsInterface($answer);

        /** @var AnswerInterface $answer **/
        $answerDTO = $this->dataTransformer->convertToDTO($answer);

        $answerDTO = $this->formHandler->handle(
            $answerDTO,
            $parameters,
            Request::METHOD_PATCH,
            $options
        );

        $this->repository->refresh($answer);

        $answer = $this->dataTransformer->updateFromDTO($answer, $answerDTO);

        $this->repository->save($answer);

        return $answer;
    }

    /**
     * @param mixed $resource
     * @return mixed
     */
    public function delete($resource)
    {
        $this->guardAnswerImplementsInterface($resource);

        $this->repository->delete($resource);

        return true;
    }

    /**
     * @param AnswerInterface $answer
     */
    private function guardAnswerImplementsInterface($answer)
    {
        if (!$answer instanceof AnswerInterface) {
            throw new \InvalidArgumentException('Expected passed Answer to implement AnswerInterface');
        }
    }
}