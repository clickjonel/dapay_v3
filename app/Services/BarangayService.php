<?php

namespace App\Services;

use App\Models\Barangay;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BarangayService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }

    /**
     * Get barangay dropdown.
     *
     * @param int $accessLevel User access level (1 = Regional/Superadmin)
     * @param int|null $provinceId
     * @return Collection<int, array{id: int, name: string}>
     */

    public function getBarangaySelection(int $accessLevel, ?int $provinceId = null): Collection
    {
        return Barangay::query()
            ->join('municipalities', 'barangays.municipality_id', '=', 'municipalities.id')
            ->join('provinces', 'municipalities.province_id', '=', 'provinces.id')
            ->when($accessLevel !== 1 && $provinceId !== null, function ($query) use ($provinceId) {
                $query->where('municipalities.province_id', $provinceId);
            })
            ->select([
                'barangays.id',
                DB::raw("CONCAT(barangays.name, ' - ', municipalities.name, ' - ', provinces.name) AS name"),
            ])
            ->orderBy('barangays.name')
            ->get();
    }

}
