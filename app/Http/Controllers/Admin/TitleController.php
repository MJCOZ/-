<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Title;
use Illuminate\Http\Request;

class TitleController extends Controller
{
    /**
     * قائمة كل الأعمال.
     */
    public function index()
    {
        $titles = Title::with('genre')->withCount('reviews')->latest()->paginate(15);

        return view('admin.titles.index', compact('titles'));
    }

    /**
     * نموذج إضافة عمل.
     */
    public function create()
    {
        $title = new Title();
        $genres = Genre::orderBy('name')->get();

        return view('admin.titles.form', compact('title', 'genres'));
    }

    /**
     * حفظ عمل جديد.
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Title::create($data);

        return redirect()->route('admin.titles.index')->with('status', 'تمت إضافة العمل بنجاح.');
    }

    /**
     * نموذج تعديل عمل.
     */
    public function edit(Title $title)
    {
        $genres = Genre::orderBy('name')->get();

        return view('admin.titles.form', compact('title', 'genres'));
    }

    /**
     * تحديث عمل.
     */
    public function update(Request $request, Title $title)
    {
        $data = $this->validateData($request);

        $title->update($data);

        return redirect()->route('admin.titles.index')->with('status', 'تم تحديث العمل بنجاح.');
    }

    /**
     * حذف عمل.
     */
    public function destroy(Title $title)
    {
        $title->delete();

        return redirect()->route('admin.titles.index')->with('status', 'تم حذف العمل.');
    }

    /**
     * قواعد التحقق من بيانات العمل.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:movie,series'],
            'genre_id' => ['nullable', 'exists:genres,id'],
            'release_year' => ['nullable', 'integer', 'between:1900,2100'],
            'poster' => ['nullable', 'string', 'max:2048'],
            'description' => ['nullable', 'string', 'max:5000'],
        ], [], [
            'name' => 'الاسم',
            'type' => 'النوع',
            'genre_id' => 'التصنيف',
            'release_year' => 'سنة الإصدار',
            'poster' => 'رابط البوستر',
            'description' => 'الوصف',
        ]);
    }
}
