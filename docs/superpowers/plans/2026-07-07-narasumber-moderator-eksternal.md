# Narasumber & Moderator Eksternal Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Let kegiatan record narasumber/moderator who are not pegawai (no login, no work unit), tag them with classification roles, and give them a proper master-data directory, without touching the existing `user_id`-based relations kegiatan already uses.

**Architecture:** Extend the existing `users` table with `is_external`, `nik`, `institution`, `external_position` columns instead of a separate table/polymorphic relation. Two new zero-permission Spatie roles (`narasumber eksternal`, `moderator eksternal`) classify capacity. A new master-data CRUD controller/menu manages these rows scoped by `is_external = true`; the existing pegawai (`users`) and akun (`accounts`) listings are adjusted to exclude them so the three lists stay non-overlapping. Surat Tugas document generation is updated at the one place it currently assumes a pegawai (rank/position lookup) — Nota Dinas was checked during planning and turned out not to render any pegawai-specific field for the speaker, so it needs no change.

**Tech Stack:** Laravel 12, PHPUnit, Spatie Permission, Tailwind (Blade views), PHPWord + DomPDF (Surat Tugas rendering).

## Global Constraints

- Follow existing master-data controller convention: FormRequest classes for validation, no controller-level permission middleware (sidebar `@can` is the only gate today — every other master-data resource, e.g. `PositionController`, `RankController`, works this way; don't be the one controller that adds stricter enforcement).
- View directory naming follows the existing snake_case singular convention (`employment_type/`, `rank/`), not kebab or plural.
- Route names/paths follow existing kebab-case convention (`employment-types`, `work-units`).
- Run `vendor/bin/pint --dirty --format agent` after any PHP file changes, before considering a task done.
- Run only the tests relevant to the task being worked (`php artisan test --compact --filter=...`), not the full suite, until the final task.

---

### Task 1: `users` table gets external-person columns + model support

**Files:**
- Create: `database/migrations/2026_07_07_090000_add_external_fields_to_users_table.php`
- Modify: `app/Models/User/User.php`
- Test: `tests/Feature/User/UserExternalFieldsTest.php`

**Interfaces:**
- Produces: `users.is_external` (bool, default false), `users.nik` (string, nullable), `users.institution` (string, nullable), `users.external_position` (string, nullable, "jabatan asal"). `User::documentRankOrInstitution(): string`, `User::documentPosition(): string` — used by Task 5 to render Surat Tugas without branching on `is_external` at the view/controller level.

- [ ] **Step 1: Write the migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_external')->default(false)->after('account_number');
            $table->string('nik')->nullable()->after('is_external');
            $table->string('institution')->nullable()->after('nik');
            $table->string('external_position')->nullable()->after('institution');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_external', 'nik', 'institution', 'external_position']);
        });
    }
};
```

- [ ] **Step 2: Run the migration**

Run: `php artisan migrate`
Expected: `2026_07_07_090000_add_external_fields_to_users_table ... DONE`

- [ ] **Step 3: Update the `User` model**

In `app/Models/User/User.php`, add the four new columns to `$fillable`, add a `casts()` method, and add the two document-label helper methods used by Surat Tugas rendering (Task 5):

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'work_unit_id',
    'position_id',
    'employment_type_id',
    'profession_id',
    'rank_id',
    'employee_id',
    'phone_number',
    'npwp',
    'bank_name',
    'account_number',
    'is_external',
    'nik',
    'institution',
    'external_position',
];

protected function casts(): array
{
    return [
        'is_external' => 'boolean',
    ];
}
```

Add these two methods near `isPegawai()`:

```php
/**
 * The value shown in the "Pangkat/Gol." column of Surat Tugas narasumber
 * tables: an internal user's rank, or an external person's institution.
 */
public function documentRankOrInstitution(): string
{
    return $this->is_external ? ($this->institution ?? '-') : ($this->rank?->name ?? '-');
}

/**
 * The value shown in the "Jabatan" column of Surat Tugas narasumber
 * tables: an internal user's position, or an external person's
 * position at their institution.
 */
public function documentPosition(): string
{
    return $this->is_external ? ($this->external_position ?? '-') : ($this->position?->name ?? '-');
}
```

- [ ] **Step 4: Write the test**

