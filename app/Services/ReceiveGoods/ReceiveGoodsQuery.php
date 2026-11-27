<?php

namespace App\Services\ReceiveGoods;

use App\Models\ReceiveGoodDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class ReceiveGoodsQuery
{
    public function build(array $filters = [], ?User $user = null): Builder
    {
        $query = ReceiveGoodDocument::query()
            ->with(['receive_good_files', 'branch', 'vendor']);

        if (filled($filters['form_doc_no'] ?? null)) {
            $documentNumber = $filters['form_doc_no'];

            $query->where(function (Builder $query) use ($documentNumber) {
                $query->where('po_no', 'ilike', '%' . $documentNumber . '%')
                    ->orWhereHas('receive_good_files', function (Builder $query) use ($documentNumber) {
                        $query->where('file', 'ilike', '%' . $documentNumber . '%');
                    })
                    ->orWhere('remark', 'ilike', '%' . $documentNumber . '%');
            });
        }

        if (filled($filters['branch_id'] ?? null)) {
            $query->where('branch_id', $filters['branch_id']);
        }

        $user ??= auth()->user();
        if ($user) {
            $adminRoleId = Role::query()->where('name', 'admin')->value('id');

            if ($user->role != $adminRoleId && !isDCUser()) {
                $branchIds = $user->user_branches
                    ->pluck('branch_id')
                    ->push($user->branch_id)
                    ->filter()
                    ->unique();

                $query->whereIn('branch_id', $branchIds);
            }
        }

        $hasFilter = collect($filters)
            ->except('page')
            ->contains(fn ($value) => filled($value));

        $hasFilter = collect($filters)
            ->except('page')
            ->contains(fn ($value) => filled($value));

        if (filled($filters['start_date'] ?? null) || filled($filters['end_date'] ?? null)) {
            $startDate = filled($filters['start_date'] ?? null)
                ? \Carbon\Carbon::parse($filters['start_date'])->startOfDay()
                : \Carbon\Carbon::createFromTimestamp(0)->startOfDay();
            $endDate = filled($filters['end_date'] ?? null)
                ? \Carbon\Carbon::parse($filters['end_date'])->endOfDay()
                : \Carbon\Carbon::today()->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (filled($filters['back_date_from'] ?? null)) {
            $query->whereDate('date', '>=', $filters['back_date_from']);
        }

        if (filled($filters['back_date_to'] ?? null)) {
            $query->whereDate('date', '<=', $filters['back_date_to']);
        }

        if (!$hasFilter) {
            $query->whereDate('created_at', '>=', now()->subMonth());
        }

        return $query;
    }
}
