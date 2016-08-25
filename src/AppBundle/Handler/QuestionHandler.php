<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 11/08/2016
 * Time: 09:22
 */

namespace AppBundle\Handler;


use AppBundle\DataTransformer\QuestionDataTransformer;
use AppBundle\DTO\QuestionDTO;
use AppBundle\Factory\QuestionFactoryInterface;
use AppBundle\Form\Handler\FormHandlerInterface;
use AppBundle\Model\QuestionInterface;
use AppBundle\Repository\QuestionRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QuestionHandler implements HandlerInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $formHandler;


    /**
     * @var QuestionRepositoryInterface
     */
    private $repository;

    /**
     * @var QuestionFactoryInterface
     */
    private $factory;

    /**
     * @var QuestionDataTransformer
     */
    private $questionDataTransformer;

    public function __construct(
        FormHandlerInterface $formHandler,
        QuestionRepositoryInterface $questionRepository,
        QuestionFactoryInterface $questionFactory,
        QuestionDataTransformer $questionDataTransformer
    )
    {
        $this->formHandler = $formHandler;
        $this->repository = $questionRepository;
        $this->factory = $questionFactory;
        $this->questionDataTransformer = $questionDataTransformer;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        if ($id === null) {
            throw new BadRequestHttpException('An question ID was not specified.');
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
        $questionDTO = $this->formHandler->handle(
            new QuestionDTO(),
            $parameters,
            Request::METHOD_POST,
            $options
        );

        $question = $this->factory->createFromDTO($questionDTO);

        $this->repository->save($question);

        return $question;
    }

    /**
     * @param QuestionInterface $question
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function put($question, array $parameters, array $options = [])
    {
        $this->guardAccountImplementsInterface($question);

        /** @var QuestionInterface $question */
        $questionDTO = $this->questionDataTransformer->convertToDTO($question);

        $questionDTO = $this->formHandler->handle(
            $questionDTO,
            $parameters,
            Request::METHOD_PUT,
            $options
        );

        $this->repository->refresh($question);

        $question = $this->questionDataTransformer->updateFromDTO($question, $questionDTO);

        $this->repository->save($question);

        return $question;
    }

    /**
     * @param QuestionInterface $question
     * @param array $parameters
     * @param array $options
     * @return mixed
     */
    public function patch($question, array $parameters, array $options = [])
    {
        $this->guardAccountImplementsInterface($question);

        /** @var QuestionInterface $question */
        $questionDTO = $this->questionDataTransformer->convertToDTO($question);

        $questionDTO = $this->formHandler->handle(
            $questionDTO,
            $parameters,
            Request::METHOD_PATCH,
            $options
        );

        $this->repository->refresh($question);

        $question = $this->questionDataTransformer->updateFromDTO($question, $questionDTO);

        $this->repository->save($question);

        return $question;
    }

    /**
     * @param mixed $resource
     * @return mixed
     */
    public function delete($resource)
    {
        $this->guardAccountImplementsInterface($resource);

        $this->repository->delete($resource);

        return true;
    }

    /**
     * @param QuestionInterface $question
     */
    private function guardAccountImplementsInterface($question)
    {
        if (!$question instanceof QuestionInterface) {
            throw new \InvalidArgumentException('Expected passed Account to implement QuestionInterface');
        }
    }
}