```php
<?php

namespace Tests\Feature\User;

use App\Models\User\Positions;
use App\Models\User\Rank;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserExternalFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_external_defaults_to_false_and_casts_to_bool(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->is_external);
        $this->assertIsBool($user->fresh()->is_external);
    }

    public function test_internal_user_document_labels_use_rank_and_position(): void
    {
        $rank = Rank::create(['name' => 'Penata', 'code' => 'III/c']);
        $position = Positions::create(['name' => 'Kepala Seksi']);

        $user = User::factory()->create([
            'rank_id' => $rank->id,
            'position_id' => $position->id,
        ]);

        $this->assertSame('Penata', $user->documentRankOrInstitution());
        $this->assertSame('Kepala Seksi', $user->documentPosition());
    }

    public function test_external_user_document_labels_use_institution_and_external_position(): void
    {
        $user = User::factory()->create([
            'is_external' => true,
            'institution' => 'Universitas Indonesia',
            'external_position' => 'Dosen',
        ]);

        $this->assertSame('Universitas Indonesia', $user->documentRankOrInstitution());
        $this->assertSame('Dosen', $user->documentPosition());
    }

    public function test_document_labels_fall_back_to_dash_when_missing(): void
    {
        $external = User::factory()->create(['is_external' => true]);
        $internal = User::factory()->create();

        $this->assertSame('-', $external->documentRankOrInstitution());
        $this->assertSame('-', $external->documentPosition());
        $this->assertSame('-', $internal->documentRankOrInstitution());
        $this->assertSame('-', $internal->documentPosition());
    }
}
```

- [ ] **Step 5: Run the test**

Run: `php artisan test --compact tests/Feature/User/UserExternalFieldsTest.php`
Expected: PASS (4 tests)

- [ ] **Step 6: Format and commit**

```bash
vendor/bin/pint --dirty --format agent
git add database/migrations/2026_07_07_090000_add_external_fields_to_users_table.php app/Models/User/User.php tests/Feature/User/UserExternalFieldsTest.php
git commit -m "feat: add external-person fields and document labels to User"
```

---

### Task 2: Classification roles and permission in `RolePermissionSeeder`

**Files:**
- Modify: `database/seeders/RolePermissionSeeder.php`
- Test: `tests/Feature/RolePermissionSeederExternalTest.php`

**Interfaces:**
- Consumes: nothing new.
- Produces: permission `view external persons` (granted to `superadmin` via the existing `syncPermissions(Permission::all())` and explicitly to `penyelenggara`); roles `narasumber eksternal` and `moderator eksternal` with zero permissions (pure classification tags, assigned manually per person in Task 3).

- [ ] **Step 1: Add the permission**

In `database/seeders/RolePermissionSeeder.php`, add `'view external persons'` to the `$permissions` array, in the "Master Data - Pegawai & Akun" group:

```php
            // Master Data - Pegawai & Akun
            'view users',
            'view accounts',
            'view external persons',
            'view professions',
```

- [ ] **Step 2: Add the two classification roles (no permissions)**

After the existing role creation block, add:

```php
        $pengusul = Role::firstOrCreate(['name' => 'pengusul', 'guard_name' => 'web']);

        // Classification-only roles for external narasumber/moderator: no
        // permissions, never used for login/menu access — they exist purely
        // to distinguish a person's capacity in the external directory.
        Role::firstOrCreate(['name' => 'narasumber eksternal', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'moderator eksternal', 'guard_name' => 'web']);
```

- [ ] **Step 3: Grant the new permission to `penyelenggara`**

`penyelenggara` already manages the narasumber/moderator tab on kegiatan, so it also manages the external directory. Add `'view external persons'` to its `syncPermissions` list:

```php
        $penyelenggara->syncPermissions([
            'view dashboard',
            'view usulan diklat',
            'view kegiatan laporan',
            'view external persons',
            'view document formulir',
```

