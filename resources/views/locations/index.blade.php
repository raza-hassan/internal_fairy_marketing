@extends('layouts.app', ['activePage' => 'affiliators', 'titlePage' => __('Products')])

@section('content')

@include('affiliators.sidebar')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet">

<style>
    body {
        background: #f5f7fb;
    }

    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
    }

    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, .06);
    }

    .card-body {
        padding: 30px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 25px;
        color: #212529;
    }

    .form-label {
        font-size: 15px;
        font-weight: 600;
        color: #374151 !important;
        margin-bottom: 8px;
    }

    .form-text {
        font-size: 13px;
        color: #6b7280;
    }

    .form-control,
    .form-select,
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 48px;
        font-size: 15px;
        border-radius: 10px;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .15);
    }

    .btn-primary {
        border-radius: 10px;
        padding: 12px 28px;
        font-size: 15px;
        font-weight: 600;
    }

    #tree {
        font-size: 16px;
        line-height: 2.1;
    }

    .tree-item {
        background: #fff;
        border-radius: 10px;
        padding: 10px 15px;
        margin-bottom: 8px;
        transition: .2s;
    }

    .tree-item:hover {
        background: #f8f9fa;
    }

    .tree-city {
        font-size: 17px;
        font-weight: 700;
        color: #0d6efd;
    }

    .tree-child {
        font-size: 15px;
        color: #374151;
    }

    .location-id {
        font-size: 13px;
    }

    .del-btn {
        font-size: 13px;
        text-decoration: none;
    }

    .alert {
        border-radius: 10px;
        font-size: 14px;
    }

    /* Select2 */
    .select2-container {
        width: 100% !important;
        font-size: 16px !important;
    }

    .select2-container--bootstrap-5 .select2-selection,
    .select2-container .select2-selection--single {
        min-height: 52px !important;
        height: 52px !important;
        border-radius: 12px !important;
        border: 1px solid #d7dce3 !important;
        display: flex !important;
        align-items: center !important;
        padding: 0 12px !important;
    }

    .select2-container .select2-selection__rendered {
        font-size: 16px !important;
        line-height: 50px !important;
        color: #374151 !important;
    }

    .select2-container .select2-selection__arrow {
        height: 50px !important;
    }

    .select2-dropdown {
        border-radius: 12px !important;
        border: 1px solid #d7dce3 !important;
        font-size: 15px !important;
    }

    .select2-results__option {
        padding: 12px 16px !important;
        font-size: 15px !important;
    }

    .select2-search__field {
        height: 40px !important;
        font-size: 15px !important;
    }

</style>

<div class="ps-main__wrapper p-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Location Management</h1>
    </div>

    <div class="row g-4">
        {{-- Add form --}}
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-3">Add location</h3>

                    <div id="alertBox"></div>

                    <form id="addForm">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Parent Location</label>
                            <select id="parent_id" class="form-select"></select>
                            <div class="form-text">Leave empty to create a new city.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted">Location Name</label>
                            <input id="name" type="text" class="form-control" placeholder="e.g. Lahore, Zone 1, G-5"
                                autocomplete="off">
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">Add Location</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Live tree --}}
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-3">Location Hierarchy</h3>
                    <div id="tree" style="font-size: 14px; line-height: 2;">Loading…</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    let locations = [];

    function childrenOf(id) {
        return locations.filter(l => (id == null ? l.parent_id == null : l.parent_id == id));
    }

    function loadLocations() {
        return $.getJSON("{{ route('locations.data') }}", function (data) {
            locations = data;
            renderParentOptions();
            renderTree();
        });
    }

    function renderParentOptions() {
        const keep = $('#parent_id').val();
        let html = '<option value="">— No Parent (Create as City) —</option>';
        locations
            .slice()
            .sort((a, b) => a.full_path.localeCompare(b.full_path))
            .forEach(l => {
                html += '<option value="' + l.id + '">' + l.full_path + '</option>';
            });
        $('#parent_id').html(html).val(keep);

        // Select2 ko dobara init karo (options badalne ke baad)
        if ($('#parent_id').hasClass('select2-hidden-accessible')) {
            $('#parent_id').select2('destroy');
        }
        $('#parent_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select a parent location (optional)',
            allowClear: true
        });
    }

    function renderTree() {
        function walk(parentId, depth) {
            let out = '';
            childrenOf(parentId).forEach(l => {
                const pad = depth * 20;
                const isCity = depth === 0;
                out += '<div style="padding-left:' + pad + 'px;" class="d-flex align-items-center gap-2 py-1">'
                    + '<span class="' + (isCity ? 'fw-semibold text-primary' : 'text-dark') + '">' + l.name + '</span>'
                    + '<span class="text-muted small">#' + l.id + '</span>'
                    + '<button class="btn btn-sm btn-link text-danger p-0 ms-1 del-btn" data-id="' + l.id + '" data-name="' + l.name + '">delete</button>'
                    + '</div>';
                out += walk(l.id, depth + 1);
            });
            return out;
        }
        const html = walk(null, 0);
        $('#tree').html(html || '<span class="text-muted">No locations found. Add your first location.</span>');
    }

    $('#addForm').on('submit', function (e) {
        e.preventDefault();
        $('#alertBox').empty();

        $.ajax({
            url: "{{ route('locations.store') }}",
            method: 'POST',
            dataType: 'json',
            data: {
                parent_id: $('#parent_id').val(),
                name: $('#name').val()
            },
            success: function (response) {
                $('#name').val('');
                loadLocations();

                $('#alertBox').html(`
                    <div class="alert alert-success py-2 small mb-3">
                        ${response.message}
                    </div>
                `);
                setTimeout(function () {
                    $('.alert').alert('close');
                }, 3000);
            },
            error: function (xhr) {

                let message = 'Something went wrong. Please try again.';
                if (xhr.status === 422) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                $('#alertBox').html(`
                    <div class="alert alert-danger py-2 small mb-3">
                        ${message}
                    </div>
                `);
                setTimeout(function () {
                    $('.alert').alert('close');
                }, 3000);
            }
        });
    });

    $('#tree').on('click', '.del-btn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const deleteUrl = "{{ route('locations.destroy', ':id') }}".replace(':id', id);

        if (!confirm('Are you sure you want to delete "' + name + '"? All child locations will also be deleted.')) return;

        $.ajax({
            url: deleteUrl,
            method: 'DELETE',
            dataType: 'json',
            success: function () { loadLocations(); },
            error: function (xhr) {
                let message = 'Unable to delete this location.';
                if (xhr.status === 409 || xhr.status === 500) {
                    message = 'This location cannot be deleted because it is currently associated with existing records.';
                }
                $('#alertBox').html(`
                    <div class="alert alert-danger py-2 small mb-3">
                        ${message}
                    </div>
                `);
                setTimeout(function () {
                    $('.alert').alert('close');
                }, 3000);
            }
        });
    });

    loadLocations();
});
</script>

@endsection
