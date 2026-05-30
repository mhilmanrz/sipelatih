<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluasi Berhasil - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
                <div class="mb-6">
                    <svg class="w-16 h-16 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Evaluasi Berhasil Dikirim
                </h1>

                <p class="text-gray-600 mb-4">
                    Terima kasih telah meluangkan waktu untuk mengisi evaluasi {{ $evaluation->participant->activity->activityName->name ?? 'kegiatan ini' }}.
                </p>

                <p class="text-sm text-gray-500">
                    Respons Anda sangat berharga bagi kami dan membantu kami untuk terus meningkatkan kualitas kegiatan.
                </p>

                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500">
                        <strong>Waktu pengiriman:</strong><br>
                        {{ now()->locale('id')->format('d F Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
