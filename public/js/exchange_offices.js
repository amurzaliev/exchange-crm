
function moneyOperation(profile_monetary_operations_create,exchange_office_id) {


    $(".money_operation .save-transactions").on("click", function (e) {
        e.preventDefault();
        var currency, national_currency, amount, data, descountCalculation, margin ;
        var cashboxId = $(this).data("cashbox-id");
        var cashboxAction = $(this).data("cashbox-action");
        var cashboxNumberClass = ".money_operation .cashbox-number-" + cashboxId;
        var vip_client_id = $(".money_operation #vip_client_id").val();
        var notes = $(".money_operation #notes").val();
        var $boxMessage = $(".money_operation .box-message");
        var $btnSaveTransactions = $(".money_operation .save-transactions");

        var amountAndCashboxId = "amount-"+cashboxId;
        var amountDiscount = $(".money_operation").find("#"+amountAndCashboxId).val();
        var btnActive = $(".money_operation").find(".activeBtn-"+cashboxId).attr("data-active");

        var currencySale = $(".cashbox-number-" + cashboxId + " .cashbox-sale").val();
        var currencyPurchase = $(".cashbox-number-" + cashboxId + " .cashbox-purchase").val();
        var differenceСourse = currencySale - currencyPurchase ;


        if (cashboxAction === "sale") {
            currency = $(cashboxNumberClass + " .cashbox-sale").val();
            amount = $(cashboxNumberClass + " .sale .cashbox-form").val();
            descountCalculation = discount(cashboxId, cashboxNumberClass, amount, amountDiscount, parseInt(btnActive));
            national_currency = currency * amount - parseFloat(descountCalculation);
            margin = differenceСourse * amount;

            if(amountDiscount !== ''){
                if(parseInt(btnActive) === 1){
                    let  procentDicount = (100 - amountDiscount) /100;
                    margin  = differenceСourse * procentDicount * amount ;
                }else if(parseInt(btnActive) === 0){
                    margin  = differenceСourse * amount - amountDiscount ;
                }
            }

        } else if (cashboxAction === "purchase") {
                currency = $(cashboxNumberClass + " .cashbox-purchase").val();
                amount = $(cashboxNumberClass + " .purchase .cashbox-form").val();
                descountCalculation = discount(cashboxId, cashboxNumberClass, amount, amountDiscount, parseInt(btnActive));
                national_currency = currency * amount + parseFloat(descountCalculation);

                margin = differenceСourse * amount;

            if(amountDiscount !== ''){
                if(parseInt(btnActive) === 1){
                    let  procentDicount = (100 - amountDiscount) /100;
                         margin  = differenceСourse * procentDicount * amount ;
                }else if(parseInt(btnActive) === 0){
                    margin  = differenceСourse * amount - amountDiscount ;
                }
            }
        }


        data = {
            cashboxId,
            cashboxAction,
            currency,
            amount,
            national_currency,
            exchange_office_id,
            vip_client_id,
            notes,
            margin
        };
        $.post(profile_monetary_operations_create, data)
            .done(function (response) {

                if (response.status) {
                    $(".money_operation .cashbox-form").val("");
                    $(".money_operation .form-result").val("");
                    $(".money_operation .cashbox-row td").removeClass("active-cashbox");
                    $boxMessage.html('<p class="bg-success">' + response.message + '</p>');
                    $btnSaveTransactions.data("cashbox-id", 0);
                    $btnSaveTransactions.data("cashbox-action", "");
                    $(cashboxNumberClass + " .cashbox-allAmount").html(response.allAmount);
                    $(".money_operation #resultDefaultCurrencyAmount").html(response.resultDefaultCurrencyAmount);
                } else {
                    $boxMessage.html('<p class="bg-danger">' + response.message + '</p>');
                }
            })
            .fail(function (response) {
                console.log(response);
            });
        $('.discount-form').val('');
    });

    $(".money_operation .cashbox-form").on("keyup", function () {

        var cashboxAction = $(this).data("cashbox-action");
        var cashboxId = $(this).data("cashbox-id");
        var currency, result, descountCalculation;
        var amount = $(this).val();

        var discountNumber = $(this).parent().parent().children('.discount').children('.discount-form').val();
        var discountField = $(this).parent().parent().children('.discount').children('.discount-form').data('cashbox-id');
        var $btnSaveTransactions = $(".money_operation .save-transactions");
        var cashboxNumberClass = ".cashbox-number-" + cashboxId;
        var $boxMessage = $(".money_operation .box-message");
        let btnActive = $(this).parent().parent().children('.discount').children('.btn_discount').attr('data-active');

        $boxMessage.html("");
        $(".money_operation .cashbox-form").val("");
        $(".money_operation .form-result").val("");
        $('.money_operation .discount-form').val('');

        if (cashboxId === discountField) {
            $(this).parent().parent().children('.discount').children('.discount-form').val(discountNumber)
        }
        $btnSaveTransactions.data("cashbox-id", cashboxId);
        $btnSaveTransactions.data("cashbox-action", cashboxAction);
        $(".money_operation .cashbox-row td").removeClass("active-cashbox");
        $(cashboxNumberClass + " td").addClass("active-cashbox");
        $(this).val(amount);

        if (cashboxAction === "sale") {
            $(".money_operation .cashbox-row td.purchase").removeClass("active-cashbox");
            currency = $(".cashbox-number-" + cashboxId + " .cashbox-sale").val();

            if (discountNumber !== '') {
                descountCalculation = discount(cashboxId, cashboxNumberClass, amount, discountNumber, parseInt(btnActive));
                result = currency * amount - descountCalculation;
            } else {

                result = currency * amount;
            }

        } else if (cashboxAction === "purchase") {
            $(".money_operation .cashbox-row td.sale").removeClass("active-cashbox");
            currency = $(".cashbox-number-" + cashboxId + " .cashbox-purchase").val();

            if (discountNumber !== '') {
                descountCalculation = discount(cashboxId, cashboxNumberClass, amount, discountNumber, parseInt(btnActive));
                result = currency * amount + parseFloat(descountCalculation);
            } else {
                result = currency * amount;
            }
        }

        $(cashboxNumberClass + " .form-result").val(result);
    });


    $(".money_operation .discount-form").on("keyup", function () {

        let cashboxId = $(this).parent().parent().children('td').children('.money_operation .cashbox-currency').data("cashbox-id");
        let cashboxNumberClass = ".cashbox-number-" + cashboxId;

        let amountPurchase = $(this).parent().parent().children('.money_operation .purchase').children('.money_operation .cashbox-form').val();
        let amountSale = $(this).parent().parent().children('.money_operation .sale').children('.money_operation .cashbox-form').val();

        let currencySale = $(".cashbox-number-" + cashboxId + " .cashbox-sale").val();
        let currencyPurchase = $(".cashbox-number-" + cashboxId + " .cashbox-purchase").val();

        let numberDiscount = $(this).val();
        let result, descountCalculation;

        let btnActive = $(this).parent().children('.money_operation .btn_discount').attr('data-active')
        if (amountSale !== '') {
            descountCalculation = discount(cashboxId, cashboxNumberClass, amountSale, numberDiscount, parseInt(btnActive));
            result = currencySale * amountSale - descountCalculation;

        }
        else if (amountPurchase !== '') {

            descountCalculation = discount(cashboxId, cashboxNumberClass, amountPurchase, numberDiscount, parseInt(btnActive));
            result = currencyPurchase * amountPurchase + parseFloat(descountCalculation);
        }
        $(cashboxNumberClass + " .form-result").val(result);
    });

    var discount = function (cashbox, cashboxNumberClass, amount, amountDiscount, btnActive) {
        let cashboxId = cashbox;
        let currencySale = $(".cashbox-number-" + cashboxId + " .cashbox-sale").val();
        let currencyPurchase = $(".cashbox-number-" + cashboxId + " .cashbox-purchase").val();
        let discount;
        if (amountDiscount !== '') {
            if (btnActive === 1) {
                let oneUnit = (currencySale - currencyPurchase) * (parseInt(amountDiscount) / 100);
                discount = amount * oneUnit;
                return discount;
            } else if (btnActive === 0) {
                return amountDiscount;
            }
        } else {
            return 0;
        }
    }

    $('.money_operation .btn_discount').on('click', function () {
        var data_active = $(this).attr('data-active');
        if (data_active > 0) {
            $(this).removeClass('discount_active');
            $(this).attr('data-active', 0);
        } else {
            $(this).addClass('discount_active');
            $(this).attr('data-active', 1);
        }

    });
}

