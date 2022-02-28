<?php
/**
crowdCuratio - Curating together virtually
Copyright (C)2022 - berlinHistory e.V.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program in the file LICENSE.

If not, see <https://www.gnu.org/licenses/>.
 */
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Spatie\WelcomeNotification\WelcomesNewUsers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/',
    function () {
        return redirect('/login');
    }
);

Route::group(
    ['middleware' => ['web', WelcomesNewUsers::class, /*ProtectAgainstSpam::class,*/]],
    function () {
        Route::get('welcome/{user}', [App\Http\Controllers\Auth\MyWelcomeController::class, 'showWelcomeForm'])->name(
            'welcome'
        );
        Route::post('welcome/{user}', [App\Http\Controllers\Auth\MyWelcomeController::class, 'savePassword']);
    }
);

Route::group(
    ['middleware' => ['admin']],
    function () {
        Route::resource('/settings', \App\Http\Controllers\SettingController::class);
    }
);

Route::get(
    '/dashboard',
    function () {
        return view('dashboard');
    }
)->middleware(['auth'])->name('dashboard');

Route::get('auth.policy', [\App\Http\Controllers\PublicController::class, 'projectPolicy'])->name('auth.policy');
Route::get('auth.terms', [\App\Http\Controllers\PublicController::class, 'projectTerms'])->name('auth.terms');

