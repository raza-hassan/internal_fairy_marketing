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

@if($user != '')
<p><label>Added By: </label> {{$user}}</p>
@endif

@if($source != '')
<p><label>Source: </label> {{$source}}</p>
@endif

@if($project != '')
<p><label>Project: </label> {{$project}}</p>
@endif

@if($interested != '')
<p><label>Project: </label> {{$interested}}</p>
@endif
@if($notes != '')
<p><label>Comments: </label> {{$notes}}</p>
@endif