<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Все пользователи - Музеи Рима и Ватикана</title>
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
        <div class="container">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="mt-4 mb-2">
                        <i class="fas fa-users primary"></i> Все пользователи
                    </h1>
                    <p class="lead text-muted">Просмотр всех зарегистрированных пользователей и их музеев</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('museums.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Назад к музеям
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

            @if($users->isEmpty())
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>Пользователи не найдены</h4>
                    <p class="mb-0">В системе пока нет зарегистрированных пользователей</p>
                </div>
            @else
                <div class="row">
                    @foreach($users as $user)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card-user card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="card-title mb-1">
                                                {{ $user->name }}
                                                @if($user->is_admin)
                                                    <span class="badge badge-admin ms-1">Админ</span>
                                                @endif
                                            </h5>
                                            <p class="card-text text-muted small mb-2 user-email">
                                                <i class="fas fa-envelope icon-email"></i> {{ $user->email }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="user-stats mb-3">
                                        <div class="row g-2">
                                            <div class="stats-item">
                                                <div class="stats-count">{{ $user->museums_count }}</div>
                                                <div class="stats-label">Активных музеев</div>
                                            </div>

                                            @if(auth()->user()->is_admin)
                                            <div class="stats-item">
                                                <div class="stats-count stats-deleted">{{ $user->deleted_museums_count }}</div>
                                                <div class="stats-label">Удалённых музеев</div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="user-actions d-grid gap-2">
                                        <a href="{{ route('users.show', $user) }}" 
                                        class="btn btn-outline btn-profile btn-sm">
                                            <i class="fas fa-user-circle"></i> Профиль
                                        </a>
                                        <a href="{{ route('users.museums.index', $user->name) }}" 
                                        class="btn btn-primary btn-museums btn-sm">
                                            <i class="fas fa-museum"></i> Музеи
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt"></i> Зарегистрирован: 
                                        {{ $user->created_at->format('d.m.Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($users->hasPages())
                    <div class="mt-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                {{ $users->links() }}
                            </ul>
                        </nav>
                    </div>
                @endif
            @endif
        </div>
    </main>
    
    <footer>
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