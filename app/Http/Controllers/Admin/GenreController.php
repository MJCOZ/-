<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GenreController extends Controller
{
    /**
     * قائمة التصنيفات.
     */
    public function index()
    {
        $genres = Genre::withCount('titles')->orderBy('name')->get();
        $genre = new Genre();

        return view('admin.genres.index', compact('genres', 'genre'));
    }

    /**
     * حفظ تصنيف جديد.
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        Genre::create($data);

        return redirect()->route('admin.genres.index')->with('status', 'تمت إضافة التصنيف.');
    }

    /**
     * نموذج تعديل تصنيف.
     */
    public function edit(Genre $genre)
    {
        $genres = Genre::withCount('titles')->orderBy('name')->get();

        return view('admin.genres.index', compact('genres', 'genre'));
    }

    /**
     * تحديث تصنيف.
     */
    public function update(Request $request, Genre $genre)
    {
        $data = $this->validateData($request, $genre);

        $genre->update($data);

        return redirect()->route('admin.genres.index')->with('status', 'تم تحديث التصنيف.');
    }

    /**
     * حذف تصنيف.
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();

        return redirect()->route('admin.genres.index')->with('status', 'تم حذف التصنيف.');
    }

    private function validateData(Request $request, ?Genre $genre = null): array
    {
        return $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('genres', 'name')->ignore($genre?->id),
            ],
        ], [], ['name' => 'اسم التصنيف']);
    }

    private function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name) ?: Str::random(8);
        $base = $slug;
        $i = 1;
        while (Genre::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
