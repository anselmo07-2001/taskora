<?php

namespace App\Support;

class PaginateService
{
    public static function paginate(int $totalItems, int $currentPage = 1, int $limit = 5, int $range = 5): array
    {
        $currentPage = max(1, $currentPage);
        $totalPages = (int) ceil($totalItems / $limit);
        $offset = ($currentPage - 1) * $limit;

        $start = max(1, $currentPage - $range);
        $end = min($totalPages, $currentPage + $range);

        return [
            'limit' => $limit,
            'offset' => $offset,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPaginationPage' => $currentPage,
            'start' => $start,
            'end' => $end,
        ];
    }
}
