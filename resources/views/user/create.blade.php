<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Document</title>
</head>

<style>
    html {
        height: full;
        background: #fff;
    }

    body {
        height: full;
    }
</style>

<body>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data"
                id="uploadForm">
                @csrf
                <input
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    id="multiple_files" name="file" type="file" accept="image/*,video/*" multiple>
                <div id="error-message" class="text-red-500 text-sm mt-2"></div>
                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Upload</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4'];
            const files = document.getElementById('multiple_files').files;
            const errorMessage = document.getElementById('error-message');
            errorMessage.textContent = '';

            for (let i = 0; i < files.length; i++) {
                if (!allowedTypes.includes(files[i].type)) {
                    errorMessage.textContent =
                        'File format tidak diizinkan. Hanya file gambar dan video MP4 yang diizinkan.';
                    event.preventDefault();
                    return;
                }
            }
        });
    </script>
</body>


</html>
