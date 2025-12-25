<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Профиль {{ $user->name }} - Музеи Рима и Ватикана</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/scss/app.scss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="nav-header w-100">
        <div class="container d-flex align-items-center py-2">
            <div class="logo fs-1 text-center">d</div>
            <div class="site-name flex-grow-1 fs-1 fw-bold ms-3">Карта музеев Рима и Ватикана</div>

            <div class="d-flex align-items-center">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle fs-5 px-3 py-2 rounded" 
                                type="button" 
                                id="userDropdown"
                                data-bs-toggle="dropdown" 
                                data-bs-auto-close="true"
                                aria-expanded="false">
                            <i class="fas fa-user me-1"></i> {{ Str::limit(auth()->user()->name, 15) }}
                            @if(auth()->user()->is_admin)
                                <span class="badge bg-danger ms-1">A</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('museums.create') }}">
                                    <i class="fas fa-plus me-3" style="width: 20px; text-align: center;"></i>
                                    <span>Добавить музей</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-3" style="width: 20px; text-align: center;"></i>
                                    <span>Профиль</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('users.index') }}">
                                    <i class="fas fa-users me-3" style="width: 20px; text-align: center;"></i>
                                    <span>Все пользователи</span>
                                </a>
                            </li>
                            @if(auth()->user()->is_admin)
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('museums.trash') }}">
                                        <i class="fas fa-trash me-3" style="width: 20px; text-align: center;"></i>
                                        <span>Корзина</span>
                                        @php
                                            $trashCount = \App\Models\Museum::onlyTrashed()->count();
                                        @endphp
                                        @if($trashCount > 0)
                                            <span class="badge bg-danger ms-auto">{{ $trashCount }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider my-2"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center py-2 w-100 border-0 bg-transparent">
                                        <i class="fas fa-sign-out-alt me-3" style="width: 20px; text-align: center;"></i>
                                        <span>Выйти</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="d-flex">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary fs-5 px-3 py-2 rounded me-2">
                            <i class="fas fa-sign-in-alt me-1"></i> Войти
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary fs-5 px-3 py-2 rounded">
                            <i class="fas fa-user-plus me-1"></i> Регистрация
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        <div class="container py-4">
            <div class="row mb-4">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('users.index') }}">Пользователи</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                        </ol>
                    </nav>
                    
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-user text-white fa-2x"></i>
                        </div>
                        <div>
                            <h1 class="mb-0">{{ $user->name }}</h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-envelope"></i> {{ $user->email }}
                                @if($user->is_admin)
                                    <span class="badge bg-danger ms-2">Администратор</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Все пользователи
                    </a>
                    <a href="{{ route('users.museums.index', $user->name) }}" class="btn btn-primary">
                        <i class="fas fa-museum"></i> Все музеи
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Информация</h5>
                        </div>
                        <div class="card-body">
                            <p><strong><i class="fas fa-id-card"></i> ID:</strong> {{ $user->id }}</p>
                            <p><strong><i class="fas fa-user-tag"></i> Роль:</strong>
                                @if($user->is_admin)
                                    <span class="badge bg-danger">Администратор</span>
                                @else
                                    <span class="badge bg-secondary">Пользователь</span>
                                @endif
                            </p>
                            <p><strong><i class="fas fa-calendar-alt"></i> Зарегистрирован:</strong>
                                {{ $user->created_at->format('d.m.Y H:i') }}
                            </p>
                            <p><strong><i class="fas fa-clock"></i> Последнее обновление:</strong>
                                {{ $user->updated_at->format('d.m.Y H:i') }}
                            </p>
                            <p><strong><i class="fas fa-museum"></i> Музеев создано:</strong>
                                <span class="badge bg-primary">{{ $user->museums_count }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-museum"></i> Последние музеи</h5>
                        </div>
                        <div class="card-body">
                            @if($museums->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-museum fa-3x text-muted mb-3"></i>
                                    <h5>Музеи не найдены</h5>
                                    <p class="text-muted">У этого пользователя пока нет музеев</p>
                                </div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach($museums as $museum)
                                        <a href="{{ route('museums.show', $museum) }}" 
                                           class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $museum->name_ru }}</h6>
                                                <small>{{ $museum->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1 text-muted">{{ $museum->short_description }}</p>
                                            <small>
                                                <i class="fas fa-ticket-alt"></i> {{ $museum->formatted_price }}
                                                <i class="fas fa-map-marker-alt ms-3"></i> {{ $museum->address_oneline }}
                                            </small>
                                        </a>
                                    @endforeach
                                </div>
                                
                                @if($museums->hasMorePages())
                                    <div class="text-center mt-3">
                                        <a href="{{ route('users.museums.index', $user->name) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            Показать все музеи
                                        </a>
                                    </div>
                                @endif
                            @endif
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
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
        
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl);
        });
        
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
    </script>
</body>
</html>