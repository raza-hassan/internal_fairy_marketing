@if($category)
@foreach($category as $cat)
<div class="form-group">
    <label class="col-md-3 control-label"> ({{$category->name}}) Sub Category </label>
    <div class="col-md-6">
        <div class="col-md-12">
            <input name="all_sub_category[]" type="checkbox" value="<?php echo $category->id; ?>" class="selectall">&nbsp;&nbsp;Select All
        </div>
        @foreach($sub_category as $cat)
        <div class="col-md-6">
            <input name="sub_category[]" type="checkbox" value="<?php echo $cat->id; ?>" class="sub_category">&nbsp;&nbsp;<?php echo $cat->name; ?>
        </div>
        @endforeach
    </div>
</div>
@endforeach
@endif