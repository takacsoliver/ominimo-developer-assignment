<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationResource extends JsonResource
{
    public function __construct(LengthAwarePaginator $paginator)
    {
        parent::__construct($paginator);
    }

    public function toArray(Request $request): array
    {
        return [
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
            'total' => $this->total(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
            'links' => $this->linkCollection()->map(function ($link) {
                return [
                    'url' => $link['url'],
                    'label' => $this->translatePaginationLabel($link['label']),
                    'active' => $link['active'],
                ];
            })->toArray(),
        ];
    }

    private function translatePaginationLabel(string $label): string
    {
        return match ($label) {
            '&laquo; Previous' => __('pagination.previous'),
            'Next &raquo;' => __('pagination.next'),
            '&laquo;' => __('pagination.previous'),
            '&raquo;' => __('pagination.next'),
            default => $label,
        };
    }
}