Route::group(
    ['middleware' => ['auth']],
    function () {
        Route::resource('/projects', ProjectController::class);
        Route::delete('/user/{userId}/project/{projectId}', [ProjectController::class, 'deleteUserFromProject'])->name(
            'project.user_delete'
        );
        Route::resource('/roles', \App\Http\Controllers\RoleController::class);
        Route::resource('/chapters', ChapterController::class);
        Route::resource('/entries', EntryController::class);
        //Route::resource('/contents', \App\Http\Controllers\ContentController::class);
        Route::post('/text/store', [ContentController::class, 'saveText'])->name('text.store');
        Route::get('/edit/{id}/text', [ContentController::class, 'editText'])->name('text.edit');
        Route::delete('/delete/{id}/text', [ContentController::class, 'destroyText'])->name(
            'text.delete'
        );
        Route::post('/check/email', [ProjectController::class, 'checkEmail'])->name('check.email');
        Route::get('/user/{id}/project/{projectId}/info', [ProjectController::class, 'inviteUserForProject'])->name('user.info');
        Route::get(
            '/user/{id}/invitation',
            [\App\Http\Controllers\UserController::class, 'resendInvitation']
        )->name(
            'resend.invitation'
        );
        Route::post('/image/store', [ContentController::class, 'saveImage'])->name('image.store');
        Route::get('/edit/{id}/image', [ContentController::class, 'editImage'])->name(
            'image.edit'
        );
        Route::delete('/delete/{id}/image', [ContentController::class, 'destroyImage'])->name(
            'image.delete'
        );
        Route::get('/element', [ProjectController::class, 'element'])->name('element');
        Route::resource('/register', RegisteredUserController::class);
        Route::resource('/users', UserController::class);
        Route::get('/profile', [UserController::class, 'profile'])->name(
            'profile'
        );
        Route::get('/permission/user/{id}/', [ProjectController::class, 'givePermissionToUser'])->name(
            'permission.project'
        );
        Route::post('/comment/chapter', [ChapterController::class, 'commentChapter'])->name(
            'comment.chapter'
        );
        Route::post(
            '/drag',
            [ChapterController::class, 'saveDragAndDrop']
        )->name(
            'chapter.drag'
        );
        Route::get(
            '/comment/chapter/{id}/',
            [ChapterController::class, 'getChapterComment']
        )->name(
            'comment.show'
        );
        Route::post(
            '/comment/chapter/{id}/save',
            [ChapterController::class, 'saveComment']
        )->name(
            'comment.save'
        );
        Route::post('/comment/entry', [EntryController::class, 'commentEntry'])->name(
            'comment.entry'
        );
        Route::post(
            '/comment/chapter/status',
            [ChapterController::class, 'setStatus']
        )->name(
            'chapter.status'
        );
        Route::get('/comment/entry/{id}/', [EntryController::class, 'getEntryComment'])->name(
            'comment.entry.show'
        );
        Route::post(
            '/comment/entry/{id}/save',
            [EntryController::class, 'saveCommentEntry']
        )->name(
            'comment.entry.save'
        );
        Route::post(
            '/comment/entry/status',
            [EntryController::class, 'setStatusEntry']
        )->name(
            'entry.status'
        );
        Route::post('/comment/text', [ContentController::class, 'commentText'])->name(
            'comment.text'
        );
        Route::get('/comment/text/{id}/', [ContentController::class, 'getTextComment'])->name(
            'comment.text.show'
        );
        Route::post(
            '/comment/text/{id}/save',
            [ContentController::class, 'saveCommentText']
        )->name(
            'comment.text.save'
        );
        Route::post(
            '/comment/text/status',
            [ContentController::class, 'setStatusText']
        )->name(
            'text.status'
        );
        Route::post(
            '/text/reset',
            [ContentController::class, 'resetText']
        )->name(
            'text.reset'
        );
        Route::post('/comment/image', [ContentController::class, 'commentImage'])->name(
            'comment.image'
        );
        Route::get('/comment/image/{id}/', [ContentController::class, 'getImageComment'])->name(
            'comment.image.show'
        );
        Route::post(
            '/comment/image/{id}/save',
            [ContentController::class, 'saveCommentImage']
        )->name(
            'comment.image.save'
        );
        Route::post(
            '/comment/image/status',
            [ContentController::class, 'setStatusImage']
        )->name(
            'image.status'
        );
        Route::post('/comment/project', [ProjectController::class, 'commentProject'])->name(
            'comment.project'
        );
        Route::get(
            '/comment/project/{id}/',
            [ProjectController::class, 'getProjectComment']
        )->name(
            'comment.project.show'
        );
        Route::get(
            '/log/text/{id}/',
            [ProjectController::class, 'getCurrentLog']
        )->name(
            'log.text'
        );
        Route::get(
            '/role/check/{id}/',
            [\App\Http\Controllers\RoleController::class, 'roleHasUsers']
        )->name(
            'role.check'
        );
        Route::post(
            '/role/{id}/alt/{alt}/',
            [\App\Http\Controllers\RoleController::class, 'customizedDelete']
        )->name(
            'customizedDelete'
        );
        Route::post(
            '/comment/project/{id}/save',
            [ProjectController::class, 'saveCommentProject']
        )->name(
            'comment.project.save'
        );
        Route::post(
            '/comment/project/status',
            [ProjectController::class, 'setStatusProject']
        )->name(
            'project.status'
        );
        Route::post(
            '/project/permission',
            [ProjectController::class, 'setPermissionForUserOnProject']
        )->name(
            'project.permission'
        );
        Route::get('/autocomplete', [ContentController::class, 'autocomplete'])->name(
            'autocomplete'
        );
        Route::get(
            '/image/{file}',
            function ($file) {
                return \Storage::response('uploads/images/' . $file);
            }
        )->name('image');
        Route::get(
            '/audio/{file}',
            function ($file) {
                return \Storage::response('uploads/audio/' . $file);
            }
        )->name('audio');

        Route::get('lang/{lang}', [\App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');

        Route::get(
            '/project/{projectId}/log/{id}/',
            [ProjectController::class, 'getDetails']
        )->name(
            'log.detail'
        );

        Route::post(
            '/reset-log',
            [ProjectController::class, 'resetValue']
        )->name(
            'log.reset'
        );

        Route::get(
            '/allComments',
            [ContentController::class, 'listComments']
        )->name(
            'all.comments'
        );

        Route::get(
            '/project/{id}/translate',
            [ProjectController::class, 'translateCurrentProject']
        )->name(
            'translate'
        );

        Route::post(
            '/project/save-translate-text',
            [ContentController::class, 'saveTranslatedText']
        )->name(
            'save.translation.text'
        );

        Route::get(
            '/project/{id}/metadata',
            [ProjectController::class, 'editMetaData']
        )->name(
            'project.metadata'
        );

        Route::post(
            '/comment/{id}/update/{status}',
            [ContentController::class, 'updateStatus']
        )->name(
            'update.status'
        );

        Route::post(
            '/save-gallery',
            [ContentController::class, 'saveGallery']
        )->name(
            'save.gallery'
        );

        Route::get(
            '/gallery/{id}/edit',
            [ContentController::class, 'editGallery']
        )->name(
            'gallery.edit'
        );

        Route::delete('/delete/{id}/gallery', [ContentController::class, 'destroyGallery'])->name(
            'gallery.delete'
        );

        Route::post(
            '/save-audiovisual',
            [\App\Http\Controllers\AudiovisualController::class, 'store']
        )->name(
            'save.audiovisual'
        );

        Route::delete('/delete/{id}/audiovisual', [\App\Http\Controllers\AudiovisualController::class, 'delete'])->name(
            'audiovisual.delete'
        );

        Route::post(
            '/comment/{id}/audiovisual',
            [\App\Http\Controllers\AudiovisualController::class, 'commentAudiovisual']
        )->name(
            'comment.audiovisual'
        );

        Route::post('/comment/audiovisual', [\App\Http\Controllers\AudiovisualController::class, 'audiovisualCommentSave'])->name(
            'comment.audiovisual.save'
        );

        Route::post(
            '/comment/{id}/gallery',
            [\App\Http\Controllers\ContentController::class, 'commentGallery']
        )->name(
            'comment.gallery.save'
        );

        Route::post('/comment/gallery', [\App\Http\Controllers\ContentController::class, 'galleryCommentSave'])->name(
            'comment.gallery'
        );

        Route::get('/preview', [\App\Http\Controllers\ProjectController::class, 'previewProject'])->name(
            'preview'
        );

        Route::get(
            '/image/{file}/preview',
            function ($file) {
                return \Storage::response('img/' . $file);
            }
        )->name('image.preview');

        Route::get('/preview/download', [\App\Http\Controllers\ProjectController::class, 'downloadPreview'])->name(
            'download'
        );

        Route::get('/copyright', [\App\Http\Controllers\ProjectController::class, 'projectMetadata'])->name(
            'preview.metadata'
        );

    }
);

require __DIR__ . '/auth.php';
