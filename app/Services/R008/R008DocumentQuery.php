<?php

namespace App\Services\R008;

use App\Models\R008Document;
use App\Models\ReceiveGoodDocument;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class R008DocumentQuery
{
    public function build(array $filters = []): Builder
    {
        $query = R008Document::query()
            ->with(['r008_files', 'branch'])
            ->addSelect([
                'invoice_no' => ReceiveGoodDocument::query()
                    ->select('delivery_note')
                    ->whereHas('receive_good_files', function (Builder $query) {
                        $query->whereColumn('receive_good_files.file', 'r008_documents.rg_no');
                    })
                    ->limit(1),
            ]);

        if (filled($filters['form_doc_no'] ?? null)) {
            $documentNumber = $filters['form_doc_no'];

            $query->where(function (Builder $query) use ($documentNumber) {
                $query->where('rg_no', 'like', '%' . $documentNumber . '%')
                    ->orWhereHas('r008_files', function (Builder $query) use ($documentNumber) {
                        $query->where('file', 'like', '%' . $documentNumber . '%');
                    });
            });
        }

        if (filled($filters['branch_id'] ?? null)) {
            $query->where('branch_id', $filters['branch_id']);
        }

        $user = auth()->user();
        $adminRoleId = Role::query()->where('name', 'admin')->value('id');
        if ($user->role != $adminRoleId && !isDCUser()) {
            $branchIds = $user->user_branches
                ->pluck('branch_id')
                ->push($user->branch_id)
                ->filter()
                ->unique();

            $query->whereIn('branch_id', $branchIds);
        }

        $hasFilter = collect($filters)->contains(fn ($value) => filled($value));

        if (filled($filters['start_date'] ?? null) || filled($filters['end_date'] ?? null)) {
            $startDate = filled($filters['start_date'] ?? null)
                ? Carbon::parse($filters['start_date'])->startOfDay()
                : Carbon::createFromTimestamp(0)->startOfDay();
            $endDate = filled($filters['end_date'] ?? null)
                ? Carbon::parse($filters['end_date'])->endOfDay()
                : Carbon::today()->endOfDay();

            $query->whereBetween('document_date', [$startDate, $endDate]);
        } elseif (!$hasFilter) {
            $query->whereDate('document_date', '>=', now()->subMonth());
        }

        return $query;
    }
}
