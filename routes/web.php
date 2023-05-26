<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BlogCategoryController;
use Illuminate\Http\Middleware\AlreadyLoggedIn;
use Illuminate\Http\Middleware\LoginCheck;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PostContoller;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TestContoller;
use App\Http\Controllers\CommentController;
use App\Http\Resources\UserResource;
use App\Models\BlogCategory;
use App\Models\Post;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\User;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/article/{blog_category:url}/{slug}/{post:id}', [HomeController::class, 'posts'])->name('post.url')->scopeBindings();
Route::get('/article/{post:id}', [HomeController::class, 'post']);
Route::get('/category/{blog_category:url}', [HomeController::class, 'category'])->name('category.url');
Route::post('contact/us', [HomeController::class, 'contactUs'])->name('blog.contact');

Route::get('pay/instamojo', [TestContoller::class, 'payInstaMojo']);
Route::get('pay/instamojo/gatewayorder', [TestContoller::class, 'instaMojoCreateGatewayOrder']);
Route::get('instamojo/success', [TestContoller::class, 'instaMojoSuccess']);
Route::get('instamojo/details', [TestContoller::class, 'instaMojoRequestDetails']);

Route::get('pay/razorpay', [TestContoller::class, 'payRazorPay']);
Route::get('razorpay/success', [TestContoller::class, 'RazorPaySuccess']);

# Tags
Route::get('/tags/{blog_tag:name}', [HomeController::class, 'showTagPost']);

# Comments
Route::post('/add/post', [CommentController::class, 'store'])->name('post.comment');

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

Route::prefix('admin')->middleware(['login.check'])->controller(BlogCategoryController::class)->group(function() {    
    Route::get('blog/category/add', 'add')->name('category.add');
    Route::post('blog/category/store', 'store')->name('admin.blog.category.store');
    Route::get('blog/category/edit/{tag}', 'edit')->name('admin.blog.category.edit');
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

Route::get('admin/test',[TestContoller::class, 'index'])->name('test');
Route::get('tags', [TagController::class, 'getTags'])->name('tags');

Route::get('livewire', function() {
    return view('admin.livewire');
})->name('livewire');


    
