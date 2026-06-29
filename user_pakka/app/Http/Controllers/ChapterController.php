<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Story;
use App\Models\MyLibrary;
use App\Models\ChapterReport;
use App\Models\ChapterPurchase;
use App\Models\ReadingHistory;

class ChapterController extends Controller
{
    // =========================
    // CHAPTER LIST (WITH PAGINATION)
    // =========================
    public function index($storyId)
    {
        $story = Story::findOrFail($storyId);

        $chapters = Chapter::where('story_id', $storyId)
            ->orderBy('chapter_number', 'asc')
            ->paginate(5);

        return view('writer.chapter', compact('story', 'chapters'));
    }

    // =========================
    // CREATE CHAPTER PAGE
    // =========================
    public function create($storyId)
    {
        $story = Story::findOrFail($storyId);

        $chapter = new Chapter();

        $chapter->chapter_number = Chapter::where('story_id', $storyId)->max('chapter_number') + 1 ?? 1;

        return view('writer.editchapter', compact('story', 'chapter'));
    }

    // =========================
    // STORE CHAPTER
    // =========================
    public function store(Request $request, $storyId)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $story = Story::findOrFail($storyId);

        $lastNumber = Chapter::where('story_id', $storyId)
            ->max('chapter_number');

        $nextChapterNumber = $lastNumber ? $lastNumber + 1 : 1;

        // Only allow premium from chapter 6 onward
        $isPremium = false;

        if ($nextChapterNumber > 5) {
            $isPremium = $request->has('is_premium');
        }

        // ===================================
        // PREMIUM CONTENT VALIDATION
        // ===================================

        if ($isPremium) {

            $content = $request->content;
            $language = strtolower($story->language);

            // English book
            if ($language === 'english') {

                $englishOnly = preg_replace(
                    '/[\x{1780}-\x{17FF}]/u',
                    ' ',
                    $content
                );

                $wordCount = str_word_count(
                    strip_tags($englishOnly)
                );

                if ($wordCount < 1500) {

                    return back()
                        ->withErrors([
                            'content' =>
                            'Premium chapters require at least 1,500 words.'
                        ])
                        ->withInput();
                }
            }

            // Khmer book
            if ($language === 'khmer') {

                preg_match_all(
                    '/[\x{1780}-\x{17FF}]/u',
                    $content,
                    $matches
                );

                $characterCount = count($matches[0]);

                if ($characterCount < 5000) {

                    return back()
                        ->withErrors([
                            'content' =>
                            'Premium chapters require at least 5,000 Khmer characters.'
                        ])
                        ->withInput();
                }
            }
        }

        // ===================================
        // SAVE CHAPTER
        // ===================================

        Chapter::create([
            'story_id' => $storyId,
            'chapter_number' => $nextChapterNumber,
            'title' => $request->title,
            'content' => $request->content,
            'is_premium' => $isPremium,
            'price' => $isPremium ? 0.25 : 0,
            'story_progress' => 'draft'
        ]);

