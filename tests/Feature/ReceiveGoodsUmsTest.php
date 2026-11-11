<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use App\Services\Ums\ReceiveGoodsUserLifecycle;
use App\Services\Ums\ReceiveGoodsUserAccessChecker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Pro1\UmsIntegration\Contracts\UserLifecycle;
use Pro1\UmsIntegration\Contracts\UserAccessChecker;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class ReceiveGoodsUmsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'ums_test', 'database.connections.ums_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
            'foreign_key_constraints' => true,
        ], 'cache.default' => 'array']);
        DB::purge('ums_test');
        // Legacy users migration declares status as auto-increment (integer's
        // second argument). Model the existing integer status without changing it.
        \Illuminate\Support\Facades\Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('employee_code')->unique();
            $table->string('password');
            $table->string('password_str');
            $table->string('role');
            $table->integer('department_id');
            $table->integer('branch_id')->nullable();
            $table->integer('status');
            $table->timestamps();
        });
        foreach ([
            '2023_12_29_034328_create_branches_table.php',
            '2023_12_29_034335_create_departments_table.php',
            '2024_02_21_094141_create_permission_tables.php',
            '2024_04_03_165024_create_user_branches_table.php',
            '2019_12_14_000001_create_personal_access_tokens_table.php',
        ] as $file) {
            (require database_path('migrations/' . $file))->up();
        }
        Branch::create(['branch_name' => 'Test Branch', 'branch_code' => 'MM-101', 'branch_short_name' => 'TEST']);
        Department::create(['name' => 'IT']);
        Role::create(['name' => 'user', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
    }

    private function payload(): array
    {
        return ['emp_code' => 'TEST-001', 'role_name' => 'user', 'branch_shortcodes' => ['MM-101'],
            'employee' => ['name' => 'Test User', 'department' => 'IT', 'position' => 'Officer']];
    }

    public function test_create_update_and_deactivate_preserve_password_and_sync_roles(): void
    {
        $service = app(UserLifecycle::class);
        $this->assertInstanceOf(ReceiveGoodsUserLifecycle::class, $service);
        $this->assertTrue($service->activate($this->payload())['created']);
        $user = User::firstOrFail();
        $this->assertTrue(Hash::check('TEST-001', $user->password));
        $this->assertSame('', $user->password_str);
        $password = $user->password;
        $this->assertTrue($user->hasRole('user'));
        $this->assertSame(1, $user->branches()->count());
        $data = $this->payload();
        $data['role_name'] = 'admin';
        unset($data['employee']);
        $this->assertFalse($service->activate($data)['created']);
        $user->refresh();
        $this->assertSame($password, $user->password);
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('user'));
        $this->assertEquals(Role::findByName('admin')->id, $user->role);
        $user->createToken('test');
        $this->assertFalse($service->deactivate('TEST-001')['already_inactive']);
        $this->assertSame(0, $user->tokens()->count());
        $this->assertTrue($service->deactivate('TEST-001')['already_inactive']);
    }

    public function test_checker_reports_missing_active_inactive_and_rejects_invalid_status(): void
    {
        $checker = app(UserAccessChecker::class);
        $this->assertInstanceOf(ReceiveGoodsUserAccessChecker::class, $checker);
        $this->assertSame('not_found', $checker->checkAccess('TEST-001')['account_status']);
        app(UserLifecycle::class)->activate($this->payload());
        $this->assertTrue($checker->checkAccess('TEST-001')['has_access']);
        app(UserLifecycle::class)->deactivate('TEST-001');
        $this->assertFalse($checker->checkAccess('TEST-001')['has_access']);
        User::query()->update(['status' => 2]);
        $this->expectException(HttpException::class);
        $checker->checkAccess('TEST-001');
    }

    public function test_bad_branch_role_and_department_do_not_create_accounts(): void
    {
        foreach (['branch_shortcodes' => ['UNKNOWN'], 'role_name' => 'missing',
            'employee' => ['name' => 'Test', 'department' => 'missing', 'position' => 'Officer'],
        ] as $key => $value) {
            $data = $this->payload();
            $data[$key] = $value;
            try {
                app(UserLifecycle::class)->activate($data);
                $this->fail('Invalid input was accepted.');
            } catch (HttpException $exception) {
                $this->assertSame(422, $exception->getStatusCode());
            }
            $this->assertSame(0, User::count());
        }
        $data = $this->payload();
        $data['branch_shortcodes'] = ['MM-101', 'MM-102'];
        $this->expectException(HttpException::class);
        app(UserLifecycle::class)->activate($data);
    }
}
