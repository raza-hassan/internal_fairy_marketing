@if($name != '')
    <p><label>Name: </label> {{$name}}</p>
@endif
@if($email != '')
    <p><label>Email: </label> {{$email}}</p>
@endif
@if($phone != '')
    <p><label>Phone: </label> {{$phone}}</p>
@endif
@if($address != '')
    <p><label>Address: </label> {{$address}}</p>
@endif
@if($source_id != '')
    <p><label>Source: </label> {{$source_id}}</p>
@endif
@if($user != '')
<p><label>Added By: </label> {{$user}}</p>
@endif
