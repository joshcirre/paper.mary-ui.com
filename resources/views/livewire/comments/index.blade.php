<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use function Livewire\Volt\{state, on, placeholder, computed};

state('post');

$comments = computed(function () {
    return $this->post
        ->comments()
        ->with(['author', 'post'])
        ->oldest()
        ->get();
});

on([
    'comment-done' => function () {
        // None. Just refresh the list from child events when necessary
    },
]);

placeholder('
    <div>
        <div class="loading loading-spinner"></div>
    </div>
    ');
?>

<div>
    {{-- COMMENT COUNT --}}
    <div class="font-bold">Comments ({{ $this->comments->count() }})</div>

    <hr class="mt-5" />

    {{-- COMMENT LIST --}}
    @foreach ($this->comments as $comment)
        <livewire:comments.card :$comment wire:key="comment-{{ $comment->id }}" class="mt-5" />
    @endforeach

    {{-- REPLY --}}
    @if (!$post->archived_at && auth()->user())
        <livewire:comments.create :post="$post" />
    @endif

    {{-- LOGIN --}}
    @if (!auth()->user())
        <x-button label="Log in to reply" link="/login?redirect_url=/posts/{{ $post->id }}" icon-right="o-arrow-right"
            class="mt-10 btn-primary" />
    @endif
</div>
