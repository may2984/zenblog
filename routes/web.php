<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\PostContoller;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TestContoller;

use Illuminate\Http\Middleware\LoginCheck;
use Illuminate\Http\Middleware\AlreadyLoggedIn;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/article/{category}/{slug}/{id}', [HomeController::class, 'posts'])->name('post.url');
Route::get('/category/{category}', [HomeController::class, 'category'])->name('category.url');
Route::post('contact/us', [HomeController::class, 'contactUs'])->name('blog.contact');

Route::get('/login', \Auth0\Laravel\Http\Controller\Stateful\Login::class)->name('login');
Route::get('/auth0/callback', \Auth0\Laravel\Http\Controller\Stateful\Callback::class)->name('auth0.callback');
Route::get('/logout', \Auth0\Laravel\Http\Controller\Stateful\Logout::class)->name('logout');


/*
Route::get('/', function () {
    if (Auth::check()) 
    {
        return view('auth0.user');
    }
    return view('auth0/guest');
})->middleware(['auth0.authenticate.optional']);
*/

// Require an authenticated session to access this route.
Route::get('/required', function () { 
    return view('auth0.user'); 
})->middleware(['auth0.authenticate']);

Route::middleware(['logged.in'])->group(function() {
    Route::get('/admin/login', function() {
        return view('admin.login');
    })->name('admin.login');
    
    Route::get('/admin/register', function() {
        return view('admin.register');
    })->name('admin.register');

    Route::post('/admin/login/check', [AdminController::class, 'login'])->name('admin.login.check');
    Route::post('/admin/register/save', [AdminController::class, 'register'])->name('admin.register.save');
});

Route::prefix('admin')->middleware(['login.check'])->controller(AdminController::class)->group(function() {
    Route::get('/', 'index')->name('admin');
    Route::get('dashboard', 'dashboard')->name('admin.dashboard');    
    Route::get('expense/add', 'add')->name('admin.expense.add');
    Route::get('expense/list', 'list')->name('admin.expense.list');
    Route::get('logout', 'logout')->name('admin.logout');
    Route::get('profile', 'profile')->name('admin.profile');
    Route::post('profile/save', 'profileSave')->name('admin.profile.save');
    Route::post('profile/photo/upload', 'profilePhotoUpload')->name('profile.photo.upload');
});

Route::prefix('admin')->middleware(['login.check'])->controller(BlogCategoryController::class)->group(function() {    
    Route::get('blog/category/add', 'add')->name('admin.blog.category.add');
    Route::post('blog/category/store', 'store')->name('admin.blog.category.store');
    Route::get('blog/category/edit/{id}', 'edit')->name('admin.blog.category.edit');
    Route::post('blog/category/edit/save', 'update')->name('admin.blog.category.edit.save');
    Route::post('blog/category/delete/{id}', 'delete')->name('admin.blog.category.dalete');
    Route::post('blog/category/sort/', 'sort')->name('admin.blog.category.sort');
});

Route::prefix('admin')->middleware(['login.check'])->controller(TagController::class)->group(function() {
    Route::get('tag/add', 'add')->name('admin.tag.add');
    Route::post('tag/store', 'store')->name('admin.tag.store');
    Route::post('tag/delete/{id}', 'delete')->name('admin.tag.delete');
    Route::get('tag/edit/{id}', 'edit')->name('admin.tag.edit');
    Route::post('tag/modify', 'modify')->name('admin.tag.modify');
});

Route::prefix('admin')->middleware(['login.check'])->controller(AuthorController::class)->group(function(){
    Route::get('author/create', 'create')->name('author.create');
    Route::post('author/store', 'store')->name('author.store');
    Route::get('author/index', 'index')->name('author.list');
    Route::get('author/destroy/{id}', 'destroy')->name('author.delete');
    Route::get('author/edit/{id}', 'edit')->name('author.edit');
    Route::post('author/update/{id}', 'update')->name('author.update');
});

Route::prefix('admin')->middleware(['login.check'])->controller(PostContoller::class)->group(function() {
    Route::get('post/list', 'index')->name('post.list');
    Route::get('post/add', 'create')->name('post.add');
    Route::post('post/store', 'store')->name('post.store');    
    Route::get('post/edit/{id}', 'edit')->name('post.edit');
    Route::post('post/update/{id}', 'update')->name('post.update');
    Route::post('post/destroy/{id}', 'destroy')->name('post.delete');

    Route::get('post/toggle/publish/{status}/{id}', 'togglePublish')->name('post.toggle.publish');


    Route::post('test/upload', 'upload')->name('post.upload');
});

Route::get('admin/test',[TestContoller::class, 'index'])->name('test');
Route::get('tags', [TagController::class, 'getTags'])->name('tags');

Route::get('livewire', function() {
    return view('admin.livewire');
})->name('livewire');


    
