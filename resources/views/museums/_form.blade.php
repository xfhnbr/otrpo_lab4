@csrf

@if(isset($museum))
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="name_ru" class="form-label">Название на русском *</label>
            <input type="text" class="form-control @error('name_ru') is-invalid @enderror" 
                   id="name_ru" name="name_ru" 
                   value="{{ old('name_ru', $museum->name_ru ?? '') }}" 
                   required minlength="3" maxlength="255">
            <div class="form-text">Полное название музея на русском языке</div>
            @error('name_ru')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="name_original" class="form-label">Оригинальное название *</label>
            <input type="text" class="form-control @error('name_original') is-invalid @enderror" 
                   id="name_original" name="name_original" 
                   value="{{ old('name_original', $museum->name_original ?? '') }}" 
                   required>
            <div class="form-text">Название на языке оригинала (итальянском, латинском)</div>
            @error('name_original')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Краткое описание *</label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                      id="description" name="description" rows="3" 
                      required>{{ old('description', $museum->description ?? '') }}</textarea>
            <div class="form-text">Краткое описание для карточки (максимум 200 символов)</div>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="detailed_description" class="form-label">Подробное описание *</label>
            <textarea class="form-control @error('detailed_description') is-invalid @enderror" 
                      id="detailed_description" name="detailed_description" rows="8" 
                      required>{{ old('detailed_description', $museum->detailed_description ?? '') }}</textarea>
            <div class="form-text">Полное описание музея. Для popovers используйте квадратные скобки: [текст для popover]</div>
            @error('detailed_description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label for="address" class="form-label">Адрес *</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                   id="address" name="address" 
                   value="{{ old('address', $museum->address ?? '') }}" 
                   required>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="working_hours" class="form-label">Часы работы *</label>
            <input type="text" class="form-control @error('working_hours') is-invalid @enderror" 
                   id="working_hours" name="working_hours" 
                   value="{{ old('working_hours', $museum->working_hours ?? '') }}" 
                   required>
            <div class="form-text">Например: "9:00-19:00 (вт-вс)"</div>
            @error('working_hours')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="ticket_price" class="form-label">Стоимость билета (€) *</label>
            <input type="number" step="0.01" min="0" class="form-control @error('ticket_price') is-invalid @enderror" 
                   id="ticket_price" name="ticket_price" 
                   value="{{ old('ticket_price', $museum->ticket_price ?? '') }}" 
                   required>
            @error('ticket_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="website_url" class="form-label">Сайт музея</label>
            <input type="url" class="form-control @error('website_url') is-invalid @enderror" 
                   id="website_url" name="website_url" 
                   value="{{ old('website_url', $museum->website_url ?? '') }}"
                   placeholder="https://example.com">
            @error('website_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">
                Изображение {{ !isset($museum) ? '*' : '' }}
            </label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                   id="image" name="image" 
                   accept="image/*"
                   {{ !isset($museum) ? 'required' : '' }}>
            <div class="form-text">
                Допустимые форматы: JPEG, PNG, GIF. Максимальный размер: 2MB
            </div>
            
            @if(isset($museum) && $museum->image_filename)
                <div class="mt-2">
                    <img src="{{ $museum->image_url }}" alt="Текущее изображение" 
                         class="img-thumbnail" width="150">
                    <p class="text-muted small mt-1">Текущее изображение</p>
                </div>
            @endif
            
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary btn-lg">
        <i class="fas fa-save"></i> {{ isset($museum) ? 'Обновить музей' : 'Создать музей' }}
    </button>
    <a href="{{ route('museums.index') }}" class="btn btn-secondary btn-lg">Отмена</a>
</div>