- [ ] **Step 4: Write the test**

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionSeederExternalTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_external_classification_roles_with_no_permissions(): void
    {
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $narasumber = Role::where('name', 'narasumber eksternal')->firstOrFail();
        $moderator = Role::where('name', 'moderator eksternal')->firstOrFail();

        $this->assertCount(0, $narasumber->permissions);
        $this->assertCount(0, $moderator->permissions);
    }

    public function test_seeder_grants_view_external_persons_to_superadmin_and_penyelenggara(): void
    {
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->assertNotNull(Permission::where('name', 'view external persons')->first());

        $superadmin = Role::where('name', 'superadmin')->firstOrFail();
        $penyelenggara = Role::where('name', 'penyelenggara')->firstOrFail();

        $this->assertTrue($superadmin->hasPermissionTo('view external persons'));
        $this->assertTrue($penyelenggara->hasPermissionTo('view external persons'));
    }
}
```

- [ ] **Step 5: Run the test**

Run: `php artisan test --compact tests/Feature/RolePermissionSeederExternalTest.php`
Expected: PASS (2 tests)

- [ ] **Step 6: Commit**

```bash
vendor/bin/pint --dirty --format agent
git add database/seeders/RolePermissionSeeder.php tests/Feature/RolePermissionSeederExternalTest.php
git commit -m "feat: seed external narasumber/moderator classification roles"
```

---

### Task 3: External-person directory (form requests, controller, routes, views, sidebar menu)

**Files:**
- Create: `app/Http/Requests/user/StoreExternalPersonRequest.php`
- Create: `app/Http/Requests/user/UpdateExternalPersonRequest.php`
- Create: `app/Http/Controllers/User/ExternalPersonController.php`
- Create: `resources/views/external_person/index.blade.php`
- Create: `resources/views/external_person/create.blade.php`
- Create: `resources/views/external_person/edit.blade.php`
- Modify: `routes/web.php`
- Modify: `resources/views/components/layouts/sidebar.blade.php`
- Test: `tests/Feature/User/ExternalPersonTest.php`

**Interfaces:**
- Consumes: permission `view external persons` (Task 2); `User::$fillable` additions (Task 1).
- Produces: routes `external-persons.index|create|store|edit|update|destroy`; every query scoped to `is_external = true`; validated payload keys `name`, `nik`, `employee_id`, `npwp`, `institution`, `external_position`, `phone_number`, `email`, `is_narasumber` (bool), `is_moderator` (bool) — rejected unless at least one of the two booleans is true.

- [ ] **Step 1: Create the store request**

```php
<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreExternalPersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'external_position' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'is_narasumber' => 'sometimes|boolean',
            'is_moderator' => 'sometimes|boolean',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->boolean('is_narasumber') && ! $this->boolean('is_moderator')) {
                $validator->errors()->add('is_narasumber', 'Pilih minimal satu kapasitas: Narasumber atau Moderator.');
            }
        });
    }
}
```

- [ ] **Step 2: Create the update request**

```php
<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExternalPersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'external_position' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,'.$this->route('id'),
            'is_narasumber' => 'sometimes|boolean',
            'is_moderator' => 'sometimes|boolean',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->boolean('is_narasumber') && ! $this->boolean('is_moderator')) {
                $validator->errors()->add('is_narasumber', 'Pilih minimal satu kapasitas: Narasumber atau Moderator.');
            }
        });
    }
}
```

- [ ] **Step 3: Create the controller**

```php
<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\StoreExternalPersonRequest;
use App\Http\Requests\user\UpdateExternalPersonRequest;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ExternalPersonController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->where('is_external', true);

        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('entries', $request->input('per_page', 10));
        $externalPersons = $query->paginate($perPage)->appends($request->all());

        return view('external_person.index', compact('externalPersons'));
    }

    public function create()
    {
        return view('external_person.create');
    }

    public function store(StoreExternalPersonRequest $request)
    {
        $data = $request->safe()->except(['is_narasumber', 'is_moderator']);
        $data['is_external'] = true;
        $data['password'] = Hash::make(Str::random(32));

        if (blank($data['email'] ?? null)) {
            $data['email'] = Str::slug($data['name']).'-'.Str::random(6).'@external.local';
        }

        $user = User::create($data);
        $user->syncRoles($this->capacityRoles($request));

        return redirect()->route('external-persons.index')->with('success', 'Narasumber/Moderator eksternal berhasil ditambahkan.');
    }

    public function show($id)
    {
        return redirect()->route('external-persons.index');
    }

    public function edit($id)
    {
        $externalPerson = User::where('is_external', true)->findOrFail($id);

        return view('external_person.edit', compact('externalPerson'));
    }

    public function update(UpdateExternalPersonRequest $request, $id)
    {
        $externalPerson = User::where('is_external', true)->findOrFail($id);

        $data = $request->safe()->except(['is_narasumber', 'is_moderator']);

        if (blank($data['email'] ?? null)) {
            $data['email'] = Str::slug($data['name']).'-'.Str::random(6).'@external.local';
        }

        $externalPerson->update($data);
        $externalPerson->syncRoles($this->capacityRoles($request));

        return redirect()->route('external-persons.index')->with('success', 'Narasumber/Moderator eksternal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $externalPerson = User::where('is_external', true)->findOrFail($id);
        $externalPerson->delete();

        return redirect()->route('external-persons.index')->with('success', 'Narasumber/Moderator eksternal berhasil dihapus.');
    }

    /**
     * @return array<int, string>
     */
    private function capacityRoles(Request $request): array
    {
        $roles = [];

        if ($request->boolean('is_narasumber')) {
            $roles[] = 'narasumber eksternal';
        }

        if ($request->boolean('is_moderator')) {
            $roles[] = 'moderator eksternal';
        }

        return $roles;
    }
}
```

- [ ] **Step 4: Add routes**

In `routes/web.php`, add the import (alphabetically, after `EmploymentTypeController`):

```php
use App\Http\Controllers\User\EmploymentTypeController;
use App\Http\Controllers\User\ExternalPersonController;
use App\Http\Controllers\User\MyActivityController;
```

Add the resource route next to the other master-data resources (after `Route::resource('ranks', RankController::class);`):

```php
    Route::resource('ranks', RankController::class);
    Route::resource('external-persons', ExternalPersonController::class);
