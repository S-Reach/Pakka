<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\MyLibraryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReadingPreferenceController;
use App\Http\Controllers\WriterDashboardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserPasswordController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StoryReportController;
use App\Http\Controllers\PayWayController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\LanguageController;
//forgot password
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//registration routes (user)
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
//Legal and Support
Route::view('/terms-and-conditions', 'legal.terms')
    ->name('terms');
Route::view('/privacy-policy', 'legal.privacy')
    ->name('privacy');
Route::view('/community-guidelines', 'legal.community-guidelines')
    ->name('community');
Route::view('/copyright-policy', 'legal.copyright-policy')
    ->name('copyright');

//login routes (user)
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');

//logout route (user)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Lang
Route::get('/language/{locale}',
    [LanguageController::class, 'switch']
)->name('lang.switch');

//home route
Route::get('/', [HomeController::class, 'index'])->name('home');

//browse route
Route::get('/browse', [BrowseController::class, 'browse'])->name('browse');
Route::get('/browse/latest', [BrowseController::class, 'latest'])->name('browse.latest');
Route::get('/browse/trending', [BrowseController::class, 'trending'])->name('browse.trending');
Route::get('/browse/complete', [BrowseController::class, 'complete'])->name('browse.complete');
Route::get('/browse/ongoing', [BrowseController::class, 'ongoing'])->name('browse.ongoing');

//story route 
Route::get('/reader/storydetail/{id}', [StoryController::class, 'show'])->name('reader.storydetail');
 Route::get('/story/{id}', [StoryController::class, 'show'])
        ->name('story.show');
Route::post('/story/{id}/library', [StoryController::class, 'toggleLibrary'])
        ->name('story.library');
Route::post('/story/{id}/like', [StoryController::class, 'toggleLike'])
        ->name('story.like');

//follow 
Route::post('/user/{id}/follow', [FollowController::class, 'toggle']);

//Comment routes
Route::post('/story/{story}/comment',
    [CommentController::class, 'storeStoryComment'])
    ->name('story.comment')
    ->middleware('auth');
Route::post('/comment/{comment}/like',
    [CommentController::class, 'like'])
    ->name('comment.like');
Route::post('/comment/{comment}/reply',
    [CommentController::class, 'reply'])
    ->name('comment.reply');
//report comment route
Route::post('/comment/{comment}/report', [CommentController::class, 'report'])
    ->name('comment.report');

//chapter comment route
Route::post('/chapter/{chapter}/comment',
    [CommentController::class, 'storeChapterComment'])
    ->name('chapter.comment')
    ->middleware('auth');

// Chapter report route
Route::post('/chapter/{chapter}/report', [ChapterController::class, 'reportChapter'])
    ->name('chapter.report')
    ->middleware('auth');

//story rating route
Route::middleware('auth')->post('/story/{id}/rate', [StoryController::class, 'rate'])
    ->name('story.rate');

//views
Route::post('/story/{id}/view', [StoryController::class, 'addView'])
    ->name('story.view');

//report story route
Route::post('/story/{id}/report', [StoryReportController::class, 'store'])
    ->name('story.report')
    ->middleware('auth');

//chapter route
Route::get('/reader/readchapters/{storyId}/{chapterId}', [ChapterController::class, 'show'])
    ->name('reader.readchapters');

//chapter comment route
Route::post('/chapter/{id}/comment', [CommentController::class, 'store'])
    ->name('chapter.comment.store');

//library route
Route::get('/mylibrary', [MyLibraryController::class, 'index']);

//notification route
Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('notifications');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.read');
Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])
    ->name('notifications.unread-count');
Route::get('/notifications/{id}', [NotificationController::class, 'show'])
    ->name('notifications.show');

//preference route
Route::get('/preferences', [ReadingPreferenceController::class, 'index'])
        ->name('preferences');

    Route::post('/preferences', [ReadingPreferenceController::class, 'save'])
        ->name('preferences.save');

//write story routes
Route::get('/writer/createstory', [StoryController::class, 'create'])
    ->name('writer.createstory');
Route::post('/writer/createstory/store', [StoryController::class, 'store'])
    ->name('writer.story.store');
