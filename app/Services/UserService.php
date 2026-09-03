<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserService
{
     /**
     * Get active HRH (Human Resources for Health) personnel options for selection dropdowns.
     *
     * @param int $accessLevel User access level (1 = Regional/Superadmin)
     * @param int|null $provinceId
     * @return Collection<int, array{id: int, name: string}>
     */
  public function getHrhSelection(int $accessLevel, ?int $provinceId = null): Collection
  {
      return User::query()
          ->select([
              'id',
              'name',
          ])
        //   ->where('active', 1)
          ->where('access_level', 2)
          ->when($accessLevel !== 1 && $provinceId !== null, function ($query) use ($provinceId) {
              $query->where('province_id', $provinceId);
          })
          ->orderBy('name')
          ->get();
  }

}
