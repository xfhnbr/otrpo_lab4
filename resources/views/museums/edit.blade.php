<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Редактирование: {{ $museum->name_ru }} - Музеи Рима</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/scss/app.scss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="nav-header w-100">
        <div class="container d-flex align-items-center py-2">
            <div class="logo fs-1 text-center">d</div>
            <div class="site-name flex-grow-1 fs-1 fw-bold ms-3">Редактирование музея</div>
            <a href="{{ route('museums.show', $museum) }}" class="btn btn-outline-secondary fs-5 px-3 py-2 rounded">
                Назад к музею
            </a>
        </div>
    </nav>

    <main>
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow">
                        <div class="card-header bg-warning text-dark">
                            <h4 class="mb-0"><i class="fas fa-edit"></i> Редактирование музея: {{ $museum->name_ru }}</h4>
                        </div>
                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <h5>Исправьте следующие ошибки:</h5>
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('museums.update', $museum) }}" enctype="multipart/form-data">
                                @include('museums._form')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="mt-5">
        <div class="container footer">
            <div class="author">Шестаков Дмитрий</div>
            <div class="socials">
                <a href="#"><img src="{{ asset('storage/museums/vk.svg') }}" alt="VK" width="24"></a>
                <a href="#"><img src="{{ asset('storage/museums/telegram.svg') }}" alt="Telegram" width="24"></a>
            </div>
        </div>
    </footer>

    @vite('resources/js/app.js')
</body>
</html>