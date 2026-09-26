{{-- Allocation <option>s grouped by HOD, each user's team indented under them. Params: $users (collection), $selected
    (optional user id) --}}
    @foreach(\App\Http\Helpers\Helper::groupUsersByHod($users) as $group)
    <optgroup label="{{ $group['label'] }}">
        @foreach($group['items'] as $item)
        <option value="{{ $item['user']->id }}" @if(isset($selected) && $selected==$item['user']->id) selected @endif>
            {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $item['depth']) !!}{{ $item['depth'] ? '└ ' : '' }}{{
            $item['user']->name }}{{ $item['details'] }}</option>
        @endforeach
    </optgroup>
    @endforeach
