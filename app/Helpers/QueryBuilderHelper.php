<?php

namespace App\Helpers;

/**
 * Трейт предназначен для наследников \Illuminate\Database\Eloquent\Builder,
 * в частности для \Illuminate\Database\Eloquent\Model (Есть для этого директива phpdoc?)
 */
trait QueryBuilderHelper
{
    /**
     * возвращает true, если в запросе не существует фильтров, группировок или джоинов
     * @return bool
     */
    public function willBeVirginSelect () {
        $query = $this->getQuery();
        return empty($query->wheres)
            && empty($query->havings)
            && empty($query->groups)
            && empty($query->joins);
    }
}