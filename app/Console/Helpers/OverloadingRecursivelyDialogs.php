<?php

namespace App\Console\Helpers;

use BadMethodCallException;
use Illuminate\Validation\ValidationException;

/**
 * Трейт добавляет методы для рекурсивного повторения
 * вопросов через консоль, пока пользователь не даст
 * ответ, прошедший валидацию
 */
trait OverloadingRecursivelyDialogs
{
    /**
     * маски для создания методов
     *
     * @var array
     */
    protected $overloadRecursivelyDialogPatterns = [
        '/(ask[a-zA-Z]+)Recursively/',
        '/(askSecret[a-zA-Z]+)Recursively/',
        '/([a-zA-Z]+Dialog)Recursively/',
    ];

    function __call($method, $arguments)
    {
        foreach ($this->overloadRecursivelyDialogPatterns as $pattern) {
            if (preg_match($pattern, $method, $matches)) {
                try {
                    return $this->{$matches[1]}(...$arguments);
                } catch (ValidationException $exception) {
                    $this->CLI()->error($exception->getMessage());
                    return $this->{$method}(...$arguments);
                }
            }
        }

        throw new BadMethodCallException("Method [$method] does not exist.");
    }

    /**
     * @return \Illuminate\Console\Command
     */
    abstract function CLI();
}