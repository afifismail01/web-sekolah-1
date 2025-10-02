<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Forgot Password</title>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
        <h1 class="text-xl font-bold mb-4 text-center">
            Lupa Password
        </h1>

        @if ($errors->any())
            <div class="text-red-600 mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- @if ($session('success'))
            <div class="text-green-600 mb-4">
                {{ $session('success') }}
            </div>
        @endif --}}

        <form action="{{ route('forgot.password.send') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="whatsapp" class="block mb-1">Nomor Telepon:</label>
                <input type="text" name="whatsapp" id="phone" class="w-full border rounded px-3 py-2"
                    placeholder="Contoh: 6285745768593" value="{{ old('phone') }}" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 ">Kirim Password
                Baru</button>
            <p class="text-center mt-4 text-sm"><a href="{{ route('login') }}"
                    class="text-blue-500 hover:underline transition">Kembali ke login</a></p>
        </form>
    </div>
</body>

</html>
