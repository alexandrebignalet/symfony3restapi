<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 29/07/2016
 * Time: 14:44
 */

namespace AppBundle\Form\Handler;


interface FormHandlerInterface
{
    /**
     * @param mixed     $object
     * @param array     $parameters
     * @param string    $method
     * @param array     $options
     * @return mixed
     */
    public function handle($object, array $parameters, $method, array $options);
}