Route::get('/writer/createstory/{id}/chapter', [StoryController::class, 'chapter'])
    ->name('writer.chapter');
Route::get('/writer/createstory/{id}/edit', [StoryController::class, 'edit'])
    ->name('writer.story.edit');
Route::put('/writer/createstory/{id}', [StoryController::class, 'update'])
    ->name('writer.story.update');
Route::post('/story/{id}/draft', [StoryController::class, 'saveDraft'])
    ->name('writer.story.draft');
Route::post('/story/{id}/publish', [StoryController::class, 'publish'])
    ->name('writer.story.publish');
Route::post('/story/{id}/republish', [StoryController::class, 'republish'])
    ->name('writer.story.republish');
Route::patch('/writer/story/{id}/complete', [StoryController::class, 'markComplete'])
    ->name('writer.story.complete');

// CHAPTER ROUTES 
// CREATE (must include storyId)
Route::get('/writer/story/{story}/chapter', [ChapterController::class, 'index'])
    ->name('writer.chapter');

Route::get('/writer/story/{storyId}/chapter/create', [ChapterController::class, 'create'])
    ->name('writer.chapter.create');
// STORE
Route::post('/writer/story/{storyId}/chapter/store', [ChapterController::class, 'store'])
    ->name('writer.chapter.store');
// EDIT
Route::get('/writer/story/{storyId}/chapter/{chapterId}/edit', [ChapterController::class, 'edit'])
    ->name('writer.chapter.edit');
// UPDATE
Route::put('/writer/story/{storyId}/chapter/{chapterId}', [ChapterController::class, 'update'])
    ->name('writer.chapter.update');
// PUBLISH
Route::post('/writer/story/{storyId}/chapter/{chapterId}/publish', [ChapterController::class, 'publish'])
    ->name('writer.chapter.publish');
// REPUBLISH
Route::post('/writer/story/{storyId}/chapter/{chapterId}/republish', [ChapterController::class, 'republish'])
    ->name('writer.chapter.republish');

//user profile routes
Route::get('/usereditprofile', [UserProfileController::class, 'edit'])->name('userprofile.edit');
Route::post('/usereditprofile', [UserProfileController::class, 'update'])->name('userprofile.update');
Route::get('/writerprofile/{id}', [UserProfileController::class, 'writerprofile'])->name('writerprofile');


//user password routes
Route::middleware('auth')->group(function () {
    Route::get('/userpassword', [UserPasswordController::class, 'index'])->name('userpassword');
    Route::post('/userpassword', [UserPasswordController::class, 'update'])->name('userpassword.update');

    //writer dashboard route
    Route::get('/writer/dashboard', [WriterDashboardController::class, 'index'])
        ->name('writer.dashboard');
});

// Show forgot page
Route::get('/forgotpassword', function () { return view('auth.forgotpassword');})
    ->name('forgotpassword.request');

// Send reset link
Route::post('/forgotpassword', function (Request $request) {

    $request->validate([
        'email' => 'required|email'
    ]);

    Password::sendResetLink($request->only('email'));

    return back()->with('status', 'If your email exists, a reset link has been sent.');
})->name('password.email');

// Show reset form
Route::get('/resetpassword/{token}', function ($token) {
    return view('auth.resetpassword', ['token' => $token]);
})->name('password.reset');

// Update password
Route::post('/resetpassword', function (Request $request) {

    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        }
    );

    return redirect('/login')->with('success', 'Password reset successful!');
})->name('resetpassword.update');

// PAYWAY PAYMENT ROUTES
Route::middleware('auth')->group(function () {

    Route::get(
        '/chapter/{chapterId}/pay', [PayWayController::class, 'payChapter']
    )->name('chapter.pay');

    Route::post(
        '/chapter/{chapterId}/pay', [PayWayController::class, 'processPayment'])
    ->name('chapter.pay');

    Route::get(
        '/payment/success', [PayWayController::class, 'success']
    )->name('payway.success');

    Route::get(
        '/payment/cancel', [PayWayController::class, 'cancel']
    )->name('payway.cancel');
    

});

// Withdrawal route
Route::post( '/writer/withdrawal-request', [WithdrawalController::class, 'store']
    )->name('writer.withdraw.request');