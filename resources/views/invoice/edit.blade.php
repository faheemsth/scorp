@extends('layouts.admin')
@section('page-title')
    {{ __('Invoice Edit') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">{{ __('Invoice') }}</a></li>
    <li class="breadcrumb-item">{{ __('Invoice Edit') }}</li>
@endsection
@push('script-page')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            // var $dragAndDrop = $("body .repeater tbody").sortable({
            //     handle: '.sort-handler'
            // });
            var $repeater = $(selector + ' .repeater').repeater({
                // initEmpty: true,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                    // if ($('.select2').length) {
                    //     $('.select2').select2();
                    // }
                },
                hide: function(deleteElement) {


                    $(this).slideUp(deleteElement);
                    $(this).remove();
                    var inputs = $(".amount");
                        var subTotal = 0;
                        for (var i = 0; i < inputs.length; i++) {
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        }
                        
                        var totalItemDiscountPrice = 0;
                        var itemDiscountPriceInput = $('.discount');

                        for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                            totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                        }

                        var totalItemTaxPrice = 0;
                        var itemTaxPriceInput = $('.taxamount');
                        var itemPriceInput = $('.price');
                        var itemQuantityInput = $('.quantity');

                        for (var k = 0; k < itemTaxPriceInput.length; k++) {
                            let tax = parseFloat(itemTaxPriceInput[k].value);
                            if(!isNaN(tax)){

                                let price = itemPriceInput[k].value;
                                let quantity = itemQuantityInput[k].value;
                                price = price * quantity;
                                var itemTaxPrice = parseFloat((tax / 100) * (price));
                                // $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

                                totalItemTaxPrice += parseFloat(itemTaxPrice.toFixed(2));
                            }else{
                                totalItemTaxPrice += 0;
                            }
                        }

                        $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));

                        $('.totalTax').html(totalItemTaxPrice.toFixed(2));

                        $('.subTotal').html(subTotal.toFixed(2));
                        $('.totalAmount').html(subTotal.toFixed(2) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice) );

                },
                ready: function(setIndexes) {
                    // $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');

            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                console.log(value)
                // $repeater.setList(value);
                
                // for (var i = 0; i < value.length; i++) {
                //     var tr = $('#sortable-table .id[value="' + value[i].id + '"]').parent();
                //     tr.find('.item').val(value[i].product_name);
                //     changeItem(tr.find('.item'));
                // }
            }

        }

        $(document).on('change', '#customer', function() {
            $('#customer_detail').removeClass('d-none');
            $('#customer_detail').addClass('d-block');
            $('#customer-box').removeClass('d-block');
            $('#customer-box').addClass('d-none');
            var id = $(this).val();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function(data) {
                    if (data != '') {
                        $('#customer_detail').html(data);
                    } else {
                        $('#customer-box').removeClass('d-none');
                        $('#customer-box').addClass('d-block');
                        $('#customer_detail').removeClass('d-block');
                        $('#customer_detail').addClass('d-none');
                    }
                },

            });
        });

        $(document).on('click', '#remove', function() {
            $('#customer-box').removeClass('d-none');
            $('#customer-box').addClass('d-block');
            $('#customer_detail').removeClass('d-block');
            $('#customer_detail').addClass('d-none');
        })

        $(document).on('change', '.item', function() {
            changeItem($(this));
        });

        var invoice_id = '{{ $invoice->id }}';

        function changeItem(element) {

            console.log(element)
            var iteams_id = element.val();
            console.log(iteams_id)

            var url = element.data('url');
            console.log(url)

            var el = element;
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'product_id': iteams_id
                },
                cache: false,
                success: function(data) {
                    var item = JSON.parse(data);

                    $.ajax({
                        url: '{{ route('invoice.items') }}',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': jQuery('#token').val()
                        },
                        data: {
                            'invoice_id': invoice_id,
                            'product_id': iteams_id,
                        },
                        cache: false,
                        success: function(data) {
                            var invoiceItems = JSON.parse(data);


                            if (invoiceItems != null) {


                                var amount = (invoiceItems.price * invoiceItems.quantity);

                                $(el.parent().parent().find('.quantity')).val(invoiceItems
                                .quantity);
                                let tax = invoiceItems.tax;
                                if(tax != NULL){
                                    tax = (tax/amount)*100;
                                }else{
                                    tax = '';
                                }
                                $(el.parent().parent().find('.taxamount')).val(tax);
                                $(el.parent().parent().find('.price')).val(invoiceItems.price);
                                $(el.parent().parent().find('.discount')).val(invoiceItems
                                .discount);
                                $('.pro_description').text(invoiceItems.description);

                            } else {
                                $(el.parent().parent().find('.quantity')).val(1);
                                $(el.parent().parent().find('.price')).val(item.product.sale_price);
                                $(el.parent().parent().find('.discount')).val(0);
                                $(el.parent().parent().find('.pro_description')).val(item.product
                                    .sale_price);
                                $('.pro_description').text(item.product.sale_price);

                            }


                            var taxes = '';
                            var tax = [];

                            var totalItemTaxRate = 0;
                            for (var i = 0; i < item.taxes.length; i++) {
                                taxes +=
                                    '<span class="badge bg-primary p-2 px-3 rounded mt-1 mr-1">' +
                                    item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' +
                                    '</span>';
                                tax.push(item.taxes[i].id);
                                totalItemTaxRate += parseFloat(item.taxes[i].rate);
                            }

                            if (invoiceItems != null) {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (
                                    invoiceItems.price * invoiceItems.quantity));
                            } else {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (item
                                    .product.sale_price * 1));
                            }

                            $(el.parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(
                                2));
                            $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate
                                .toFixed(2));
                            $(el.parent().parent().find('.taxes')).html(taxes);
                            $(el.parent().parent().find('.tax')).val(tax);
                            $(el.parent().parent().find('.unit')).html(item.unit);


                            if (invoiceItems != null) {
                                $(el.parent().parent().find('.amount')).html(amount);
                            } else {
                                $(el.parent().parent().find('.amount')).html(item.totalAmount);
                            }

                            var inputs = $(".amount");
                            var subTotal = 0;
                            for (var i = 0; i < inputs.length; i++) {
                                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                            }
                            $('.subTotal').html(subTotal.toFixed(2));

                            var totalItemDiscountPrice = 0;
                            var itemDiscountPriceInput = $('.discount');

                            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k]
                                    .value);
                            }


                            var totalItemPrice = 0;
                            var priceInput = $('.price');
                            for (var j = 0; j < priceInput.length; j++) {
                                totalItemPrice += parseFloat(priceInput[j].value);
                            }

                            var totalItemTaxPrice = 0;
                            var itemTaxPriceInput = $('.itemTaxPrice');
                            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                            }

                            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                            $('.totalAmount').html((parseFloat(subTotal) - parseFloat(
                                    totalItemDiscountPrice) + parseFloat(totalItemTaxPrice))
                                .toFixed(2));
                            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));

                        }
                    });


                },
            });
        }

        // $(document).on('keyup', '.quantity', function() {
        //     var quntityTotalTaxPrice = 0;

        //     var el = $(this).parent().parent().parent().parent();
        //     var quantity = $(this).val();
        //     var price = $(el.find('.price')).val();
        //     var discount = $(el.find('.discount')).val();

        //     var totalItemPrice = (quantity * price);
        //     var amount = (totalItemPrice);
        //     $(el.find('.amount')).html(amount);

        //     var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
        //     var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
        //     $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


        //     var totalItemTaxPrice = 0;
        //     var itemTaxPriceInput = $('.itemTaxPrice');
        //     for (var j = 0; j < itemTaxPriceInput.length; j++) {
        //         totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
        //     }


        //     var inputs = $(".amount");
        //     var subTotal = 0;
        //     for (var i = 0; i < inputs.length; i++) {
        //         subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
        //     }
        //     $('.subTotal').html(subTotal.toFixed(2));
        //     $('.totalTax').html(totalItemTaxPrice.toFixed(2));

        //     $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

        // })


        // $(document).on('keyup', '.price', function() {

        //     var el = $(this).parent().parent().parent().parent();
        //     var price = $(this).val();
        //     var quantity = $(el.find('.quantity')).val();
        //     var discount = $(el.find('.discount')).val();
        //     var totalItemPrice = (quantity * price);

        //     var amount = (totalItemPrice);
        //     $(el.find('.amount')).html(amount);


        //     var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
        //     var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
        //     $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


        //     var totalItemTaxPrice = 0;
        //     var itemTaxPriceInput = $('.itemTaxPrice');
        //     for (var j = 0; j < itemTaxPriceInput.length; j++) {
        //         totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
        //     }


        //     var inputs = $(".amount");
        //     var subTotal = 0;
        //     for (var i = 0; i < inputs.length; i++) {
        //         subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
        //     }
        //     $('.totalTax').html(totalItemTaxPrice.toFixed(2));

        //     $('.subTotal').html(subTotal.toFixed(2));
        //     $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

        // })
        $(document).on('keyup', '.price, .quantity, .discount, .taxamount', function() {
            var el = $(this).parent().parent().parent();
            // var price = $(this).val();
            // var price = $('td .price').val();
            var price = $(el.find('.price')).val();
            // var quantity = $('td .quantity').val();
            var quantity = $(el.find('.quantity')).val();

            if (quantity < 1) {
                quantity = 1;
            }
            var discount = $(el.find('.discount')).val();
            var totalItemPrice = (quantity * price);

            var amount = (totalItemPrice);
            // alert(amount)
            console.log(el)
            console.log(el.find('.amount'))
            console.log('amount',amount)
            // console.log(el.find('.amount'))

            $(el.find('.amount').first()).html(amount);


            // var totaltex = $('td .taxamount').val();
            var totaltex = el.find('.taxamount').val();

            console.log(totaltex)
            var itemTaxPrice = parseFloat((totaltex / 100) * (totalItemPrice));
            
            $(el.find('.itemTaxPrice').first()).val(itemTaxPrice.toFixed(2));


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                let tax = parseFloat(itemTaxPriceInput[j].value);
                console.log('totalItemTaxPrice',tax)
                
                if(!isNaN(tax)){

                    totalItemTaxPrice += parseFloat(tax);
                console.log('totalItemTaxPrice===',totalItemTaxPrice)

                }else{
                    totalItemTaxPrice += 0;
                }
            }

            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {
                let dis = parseFloat(itemDiscountPriceInput[k].value);
                if(isNaN(dis)){
                    totalItemDiscountPrice += 0;
                }else{
                    totalItemDiscountPrice += parseFloat(dis);
                }
                console.log('totalItemDiscountPricetotalItemDiscountPricetotalItemDiscountPrice',totalItemDiscountPrice)
            }



            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.subTotal').html(subTotal.toFixed(2));

            subTotal = parseFloat(subTotal);
            totalItemDiscountPrice = parseFloat(totalItemDiscountPrice);
            totalItemTaxPrice = parseFloat(totalItemTaxPrice);

            if(isNaN(totalItemDiscountPrice)){
                totalItemDiscountPrice = 0;
                $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));

            }
            console.log('subTotal',subTotal)
            console.log('totalItemDiscountPrice',totalItemDiscountPrice)
            console.log('totalItemTaxPrice',totalItemTaxPrice)

            $('.totalAmount').html((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));

        })

        $(document).on('keyup', '.discount', function() {
            var el = $(this).parent().parent().parent();
            var discount = $(this).val();
            var price = $(el.find('.price')).val();

            var quantity = $(el.find('.quantity')).val();
            if (quantity < 1) {
                quantity = 1;
            }
            var totalItemPrice = (quantity * price);

            // var totalItemTaxRate = $(el.find('.taxamount')).val();
            var totaltex = $(el.find('.taxamount')).val();

            var itemTaxPrice = parseFloat((totaltex / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {
                let dis = parseFloat(itemDiscountPriceInput[k].value);
                if(isNaN(dis)){
                    totalItemDiscountPrice += 0;
                }else{
                    totalItemDiscountPrice += parseFloat(dis);
                }
                
            }

            var amount = (totalItemPrice);
            console.log(el.find('.amount'))
            $(el.find('.amount').first()).html(amount);

            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + parseFloat(
                totalItemTaxPrice)).toFixed(2));
        })

        $(document).on('click', '[data-repeater-create]', function() {
            
            $(".amount").last().text('0.00')
            let item = document.querySelectorAll('.item').length;
            item = item-1;

            $('.ui-sortable').append(`<tr >
                                        <td colspan="2">
                                            <div class="form-group">
                                                {{ Form::textarea('description', null, ['name' => 'items[`+item+`][description]','class' => 'form-control', 'rows' => '2', 'placeholder' => __('Description')]) }}
                                            </div>
                                        </td>
                                        <td colspan="5"></td>
                                    </tr>`);
        })

        $(document).on('click', '[data-repeater-delete]', function() {
            // $('.delete_item').click(function () {
            if (confirm('Are you sure you want to delete this element?')) {
                var el = $(this).parent().parent();
                var id = $(el.find('.id')).val();

                let item = document.querySelectorAll('.item').length;
                console.log(item)
                item = item;
                let name = 'items['+item+'][description]';
                $("textarea[name='"+name+"']").remove();
                console.log(name)
            }
        });
        $(document).ready(function() {
            // $("div.desc").hide();
            $("input[name$='cars']").click(function() {
                var test = $(this).val();
                $("div.desc").hide();
                $("#" + test).show();
            });
        });
    </script>
