<?php

namespace App\Http\Controllers;

use App\Http\Requests\TweetRequest;
use App\Models\Tweet;
use App\Services\TweetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TweetController extends Controller
{
    protected TweetService $tweetService;

    public function __construct(TweetService $tweetService)
    {
        $this->tweetService = $tweetService;
    }

    public function index(Request $request): Response|JsonResponse
    {
        $tweets = $this->tweetService->getTweets(
            $request->get('per_page', config('constants.tweets.per_page'))
        );

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'data' => $tweets->items(),
                'next_page_url' => $tweets->nextPageUrl(),
            ]);
        }

        return Inertia::render('tweets/Feed', [
            'tweets' => $tweets,
            'maxTweetLength' => config('constants.tweet.content_max_length'),
        ]);
    }

    public function userTweets(Request $request, string $userId): Response
    {
        $tweets = $this->tweetService->getUserTweets(
            $userId,
            $request->get('per_page', config('constants.tweets.per_page'))
        );

        return Inertia::render('Users/Profile', [
            'tweets' => $tweets,
        ]);
    }

    public function store(TweetRequest $request)
    {
        $this->tweetService->createTweet($request->input('content'));

        return redirect()->back()->with('success', 'Tweet posted!');
    }

    public function destroy(Tweet $tweet)
    {
        $this->tweetService->deleteTweet($tweet);

        return redirect()->back()->with('success', 'Tweet deleted.');
    }
}
