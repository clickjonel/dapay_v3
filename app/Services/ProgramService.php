<?php

namespace App\Services;

use App\Models\Program;
use Illuminate\Support\Collection;

class ProgramService
{
    /**
     * Get active programs for dropdown selection.
     * 
     * @return Collection<int, array{id: int, name: string}>
     */
    public function getProgramSelection(): Collection
    {
        return Program::query()
            ->select(['id', 'name']) 
            ->where('active', 1)
            ->latest('id')
            ->get();
    }
}
