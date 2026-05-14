<?php

declare(strict_types=1);

use App\Enums\BookStatus;
use App\Enums\MemberStatus;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a book successfully', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('admin.books.store'), [
        'book_code' => 'BK-001',
        'title' => 'Buku Laravel',
        'author' => 'Admin',
        'publisher' => 'Penerbit',
        'year' => 2024,
        'stock' => 3,
        'status' => BookStatus::Available,
    ]);

    $response->assertRedirect(route('admin.books.index'));
    $this->assertDatabaseHas('books', ['book_code' => 'BK-001']);
});

it('creates a member successfully', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('admin.members.store'), [
        'member_code' => 'AG-001',
        'name' => 'Anggota Test',
        'class' => 'XII-A',
        'whatsapp' => '081234567890',
        'status' => MemberStatus::Active,
    ]);

    $response->assertRedirect(route('admin.members.index'));
    $this->assertDatabaseHas('members', ['member_code' => 'AG-001']);
});

it('handles borrowing and return flow', function () {
    $user = User::factory()->create();
    $member = Member::create([
        'member_code' => 'AG-002',
        'name' => 'Member Flow',
        'status' => MemberStatus::Active,
    ]);

    $book = Book::create([
        'book_code' => 'BK-002',
        'title' => 'Flow Buku',
        'stock' => 1,
        'status' => BookStatus::Available,
    ]);

    $borrowResponse = $this->actingAs($user)->post(route('admin.borrowings.store'), [
        'member_id' => $member->id,
        'book_ids' => [$book->id],
        'due_date' => now()->addDays(3)->toDateString(),
    ]);

    $borrowResponse->assertRedirect(route('admin.borrowings.index'));
    $this->assertDatabaseHas('borrowings', ['member_id' => $member->id]);

    $borrowing = Borrowing::first();

    $returnResponse = $this->actingAs($user)->post(route('admin.returns.store', $borrowing), [
        'return_date' => now()->addDays(3)->toDateString(),
    ]);

    $returnResponse->assertRedirect(route('admin.returns.index'));
    $this->assertDatabaseHas('book_returns', ['borrowing_id' => $borrowing->id]);
});
