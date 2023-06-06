<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use Illuminate\Http\Middleware\AlreadyLoggedIn;
use Illuminate\Http\Middleware\LoginCheck;

use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestContoller;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostContoller;
use App\Http\Resources\UserResource;
use App\Models\BlogCategory;
use App\Models\Post;
use App\Models\User;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

Route::get('/article/{blog_category:url}/{slug}/{post:id}', [HomeController::class, 'posts'])        
        ->name('post.url')
        ->scopeBindings();

Route::get('/article/{post:id}', [HomeController::class, 'post']);
Route::get('/category/{blog_category:url}', [HomeController::class, 'category'])->name('category.url');
Route::post('contact/us', [HomeController::class, 'contactUs'])->name('blog.contact');

# Tags
Route::get('/tags/{blog_tag:name}', [HomeController::class, 'showTagPost']);

# Comments
Route::post('/add/post', [CommentController::class, 'store'])->name('post.comment');

# Users
Route::get('/user/{id}', function (Request $request, string $id) {
    return 'User '.$id;
});

Route::get('/user/{name?}', function (string $name = null) {
    return $name;
});

Route::get('/user/{name?}', function (string $name = 'John') {
    return $name;
});

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
    Route::get('blog/home/page', 'blogHomePageNews')->name('home.page');
    Route::get('blog/home/news/{category_id}', 'getPostList')->name('category.post.list');
    Route::post('blog/home/news/save', 'savePostList')->name('category.post.save');
});

Route::prefix('admin')->middleware(['login.check'])->group(function(){
    Route::resource('banner', BannerController::class);
    Route::post('banner/sort/', [BannerController::class,'sort'])->name('banner.sort');
    Route::post('banner/upload', [BannerController::class,'upload'])->name('banner.upload');
    Route::get('banner/toggle/status/{status}/{id}', [BannerController::class,'toggleStatus']);
});

Route::prefix('admin')->middleware(['login.check'])->controller(BlogCategoryController::class)->group(function() {    
    Route::get('blog/category/add', 'add')->name('category.add');
    Route::post('blog/category/store', 'store')->name('admin.blog.category.store');
    Route::get('blog/category/edit/{id}', 'edit')->name('admin.blog.category.edit');
    Route::post('blog/category/edit/save', 'update')->name('admin.blog.category.edit.save');
    Route::post('blog/category/delete/{id}', 'delete')->name('admin.blog.category.dalete');
    Route::post('blog/category/sort/', 'sort')->name('admin.blog.category.sort');
});

Route::prefix('admin')->middleware(['login.check'])->controller(TagController::class)->group(function() {
    Route::get('tag/add', 'add')->name('tag.add');
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
    Route::get('author/all', 'index');
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

# Crop

Route::post('/test/crop', [TestContoller::class, 'crop']);
Route::post('/test/crop2', [TestContoller::class, 'crop2']);

Route::get('admin/test',[TestContoller::class, 'index'])->name('test');
Route::get('admin/test2',[TestContoller::class, 'test2'])->name('test2');

# drop zone

Route::get('/admin/dropzone1', function(){
    return view('admin.dropzone1');
})->name('dropzone1');

Route::post('/upload/drpozone1', [TestContoller::class, 'uploadDropzone1']);

Route::get('tags', [TagController::class, 'getTags'])->name('tags');

Route::get('livewire', function() {
    return view('admin.livewire');
})->name('livewire');


    
