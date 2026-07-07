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
