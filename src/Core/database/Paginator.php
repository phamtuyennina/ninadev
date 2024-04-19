<?php

namespace NINA\Database;

final class Paginator
{
    public static function resolveCurrentPage(): int
    {
        return request()->get('page') ?: 1;
    }
    public static function resolvePagePagination(int $total, int $perPage, string $pageUrl): array
    {
        $lastPage = self::resolveLastPage($total, $perPage);
        $currentPage = self::resolveCurrentPage();
        $firstPage = self::resolveFirstPage();
        $nextPage = self::resolveNextPage($currentPage, $lastPage);
        $prevPage = self::resolvePreviousPage($currentPage, $lastPage);
        return self::buildPagination($lastPage, $firstPage, $nextPage, $prevPage, $currentPage, $pageUrl);
    }
    public static function buildPagination(int $lastPage, int $firstPage, ?int $nextPage, ?int $prevPage, int $currentPage, string $pageUrl): array
    {
        $parseUrl = parse_url($pageUrl);

        $query = isset($parseUrl['query']) ? $parseUrl['query'] : "page={$currentPage}";

        parse_str($query, $params);

        foreach ($params as $param => $value) {
            if ($param == 'page') {
                $hasPage = true;
                $params['page'] = $currentPage;
            } else {
                $params[$param] = $value;
            }
            $params = isset($hasPage) ? $params : array_merge($params, ['page' => $currentPage]);

            $pageUrl = explode('?', $pageUrl)[0] . '?' . http_build_query($params);
        }

        $firstPageUrl = str_replace('page=' . $currentPage, "page={$firstPage}", $pageUrl);
        $nextPageUrl = $nextPage === null ? null : str_replace('page=' . $currentPage, "page={$nextPage}", $pageUrl);
        $lastPageUrl = $lastPage == 0 ? null : str_replace('page=' . $currentPage, "page={$lastPage}", $pageUrl);
        $prevPageUrl = $prevPage === null ? null : str_replace('page=' . $currentPage, "page={$prevPage}", $pageUrl);
        return [
            $lastPage,
            $firstPageUrl,
            $lastPageUrl,
            $nextPageUrl,
            $prevPageUrl
        ];
    }
    public static function resolveLastPage(int $total, int $perPage): int
    {
        $lastPage = (string) $total / $perPage;
        $lastPage = explode('.', $lastPage);
        $lastPage = count($lastPage) > 1 ? array_shift($lastPage) + 1 : array_shift($lastPage);
        return $lastPage;
    }
    public static function resolveFirstPage(): int
    {
        return 1;
    }
    public static function resolveNextPage(int $currentPage, int $lastPage): ?int
    {
        return $currentPage + 1 <= $lastPage ? $currentPage + 1 : null;
    }
    public static function resolvePreviousPage(int $currentPage): ?int
    {
        return $currentPage > 1 ? $currentPage - 1 : null;
    }
    public static function resolveFrom(int $currentPage, int $perPage): int
    {
        return (int) ($currentPage - 1) * $perPage + 1;
    }
    public static function resolveTo(int $currentPage, int $perPage, int $total, int $lastPage): int
    {
        if ($currentPage == $lastPage) {
            return $total;
        }
        return (int) ($currentPage - 1) * $perPage + $perPage;
    }
}