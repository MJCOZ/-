<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('titles')->orderBy('name')->get();
        $tag = new Tag();

        return view('admin.tags.index', compact('tags', 'tag'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        Tag::create($data);

        return redirect()->route('admin.tags.index')->with('status', 'تمت إضافة الوسم.');
    }

    public function edit(Tag $tag)
    {
        $tags = Tag::withCount('titles')->orderBy('name')->get();

        return view('admin.tags.index', compact('tags', 'tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $this->validateData($request, $tag);

        $tag->update($data);

        return redirect()->route('admin.tags.index')->with('status', 'تم تحديث الوسم.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('status', 'تم حذف الوسم.');
    }

    private function validateData(Request $request, ?Tag $tag = null): array
    {
        return $request->validate([
            'name' => [
                'required', 'string', 'max:50',
                Rule::unique('tags', 'name')->ignore($tag?->id),
            ],
        ], [], ['name' => 'اسم الوسم']);
    }

    private function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name) ?: Str::random(8);
        $base = $slug;
        $i = 1;
        while (Tag::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
