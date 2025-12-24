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
			@auth
				<div class="me-3">
					<span class="fs-5">Вы вошли как: <strong>{{ auth()->user()->name }}</strong></span>
				</div>
			@endauth

			<div class="d-flex">
                @auth
					<a href="{{ route('museums.create') }}" class="btn btn-primary fs-5 px-3 py-2 rounded">
						Добавить музей
					</a>
				@else
					<a href="{{ route('login') }}" class="btn btn-outline-primary fs-5 px-3 py-2 rounded">
						Войти для добавления
					</a>
				@endauth

				<a href="{{ route('museums.trash') }}" class="btn btn-outline-danger fs-5 px-3 py-2 rounded ms-2 position-relative">
					<i class="fas fa-trash"></i> Корзина
					@php
						$trashCount = App\Models\Museum::onlyTrashed()->count();
					@endphp
					@if($trashCount > 0)
						<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
							{{ $trashCount }}
							<span class="visually-hidden">удаленных музеев</span>
						</span>
					@endif
				</a>
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
                
                @if(auth()->check())
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Смотреть музеи пользователей:</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(App\Models\User::all() as $u)
                                <a href="{{ route('users.museums.index', $u->id) }}" 
                                   class="btn btn-sm {{ $u->id == (auth()->id() ?? 0) ? 'btn-success' : 'btn-outline-primary' }}">
                                    {{ $u->name }} (ID: {{ $u->id }})
                                </a>
                            @endforeach
                        </div>
                        <p class="mt-2 small text-muted">
                            Или введите в адресной строке: <code>/users/{id}/museums</code>
                        </p>
                    </div>
                </div>
                @endif
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
                            <span class="position-absolute bottom-0 start-0 bg-info text-white px-2 py-1 m-2 small">
                                @if($museum->user_id)
                                    <a href="{{ route('users.museums.index', $museum->user_id) }}">
                                        <i class="fas fa-user"></i> User ID: {{ $museum->user_id }}
                                    </a>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-user"></i> Без владельца
                                    </span>
                                @endif
                            </span>
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
                                    <a href="{{ route('users.museums.index', $museum->user_id) }}" 
                                        class="btn btn-outline-info btn-sm" 
                                        title="Смотреть все музеи этого пользователя">
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
                    @else
                        <p class="lead">Пока нет ни одного музея в базе данных.</p>
                    @endif
                    @auth
                        <a href="{{ route('museums.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus"></i> Добавить музей
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </main>
    
    <footer>
        <div class="container footer">
            <div class="author">Фамилия Имя</div>
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