//balance

    function balance(exchange_office_id, path_monetary_operations_balance_change) {


        var cashboxAmount = $(".balance .cashbox-amount");
        cashboxAmount.on("keyup", function () {
            operations(this);
        });

        cashboxAmount.on("change", function () {
            operations(this);
        });

        function operations(object) {
            var cashboxId = $(object).data("cashbox-id");
            var cashboxAmount = $(object).val();

            if (cashboxAmount < 0) {
                cashboxAmount = 0;
            }

            $(".balance .save-transactions").data("cashbox-id", cashboxId);

            $(".balance .cashbox-row").removeClass("active-cashbox-balance");
            $(".balance .cashbox-amount").val("");
            $(object).val(cashboxAmount);
            $(".cashbox-number-" + cashboxId).addClass("active-cashbox-balance");
        }


        $(".balance .save-transactions").on("click", function () {

            var cashboxId = $(this).data("cashbox-id");

            var cashboxNumberClass = ".cashbox-number-" + cashboxId;

            var amount = $('.balance').find(cashboxNumberClass + " .cashbox-amount").val();
            var $btnSaveTransactions = $(this);
            var type_transaction = $(".balance #type_transaction").val();
            var notes = $(".balance #notes").val();
            var $boxMessage = $(".balance .box-message");

            var data = {
                cashboxId,
                amount,
                exchange_office_id,
                type_transaction,
                notes,
            };

            $.post(path_monetary_operations_balance_change, data)
                .done(function (response) {
                    console.log(response);

                    if (response.status) {
                        $(".balance .cashbox-amount").val("");
                        $(".balance .cashbox-row").removeClass("active-cashbox-balance");
                        $boxMessage.html('<p class="bg-success">' + response.message + '</p>');
                        $btnSaveTransactions.data("cashbox-id", 0);
                        $(cashboxNumberClass + " .cashbox-allAmount").html(response.resultAmount);
                    } else {
                        $boxMessage.html('<p class="bg-danger">' + response.message + '</p>');
                    }
                })
                .fail(function (response) {
                    console.log(response);
                });

        });

    }