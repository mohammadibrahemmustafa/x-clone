<?php

namespace App\Services;

use App\Models\Tweet;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Support\Facades\Auth;

class TweetService
{
    public function getTweets(int $perPage = 10): CursorPaginator
    {
        return Tweet::with('user')
            ->latest('created_at')
            ->cursorPaginate($perPage);
    }

    public function getUserTweets(string $userId, int $perPage = 10): CursorPaginator
    {
        return Tweet::where('created_by', $userId)
            ->latest('created_at')
            ->cursorPaginate($perPage);
    }

    public function createTweet(string $content): Tweet
    {
        return Tweet::create([
            'created_by' => Auth::id(),
            'content' => $content,
        ]);
    }

    public function deleteTweet(Tweet $tweet): bool
    {
        if ($tweet->created_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return $tweet->delete();
    }
}