        return redirect()
            ->route('writer.chapter', $storyId)
            ->with('chapter_created', true);
    }

    // =========================
    // EDIT CHAPTER
    // =========================
    public function edit($storyId, $chapterId)
    {
        $story = Story::findOrFail($storyId);

        $chapter = Chapter::where('story_id', $storyId)
            ->where('id', $chapterId)
            ->firstOrFail();

        // ❌ BLOCK editing premium for chapter 1–5
        $chapterCount = Chapter::where('story_id', $storyId)->count();

        if ($chapterCount <= 5 && $chapter->is_premium) {
            return back()->with('error', 'First 5 chapters cannot be premium.');
        }

        return view('writer.editchapter', compact('story', 'chapter'));
    }

    // =========================
    // UPDATE CHAPTER
    // =========================
    public function update(Request $request, $storyId, $chapterId)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $story = Story::findOrFail($storyId);

        $chapter = Chapter::where('story_id', $storyId)
            ->where('id', $chapterId)
            ->firstOrFail();

        // =========================
        // BLOCK PREMIUM FOR CHAPTER 1–5
        // =========================

        $chapterNumber = $chapter->chapter_number;

        if ($chapterNumber <= 5) {
            $isPremium = false;
        }
        else {
            $isPremium = $request->has('is_premium');
        }

        // =========================
        // PREMIUM VALIDATION (UNCHANGED)
        // =========================
        if ($isPremium) {

            $content = $request->content;
            $language = strtolower($story->language);

            if ($language === 'english') {

                $englishOnly = preg_replace('/[\x{1780}-\x{17FF}]/u', ' ', $content);

                $wordCount = str_word_count(strip_tags($englishOnly));

                if ($wordCount < 1500) {
                    return back()->withErrors([
                        'content' => 'Premium chapters require at least 1,500 words.'
                    ])->withInput();
                }
            }

            if ($language === 'khmer') {

                preg_match_all('/[\x{1780}-\x{17FF}]/u', $content, $matches);

                $characterCount = count($matches[0]);

                if ($characterCount < 5000) {
                    return back()->withErrors([
                        'content' => 'Premium chapters require at least 5,000 Khmer characters.'
                    ])->withInput();
                }
            }
        }

        // =========================
        // 🔥 NEW LOGIC: IF CHAPTER WAS PUBLISHED → RESET TO PENDING
        // =========================
        $wasPublished = $chapter->story_progress === 'published'
            && $chapter->chapter_approval_status === 'approved';

        $newStatus = $wasPublished ? 'pending' : $chapter->story_progress;

        // =========================
        // UPDATE CHAPTER
        // =========================
        $chapter->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_premium' => $isPremium,
            'price' => $isPremium ? 0.25 : 0,

            // 🔥 IMPORTANT CHANGE
            'story_progress' => $newStatus,
            'chapter_approval_status' => $wasPublished ? 'pending' : $chapter->chapter_approval_status,
        ]);

        return redirect()
            ->route('writer.chapter.edit', [
                'storyId' => $storyId,
                'chapterId' => $chapterId,
            ])
            ->with('updated', true);
    }

    // =========================
    // PUBLISH CHAPTER
    // =========================
    public function publish($storyId, $chapterId)
    {
        $chapter = Chapter::where('story_id', $storyId)
            ->where('id', $chapterId)
            ->firstOrFail();

        $chapter->update([
            'story_progress' => 'published'
        ]);

        $story = Story::findOrFail($storyId);

        $hasPublished = Chapter::where('story_id', $storyId)
            ->where('story_progress', 'published')
            ->exists();

        if ($hasPublished) {
            $story->update([
                'story_progress' => 'published'
            ]);
        }

        return back()->with('success', 'Chapter Published Successfully!');
    }

    // =========================
    // REPUBLISH CHAPTER (for rejected chapters that have been edited)
    // =========================
    public function republish($storyId, $chapterId)
    {
        $chapter = Chapter::where('story_id', $storyId)
            ->where('id', $chapterId)
            ->firstOrFail();

        $chapter->update([
            'chapter_approval_status' => 'pending',
            'story_progress' => 'draft', // or keep published if you want
        ]);

        return back()->with('success', 'Chapter resubmitted for admin review.');
    }
    // =========================
    // READER VIEW
    // =========================
    public function show($storyId, $chapterId)
    {
        $story = Story::findOrFail($storyId);

        $chapters = Chapter::where('story_id', $storyId)
            ->where('story_progress', 'published')
            ->where('chapter_approval_status', 'approved')
            ->orderBy('chapter_number')
            ->get();

        $chapter = Chapter::where('story_id', $storyId)
            ->where('id', $chapterId)
            ->where('story_progress', 'published')
            ->where('chapter_approval_status', 'approved')
            ->firstOrFail();

        // =========================
        // ACCESS CONTROL
        // =========================
        $isOwner = auth()->check() && auth()->id() === $story->user_id;

        if ($chapter->is_premium && !$isOwner) {

            if (!auth()->check()) {
                return redirect()->route('login')
                    ->with('error', 'Please login first to unlock premium chapters.');
            }

            $isPurchased = ChapterPurchase::where('user_id', auth()->id())
                ->where('chapter_id', $chapter->id)
                ->where('payment_status', 'paid')
                ->exists();

            if (!$isPurchased) {
                return redirect()->route('chapter.pay', $chapter->id)
                    ->with('error', 'Please purchase this chapter first.');
            }
        }

        // =========================
        // SAVE READING HISTORY
        // =========================

        if (auth()->check()) {

            MyLibrary::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'story_id' => $story->id,
                ],
                [
                    'last_chapter_id' => $chapter->id,
                ]
            );
        }

        // =========================
        // SESSION TRACKING
        // =========================

        session([
            'last_story_id' => $story->id,
            'last_chapter_id' => $chapter->id,
        ]);

        // =========================
        // PREVIOUS / NEXT CHAPTER
        // =========================

        $chapterIndex = $chapters->search(
            fn ($c) => $c->id == $chapter->id
        );

        $previousChapter = $chapters[$chapterIndex - 1] ?? null;

        $nextChapter = $chapters[$chapterIndex + 1] ?? null;

        $totalChapters = $chapters->count();

        // =========================
        // RETURN VIEW
        // =========================

        return view('reader.readchapters', compact(
            'story',
            'chapter',
            'chapterIndex',
            'previousChapter',
            'nextChapter',
            'chapters',
            'totalChapters'
        ));
    }

    // =========================
    // SINGLE CHAPTER VIEW
    // =========================
    public function readChapter($storyId, $chapterId)
    {
        $story = Story::findOrFail($storyId);
        $chapter = Chapter::where('story_id', $storyId)
            ->where('id', $chapterId)
            ->firstOrFail();

        // =========================
        // ACCESS CONTROL (ADDED)
        // =========================

        $isOwner = auth()->check() && auth()->id() === $story->user_id;
        if ($chapter->is_premium && !$isOwner) {
            // must login first
            if (!auth()->check()) {
                return redirect()->route('login')
                    ->with('error', 'Please login to continue.');
            }
            // check purchase
            $isPurchased = ChapterPurchase::where('user_id', auth()->id())
                ->where('chapter_id', $chapter->id)
                ->where('payment_status', 'paid')
                ->exists();
            if (!$isPurchased) {
                return redirect()
                    ->route('chapter.pay', $chapter->id)
                    ->with('error', 'You need to purchase this chapter first.');
            }
        }
        // =========================
        // UPDATE READING HISTORY
        // =========================
        ReadingHistory::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'story_id' => $story->id,
            ],
            [
                'chapter_id' => $chapter->id,
                'updated_at' => now()
            ]
        );
        return view('reader.chapter', compact('story', 'chapter'));
    }

    // =========================
    // REPORT CHAPTER
    // =========================
    public function reportChapter(Request $request, $chapterId)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
        ]);

        ChapterReport::create([
            'user_id' => auth()->id(),
            'chapter_id' => $chapterId,
            'reason' => $request->reason,
            'details' => $request->details,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Chapter reported successfully.');
    }

    // =========================
    // 💰 PAYWAY PAYMENT PAGE (FIX ADDED)
    // =========================
    public function pay($id)
    {
        $chapter = Chapter::findOrFail($id);

        if ($chapter->is_premium) {
            return redirect()->back()->with('error', 'This chapter is free.');
        }

        $data = $this->generatePaywayData($chapter);

        return view('chapter.pay', compact('chapter', 'data'));
    }

    // =========================
    // PAYWAY DATA GENERATOR
    // =========================
    private function generatePaywayData($chapter)
    {
        return [
            "amount" => $chapter->price,
            "chapter_id" => $chapter->id,
            "order_id" => time() . $chapter->id,
            "return_url" => route('chapter.success', $chapter->id),
            "continue_success_url" => route('chapter.success', $chapter->id),
        ];
    }

    // =========================
    // PAYMENT SUCCESS PAGE
    // =========================
    public function paymentSuccess($id)
    {
        $chapter = Chapter::findOrFail($id);

        ChapterPurchase::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'chapter_id' => $chapter->id,
            ],
            [
                'payment_status' => 'paid',
                'amount' => $chapter->price,
            ]
        );

        return redirect()
            ->route('reader.readchapters', [$chapter->story_id, $chapter->id])
            ->with('success', 'Chapter unlocked successfully!');
    }
}