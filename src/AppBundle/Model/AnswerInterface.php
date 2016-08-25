<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/08/2016
 * Time: 16:54
 */

namespace AppBundle\Model;


interface AnswerInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getEntitled();

    /**
     * @param string $entitled
     */
    public function changeEntitled($entitled);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $value
     */
    public function setValue($value);
}