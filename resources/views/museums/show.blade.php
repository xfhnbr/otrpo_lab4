<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{{ $museum->name_ru }} - Музеи Рима</title>
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
        <div class="container mt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('museums.index') }}">Все музеи</a></li>
                    <li class="breadcrumb-item active">{{ $museum->name_ru }}</li>
                </ol>
            </nav>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <h1>{{ $museum->name_ru }}</h1>
                    <h4 class="text-muted">{{ $museum->name_original }}</h4>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="mb-4">
                        <img src="{{ $museum->image_url }}" class="img-fluid rounded shadow" alt="{{ $museum->name_ru }}" style="max-height: 500px; width: 100%; object-fit: cover;">
                    </div>

                    <div>
                        <h3>Описание</h3>
                        <div class="p-4 bg-light rounded">
                            {!! $museum->formatted_description !!}
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow" style="margin-top: 0;">
                        <div class="card-header text-white" style="background-color: #94a364;">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Информация о музее</h5>
                        </div>
                        <div class="card-body">
                            <p><strong><i class="fas fa-map-marker-alt"></i> Адрес:</strong><br>{{ $museum->address }}</p>
                            <p><strong><i class="fas fa-clock"></i> Часы работы:</strong><br>{{ $museum->working_hours }}</p>
                            <p><strong><i class="fas fa-ticket-alt"></i> Стоимость билета:</strong><br>{{ $museum->formatted_price }}</p>
                            
                            @if($museum->website_url)
                                <p><strong><i class="fas fa-globe"></i> Сайт:</strong><br>
                                    <a href="{{ $museum->website_url }}" target="_blank" class="text-decoration-none">
                                        {{ parse_url($museum->website_url, PHP_URL_HOST) }}
                                    </a>
                                </p>
                            @endif
                            <p><strong><i class="fas fa-user"></i> Владелец:</strong><br>
                                @if($museum->user)
                                    <a href="{{ route('users.show', $museum->user->name) }}">
                                        <i class="fas fa-user"></i> {{ $museum->user->name }}
                                    </a>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-user"></i> Без владельца
                                    </span>
                                @endif
                            </p>

                            <div class="d-grid gap-2 mt-4">
                                @can('update-museum', $museum)
                                    <a href="{{ route('museums.edit', $museum) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Редактировать музей
                                    </a>
                                @endcan
                                
                                @if(!$museum->trashed())
                                    @can('delete-museum', $museum)
                                        <form action="{{ route('museums.destroy', $museum) }}" method="POST" 
                                            onsubmit="return confirm('Вы уверены, что хотите удалить музей «{{ $museum->name_ru }}» в корзину?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="fas fa-trash"></i> Удалить в корзину
                                            </button>
                                        </form>
                                    @endcan
                                @endif
                                
                                @if($museum->trashed())
                                    <div class="alert alert-warning mb-3">
                                        <i class="fas fa-exclamation-triangle"></i> Этот музей находится в корзине
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        @can('restore-museum')
                                            <form action="{{ route('museums.restore', $museum->id) }}" method="POST" class="flex-grow-1">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="fas fa-undo me-1"></i> Восстановить
                                                </button>
                                            </form>
                                        @endcan
                                        
                                        @can('force-delete-museum')
                                            <form action="{{ route('museums.force-delete', $museum->id) }}" method="POST" class="flex-grow-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100" 
                                                        onclick="return confirm('ВНИМАНИЕ! Музей будет удален полностью без возможности восстановления. Продолжить?')">
                                                    <i class="fas fa-fire me-1"></i> Удалить навсегда
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                @endif
                                <p class="mt-3 mb-0"><strong>Дата добавления:</strong> {{ $museum->created_at_formatted }} ({{ $museum->created_at_human }})</p>
                            </div>
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