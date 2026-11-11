<?php

namespace App\Services\Ums;

use App\Models\User;
use Pro1\UmsIntegration\Contracts\UserAccessChecker;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReceiveGoodsUserAccessChecker implements UserAccessChecker
{
    public function checkAccess(string $empCode): array
    {
        $users = User::query()
            ->where('employee_code', $empCode)
            ->limit(2)
            ->get(['id', 'employee_code', 'status']);

        if ($users->count() > 1) {
            throw new HttpException(
                409,
                'Duplicate employee accounts found.'
            );
        }

        $user = $users->first();

        if ($user === null) {
            return [
                'emp_code' => $empCode,
                'account_status' => 'not_found',
                'has_access' => false,
            ];
        }

        // Unexpected values must not be reported as inactive.
        if (!in_array($user->status, [0, 1, '0', '1'], true)) {
            throw new HttpException(
                409,
                'Unexpected account status.'
            );
        }

        $hasAccess = (int) $user->status === 1;

        return [
            'emp_code' => $empCode,
            'account_status' => $hasAccess ? 'active' : 'inactive',
            'has_access' => $hasAccess,
        ];
    }
}
