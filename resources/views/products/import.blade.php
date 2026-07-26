@extends('layouts.app', ['activePage' => 'inventory', 'titlePage' => __('Inventory Import')])

@section('content')

@include('products.sidebar')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="ps-main__wrapper import-page">

    <header class="header--dashboard import-header">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h3 class="mb-2">
                    <i class="fa fa-upload text-primary mr-2"></i>
                    Import Inventory
                </h3>

                <p class="mb-0 text-muted">
                    Upload a CSV or Excel file to import or update available inventory.
                </p>

            </div>

            <div class="header-right text-center">

                <div class="header-icon mb-2">
                    <i class="fa fa-file-excel-o"></i>
                </div>

                <span class="badge badge-primary px-3 py-2">
                    CSV / XLSX
                </span>

            </div>

        </div>

    </header>

    <style>
        .import-header {
            background: linear-gradient(135deg, #ffffff, #f8fbff);
            border: 1px solid #e9ecef;
            border-radius: 14px;
            padding: 22px 28px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
        }

        .import-header h3 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
        }

        .import-header p {
            font-size: 15px;
            color: #6c757d;
        }

        .header-right {
            min-width: 120px;
        }

        .header-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            border-radius: 50%;
            background: #eaf3ff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-icon i {
            font-size: 28px;
            color: #0d6efd;
        }

        .header-right .badge {
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
        }
    </style>
    <section class="ps-items-listing">
        <div class="container-fluid">

            @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <h6 class="mb-2">
                    <i class="fa fa-exclamation-circle"></i>
                    Please fix the following errors
                </h6>

                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card shadow-sm border-0">

                <div class="card-header bg-light py-3 px-2">

                    <div class="d-flex align-items-center">

                        <div class="section-icon mr-3">
                            <i class="fa fa-sliders"></i>
                        </div>

                        <div>
                            <label class="font-weight-bold mb-0 d-block">
                                Import Mode
                                <span class="text-danger">*</span>
                            </label>

                            <small class="text-muted">
                                Choose how inventory values should be imported.
                            </small>
                        </div>

                    </div>

                </div>

                <style>
                    .section-icon {
                        width: 42px;
                        height: 42px;
                        border-radius: 10px;
                        background: #eef5ff;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0px 10px 0px 10px;
                    }

                    .section-icon i {
                        color: #0d6efd;
                        font-size: 18px;
                    }
                </style>

                <div class="card-body">

                    <form action="{{ route('products.import.store') }}" method="POST" enctype="multipart/form-data"
                        id="product_import_form">

                        @csrf

                        <div class="mb-2">

                            <label class="font-weight-bold d-block mb-10 mt-10">
                                Import Mode
                                <span class="text-danger">*</span>
                            </label>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="import-option w-100">

                                        <input type="radio" name="value_source" value="calculate" checked>

                                        <div class="import-card">

                                            <div class="mb-3">

                                                <i class="fa fa-calculator fa-3x text-success"></i>

                                            </div>

                                            <h5 class="font-weight-bold">
                                                Recalculate
                                            </h5>

                                            <p class="text-muted mb-0">
                                                Amounts, Quarterly Installments, Possession Amount and Down Payment will be
                                                calculated using Advanced Settings or default values.
                                            </p>

                                        </div>

                                    </label>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="import-option w-100">

                                        <input type="radio" name="value_source" value="file">

                                        <div class="import-card">

                                            <div class="mb-3">

                                                <i class="fa fa-file-excel-o fa-3x text-primary"></i>

                                            </div>

                                            <h5 class="font-weight-bold">
                                                Exact Values
                                            </h5>

                                            <p class="text-muted mb-0">
                                                Import exact Amount, Total, Down Payment, Remaining, Quarterly &
                                                Possession values directly from your file.
                                            </p>

                                        </div>

                                    </label>

                                </div>

                            </div>

                        </div>

                        <div class="mb-5">

                            <label class="font-weight-bold mb-10 d-block">
                                Select CSV / Excel File
                                <span class="text-danger">*</span>
                            </label>

                            <div class="file-upload-wrapper mb-15">

                                <input type="file" id="import_file" name="import_file" accept=".csv,.xls,.xlsx" required
                                    class="hidden">

                                <label for="import_file" class="file-upload-card">

                                    <div class="upload-icon">
                                        <i class="fa fa-cloud-upload"></i>
                                    </div>

                                    <h5 class="mb-2">
                                        Drag & Drop your file here
                                    </h5>

                                    <p class="text-muted mb-3">
                                        or click the button below to browse
                                    </p>

                                    <span class="btn btn-primary px-4">
                                        <i class="fa fa-folder-open mr-2"></i>
                                        Browse File
                                    </span>

                                    <div class="selected-file mt-3 text-success font-weight-bold">
                                        No file selected
                                    </div>

                                    <small class="text-muted d-block mt-2">
                                        Supported formats: CSV, XLS, XLSX
                                    </small>

                                </label>

                            </div>

                            <div class="alert alert-info mt-3">

                                <i class="fa fa-info-circle mr-2"></i>

                                <span id="file_requirements_hint">

                                </span>

                            </div>

                        </div>
                        <div class="mb-5">

                            <h5 class="font-weight-bold mb-3">
                                <i class="fa fa-download text-primary mr-2"></i>
                                Sample Files
                            </h5>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <div class="card border h-100">

                                        <div class="card-body text-center">

                                            <i class="fa fa-file-text-o fa-3x text-success mb-3"></i>

                                            <h6 class="font-weight-bold">
                                                Recalculate Mode
                                            </h6>

                                            <p class="text-muted small">
                                                Download sample file for automatic calculations.
                                            </p>

                                            <a href="{{ asset('public/inventory-import-samples/product_import_minimal_sample.csv') }}"
                                                class="btn btn-outline-success btn-block" download>

                                                <i class="fa fa-download"></i>

                                                Download Sample

                                            </a>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <div class="card border h-100">
                                        <div class="card-body text-center">
                                            <i class="fa fa-file-excel-o fa-3x text-primary mb-3"></i>
                                            <h6 class="font-weight-bold">
                                                Exact Values Mode
                                            </h6>
                                            <p class="text-muted small">
                                                Download sample file with complete values.
                                            </p>
                                            <a href="{{ asset('public/inventory-import-samples/product_import_full_sample.csv') }}"
                                                class="btn btn-outline-primary btn-block" download>
                                                <i class="fa fa-download"></i>
                                                Download Sample
                                            </a>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="alert alert-light border mt-3">
                                <i class="fa fa-lightbulb-o text-warning"></i>
                                Download the correct sample before importing. Your file columns should exactly match the
                                sample.
                            </div>

                        </div>

                        <div class="mb-2">
                            <button type="button" id="toggle_advanced_btn" class="btn btn-light border p-3">
                                <i class="fa fa-cog"></i>
                                Advanced Settings (Optional)
                            </button>

                        </div>

                        <div id="advanced_settings_panel" class="card border-0 shadow-sm mb-4" style="display:none;">
                            <div class="card-header bg-light mb-15">
                                <h5>
                                    <i class="fa fa-sliders"></i>
                                    Advanced Settings
                                </h5>
                            </div>

                            <div class="card-body">

                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i>
                                    <span id="advanced_settings_note">
                                        Leave any field empty to use database settings first, otherwise hardcoded
                                        defaults will be used.
                                    </span>
                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                Down Payment %
                                            </label>
                                            <div class="form-group">
                                                <input type="number" step="0.01" name="down_payment_percent"
                                                    class="form-control" value="{{ old('down_payment_percent') }}"
                                                    placeholder="25">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                Possession %
                                            </label>
                                            <div class="form-group">
                                                <input type="number" step="0.01" name="possession_percent"
                                                    class="form-control" value="{{ old('possession_percent') }}"
                                                    placeholder="10">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                No. Quarterly Installments
                                            </label>
                                            <input type="number" name="quarterly_installments_count"
                                                class="form-control" value="{{ old('quarterly_installments_count') }}"
                                                placeholder="24">
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                No. Monthly Installments
                                            </label>
                                            <input type="number" name="monthly_installments_count"
                                                class="form-control" value="{{ old('monthly_installments_count') }}"
                                                placeholder="60">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                Corner Amount (Rs.)
                                            </label>
                                            <div class="form-group">
                                                <input type="number" step="0.01" name="corner_amount"
                                                    id="corner_amount_input" class="form-control"
                                                    value="{{ old('corner_amount') }}" placeholder="2000000">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                Corner %
                                            </label>
                                            <div class="form-group">
                                                <input type="number" step="0.01" name="corner_percent"
                                                    id="corner_percent_input" class="form-control"
                                                    value="{{ old('corner_percent') }}" placeholder="10">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle mr-1"></i>
                                    Fill only one field:
                                    <strong>Corner Amount</strong> OR
                                    <strong>Corner %</strong>.
                                    The other field will automatically be disabled.
                                </div>

                            </div>

                        </div>

                        <div class="text-right mt-10">
                            <button type="submit" class="btn btn-success btn-lg px-5" id="import_submit_btn">
                                <i class="fa fa-upload mr-2"></i>
                                Update Inventory
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </section>

