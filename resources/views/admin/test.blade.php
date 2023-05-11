<x-admin.layout>
    <x-slot:title>ddd</x-slot:title>    
    <div class="container row">
    {{ $posts->title }}                            
    <br>
    {{ $posts->user->name }}                            
    <br>
    <b>Authors</b>
     @foreach($posts->authors As $author)
      {{ $author->full_name }}
     @endforeach     
    <b>Tags</b>
     @foreach($posts->tags As $tag)
      {{ $tag->name }}
     @endforeach     
     <br>
     <b>Main Category</b>
     @foreach($posts->post_main_category As $post_main_category)
    <b>Name:</b> {{ $post_main_category->name }}
     <b>URL:</b> {{ $post_main_category->description }}
     @endforeach  
     <br>
     <b>Other Categories</b>
     {{ $categories }}
    </div>    
</x-admin.layout>