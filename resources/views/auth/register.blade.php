<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Daftar Akun Siswa</title>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Daftar Akun Siswa</h1>
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- form pendaftaran --}}
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block font-medium">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full border rounded px-3 py-2"
                    value="{{ old('name') }}" required>
            </div>
            <div>
                <label for="email" class="block font-medium">Email</label>
                <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2 mt-1"
                    value="{{ old('email') }}" required>
            </div>
            <div>
                <label for="whatsapp" class="block font-medium">Nomor Whatsapp</label>
                <input type="text" name="whatsapp" id="whatsapp" class="w-full border rounded px-3 py-2 mt-1"
                    value="{{ old('whatsapp') }}" placeholder="Contoh: 6281234567890" required>
            </div>
            <button type="submit"
                class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700">Daftar</button>
            <p class="text-center mt-4 text-sm">
                Sudah punya akun ? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login disini</a>
            </p>
        </form>
    </div>
</body>

</html>
