//TODO: layer out complete file
const config = {
    isDev: false,
    devPrinterReceiptUrl: 'http://localhost:51739',
    apiUrl: $("#printerReceiptUrl").val(),
    printerType: $("#printerType").val(),
    printerAccessToken: $("#printerAccessToken").val(),
    fiscalReceiptMethod: "fiscal",
    invoiceMethod: "invoice"
}

const serbiaNoPrintResponse = {
    "receiptNo":-1,
    "receiptAmount": -1
}

function printAll(id, receiptId) {
    if (!receiptId) {
        printReceipt(id, true);
    } else {
        printInvoice(id);
    }
}

function printReceipt(id, invoice = false) {
    loader_on();
    getRequestData(id)
        .done(requestData => {

            if (!requestData) {
                swal('Podaci o fakturi nisu preuzeti.')
                return;
            }

            let paymentType = JSON.parse(requestData).PaymentTypes[0].TypeName;
            
            if(config.printerType == "FP200" 
                && (paymentType == "Virman" || paymentType == "Avans")){               
                printInvoice(id)
                    .done(response =>{
                        if(response !== null){
                            saveFiscalData(id, serbiaNoPrintResponse)
                            .done(()=>{
                                window.location.reload();
                            });
                    }
                })
            }
            else{
                sendFiscalRequest("POST", requestData)
                    .done(response => {
                        if (response.receiptNo) {
                            saveFiscalData(id, response)
                                .done(() => {
                                    if (invoice === true) {
                                        printInvoice(id);
                                    }

                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 200);
                                })
                        }
                    })
            }
        });
}

function printDuplicateReceipt(id) {
    loader_on();
    getRequestData(id)
        .done(requestData => {
            sendFiscalRequest("PUT", requestData);
        })
}

function printCancellationReceipt(id) {
    swal({
        title: 'Storniranje fiskalnog računa',
        text: 'Da li ste sigurni da želite stornirati nardužbu br. ' + id + '?',
        icon: 'warning',
        buttons: true,
        dangerMode: true
    }).then((willDelete) => {
        if (willDelete) {
            loader_on();
            getRequestData(id)
                .done(requestData => {
                    //Serbia does not handle receipt void over printer
                    if(config.printerType == "FP200"){
                        saveFiscalVoidData(id, serbiaNoPrintResponse).done(() => {
                                window.location.reload();
                            });
                    }
                    else{
                    sendFiscalRequest("DELETE", requestData)
                        .done(response => {
                            saveFiscalVoidData(id, response).done(() => {
                                window.location.reload();
                            });
                        }
                    )}
                });
        }
    })
}

function sendFiscalRequest(method, requestData) {
    return $.ajax({
        beforeSend: function (request) {
            setRequestHeaders(request);
        },
        url: getFiscalReceiptUrl(),
        method: method,
        data: requestData,
        success: function (success) {
            checkErrors(success);
            return success;
        },
        error: function (error) {
            checkErrors(error);
            return error;
        }
    });
}

function printInvoice(id) {
    return getDocument(id)
        .done(document => {
            if(document.payment_type === "cash_payment"){
                return;
            }

            let requestData = {
                NumberOfCopies: 2,
                Document: document
            }

            return $.ajax({
                beforeSend: function (request) {
                    setRequestHeaders(request);
                },
                url: getPrintInvoiceUrl(),
                method: 'POST',
                data: JSON.stringify(requestData),
                success: function (success) {
                    return success;
                },
                error: function (error) {
                    //console.log(error);
                    return null;
                }
            });
    });
}

function getRequestData(id) {
    return $.ajax({
        url: '/invoicing/getFiscalRequest/' + id,
        success: function (success) {
            return success.data;
        },
        error: function (error) {
            return error;
        }
    });
}

function saveFiscalData(id, response) {
    loader_on();
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    return $.ajax({
        url: getSaveFiscalDataUrl(id),
        method: "POST",
        dataType: 'JSON',
        data: {_token: CSRF_TOKEN, 'fiscalResponse': JSON.stringify(response)},
        success: function (success) {
            loader_off();
            return success;
        },
        error: function (error) {
            loader_off();
            checkErrors(null, 'Greška prilikom spremanja podataka na narudžbu.');
        }
    });
}

function saveFiscalVoidData(id, response) {
    loader_on();
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    return $.ajax({
        url: getSaveFiscalVoidDataUrl(id),
        method: "POST",
        dataType: 'JSON',
        data: {_token: CSRF_TOKEN, 'fiscalResponse': JSON.stringify(response)},
        success: function (success) {
            loader_off();
            return success;
        },
        error: function (error) {
            console.log(error);
            loader_off();
            checkErrors(null, 'Greška prilikom spremanja podataka na narudžbu.');
        }
    });
}

function checkErrors(response = null, errorText = null) {
    loader_off();
    if (response?.status !== 200 || errorText) {
        let err = '';

        if (response?.errors) {
            err = response?.errors?.join(',');
        }

        if (errorText) {
            err += ', ' + errorText;
        }

        swal({
            title: "Greška",
            text: err !== undefined ? err : response.statusText,
            icon: "warning",
            dangerMode: true,
        })
    }
}

function getDocument(id) {
    return $.ajax({
        url: '/invoicing/getDocument/' + id.toString(),
        method: "GET",
        dataType: 'JSON',
        success: function (success) {
            //console.log(success);
        },
        error: function (error) {
            console.error(error);
        }
    });
}

function getFiscalReceiptUrl() {
    if (config.isDev) {
        return config.devPrinterReceiptUrl + '/' + config.fiscalReceiptMethod;
    } else {
        return config.apiUrl + '/' + config.fiscalReceiptMethod;
    }
}

function getPrintInvoiceUrl() {
    if (config.isDev) {
        return config.devPrinterReceiptUrl + '/' + config.invoiceMethod;
    } else {
        return config.apiUrl + '/' + config.invoiceMethod;
    }
}

function getSaveFiscalDataUrl(id) {
    return '/invoicing/saveFiscalData/' + id;
}

function getSaveFiscalVoidDataUrl(id) {
    return '/invoicing/saveFiscalVoidData/' + id;
}

function setRequestHeaders(request) {
    request.setRequestHeader("Accept", '*/*');
    request.setRequestHeader("Content-Type", 'application/json');
    request.setRequestHeader("PRINTER_TYPE", config.printerType);
    request.setRequestHeader("ACCESS_TOKEN", config.printerAccessToken);
}

$(document).ready(function () {
    $('input[data-form-warehouse-package]').change(function (e) {
        e.preventDefault();

        HttpRequest.post($(this).data('form-url'), {
            package_number: $(this).val(),
            weight: $(this).next('input').val(),
        })
    });

    $('input[name="start"], input[name="end"]').change(function () {
        loader_on();
        $('form.form-dates-range').submit();
    });
});