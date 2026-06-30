<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\MyLibrary;
use App\Models\Follow;
use App\Models\CommetLike;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\CommentReport;
use App\Models\Like;
use App\Models\StoryRating;
use App\Models\ReadingHistory;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function create()
    {
        $genres = [
            __('ui.action'),__('ui.adventure'),__('ui.comedy'),__('ui.contemporary'),__('ui.drama'),
            __('ui.fantasy'),__('ui.mystery'),__('ui.romance'),__('ui.horror'),__('ui.scifi'),__('ui.thriller'),
            __('ui.contemporary'),__('ui.historical'),__('ui.psychological'),__('ui.tragedy'),__('ui.satire'),__('ui.urban_fantasy'),__('ui.dark_fantasy'),
            __('ui.supernatural'),__('ui.crime'),__('ui.slice_of_life'),__('ui.war'),__('ui.sports'),__('ui.western'),__('ui.mythology'), __('ui.short_story')
        ];

        $tags = [
            __('ui.anti_hero_lead'),__('ui.strong_lead'),__('ui.weak_to_strong'),__('ui.female_lead'),__('ui.male_lead'),__('ui.romance_subplot'),__('ui.magic'),
            __('ui.time_travel'),__('ui.reincarnation'),__('ui.system'),__('ui.school_life'),__('ui.overpowered_mc'),__('ui.genius_mc'),__('ui.cold_mc'),__('ui.soft_mc'),
            __('ui.love_triangle'),__('ui.arranged_marriage'),__('ui.enemies_to_lovers'),__('ui.friends_to_lovers'),__('ui.slow_burn'),'Harem','Reverse Harem',
            __('ui.childhood_friends'),(__('ui.game_system')),(__('ui.parallel_world')),(__('ui.cultivation')),(__('ui.demon_world')),(__('ui.superpowers')),'Cyberpunk',
            __('ui.virtual_reality'), __('ui.modern_day'), __('ui.historical'), __('ui.ancient_era'), __('ui.medieval'), __('ui.futuristic'), __('ui.post_apocalyptic'), __('ui.urban_fantasy'),
            __('ui.kingdom'), __('ui.revenge'), __('ui.survival'), __('ui.mystery'), __('ui.political_intrigue'), __('ui.war_strategy'), __('ui.rise_to_power'), __('ui.hidden_identity'),
            __('ui.tournament_arc'), __('ui.slice_of_life'), __('ui.tragedy'), __('ui.comedy'), __('ui.dark_fantasy'), __('ui.psychological'), __('ui.thriller'), __('ui.youth_love'), __('ui.family_saga'),
           (__('ui.friendship')),'Redemption',(__('ui.betrayal')),(__('ui.sacrifice')),(__('ui.reincarnation')),(__('ui.time_loop')),(__('ui.body_swap'))
        ];

        return view('writer.createstory', compact('genres', 'tags'));
    }

    public function store(Request $request)
    {
        // ONLY draft button is considered draft
        $isDraft = $request->action === 'draft';

        // VALIDATION
        $rules = [

            'title' => $isDraft
                ? 'nullable|max:255'
                : 'required|max:255',

            'synopsis' => $isDraft
                ? 'nullable'
                : 'required',

            'genres' => $isDraft
                ? 'nullable|array'
                : 'required|array|min:1',

            'language' => $isDraft
                ? 'nullable'
                : 'required',

            'format' => $isDraft
                ? 'nullable'
                : 'required|in:serialized',

            'cover_image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];

        $request->validate($rules);

        // UPLOAD COVER
        $coverPath = null;

        if ($request->hasFile('cover_image')) {

            $coverPath = $request
                ->file('cover_image')
                ->store('covers', 'spaces');
        }

        // CREATE STORY
        $story = Story::create([

            'user_id' => Auth::id(),

            'title' => $request->title,

            'synopsis' => $request->synopsis,

            'genres' => $request->genres ?? [],

            'tags' => $request->tags ?? [],

            'language' => $request->language ?? 'English',

            'format' => 'serialized',

            'cover_image' => $coverPath,

            // IMPORTANT FIX
            'story_progress' => 'draft',

            // OPTIONAL
            'story_approval_status' => 'pending',

            // CONTENT WARNINGS
            'warnings' => $request->warnings ?? [],

            // FANFICTION
            'is_fanfiction' => $request->has('is_fanfiction'),
        ]);

        // ===================================
        // SAVE DRAFT
        // ===================================
        if ($request->action === 'draft') {

            return redirect()
                ->route('writer.dashboard')
                ->with('draft_story', true);
        }

        // ===================================
        // NEXT → GO TO CHAPTER PAGE
        // ===================================
        return redirect()
            ->route('writer.chapter', $story->id)
            ->with('story_created', true);
    }

    // ======================================================
    // 📌 VIEW CHAPTER PAGE
    // ======================================================
    public function chapter($id)
    {
        $story = Story::with('chapters')->findOrFail($id);
        return view('writer.chapter', compact('story'));
    }

    // ======================================================
    // ✏️ EDIT STORY PAGE (NEW)
    // ======================================================
    public function edit($id)
    {
        $story = Story::findOrFail($id);

        $genres = [
            __('ui.action'),__('ui.adventure'),__('ui.comedy'),__('ui.drama'),__('ui.fantasy'),__('ui.mystery'),__('ui.romance'),__('ui.horror'),__('ui.scifi'),__('ui.thriller'),
            __('ui.contemporary'),__('ui.historical'),__('ui.psychological'),__('ui.tragedy'),__('ui.satire'),__('ui.urban_fantasy'),__('ui.dark_fantasy'),
            __('ui.supernatural'),__('ui.crime'),__('ui.slice_of_life'),__('ui.war'),__('ui.sports'),__('ui.western'),__('ui.mythology'),__('ui.short_story')
        ];

        $tags = [
            __('ui.anti_hero_lead'),__('ui.strong_lead'),__('ui.weak_to_strong'),__('ui.female_lead'),__('ui.male_lead'),__('ui.romance_subplot'),__('ui.magic'),
            __('ui.time_travel'),__('ui.reincarnation'),__('ui.system'),__('ui.school_life'),__('ui.overpowered_mc'),__('ui.genius_mc'),__('ui.cold_mc'),__('ui.soft_mc'),
            __('ui.love_triangle'),__('ui.arranged_marriage'),__('ui.enemies_to_lovers'),__('ui.friends_to_lovers'),__('ui.slow_burn'),'Harem','Reverse Harem',
            __('ui.childhood_friends'),(__('ui.game_system')),(__('ui.parallel_world')),(__('ui.cultivation')),(__('ui.demon_world')),(__('ui.superpowers')),'Cyberpunk',
            __('ui.virtual_reality'), __('ui.modern_day'), __('ui.historical'), __('ui.ancient_era'), __('ui.medieval'), __('ui.futuristic'), __('ui.post_apocalyptic'), __('ui.urban_fantasy'),
            __('ui.kingdom'), __('ui.revenge'), __('ui.survival'), __('ui.mystery'), __('ui.political_intrigue'), __('ui.war_strategy'), __('ui.rise_to_power'), __('ui.hidden_identity'),
            __('ui.tournament_arc'), __('ui.slice_of_life'), __('ui.tragedy'), __('ui.comedy'), __('ui.dark_fantasy'), __('ui.psychological'), __('ui.thriller'), __('ui.youth_love'), __('ui.family_saga'),
           (__('ui.friendship')),'Redemption',(__('ui.betrayal')),(__('ui.sacrifice')),(__('ui.reincarnation')),(__('ui.time_loop')),(__('ui.body_swap'))
        ];

        $warningsList = [
            __('ui.ai_assisted_content'),
            __('ui.ai_generated_content'),
            __('ui.graphic_violence_content'),
            __('ui.profanity_content'),
            __('ui.sensitive_content'),
            __('ui.sexual_content')
        ];
        return view('writer.editstory', compact('story', 'genres', 'tags', 'warningsList'));
    }

    //  UPDATE STORY (NEW)
    public function update(Request $request, $id)
    {
        $story = Story::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'synopsis' => 'nullable',
            'language' => 'required',
            'format' => 'required',
        ]);

        $coverPath = $story->cover_image;

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'spaces');
        }

        $story->update([
            'title' => $request->title,
            'synopsis' => $request->synopsis,
            'language' => $request->language,
            'format' => $request->format,
            'genres' => $request->genres ?? [],
            'tags' => $request->tags ?? [],
            'warnings' => $request->warnings ?? [],
            'is_fanfiction' => $request->has('is_fanfiction'),
            'cover_image' => $coverPath,
        ]);

        return redirect()->route('writer.dashboard')
            ->with('success', 'Story updated successfully!');
    }

    //save draft and publish functions (NEW)
    public function saveDraft($id)
    {
        $story = Story::findOrFail($id);

        $story->update([
            'story_progress' => 'draft'
        ]);

        return back()->with('success', 'Saved as draft');
    }

    public function publish($id)
    {
        $story = Story::findOrFail($id);

        $story->update([
            'story_progress' => 'published'
        ]);

        return back()->with('success', 'Story published');
    }

    // SHOW STORY DETAIL (NEW)
    public function show($id)
    {
        $story = Story::with([

            'user',

            // ONLY APPROVED CHAPTERS
            'chapters' => function ($query) {

                $query->where('story_progress', 'published')

                    ->where('chapter_approval_status', 'approved')

                    ->where('is_hidden', 0)

                    ->orderBy('chapter_number', 'asc');
            },

            // ONLY STORY COMMENTS
            'comments' => function ($query) {

                $query->whereNull('chapter_id')
                    ->where('is_hidden', 0)
                    ->latest();
            },

            'comments.replies' => function ($query) {

                $query->latest();

            },

            'comments.likes'

        ])

        // STORY MUST ALSO BE APPROVED
        ->where('story_approval_status', 'approved')

        ->where('is_hidden', 0)

        ->findOrFail($id);

        // =========================
        // CHECK LIBRARY
        // =========================
        $isSaved = Auth::check()

            ? MyLibrary::where('user_id', Auth::id())
                ->where('story_id', $story->id)
                ->exists()

            : false;

        // =========================
        // CONTINUE READING
        // =========================
        $firstChapter = $story->chapters->first();

        $lastRead = session('last_read_chapter_'.$story->id);

        $continueChapter = $story->chapters
            ->where('id', $lastRead)
            ->first()

            ?? $firstChapter;

        // =========================
        // CHECK LIKE
        // =========================
        $liked = Auth::check()

            ? $story->likes()
                ->where('user_id', Auth::id())
                ->exists()

            : false;

        // =========================
        // FOLLOW
        // =========================
        $authorId = $story->user_id;

        $isFollowing = auth()->check()

            ? Follow::where('follower_id', auth()->id())
                ->where('following_id', $authorId)
                ->exists()

            : false;

        $followersCount = Follow::where(
            'following_id',
            $authorId
        )->count();

        // =========================
        // VIEWS
        // =========================
        if (!session()->has("viewed_story_$id")) {

            $story->increment('views');

            session([
                "viewed_story_$id" => true
            ]);
        }

        // =========================
        // READING HISTORY
        // =========================
        $readingHistory = null;

        if (auth()->check()) {

            ReadingHistory::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'story_id' => $story->id,
                ],
                [
                    'updated_at' => now(),
                ]
            );

            $readingHistory = ReadingHistory::where(
                'user_id',
                auth()->id()
            )

            ->where('story_id', $story->id)

            ->first();
        }

        return view('reader.storydetail', compact(
            'story',
            'isSaved',
            'continueChapter',
            'liked',
            'isFollowing',
            'followersCount',
            'readingHistory'
        ));
    }

    // TOGGLE LIBRARY (NEW)
    public function toggleLibrary($id)
    {
        $library = MyLibrary::where('user_id', Auth::id())
            ->where('story_id', $id)
            ->first();

        if ($library) {
            $library->delete();
            return back()->with('success', 'Removed from library');
        }

        MyLibrary::create([
            'user_id' => Auth::id(),
            'story_id' => $id
        ]);

        return back()->with('success', 'Added to library');
    }

    // TOGGLE LIKE 
    public function toggleLike($id)
    {
        $story = Story::findOrFail($id);

        if (!auth()->check()) {
            return response()->json([
                'message' => 'Login required'
            ], 401);
        }

        $like = $story->likes()
            ->where('user_id', auth()->id())
            ->first();

        if ($like) {
            $like->delete();

            return response()->json([
                'liked' => false,
                'likes' => $story->likes()->count()
            ]);
        }

        $story->likes()->create([
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'liked' => true,
            'likes' => $story->likes()->count()
        ]);
    }

    // RATE STORY
    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $story = Story::findOrFail($id);

        $rating = StoryRating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'story_id' => $story->id
            ],
            [
                'rating' => $request->rating
            ]
        );

        $story->rating = $story->ratings()->avg('rating');
        $story->ratings_count = $story->ratings()->count();
        $story->save();

        return response()->json([
            'success' => true,
            'rating' => $rating->rating,
            'average' => $story->rating
        ]);
    }

    //views
    public function addView($id)
    {
        $story = Story::findOrFail($id);

        // prevent spam refresh views (optional but recommended)
        if (!session()->has("viewed_story_$id")) {
            $story->increment('views');
            session(["viewed_story_$id" => true]);
        }

        return response()->json([
            'views' => $story->views
        ]);
    }

    public function markComplete($id)
    {
        $story = Story::findOrFail($id);

        $story->update([
            'story_status' => 'complete' // or 'published' or whatever your column is
        ]);

        return back()->with('success', 'Story marked as completed!');
    }
    
}