</div>

<style>
    .import-option input {
        display: none;
    }

    .import-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all .25s ease;
        height: 100%;
        background: #fff;
    }

    .import-card:hover {
        border-color: #007bff;
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
    }

    .import-option input:checked+.import-card {
        border-color: #007bff;
        background: #f4f9ff;
        box-shadow: 0 8px 20px rgba(0, 123, 255, .15);
    }

    .card {
        background: #fff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(0, 0, 0, .05);
    }

    .card-header {
        background: #f8fafc !important;
        border-bottom: 1px solid #eef2f7;
        padding: 18px 25px;
    }

    .card-body {
        padding: 10px 30px;
    }

    .mb-5 {
        margin-bottom: 2.5rem !important;
    }

    .mb-2 {
        margin-bottom: 1.5rem !important;
    }

    label {
        margin-bottom: 10px;
    }

    .alert {
        margin-top: 10px;
        border-radius: 10px;
    }


    .form-control {
        border-radius: 8px;
    }

    .input-group-text {
        border-radius: 8px;
    }

    .btn {
        border-radius: 8px;
    }

    .btn-outline-success:hover,
    .btn-outline-success:focus {
        color: #fff !important;
    }

    .btn-outline-primary:hover,
    .btn-outline-primary:focus {
        color: #fff !important;
    }


    /* ---------- Upload Area ---------- */

    .file-upload-card {
        display: block;
        border: 2px dashed #cfd8dc;
        border-radius: 14px;
        padding: 20px 30px;
        text-align: center;
        background: #fafcff;
        cursor: pointer;
        transition: all .3s ease;
    }

    .file-upload-card:hover {
        border-color: #007bff;
        background: #f4f9ff;
        box-shadow: 0 12px 30px rgba(0, 123, 255, .12);
    }

    .file-upload-card.active {
        border-color: #28a745;
        background: #f5fff7;
    }

    .upload-icon {
        width: 85px;
        height: 85px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: #eaf3ff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .3s;
    }

    .file-upload-card:hover .upload-icon {
        transform: scale(1.08);
    }

    .upload-icon i {
        font-size: 38px;
        color: #007bff;
    }

    .selected-file {
        margin-top: 18px;
        min-height: 24px;
        font-weight: 600;
        color: #28a745;
        word-break: break-word;
    }

    .file-upload-card small {
        display: block;
        margin-top: 12px;
        color: #6c757d;
    }

    .file-upload-wrapper {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px;
    }


    /* Browse Button */

    .file-upload-card .btn {
        padding: 10px 28px;
        font-weight: 600;
        border-radius: 30px;
        box-shadow: 0 4px 12px rgba(0, 123, 255, .15);
    }

    .file-upload-card .btn:hover {
        transform: translateY(-2px);
    }

    .import-page {
        background: #f4f7fb;
        min-height: 100vh;
        padding: 25px;
    }

    .ps-items-listing {
        background: #ffffff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, .05);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(function () {

        $('#toggle_advanced_btn').on('click', function () {
            $('#advanced_settings_panel').slideToggle();
        });

        var minimalHint =
            '"Recalculate" mode requires only the <strong>Unit No, Covered Area, Price P/Sft, and Corner</strong> columns in the file. Only inventory with <strong>Status = Available</strong> will be updated.';

        var fullHint =
            '"Exact Values" mode requires the following columns: <strong>Unit No, Covered Area, Price P/Sft, Corner, Amount, Total Amount, Down Payment, Remaining Amount, Quarterly Installment, and Possession Amount</strong>. Only inventory with <strong>Status = Available</strong> will be updated.';
            
        function applyModeState() {

            var isFileMode =
                $('input[name="value_source"]:checked').val() === 'file';

            $('#file_requirements_hint').html(
                isFileMode ? fullHint : minimalHint
            );

            $('#advanced_settings_panel :input')
                .prop('disabled', isFileMode);

            $('#toggle_advanced_btn')
                .prop('disabled', isFileMode);

            if(isFileMode){
                $('#advanced_settings_panel').slideUp();
            }

        }

        $('input[name="value_source"]')
            .on('change', applyModeState);

        applyModeState();

        $('#corner_amount_input').on('input', function () {

            var hasValue = $(this).val() !== '';

            $('#corner_percent_input').prop('disabled', hasValue);

            if(hasValue){
                $('#corner_percent_input').val('');
            }

        });

        $('#corner_percent_input').on('input', function () {

            var hasValue = $(this).val() !== '';

            $('#corner_amount_input').prop('disabled', hasValue);

            if(hasValue){
                $('#corner_amount_input').val('');
            }

        });

        $('#product_import_form').on('submit', function(){

            $('#import_submit_btn')
                .prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin mr-2"></i> Importing...');

        });

        $('#import_file').on('change', function () {

            if (this.files.length) {

                $('.selected-file').html(
                    '<i class="fa fa-check-circle"></i> ' + this.files[0].name
                );

                $('.file-upload-card').addClass('active');

            } else {

                $('.selected-file').html('No file selected');

                $('.file-upload-card').removeClass('active');
            }

        });

    });

</script>

@endsection