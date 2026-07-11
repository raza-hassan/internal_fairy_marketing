
@if($id != '')
    <p><label>Affliator ID: </label> {{$id}}</p>
@endif

@if($name != '')
    <p><label>Name: </label> {{$name}}</p>
@endif

@if($email != '')
    <p><label>Email: </label> {{$email}}</p>
@endif

@if($phone != '')
    <p><label>Phone: </label> {{$phone}}</p>
@endif

@if($status != '')
    @if($status == 0)
        <p><label>Status : </label> Disable</p>
    @elseif($status == 1)
        <p><label>Status : </label> Enable</p>
    @elseif($status == 2)
        <p><label>Status : </label> Rejected</p>
    @else
   	<p><label>Status : </label> Under Progress</p>
    @endif
@endif

@if($note != '')
    <p><label>Note : </label> {{$note}}</p>
@endif



{{-- ============================================= --}}
{{--
@if($telephone != '')
    <p><label>Telephone: </label> {{$telephone}}</p>
@endif

@if($telephone1 != '')
    <p><label>Telephone1: </label> {{$telephone1}}</p>
@endif

@if($address != '')
    <p><label>Address: </label> {{$address}}</p>
@endif

@if($cnic != '')
    <p><label>CNIC: </label> {{$cnic}}</p>
@endif

@if($cnicf != '')
    <p> <label>Cnic Front Image: </label>
        {{$cnicf}}
    </p>
@endif

@if($cnicb != '')
    <p><label>Cnic Back Image : </label> {{$cnicb}}</p>
@endif

@if($gender != '')
    <p><label>Gender : </label> {{$gender}}</p>
@endif

@if($user_id != '')
    <p><label>User_ID : </label> {{$user_id}}</p>
@endif

@if($offinspic != '')
    <p><label>Offinspic: </label> {{$offinspic}}</p>
@endif

@if($offoutspic != '')
    <p><label>Offoutspic : </label> {{$offoutspic}}</p>
@endif

@if($offboardpic != '')
    <p><label>Offboardpic: </label> {{$offboardpic}}</p>
@endif

@if($offvisitpic != '')
    <p><label>Offvisitpic : </label> {{$offvisitpic}}</p>
@endif

@if($type != '')
    <p><label>Type : </label> {{$type}}</p>
@endif --}}




