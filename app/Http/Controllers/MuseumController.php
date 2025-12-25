<?php

namespace App\Http\Controllers;

use App\Models\Museum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class MuseumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $identifier = null)
    {
        $user = null;
        
        // Если передан identifier в URL (/users/{identifier}/museums)
        if ($identifier) {
            // Определяем, это ID или имя
            if (is_numeric($identifier)) {
                $user = User::findOrFail($identifier);
            } else {
                $user = User::whereRaw('LOWER(name) = LOWER(?)', [$identifier])->firstOrFail();
            }
            
            $museums = Museum::where('user_id', $user->id)
                            ->with('popovers')
                            ->orderBy('id', 'asc')
                            ->get();
            
            return view('museums.index', compact('museums', 'user'));
        }
        
        // Если передан user_id в query string (?user_id=...)
        $user_id = $request->get('user_id');
        if ($user_id) {
            $user = User::findOrFail($user_id);
            $museums = Museum::where('user_id', $user_id)
                            ->with('popovers')
                            ->orderBy('id', 'asc')
                            ->get();
            return view('museums.index', compact('museums', 'user'));
        }
        
        $museums = Museum::with('popovers')->orderBy('id', 'asc')->get();
        return view('museums.index', compact('museums'));
    }


    public function userMuseums(User $user)
    {
        $currentUser = auth()->user();
        
        $isAdmin = $currentUser && $currentUser->is_admin;
        
        if ($isAdmin) {
            $museums = Museum::withTrashed()
                ->where('user_id', $user->id)
                ->with('popovers')
                ->orderBy('deleted_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $museums = Museum::where('user_id', $user->id)
                ->with('popovers')
                ->orderBy('id', 'asc')
                ->get();
        }
        
        return view('museums.index', compact('museums', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('museums.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ru' => 'required|string|max:255',
            'name_original' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'detailed_description' => 'required|string',
            'address' => 'required|string|max:500',
            'working_hours' => 'required|string|max:100',
            'ticket_price' => 'required|numeric|min:0',
            'website_url' => 'nullable|url',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $validated['user_id'] = auth()->id();

        // Обработка изображения
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            
            // Сохранение в storage
            $image->storeAs('museums', $filename, 'public');
            $validated['image_filename'] = $filename;
        }

        // Создание музея
        $museum = Museum::create($validated);
        
        return redirect()->route('museums.show', $museum)
            ->with('success', 'Музей успешно создан');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $museum = Museum::withTrashed()->find($id);

        if(!$museum)
        {
            abort(404, 'Музей не найден');
        }

		if ($museum->trashed() && !Gate::allows('view-trash')) {
            abort(404, 'Музей был удален');
        }

        $museum->load('popovers');
        return view('museums.show', compact('museum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $museum = Museum::withTrashed()->find($id);
		
        if (!$museum) {
            abort(404, 'Музей не найден');
        }
        
        if (!Gate::allows('update-museum', $museum)) {
            abort(403, 'У вас нет прав для редактирования этого музея');
        }

        return view('museums.edit', compact('museum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
		$museum = Museum::withTrashed()->find($id);
        
        if (!$museum) {
            abort(404, 'Музей не найден');
        }

        if (!Gate::allows('update-museum', $museum)) {
            abort(403, 'У вас нет прав для редактирования этого музея');
        }
		
        $validated = $request->validate([
            'name_ru' => 'required|string|max:255',
            'name_original' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'detailed_description' => 'required|string',
            'address' => 'required|string|max:500',
            'working_hours' => 'required|string|max:100',
            'ticket_price' => 'required|numeric|min:0',
            'website_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($museum->image_filename) {
                $path = 'museums/' . $museum->image_filename;
                
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('museums', $filename, 'public');
            $validated['image_filename'] = $filename;
        }

        $museum->update($validated);
        
        return redirect()->route('museums.show', $museum)
            ->with('success', 'Музей успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Museum $museum)
	{

		if ($museum->trashed()) {
			return redirect()->route('museums.index')
				->with('error', 'Музей уже удален');
		}

        if (!Gate::allows('delete-museum', $museum)) {
            abort(403, 'У вас нет прав для удаления этого музея');
        }
		
		$museum->delete();
		
		return redirect()->route('museums.index')
			->with('success', 'Музей «' . $museum->name_ru . '» перемещен в корзину!');
	}
	
	public function restore($id)
    {
        $museum = Museum::withTrashed()->findOrFail($id);
        
        if (!$museum->trashed()) {
            return redirect()->route('museums.index')
                ->with('error', 'Музей не был удален');
        }
        
        if (!Gate::allows('restore-museum', $museum)) {
            abort(403, 'Только администратор может восстанавливать удаленные музеи');
        }

        $museum->restore();
        
        return redirect()->route('museums.show', $museum)
            ->with('success', 'Музей успешно восстановлен');
    }
	
	public function forceDelete($id)
    {
        $museum = Museum::withTrashed()->findOrFail($id);
        
        if (!Gate::allows('force-delete-museum', $museum)) {
            abort(403, 'Только администратор может полностью удалять музеи');
        }

        if ($museum->image_filename) {
            $path = 'museums/' . $museum->image_filename;
            
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $museum->popovers()->delete();
        
        $museum->forceDelete();
        
        return redirect()->route('museums.trash')
            ->with('success', 'Музей полностью удален');
    }
	
	public function trash()
	{
        if (!Gate::allows('view-trash')) {
            abort(403, 'Только администратор может просматривать корзину');
        }

		$museums = Museum::onlyTrashed()
			->with('popovers')
			->orderBy('deleted_at', 'desc')
			->get();
			
		return view('museums.trash', compact('museums'));
	}
	
	public function forceDeleteAll()
	{
        if (!Gate::allows('force-delete-all')) {
            abort(403, 'Только администратор может очищать корзину');
        }
        
		$museums = Museum::onlyTrashed()->get();
		
		$deletedCount = 0;
		
		foreach ($museums as $museum) {
			if ($museum->image_filename) {
				$path = 'museums/' . $museum->image_filename;
				
				if (Storage::disk('public')->exists($path)) {
					Storage::disk('public')->delete($path);
				}
			}
			
			$museum->popovers()->delete();
			
			$museum->forceDelete();
			$deletedCount++;
		}
		
		if ($deletedCount > 0) {
			$message = $deletedCount == 1 
				? '1 музей полностью удален из корзины' 
				: "{$deletedCount} музеев полностью удалены из корзины";
		} else {
			$message = 'Корзина уже была пуста';
		}
		
		return redirect()->route('museums.trash')
			->with('success', $message);
	}
}