```

- [ ] **Step 5: Create the index view**

```blade
<x-layouts.app>
    <x-slot:title>Narasumber/Moderator Eksternal</x-slot:title>

    <div class="px-8 py-6">

        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            <x-page-title>Narasumber/Moderator Eksternal</x-page-title>
            <a href="{{ route('external-persons.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#007a7a] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#005f5f] active:bg-[#004d4d] transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form method="GET" action="{{ route('external-persons.index') }}" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="flex flex-wrap items-center gap-3 px-5 py-4 border-b border-gray-200">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama atau Instansi..."
                    class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                <button type="submit" class="bg-[#007a7a] text-white px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-[#005f5f] transition">Cari</button>
                <a href="{{ route('external-persons.index') }}" class="bg-gray-100 text-gray-700 px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Reset</a>
            </div>
        </form>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" style="min-width: 700px;">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-center w-12 py-3 px-4 font-semibold text-sm">No.</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Nama</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Instansi Asal</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Jabatan Asal</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Kapasitas</th>
                            <th class="text-center w-48 py-3 px-4 font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($externalPersons as $index => $person)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="text-center py-3 px-4">{{ $externalPersons->firstItem() + $index }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('external-persons.edit', $person->id) }}" class="text-[#007a7a] hover:underline font-medium">{{ $person->name }}</a>
                                </td>
                                <td class="py-3 px-4">{{ $person->institution ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $person->external_position ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $person->roles->pluck('name')->map(fn ($r) => ucfirst($r))->implode(', ') ?: '-' }}</td>
                                <td class="text-center py-3 px-4">
                                    <div class="flex justify-center gap-1.5">
                                        <a href="{{ route('external-persons.edit', $person->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs font-semibold transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('external-persons.destroy', $person->id) }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus data ini?')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-6 px-4">
                                    Belum ada data narasumber/moderator eksternal.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-gray-200">
                {{ $externalPersons->links('components.pagination') }}
            </div>
        </div>
    </div>
