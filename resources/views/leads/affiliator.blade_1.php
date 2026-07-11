<div class="form-group payment_div"><!---->
    <div class="form-group col-md-12">
        <label class="col-md-3 control-label ">Unit Price</label>
        <div class="col-md-8 ">
            <span class="text-bold">PKR 5,619,240</span>
        </div>
    </div>
    <!---->
    <div class="form-group col-md-12 ng-star-inserted">
        <label class="col-md-3 control-label " for="statuses_list">Unit Status
            <span aria-required="true" class="required"> * </span>
        </label>
        <div class="col-md-8 ">
            <select class="form-control input-sm" disabled="" id="statuses_list" name="statuses_list">
                <option value="">Select</option>
                <option value="Token Payment">Token Payment</option>
                <option value="Complete Down Payment">Complete Down Payment</option>
            </select>
        </div>
    </div>
    <div class="form-group col-md-12">
        <label class="col-md-3 control-label ">Unit Discount</label>
        <div class="col-md-5 ">
            <input class="form-control input-sm input-arrow" type="number">
        </div>
        <div class="col-md-3">
            <input class="form-control input-sm input-arrow" type="number">
        </div>
        <div class="col-md-1 nopadding ">
            <span>%</span>
        </div>
    </div>
    <div class="form-group col-md-12">
        <label class="col-md-3 control-label " for="deal_price">Deal Price 
            <span aria-required="true" class="required"> * </span>
        </label>
        <div class="col-md-8 ">
            <input class="form-control" id="deal_price" name="deal_price" type="number">
        </div>
        <span class="col-md-9"> Enter the price on which the deal was locked with the customer. </span>
    </div>
    <div class="form-group col-md-12">
        <label class="col-md-3 control-label" for="deal_price"> Due Amount: </label>
        <span class="col-md-8"> PKR 1,685,772 (30%) </span>
    </div>
</div>

<div class="nopadding form-group col-md-12 ng-star-inserted" style="">
    <div class="row add-payment-form">
        <h3 class="modal-title col-md-12 margin-bottom-12">Add Payment</h3>
        <div class="form-group col-md-12">
            <label class="col-md-3 control-label">Amount: 
                <span aria-required="true" class="required ng-star-inserted"> * </span>
            </label>
            <div class="col-md-8">
                <input class="form-control input-sm ng-pristine ng-valid ng-touched" type="number">
            </div>
        </div>
        <div class="form-group col-md-12">
            <label class="col-md-3 control-label col-md-offset-3">Payment Mode: 
                <span aria-required="true" class="required ng-star-inserted"> * </span>
            </label>
            <div class="col-md-8">
                <select class="form-control input-sm ng-untouched ng-pristine ng-valid">
                    <option value="">Select</option>
                    <option value="1"> Cheque </option>
                    <option value="2"> Cash </option>
                    <option value="3"> Pay Order </option>
                    <option value="8"> Bank Loan </option>
                    <option value="9"> Online Payment </option>
                </select>
            </div>
        </div>
        <div class="form-group col-md-12">
            <label class="col-md-3 control-label">Receipt #: </label>
            <div class="col-md-8">
                <input class="form-control input-sm" min="1" type="number">
            </div>
            <span class="col-md-9 text-muted description text-left"> Receipt number </span>
        </div>
    </div>
</div>