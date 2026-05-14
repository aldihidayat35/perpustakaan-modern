<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $categoryId = $request->integer('category');

        $categories = Category::withCount('books')->orderBy('name')->get();

        // Get active hero slides
        $slides = HeroSlide::active()->get();

        $query = Book::with('category')
            ->where('status', 'available')
            ->where('stock', '>', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Group books by rack location for shelves
        $shelves = $query->get()->groupBy('rack_location')->take(6);

        // Popular books
        $popularBooks = Book::with('category')
            ->where('status', 'available')
            ->where('stock', '>', 0)
            ->orderBy('stock', 'desc')
            ->take(8)
            ->get();

        // All filtered books for display
        $books = $query->latest()->paginate(12)->withQueryString();

        $totalBooks = Book::where('status', 'available')->count();
        $totalCategories = Category::count();

        return view('landing.index', compact(
            'categories',
            'shelves',
            'popularBooks',
            'books',
            'search',
            'totalBooks',
            'totalCategories',
            'slides'
        ));
    }

    public function books(Request $request): View
    {
        $search = $request->string('search')->toString();
        $categoryId = $request->integer('category');

        $categories = Category::withCount('books')->orderBy('name')->get();

        $query = Book::with('category')
            ->where('status', 'available')
            ->where('stock', '>', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $books = $query->latest()->paginate(12)->withQueryString();

        return view('landing.books', compact('categories', 'books', 'search'));
    }

    public function showBook(Book $book): View
    {
        $book->load('category');

        return view('landing.book-detail', compact('book'));
    }

    public function categories(): View
    {
        $categories = Category::withCount(['books' => fn ($q) => $q->where('status', 'available')])
            ->orderBy('name')
            ->get();

        return view('landing.categories', compact('categories'));
    }
}
