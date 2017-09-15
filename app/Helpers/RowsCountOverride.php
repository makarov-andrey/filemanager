<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\RowsCount;

/**
 * Трейт предназначен для наследников \Illuminate\Database\Eloquent\Builder,
 * в частности для \Illuminate\Database\Eloquent\Model (Есть для этого директива phpdoc?)
 * Трейт переопределяет методы для изменения логики подсчета общего количества строк
 * в модели через \App\RowsCount.
 *
 * TODO: сделать возможность статического вызова переопределенных функций
 */
trait RowsCountOverride
{
    use QueryBuilderHelper;

    /**
     * если в запросе существуют фильтры, группировка или джоины, то передает
     * управление родительскому методу \Illuminate\Database\Eloquent\Builder::paginate
     * сам этот метод является почти полной копией последнего, за исключением метода
     * получения общего количества записей. Получает последнее с помощью модели \App\RowsCount
     *
     * @see \Illuminate\Database\Eloquent\Builder::paginate()
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate ($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null) {
        if (!$this->willBeVirginSelect()) {
            return parent::paginate($perPage, $columns, $pageName, $page);
        }

        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $this->getPerPage();


        $results = ($total = $this->count())
            ? $this->forPage($page, $perPage)->get($columns)
            : $this->newCollection();

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    /**
     * @see \Illuminate\Database\Query\Builder::count()
     * @param string $columns
     * @return mixed
     */
    public function count($columns = '*')
    {
        if (!$this->willBeVirginSelect()) {
            return parent::count($columns);
        }

        $filesRowsCounter = RowsCount::find($this->getTable());
        return $filesRowsCounter->count;
    }
}