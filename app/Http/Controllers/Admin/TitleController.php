<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $data = $this->handlePoster($request, $data);

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
        $data = $this->handlePoster($request, $data, $title);

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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:movie,series'],
            'genre_id' => ['nullable', 'exists:genres,id'],
            'release_year' => ['nullable', 'integer', 'between:1900,2100'],
            'poster' => ['nullable', 'string', 'max:2048'],
            'poster_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'description' => ['nullable', 'string', 'max:5000'],
            'imdb_rating' => ['nullable', 'numeric', 'between:0,10'],
            'rt_rating' => ['nullable', 'integer', 'between:0,100'],
            'personal_rating' => ['nullable', 'integer', 'between:1,10'],
            'watched' => ['nullable', 'boolean'],
            'watched_at' => ['nullable', 'date'],
        ], [], [
            'name' => 'الاسم',
            'type' => 'النوع',
            'genre_id' => 'التصنيف',
            'release_year' => 'سنة الإصدار',
            'poster' => 'رابط البوستر',
            'poster_file' => 'ملف البوستر',
            'description' => 'الوصف',
            'imdb_rating' => 'تقييم IMDb',
            'rt_rating' => 'تقييم Rotten Tomatoes',
            'personal_rating' => 'تقييمي الشخصي',
        ]);

        // صندوق الاختيار: غير مرسل = false
        $validated['watched'] = $request->boolean('watched');

        return $validated;
    }

    /**
     * معالجة بوستر العمل: ملف مرفوع له الأولوية، وإلا الرابط النصّي.
     */
    private function handlePoster(Request $request, array $data, ?Title $title = null): array
    {
        unset($data['poster_file']);

        if ($request->hasFile('poster_file')) {
            // حذف الصورة القديمة إن كانت ملفاً مرفوعاً
            if ($title && $title->poster && ! str_starts_with($title->poster, 'http')) {
                Storage::disk('public')->delete($title->poster);
            }

            $data['poster'] = $request->file('poster_file')->store('posters', 'public');
        } elseif ($title && blank($data['poster'] ?? null)) {
            // إبقاء البوستر الحالي إن لم يُرسل رابط أو ملف جديد
            $data['poster'] = $title->poster;
        }

        return $data;
    }
}