</x-layouts.app>
```

- [ ] **Step 6: Create the create view**

```blade
<x-layouts.app>
    <x-slot:title>Tambah Narasumber/Moderator Eksternal</x-slot:title>

    <div class="p-6 max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">TAMBAH NARASUMBER/MODERATOR EKSTERNAL</h1>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Terjadi Kesalahan!</strong>
                <ul class="list-disc mt-2 ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('external-persons.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                </div>

                <div class="mb-4">
                    <label for="nik" class="block text-gray-700 font-semibold mb-2">NIK</label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="employee_id" class="block text-gray-700 font-semibold mb-2">NIP (jika ada)</label>
                    <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="npwp" class="block text-gray-700 font-semibold mb-2">NPWP</label>
                    <input type="text" name="npwp" id="npwp" value="{{ old('npwp') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="institution" class="block text-gray-700 font-semibold mb-2">Instansi Asal</label>
                    <input type="text" name="institution" id="institution" value="{{ old('institution') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="external_position" class="block text-gray-700 font-semibold mb-2">Jabatan di Instansi Asal</label>
                    <input type="text" name="external_position" id="external_position" value="{{ old('external_position') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="phone_number" class="block text-gray-700 font-semibold mb-2">No HP</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email (opsional)</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Kapasitas <span class="text-red-500">*</span></label>
                    <label class="inline-flex items-center mr-6">
                        <input type="checkbox" name="is_narasumber" value="1" {{ old('is_narasumber') ? 'checked' : '' }} class="mr-2">
                        Narasumber
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_moderator" value="1" {{ old('is_moderator') ? 'checked' : '' }} class="mr-2">
                        Moderator
                    </label>
                </div>

                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('external-persons.index') }}"
                        class="text-gray-600 hover:text-gray-800 font-medium px-4 py-2 border border-gray-300 rounded shadow-sm hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded shadow transition-colors">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
```

- [ ] **Step 7: Create the edit view**

Same as the create view, but posting to the update route with `@method('PUT')`, values pre-filled from `$externalPerson`, and checkboxes pre-checked from `$externalPerson->roles`:

```blade
<x-layouts.app>
    <x-slot:title>Edit Narasumber/Moderator Eksternal</x-slot:title>

    <div class="p-6 max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">EDIT NARASUMBER/MODERATOR EKSTERNAL</h1>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Terjadi Kesalahan!</strong>
                <ul class="list-disc mt-2 ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('external-persons.update', $externalPerson->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $externalPerson->name) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                </div>

                <div class="mb-4">
                    <label for="nik" class="block text-gray-700 font-semibold mb-2">NIK</label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik', $externalPerson->nik) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="employee_id" class="block text-gray-700 font-semibold mb-2">NIP (jika ada)</label>
                    <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id', $externalPerson->employee_id) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="npwp" class="block text-gray-700 font-semibold mb-2">NPWP</label>
                    <input type="text" name="npwp" id="npwp" value="{{ old('npwp', $externalPerson->npwp) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="institution" class="block text-gray-700 font-semibold mb-2">Instansi Asal</label>
                    <input type="text" name="institution" id="institution" value="{{ old('institution', $externalPerson->institution) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="external_position" class="block text-gray-700 font-semibold mb-2">Jabatan di Instansi Asal</label>
                    <input type="text" name="external_position" id="external_position" value="{{ old('external_position', $externalPerson->external_position) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="phone_number" class="block text-gray-700 font-semibold mb-2">No HP</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $externalPerson->phone_number) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email (opsional)</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $externalPerson->email) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Kapasitas <span class="text-red-500">*</span></label>
                    <label class="inline-flex items-center mr-6">
                        <input type="checkbox" name="is_narasumber" value="1" {{ old('is_narasumber', $externalPerson->hasRole('narasumber eksternal')) ? 'checked' : '' }} class="mr-2">
                        Narasumber
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_moderator" value="1" {{ old('is_moderator', $externalPerson->hasRole('moderator eksternal')) ? 'checked' : '' }} class="mr-2">
                        Moderator
                    </label>
                </div>

                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('external-persons.index') }}"
                        class="text-gray-600 hover:text-gray-800 font-medium px-4 py-2 border border-gray-300 rounded shadow-sm hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded shadow transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
