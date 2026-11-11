<?php

namespace App\Services\Ums;

use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Pro1\UmsIntegration\Contracts\UserLifecycle;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReceiveGoodsUserLifecycle implements UserLifecycle
{
    public function activate(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $user = $this->findUser($data['emp_code']);
            $created = $user === null;
            $role = $this->uniqueMatch(
                Role::where('name', $data['role_name'])->where('guard_name', 'web'),
                'Role'
            );
            $codes = $data['branch_shortcodes'];

            if (count($codes) !== 1) {
                throw new HttpException(422, 'Exactly one target branch is required.');
            }

            $serverBranch = trim((string) config('ums.branch_code'));

            if ($serverBranch === '') {
                throw new HttpException(503, 'Server branch is not configured.');
            }

            if ($codes[0] !== $serverBranch) {
                throw new HttpException(
                    422,
                    'Requested branch does not match this server branch.'
                );
            }

            $branch = $this->uniqueMatch(
                Branch::where('branch_code', $serverBranch),
                'Branch'
            );
            
            if ($created) {
                if (empty($data['employee'])) {
                    throw new HttpException(422, 'Employee information is required for a new user.');
                }
                $employee = $data['employee'];
                $department = $this->uniqueMatch(
                    Department::where('name', trim($employee['department'])),
                    'Department'
                );
                $user = new User();
                $user->employee_code = $data['emp_code'];
                $user->name = $employee['name'];
                $user->department_id = $department->id;
                $user->password = Hash::make($data['emp_code']);
                // Required legacy column; do not store a plaintext password.
                $user->password_str = '';
            }

            $user->role = $role->id;
            $user->branch_id = $branch->id;
            $user->status = 1;
            $user->save();
            $user->syncRoles([$role]);
            $user->branches()->sync([$branch->id]);

            return [
                'created' => $created,
                'emp_code' => $user->employee_code,
                'status' => 1,
            ];
        });
    }

    public function deactivate(string $empCode): array
    {
        return DB::transaction(function () use ($empCode) {
            $user = $this->findUser($empCode);
            if ($user === null) {
                throw new HttpException(404, 'User not found.');
            }
            $alreadyInactive = $user->status !== null && (int) $user->status === 0;
            if (!$alreadyInactive) {
                $user->status = 0;
                $user->save();
            }
            $user->tokens()->delete();

            return [
                'already_inactive' => $alreadyInactive,
                'emp_code' => $user->employee_code,
                'status' => 0,
            ];
        });
    }

    private function findUser(string $empCode): ?User
    {
        $users = User::where('employee_code', $empCode)->lockForUpdate()->limit(2)->get();
        if ($users->count() > 1) {
            throw new HttpException(409, 'Duplicate employee accounts found.');
        }
        return $users->first();
    }

    private function uniqueMatch($query, string $label)
    {
        $matches = $query->limit(2)->get();
        if ($matches->isEmpty()) {
            throw new HttpException(422, "{$label} not found.");
        }
        if ($matches->count() > 1) {
            throw new HttpException(409, "Duplicate {$label} records found.");
        }
        return $matches->first();
    }
}
