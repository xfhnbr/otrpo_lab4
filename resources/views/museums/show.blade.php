<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{{ $museum->name_ru }} - Музеи Рима</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="nav-header w-100">
		<div class="container d-flex align-items-center py-2">
			<div class="logo fs-1 text-center">d</div>
			<div class="site-name flex-grow-1 fs-1 fw-bold ms-3">Карта музеев Рима и Ватикана</div>
			<div class="d-flex">
				<a href="{{ route('museums.index') }}" class="btn btn-outline-secondary fs-5 px-3 py-2 rounded">
					Назад к списку
				</a>
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
                <div class="col-md-8">
                    <h1>{{ $museum->name_ru }}</h1>
                    <h4 class="text-muted">{{ $museum->name_original }}</h4>
                    
                    <div class="mt-4">
                        <img src="{{ $museum->image_url }}" class="img-fluid rounded shadow" alt="{{ $museum->name_ru }}" style="max-height: 500px; width: 100%; object-fit: cover;">
                    </div>

                    <div class="mt-4">
                        <h3>Описание</h3>
                        <div class="p-4 bg-light rounded">
                            {!! $museum->formatted_description !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
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

                            <div class="d-grid gap-2 mt-4">
                                <a href="{{ route('museums.edit', $museum) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Редактировать музей
                                </a>
                                
                                <form action="{{ route('museums.destroy', $museum) }}" method="POST" 
                                      onsubmit="return confirm('Вы уверены, что хотите удалить музей «{{ $museum->name_ru }}»?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> Удалить музей
                                    </button>
                                </form>
								
                                <p><strong>Дата добавления:</strong> {{ $museum->created_at_formatted }} ({{ $museum->created_at_human }})</p>
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

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>