```

- [ ] **Step 8: Add the sidebar menu entry**

In `resources/views/components/layouts/sidebar.blade.php`, add `'view external persons'` to the `@canany` permission list (after `'view accounts'`):

```php
        @canany([
            'view users', 'view accounts', 'view external persons', 'view professions', 'view profession categories', 
```

Add `external-persons*` to the `$isMasterDataOpen` check (after `accounts*`):

```php
                    request()->is('accounts*') ||
                    request()->is('external-persons*') ||
                    request()->is('professions*') ||
```

Add the link itself, after the "Data Akun" block:

```blade
                    @can('view accounts')
                        <a href="{{ route('accounts.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('accounts*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-user-shield w-6 text-center mr-2 text-sm"></i>
                            <span>Data Akun</span>
                        </a>
                    @endcan

                    @can('view external persons')
                        <a href="{{ route('external-persons.index') }}"
                            class="flex items-center pl-8 pr-4 py-2.5 text-gray-200 hover:bg-[#1fd1d1] hover:text-black transition-colors {{ request()->is('external-persons*') ? 'bg-[#1fd1d1] text-black border-l-4 border-[#1fd1d1] font-semibold' : '' }}">
                            <i class="fa-solid fa-user-group w-6 text-center mr-2 text-sm"></i>
                            <span>Narasumber/Moderator Eksternal</span>
                        </a>
                    @endcan
```

- [ ] **Step 9: Write the test**

```php
<?php

namespace Tests\Feature\User;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExternalPersonTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->adminUser = User::factory()->create(['email' => 'admin@sipelatih.test']);
        $this->adminUser->assignRole('superadmin');
    }

    public function test_index_only_lists_external_users(): void
    {
        $external = User::factory()->create(['name' => 'Dr. Eksternal', 'is_external' => true]);
        $internal = User::factory()->create(['name' => 'Pegawai Internal']);

        $response = $this->actingAs($this->adminUser)->get(route('external-persons.index'));

        $response->assertStatus(200);
        $response->assertSee('Dr. Eksternal');
        $response->assertDontSee('Pegawai Internal');
    }

    public function test_store_requires_at_least_one_capacity(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('external-persons.store'), [
            'name' => 'Dr. Tanpa Kapasitas',
        ]);

        $response->assertSessionHasErrors(['is_narasumber']);
        $this->assertDatabaseMissing('users', ['name' => 'Dr. Tanpa Kapasitas']);
    }

    public function test_store_creates_external_user_and_assigns_capacity_roles(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('external-persons.store'), [
            'name' => 'Dr. Baru',
            'institution' => 'Universitas Indonesia',
            'external_position' => 'Dosen',
            'is_narasumber' => '1',
            'is_moderator' => '1',
        ]);

        $response->assertRedirect(route('external-persons.index'));

        $person = User::where('name', 'Dr. Baru')->firstOrFail();
        $this->assertTrue($person->is_external);
        $this->assertNotEmpty($person->email);
        $this->assertTrue($person->hasRole('narasumber eksternal'));
        $this->assertTrue($person->hasRole('moderator eksternal'));
    }

    public function test_store_generates_placeholder_email_when_blank(): void
    {
        $this->actingAs($this->adminUser)->post(route('external-persons.store'), [
            'name' => 'Dr. Tanpa Email',
            'is_narasumber' => '1',
        ]);

        $person = User::where('name', 'Dr. Tanpa Email')->firstOrFail();
        $this->assertStringContainsString('@external.local', $person->email);
    }

    public function test_update_syncs_capacity_roles(): void
    {
        $person = User::factory()->create(['name' => 'Dr. Update', 'is_external' => true]);
        $person->assignRole('narasumber eksternal');

        $response = $this->actingAs($this->adminUser)->put(route('external-persons.update', $person->id), [
            'name' => 'Dr. Update',
            'is_moderator' => '1',
        ]);

        $response->assertRedirect(route('external-persons.index'));

        $person->refresh();
        $this->assertFalse($person->hasRole('narasumber eksternal'));
        $this->assertTrue($person->hasRole('moderator eksternal'));
    }

    public function test_destroy_deletes_external_user(): void
    {
        $person = User::factory()->create(['is_external' => true]);

        $response = $this->actingAs($this->adminUser)->delete(route('external-persons.destroy', $person->id));

        $response->assertRedirect(route('external-persons.index'));
        $this->assertDatabaseMissing('users', ['id' => $person->id]);
    }
}
```

- [ ] **Step 10: Run the test**

Run: `php artisan test --compact tests/Feature/User/ExternalPersonTest.php`
Expected: PASS (6 tests)

- [ ] **Step 11: Format and commit**

```bash
vendor/bin/pint --dirty --format agent
git add app/Http/Requests/user/StoreExternalPersonRequest.php app/Http/Requests/user/UpdateExternalPersonRequest.php app/Http/Controllers/User/ExternalPersonController.php resources/views/external_person routes/web.php resources/views/components/layouts/sidebar.blade.php tests/Feature/User/ExternalPersonTest.php
git commit -m "feat: add external narasumber/moderator directory menu"
```

---

### Task 4: Keep pegawai and akun listings external-free

**Files:**
- Modify: `app/Http/Controllers/User/UserController.php:25`
- Modify: `app/Http/Controllers/User/AccountController.php:21`
- Test: `tests/Feature/User/UserAccountExternalScopeTest.php`

**Interfaces:**
- Consumes: `users.is_external` (Task 1), roles from Task 3.
- Produces: `UserController::index` and `AccountController::index` now exclude `is_external = true` rows, independent of whether the row happens to hold a role.

- [ ] **Step 1: Scope `UserController::index`**

In `app/Http/Controllers/User/UserController.php`, line 25, change:

```php
        $query = User::with(['workUnit', 'position', 'employmentType', 'profession', 'rank', 'roles'])->doesntHave('roles')->where('email', '!=', 'admin@mail.com');
