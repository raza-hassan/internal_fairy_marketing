@extends('layouts.app', ['activePage' => 'settings', 'titlePage' => __('Settings')])

@section('content')
@include('admin.settings.sidebar')
<div class="ps-main__wrapper">
    @if (session('status'))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>Success! </strong> {{ session('status') }}
            </div>
        </div>
    </div>
    @endif
    @if ($errors->any())
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <header class="header--dashboard">
        <div class="header__left">
            <h3>Facebook Access Token</h3>
            <p>Update the long-lived token used for Facebook Lead Ads sync and Conversions API reporting.</p>
        </div>
    </header>

    <section class="ps-new-item">
        <div class="ps-form__content">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <figure class="ps-block--form-box">
                        <figcaption>Current Status</figcaption>
                        <div class="ps-block__content">

                            @if ($facebook)
                            @php
                            $token = $facebook->long_lived_token;
                            $maskedToken = strlen($token) > 16
                            ? substr($token, 0, 8) . str_repeat('*', 20) . substr($token, -6)
                            : str_repeat('*', 12);

                            $expiresAt = $facebook->expires_at;
                            $isExpired = $expiresAt && now()->greaterThan($expiresAt);
                            $daysLeft = $expiresAt ? now()->diffInDays($expiresAt, false) : null;
                            @endphp

                            <div class="form-group">
                                <label>Token</label>
                                <input class="form-control" type="text" value="{{ $maskedToken }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>Page ID</label>
                                <input class="form-control" type="text" value="{{ $facebook->page_id }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>Expires At</label>
                                <input class="form-control" type="text"
                                    value="{{ $expiresAt ? $expiresAt->format('Y-m-d H:i') : 'Unknown' }}" readonly>
                            </div>

                            <div class="form-group">
                                @if ($isExpired)
                                <span class="badge badge-danger">Expired</span>
                                @elseif (!is_null($daysLeft) && $daysLeft <= 3) <span class="badge badge-warning">
                                    Expires in {{ $daysLeft }} day(s) &mdash; refresh soon</span>
                                    @else
                                    <span class="badge badge-success">Valid{{ !is_null($daysLeft) ? " — {$daysLeft}
                                        day(s) left" : '' }}</span>
                                    @endif
                            </div>

                            <form method="post" action="{{ url('admin/facebook-token/refresh') }}">
                                @csrf
                                <button type="submit" class="ps-btn ps-btn--gray"
                                    onclick="return confirm('Refresh the current token now?')">
                                    <i class="icon icon-reload mr-2"></i>Refresh Now
                                </button>
                            </form>
                            @else
                            <p>No Facebook token has been saved yet. Paste one below to set it up.</p>
                            @endif

                        </div>
                    </figure>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <figure class="ps-block--form-box">
                        <figcaption>Update Token</figcaption>
                        <div class="ps-block__content">
                            <form method="post" action="{{ url('admin/facebook-token/update') }}">
                                @csrf

                                <div class="form-group">
                                    <label>New Access Token<sup>*</sup></label>
                                    <textarea class="form-control" name="access_token" rows="4"
                                        placeholder="Paste the User/Page token generated from the Facebook Graph API Explorer or App Dashboard"
                                        required>{{ old('access_token') }}</textarea>
                                    <small class="form-text text-muted">This will be exchanged for a long-lived token
                                        and saved automatically — no code changes or deploys required.</small>
                                </div>

                                <div class="form-group">
                                    <label>App ID</label>
                                    <input class="form-control" type="text" name="client_id"
                                        value="{{ old('client_id', optional($facebook)->client_id) }}"
                                        placeholder="Leave blank to keep the current App ID">
                                </div>

                                <div class="form-group">
                                    <label>App Secret</label>
                                    <input class="form-control" type="password" name="client_secret" value=""
                                        placeholder="Leave blank to keep the current App Secret">
                                </div>

                                <div class="form-group">
                                    <label>Page ID</label>
                                    <input class="form-control" type="text" name="page_id"
                                        value="{{ old('page_id', optional($facebook)->page_id) }}"
                                        placeholder="Leave blank to keep the current Page ID">
                                </div>

                                <button type="submit" class="ps-btn success">Exchange &amp; Save Token</button>
                            </form>
                        </div>
                    </figure>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection