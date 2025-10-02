<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
</head>

<body>
    <h1 class="text-2xl font-bold mb-4 text-center">Selamat Datang</h1>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="text-red-500">Logout</button>
    </form>
</body>

</html>
