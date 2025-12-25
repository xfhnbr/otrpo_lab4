<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Музеи Рима и Ватикана</title>
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
            @if(isset($user) && $user)
                <h1 class="mt-4 mb-4">Музеи пользователя: {{ $user->name }} (ID: {{ $user->id }})</h1>
                <p class="mb-4">
                    <a href="{{ route('museums.index') }}" class="btn btn-outline-secondary btn-sm">
                        ← Вернуться ко всем музеям
                    </a>
                </p>
            @else
                <h1 class="mt-4 mb-4">Все музеи Рима и Ватикана</h1>
            @endif

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
                @foreach($museums as $museum)
                <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 col-xxxl-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-img-top position-relative">
                            <img src="{{ $museum->image_url }}" class="card-img-top img-fluid" alt="{{ $museum->name_ru }}" style="height: 200px; object-fit: cover;">
                            <span class="position-absolute top-0 start-0 bg-dark text-white px-2 py-1 m-2 small">{{ $museum->name_original }}</span>
                            @if($museum->trashed())
                                <span class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 small">
                                    <i class="fas fa-trash"></i> Удален
                                </span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $museum->name_ru }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($museum->description, 100) }}</p>
                            
                            <div class="museum-info mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ Str::limit($museum->address, 30) }}<br>
                                    <i class="fas fa-clock"></i> {{ $museum->working_hours }}<br>
                                    <i class="fas fa-ticket-alt"></i> {{ $museum->formatted_price }}
                                </small>
                            </div>
                            
                            <div class="btn-group mt-auto">
								<a href="{{ route('museums.show', $museum) }}" class="btn btn-outline-primary btn-sm">
									<i class="fas fa-info-circle"></i> Подробнее
								</a>
                                @if($museum->user_id)
                                    <a href="{{ route('users.show', $museum->user->name) }}" 
                                        class="btn btn-outline-info btn-sm" 
                                        title="Смотреть профиль владельца">
                                    <i class="fas fa-user"></i>
                                </a>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-user"></i> Без владельца
                                    </span>
                                @endif
                                
							</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($museums->isEmpty())
                <div class="text-center py-5">
                    <h3 class="text-muted">Музеи не найдены</h3>
                    
                    @if(isset($user) && $user)
                        <p class="lead">У пользователя {{ $user->name }} пока нет музеев.</p>
                        
                        @auth
                            @if($user->id === auth()->id())
                                <a href="{{ route('museums.create') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-plus"></i> Добавить музей
                                </a>
                            @endif
                        @endauth
                    @else
                        <p class="lead">Пока нет ни одного музея в базе данных.</p> 
                        
                        @auth
                            <a href="{{ route('museums.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus"></i> Добавить музей
                            </a>
                        @endauth
                    @endif
                    
                </div>
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