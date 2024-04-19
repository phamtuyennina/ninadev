<?php
namespace NINA\Database\Traits\Eloquent;
use NINA\Database\Paginator;
use NINA\Database\LengthAwarePaginator;
trait Pagination
{
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        $total = func_num_args() === 5 ? value(func_get_arg(4)) : $this->toBase()->getCountForPagination();
        $perPage = $perPage ?: $this->model->getPerPage();
        $page = $page ?: Paginator::resolveCurrentPage();
        $results = $total
            ? $this->forPage($page, $perPage)->get($columns)
            : $this->model->newCollection();

        return $this->makeRequest(
            $results,
            $total,
            $perPage,
            $page,
            ['path'=>getCurrentPath(),'pageName'=>$pageName]
        );
    }
    public function getSkip(int $currentPage, int $perPage): int
    {
        return (int) $currentPage == 1 ? 0 : ($currentPage - 1) * $perPage;
    }
    private function makeRequest($items,int $total, int $perPage, int $page, $options,$columns = ['*']): LengthAwarePaginator
    {
        return new LengthAwarePaginator($items,$total,$perPage,$page,$options);
    }
}