```

to:

```php
        $query = User::with(['workUnit', 'position', 'employmentType', 'profession', 'rank', 'roles'])->doesntHave('roles')->where('is_external', false)->where('email', '!=', 'admin@mail.com');
```

- [ ] **Step 2: Scope `AccountController::index`**

In `app/Http/Controllers/User/AccountController.php`, line 21, change:

```php
        $query = User::with(['workUnit', 'position', 'employmentType', 'profession', 'roles'])->has('roles');
```

to:

```php
        $query = User::with(['workUnit', 'position', 'employmentType', 'profession', 'roles'])->has('roles')->where('is_external', false);
```

- [ ] **Step 3: Write the test**

```php
<?php

namespace Tests\Feature\User;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAccountExternalScopeTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->adminUser = User::factory()->create(['email' => 'admin@sipelatih.test']);
        $this->adminUser->assignRole('superadmin');
    }

    public function test_pegawai_listing_excludes_external_users_even_without_a_role(): void
    {
        User::factory()->create(['name' => 'Eksternal Tanpa Role', 'is_external' => true]);
        User::factory()->create(['name' => 'Pegawai Biasa']);

        $response = $this->actingAs($this->adminUser)->get(route('users.index'));

        $response->assertDontSee('Eksternal Tanpa Role');
        $response->assertSee('Pegawai Biasa');
    }

    public function test_akun_listing_excludes_external_users_even_with_a_capacity_role(): void
    {
        $external = User::factory()->create(['name' => 'Eksternal Dengan Role', 'is_external' => true]);
        $external->assignRole('narasumber eksternal');

        $staffAccount = User::factory()->create(['name' => 'Staff Akun']);
        $staffAccount->assignRole('pengusul');

        $response = $this->actingAs($this->adminUser)->get(route('accounts.index'));

        $response->assertDontSee('Eksternal Dengan Role');
        $response->assertSee('Staff Akun');
    }
}
```

- [ ] **Step 4: Run the test**

Run: `php artisan test --compact tests/Feature/User/UserAccountExternalScopeTest.php`
Expected: PASS (2 tests)

- [ ] **Step 5: Format and commit**

```bash
vendor/bin/pint --dirty --format agent
git add app/Http/Controllers/User/UserController.php app/Http/Controllers/User/AccountController.php tests/Feature/User/UserAccountExternalScopeTest.php
git commit -m "fix: exclude external persons from pegawai and akun listings"
```

---

### Task 5: Surat Tugas renders external narasumber correctly

**Files:**
- Modify: `resources/views/pdf/surat-tugas/lampiran-narasumber.blade.php`
- Modify: `app/Http/Controllers/SuratTugasController.php:239-257`
- Test: `tests/Feature/SuratTugasNarasumberRenderingTest.php`

**Interfaces:**
- Consumes: `User::documentRankOrInstitution()`, `User::documentPosition()` (Task 1).
- Produces: the "Pangkat/Gol." column in both the PDF partial and the DOCX table shows institution for external narasumber, rank for internal narasumber; "Jabatan" shows external position or internal position respectively.

- [ ] **Step 1: Update the PDF partial**

In `resources/views/pdf/surat-tugas/lampiran-narasumber.blade.php`, change the header and the two data cells:

```blade
        <tr>
            <th style="width: 5%;">No.</th>
            <th style="width: 25%;">Nama</th>
            <th style="width: 20%;">NIP/NPS</th>
            <th style="width: 20%;">Pangkat/Gol. / Instansi</th>
            <th style="width: 30%;">Jabatan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($narasumber as $index => $n)
            <tr>
                <td class="center">{{ $loop->iteration }}.</td>
                <td>{{ $n->user->name ?? '-' }}</td>
                <td>{{ $n->user->employee_id ?? '-' }}</td>
                <td>{{ $n->user?->documentRankOrInstitution() ?? '-' }}</td>
                <td>{{ $n->user?->documentPosition() ?? '-' }}</td>
            </tr>
