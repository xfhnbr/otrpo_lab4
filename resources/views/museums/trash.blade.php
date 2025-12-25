<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Корзина музеев - Музеи Рима</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/scss/app.scss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="nav-header w-100">
        <div class="container d-flex align-items-center py-2">
            <div class="logo fs-1 text-center">d</div>
            <div class="site-name flex-grow-1 fs-1 fw-bold ms-3">Карта музеев Рима и Ватикана</div>
            <a href="{{ route('museums.index') }}" class="btn btn-outline-secondary fs-5 px-3 py-2 rounded">
                Назад к списку
            </a>
        </div>
    </nav>

    <main>
        <div class="container mt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('museums.index') }}">Все музеи</a></li>
                    <li class="breadcrumb-item active">Корзина</li>
                </ol>
            </nav>

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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0"><i class="fas fa-trash"></i> Корзина музеев</h1>
                <div>
                    @php
                        $trashCount = App\Models\Museum::onlyTrashed()->count();
                    @endphp
                    @if($trashCount > 0)
                        <span class="badge bg-danger fs-6">{{ $trashCount }} музеев</span>
                    @endif
                </div>
            </div>

            @if($museums->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Корзина пуста
                </div>
            @else
                <div class="row">
                    @foreach($museums as $museum)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow">
                                <div class="position-relative">
                                    @if($museum->image_filename)
                                        <img src="{{ $museum->image_url }}" 
                                             class="card-img-top" 
                                             alt="{{ $museum->name_ru }}"
                                             style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="position-absolute top-0 end-0 bg-danger text-white p-2">
                                        <i class="fas fa-trash"></i> Удален
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title">{{ $museum->name_ru }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $museum->name_original }}</h6>
                                    
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-times"></i> 
                                            Удален: {{ $museum->deleted_at_formatted }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            ({{ $museum->deleted_at->diffForHumans() }})
                                        </small>
                                    </p>
                                    
                                    <div class="mt-3">
                                        <div class="btn-group w-100" role="group">
                                            <form action="{{ route('museums.restore', $museum->id) }}" 
                                                  method="POST" 
                                                  class="d-inline w-50">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" 
                                                        class="btn btn-success btn-sm w-100"
                                                        onclick="return confirm('Восстановить музей «{{ $museum->name_ru }}»?')">
                                                    <i class="fas fa-trash-restore"></i> Восстановить
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('museums.force-delete', $museum->id) }}" 
                                                  method="POST" 
                                                  class="d-inline w-50">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm w-100"
                                                        onclick="return confirm('Музей «{{ $museum->name_ru }}» будет удален навсегда, включая изображение и все данные. Продолжить?')">
                                                    <i class="fas fa-fire"></i> Удалить навсегда
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Всего удалено: {{ $museums->count() }} музеев
                    </div>
                    <div>
                        <form action="{{ route('museums.forceDeleteAll') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-outline-danger"
                                    onclick="return confirm('ВНИМАНИЕ! Это действие удалит ВСЕ музеи в корзине навсегда. Это действие нельзя отменить. Продолжить?')">
                                <i class="fas fa-broom"></i> Очистить корзину полностью
                            </button>
                        </form>
                    </div>
                </div>
            @endif
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
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>