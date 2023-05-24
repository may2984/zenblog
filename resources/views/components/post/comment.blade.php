<div class="col-lg-12">
    <h5 class="comment-title">Leave a Comment</h5>
    <form action="{{ route('post.comment') }}" method="post">
        @csrf        
        <div class="row">
            <x-admin.form.alert />
            <div class="col-lg-6 mb-3">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" value="{{ old('name') }}">
                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="col-lg-6 mb-3">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}">
                @error('email')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="col-12 mb-3">
                <label for="message">Message</label>
                <textarea class="form-control" id="message" name="message" placeholder="Enter your name" cols="30" rows="10">{{ old('message') }}</textarea>
                @error('message')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="col-12">
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <input type="submit" class="btn btn-primary" value="Post comment">
            </div>
        </div>
    </form>
</div>