```

- [ ] **Step 2: Update the DOCX table**

In `app/Http/Controllers/SuratTugasController.php`, change the narasumber table header (around line 245) and data cells (around line 254-255):

```php
            $narasumberTable->addCell(2000, ['bgColor' => 'F2F2F2'])->addText('Pangkat/Gol. / Instansi', ['bold' => true, 'size' => 11]);
            $narasumberTable->addCell(3000, ['bgColor' => 'F2F2F2'])->addText('Jabatan', ['bold' => true, 'size' => 11]);

            $index = 1;
            foreach ($data['narasumber'] as $n) {
                $narasumberTable->addRow();
                $narasumberTable->addCell(500)->addText($index.'.', ['size' => 11]);
                $narasumberTable->addCell(2500)->addText($n->user?->name ?? '-', ['size' => 11]);
                $narasumberTable->addCell(2000)->addText($n->user?->employee_id ?? '-', ['size' => 11]);
                $narasumberTable->addCell(2000)->addText($n->user?->documentRankOrInstitution() ?? '-', ['size' => 11]);
                $narasumberTable->addCell(3000)->addText($n->user?->documentPosition() ?? '-', ['size' => 11]);
                $index++;
            }
```

- [ ] **Step 3: Write the test**

This renders the changed Blade partial directly with both an internal and an external narasumber, which exercises exactly the code changed in Step 1 without needing to parse a PDF binary:

```php
<?php

namespace Tests\Feature;

use App\Models\Act\ActivitySpeaker;
use App\Models\User\Positions;
use App\Models\User\Rank;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SuratTugasNarasumberRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_lampiran_narasumber_shows_institution_for_external_and_rank_for_internal(): void
    {
        $rank = Rank::create(['name' => 'Pembina', 'code' => 'IV/a']);
        $position = Positions::create(['name' => 'Kepala Bidang']);

        $internalUser = User::factory()->create([
            'name' => 'Internal Narasumber',
            'rank_id' => $rank->id,
            'position_id' => $position->id,
        ]);
        $externalUser = User::factory()->create([
            'name' => 'External Narasumber',
            'is_external' => true,
            'institution' => 'RS Mitra Sehat',
            'external_position' => 'Dokter Spesialis',
        ]);

        $internalSpeaker = new ActivitySpeaker;
        $internalSpeaker->setRelation('user', $internalUser);

        $externalSpeaker = new ActivitySpeaker;
        $externalSpeaker->setRelation('user', $externalUser);

        $narasumber = new Collection([$internalSpeaker, $externalSpeaker]);

        $html = view('pdf.surat-tugas.lampiran-narasumber', [
            'narasumber' => $narasumber,
            'nomorSurat' => '001/ST/2026',
            'tanggalSuratFormatted' => '7 Juli 2026',
        ])->render();

        $this->assertStringContainsString('Pembina', $html);
        $this->assertStringContainsString('Kepala Bidang', $html);
        $this->assertStringContainsString('RS Mitra Sehat', $html);
        $this->assertStringContainsString('Dokter Spesialis', $html);
    }
}
```

- [ ] **Step 4: Run the test**

Run: `php artisan test --compact tests/Feature/SuratTugasNarasumberRenderingTest.php`
Expected: PASS (1 test)

- [ ] **Step 5: Format and commit**

```bash
vendor/bin/pint --dirty --format agent
git add resources/views/pdf/surat-tugas/lampiran-narasumber.blade.php app/Http/Controllers/SuratTugasController.php tests/Feature/SuratTugasNarasumberRenderingTest.php
git commit -m "feat: render institution/jabatan asal for external narasumber in Surat Tugas"
```

---

### Final check

- [ ] Run the full suite once all five tasks are committed:

Run: `php artisan test --compact`
Expected: all tests pass, no regressions in existing `UserSearchTest`, `EmploymentTypeTest`, or other pre-existing suites.
