<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background: #304f63;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100vw;
        }
    </style>
</head>

<body>
    <div class="flex flex-col">
        <div class=" overflow-x-auto sm:mx-0.5 lg:mx-0.5">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-x-auto rounded-2xl">
                    <table class="w-full table-fixed">
                        <div
                            class="bg-white w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0 h-[5rem] ">
                            <a type="button" id="createProductModalButton" href="{{ route('user.create') }}"
                                class="flex items-center justify-center bg-blue-800 text-white bg-primary-400 hover:bg-primary-100 rounded-full focus:ring-4 focus:ring-primary-300 font-medium text-xs px-4 py-2 dark:bg-primary-400 dark:hover:bg-primary-400 focus:outline-none dark:focus:ring-primary-400 mr-[1rem] cursor-pointer">
                                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                Tambah
                            </a>
                        </div>
                        <thead class="bg-white border-b border-0">
                            <tr>
                                <th scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-center border-b border-[#607FBB] ml-14 w-24">
                                    No
                                </th>
                                <th scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-center border-b border-[#607FBB] ml-14 w-24">
                                    File
                                </th>
                                <th scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-center border-b border-[#607FBB] ml-14 w-24">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $no = 0; ?>
                            @foreach ($news as $p)
                                <tr class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium text-gray-900">
                                        {{ ++$no }}</td>
                                    <td
                                        class="text-sm text-gray-900 font-light px-6 py-4 text-center whitespace-nowrap">
                                        {{ $p->file }}
                                    </td>
                                    <form action="{{ route('user.destroy', $p->id) }}" method="POST">
                                        <td class="flex px-6 py-2 items-center justify-center">
                                            @csrf
                                            @method('DELETE')
                                            <button class="my-5 mx-3">
                                                <img class="w-5 my-2 items-center justify-center"
                                                    src="{{ url('/img/bin.png') }}" alt="Delete">
                                            </button>
                                            <a href="#" class="edit-btn my-5 mx-3 cursor-pointer"
                                                data-file-url="{{ asset('storage/' . $p->file_path) }}"
                                                data-file-type="{{ pathinfo($p->file_path, PATHINFO_EXTENSION) }}">
                                                <img class="w-5 my-2 items-center justify-center"
                                                    src="{{ url('/img/view.png') }}" alt="View">
                                            </a>
                                        </td>
                                    </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div id="fileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>

    <script>
        // Modal functionality
        var modal = document.getElementById("fileModal");
        var span = document.getElementsByClassName("close")[0];

        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var fileUrl = this.getAttribute('data-file-url');
                var fileType = this.getAttribute('data-file-type');
                var modalBody = document.getElementById('modal-body');
                if (fileType === 'pdf') {
                    modalBody.innerHTML =
                        `<iframe src="${fileUrl}" style="width: 100%; height: 600px;" frameborder="0"></iframe>`;
                } else {
                    modalBody.innerHTML =
                        `<img src="${fileUrl}" style="width: 100%; height: 100%; object-fit: contain;">`;
                }
                modal.style.display = "block";
            });
        });

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>
