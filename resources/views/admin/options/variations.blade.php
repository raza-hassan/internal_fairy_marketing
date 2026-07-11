<?php
$counter = 1;
if (count($records) > 0) {
    ?>
    @foreach($records as $record)
    <div class="variation-row" id="row_<?php echo $counter; ?>">
        <label class="col-md-2 control-label deleterow" data-row="<?php echo $counter; ?>"><i class="fa fa-trash-o"></i></label>
        <div class="col-md-5">
            <input class="form-control" type="text" placeholder="Title" value="{{ $record->title }}" disabled/>
        </div>

        <div class="col-md-5">
            <input class="form-control" name="variation_price[]" type="text" placeholder="Price"/>
            <input name="variation[]" type="hidden" value="{{ $record->id }}"/>
            <input name="attributes[]" type="hidden" value="{{ $record->attributes_id }}"/>
        </div>
    </div>
    <?php $counter++; ?>
    
    @endforeach
    <hr>
    <?php
}
?>