@endpush

@section('content')
    {{--    @dd($invoice) --}}
    <div class="row">
        {{ Form::model($invoice, ['route' => ['invoice.update', $invoice->id], 'method' => 'PUT', 'class' => 'w-100']) }}
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{ Form::radio('cars', 'customer', false, ['class' => 'form-check-input','checked' => $invoice->customer_id != NULL ?'checked':'']) }}
                                    User Voucher
                                </label>

                                <label class="px-5">
                                    {{ Form::radio('cars', 'threeCarDiv', false, ['class' => 'form-check-input','checked' => $invoice->customer_id == NULL ?'checked':'']) }}
                                    Open Voucher
                                </label>
                            </div>
                            <div id="customer" class="desc {{ $invoice->customer_id == NULL ?'d-none':'' }}">
                                <div class="form-group type1" id="customer">
                                    {{ Form::label('customer_id', __('User'), ['class' => 'form-label']) }}
                                    {{ Form::select('customer_id', $customers, null, ['class' => 'form-control select2 desc', 'id' => 'customer', 'data-url' => route('invoice.customer')]) }}
                                </div>
                            </div>

                            <div id="threeCarDiv" class="desc {{ $invoice->customer_id != NULL ?'d-none':'' }}">
                                <div class="form-group type2" id="threeCarDiv">
                                    {{ Form::label('user_name', __('User Name'), ['class' => 'form-label']) }}
                                    {{ Form::text('user_name', $invoice->user_name, ['class' => 'form-control  desc', 'placeholder' => __('Enter user name')]) }}
                                </div>
                            </div>
                            <div id="customer_detail" class="d-none">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('issue_date', __('Issue Date'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            {{ Form::date('issue_date', null, ['class' => 'form-control', 'required' => 'required']) }}


                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('due_date', __('Due Date'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            {{ Form::date('due_date', null, ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('invoice_number', __('Invoice Number'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            <input type="text" class="form-control" value="{{ $invoice_number }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{ Form::label('category_id', __('Category'), ['class' => 'form-label']) }}
                                    {{ Form::select('category_id', $category, null, ['class' => 'form-control select']) }}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('ref_number', __('Ref Number'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            <span><i class="ti ti-joint"></i></span>
                                            {{ Form::text('ref_number', null, ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                                {{--                                <div class="col-md-6"> --}}
                                {{--                                    <div class="form-check custom-checkbox mt-4"> --}}
                                {{--                                        <input class="form-check-input" type="checkbox" name="discount_apply" id="discount_apply" {{$invoice->discount_apply==1?'checked':''}}> --}}
                                {{--                                        <label class="form-check-label" for="discount_apply">{{__('Discount Apply')}}</label> --}}
                                {{--                                    </div> --}}
                                {{--                                </div> --}}

                                {{--                                <div class="col-md-6"> --}}
                                {{--                                    <div class="form-group"> --}}
                                {{--                                        {{Form::label('sku',__('SKU')) }} --}}
                                {{--                                        {!!Form::text('sku', null,array('class' => 'form-control','required'=>'required')) !!} --}}
                                {{--                                    </div> --}}
                                {{--                                </div> --}}
                                @if (!$customFields->isEmpty())
                                    <div class="col-md-6">
                                        <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                            @include('customFields.formBuilder')
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class=" d-inline-block mb-4">{{ __('Product & Services') }}</h5>
           {{-- <div class="card repeater" data-value='{!! json_encode($invoice->items) !!}'> --}}
            <div class="card repeater" >

                <div class="item-section py-2">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box me-2">
                                <a href="#" data-repeater-create="" class="btn btn-primary" data-bs-toggle="modal"
                                    data-target="#add-bank">
                                    <i class="ti ti-plus"></i> {{ __('Add item') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 table-custom-style"  id="sortable-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Items') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Price') }} </th>
                                    <th>{{ __('Tax') }}(%)</th>
                                    <th>{{ __('Discount') }}</th>
                                    <th class="text-end">{{ __('Amount') }} </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="ui-sortable" data-repeater-list="items">
                                @php
                                    $totalQuantity=0;
                                    $totalRate=0;
                                    $totalTaxPrice=0;
                                    $totalDiscount=0;
                                    $taxesData=[];
                                    $totalTax= 0 ;

                                @endphp
                                @foreach($items as $key =>$item)
                                    @php
                                                        
                                        $totalQuantity+=$item->quantity;
                                        $totalRate+=$item->price;
                                        $totalDiscount+=$item->discount;
                                        $totalTax+=$item->tax;
                                        $amount = $item->quantity * $item->price;
                                        $tax = ($item->tax/$amount)*100;
                                    @endphp
                               
                                    <tr data-repeater-item>
                                    {{-- {{ Form::hidden('id', null, ['class' => 'form-control id']) }} --}}
                                        <td >
                                            <div class="form-group price-input input-group search-form">
                                                {{ Form::text('item', $item->product_name, ['class' => 'form-control item', 'required' => 'required', 'placeholder' => __('Item')]) }}
                                                <span class="unit input-group-text bg-transparent"></span>
                                            </div>

                                        </td>
                                        <td>

                                            <div class="form-group price-input input-group search-form">
                                                {{ Form::text('quantity',$item->quantity , ['class' => 'form-control quantity', 'required' => 'required', 'placeholder' => __('Qty'), 'required' => 'required']) }}
                                                <span class="unit input-group-text bg-transparent"></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group price-input input-group search-form">
                                                {{ Form::text('price', $item->price, ['class' => 'form-control price', 'required' => 'required', 'placeholder' => __('Price'), 'required' => 'required']) }}
                                                <span
                                                    class="input-group-text bg-transparent">{{ \Auth::user()->currencySymbol() }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <!-- <div class="input-group colorpickerinput"> -->
                                                    
                                                    {{ Form::text('tax', $tax, ['class' => 'form-control taxamount', 'required' => 'required', 'placeholder' => __('Tax')]) }}</div>
                                                    {{ Form::hidden('tax', '', ['class' => 'form-control tax']) }}
                                                    {{ Form::hidden('itemTaxPrice', $item->tax, ['class' => 'form-control itemTaxPrice']) }}
                                                    {{ Form::hidden('itemTaxRate', '', ['class' => 'form-control itemTaxRate']) }}
                                                <!-- </div> -->
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group price-input input-group search-form">
                                                {{ Form::text('discount', $item->discount, ['class' => 'form-control discount', 'required' => 'required', 'placeholder' => __('Discount')]) }}
                                                <span
                                                    class="input-group-text bg-transparent">{{ \Auth::user()->currencySymbol() }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end amount">{{$amount}}</td>

                                        <td>
                                            @can('delete invoice product')
                                                <a href="#" class="ti ti-trash text-white text-danger delete_item"
                                                    data-repeater-delete></a>
                                            @endcan
                                        </td>
                                    </tr>
                                    <tr >
                                        <td colspan="2">
                                            <div class="form-group">
                                                {{ Form::textarea('description', $item->description, ['name' => 'items['.$key.'][description]','class' => 'form-control', 'rows' => '2', 'placeholder' => __('Description')]) }}
                                            </div>
                                        </td>
                                        <td colspan="5"></td>
                                    </tr>
                                @endforeach
                             
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{ __('Sub Total') }} ({{ \Auth::user()->currencySymbol() }})</strong>
                                    </td>
                                    <td class="text-end subTotal">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{ __('Discount') }} ({{ \Auth::user()->currencySymbol() }})</strong></td>
                                    <td class="text-end totalDiscount">{{\Auth::user()->priceFormat($totalDiscount)}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{ __('Tax') }} ({{ \Auth::user()->currencySymbol() }})</strong></td>
                                    <td class="text-end totalTax">{{\Auth::user()->priceFormat($totalTax)}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td class="blue-text"><strong>{{ __('Total Amount') }}
                                            ({{ \Auth::user()->currencySymbol() }})</strong></td>
                                    <td class="text-end totalAmount blue-text">{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="button" value="{{ __('Cancel') }}" onclick="location.href = '{{ route('invoice.index') }}';"
                class="btn btn-light">
            <input type="submit" value="{{ __('Update') }}" class="btn  btn-dark px-2">
        </div>
        {{ Form::close() }}
    </div>
@endsection
