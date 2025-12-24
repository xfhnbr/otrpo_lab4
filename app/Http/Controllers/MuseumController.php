<?php

namespace App\Http\Controllers;

use App\Models\Museum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MuseumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $museums = Museum::with('popovers')->orderBy('id', 'asc')->get();
        return view('museums.index', compact('museums'));
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
    public function show(Museum $museum)
    {
		if ($museum->trashed()) {
            abort(404, 'Музей был удален');
        }
        $museum->load('popovers');
        return view('museums.show', compact('museum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Museum $museum)
    {
		if ($museum->trashed()) {
            abort(404, 'Музей был удален');
        }
        return view('museums.edit', compact('museum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Museum $museum)
    {
		if ($museum->trashed()) {
            abort(404, 'Музей был удален');
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
                Storage::delete('public/museums/' . $museum->image_filename);
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
        
        $museum->restore();
        
        return redirect()->route('museums.show', $museum)
            ->with('success', 'Музей успешно восстановлен');
    }
	
	public function forceDelete($id)
    {
        $museum = Museum::withTrashed()->findOrFail($id);
        
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
		$museums = Museum::onlyTrashed()
			->with('popovers')
			->orderBy('deleted_at', 'desc')
			->get();
			
		return view('museums.trash', compact('museums'));
	}
	
	public function forceDeleteAll()
	{
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