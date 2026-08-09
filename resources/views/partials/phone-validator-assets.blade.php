{{--
Include this partial ONCE on any page that has a phone field using
PhoneFieldValidator. Usage: @include('partials.phone-validator-assets')

Centralizes the intl-tel-input CDN version — if it ever needs
upgrading, this is the only file that needs to change.
--}}

<style>
    .iti--inline-dropdown {
        width: 100%;
    }
</style>

<link rel="stylesheet" type="text/css"
    href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js">
</script>
<script type="text/javascript" src="{{ asset('public/js/phone-field-validator.js') }}"></script>
