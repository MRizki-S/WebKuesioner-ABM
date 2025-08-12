<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Karyawan ABM</title>

    {{-- favicon --}}
    <link rel="icon" href="{{ asset('assets/img/logo-abm2.png') }}" type="image/x-icon">

    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Flowbite -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

    <!-- Card Login -->
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="w-full px-4 lg:max-w-[500px] -mt-20">
            <div class="bg-white border-l-4 border-purple-700 shadow-xl rounded-lg p-8 mb-6">

                <!-- Title Login -->
                <h2 class="text-2xl font-bold text-center text-purple-700 mb-6">Login</h2>

                <form class="space-y-5" action="{{ route('login.action') }}" method="POST">
                    @csrf

                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                        <input type="text" id="username" name="username"
                             class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5"
                            placeholder="user1" required />
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5"
                            required placeholder="******"/>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>
