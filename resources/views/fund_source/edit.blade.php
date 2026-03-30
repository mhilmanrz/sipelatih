@extends('layout.LayoutSuperAdmin')

@section('title', 'Edit Sumber Dana')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
@endpush

@section('content')
    <div class="tw-wrap p-6">
        <div class="max-w-xl bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Edit Data Sumber Dana</h1>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('fund-sources.update', $fundSource->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Sumber Dana <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $fundSource->name) }}" required
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded shadow">
                        Update Simpan
                    </button>
                    <a href="{{ route('fund-sources.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
