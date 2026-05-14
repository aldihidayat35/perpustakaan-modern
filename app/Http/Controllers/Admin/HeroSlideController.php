<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeroSlide\StoreHeroSlideRequest;
use App\Http\Requests\HeroSlide\UpdateHeroSlideRequest;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index(): View
    {
        $slides = HeroSlide::orderBy('order')->paginate(10);

        return view('admin.hero-slides.index', compact('slides'));
    }

    public function store(StoreHeroSlideRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_url'] = $this->uploadImage($request->file('image'), 'slides');
        }

        if ($request->filled('illustration_type') && $request->illustration_type === 'image' && $request->hasFile('illustration_image')) {
            $data['illustration_image'] = $this->uploadImage($request->file('illustration_image'), 'illustrations');
        }

        HeroSlide::create($data);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide berhasil ditambahkan.');
    }

    public function update(UpdateHeroSlideRequest $request, HeroSlide $heroSlide): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $this->deleteImage($heroSlide->image_url);
            $data['image_url'] = $this->uploadImage($request->file('image'), 'slides');
        }

        if ($request->filled('illustration_type') && $request->illustration_type === 'image' && $request->hasFile('illustration_image')) {
            if ($heroSlide->illustration_image) {
                $this->deleteImage($heroSlide->illustration_image);
            }
            $data['illustration_image'] = $this->uploadImage($request->file('illustration_image'), 'illustrations');
        }

        $heroSlide->update($data);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide berhasil diperbarui.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $this->deleteImage($heroSlide->image_url);
        if ($heroSlide->illustration_image) {
            $this->deleteImage($heroSlide->illustration_image);
        }
        $heroSlide->delete();

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide berhasil dihapus.');
    }

    public function toggle(HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->update(['is_active' => !$heroSlide->is_active]);

        $status = $heroSlide->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Slide berhasil {$status}.");
    }

    private function uploadImage($file, string $folder = 'slides'): string
    {
        $path = $file->storeAs(
            $folder,
            time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        return $path;
    }

    private function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}