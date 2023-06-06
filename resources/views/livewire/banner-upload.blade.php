<div class="col-sm-10">
    @if($banner_image)<img src="{{ $banner_image }}"/>@endif
    <input class="form-control" type="file" id="banner_image" name="banner_image" wire:model="banner_image" wire:change="$emit('previewFile')">
</div>