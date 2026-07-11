
<ul>

    @foreach($childs as $user)
        <li>
            <a href="javascript:void(0);">
                <div class="member-view-box">
                    <div class="member-image">
                        <img src="{{ asset('public/img/tree-user.png') }}"  alt="Member">
                        <div class="member-details">
                            <h5>{{ $user->name }} @if (count($user->childs) > 0) ({{ count($user->childs) }}) @endif </h5>
                            {{-- <h6>({{ $user->designation->name  }})</h6> --}}
                            @if ($user->designation_name)
                                <h6>({{ $user->designation_name  }})</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </a>

            @if(count($user->childs) > 0)
                @include('users.tree.level-2-child',['childs' => $user->childs])
            @endif

        </li>
    @endforeach

</ul>
