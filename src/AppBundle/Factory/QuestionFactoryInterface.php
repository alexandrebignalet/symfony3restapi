<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 16:23
 */

namespace AppBundle\Factory;


use AppBundle\DTO\QuestionDTO;

interface QuestionFactoryInterface
{

    /**
     * @param string $entitled
     * @return mixed
     */
    public function create($entitled);

    /**
     * @param QuestionDTO $questionDTO
     * @return mixed
     */
    public function createFromDTO(QuestionDTO $questionDTO);
}