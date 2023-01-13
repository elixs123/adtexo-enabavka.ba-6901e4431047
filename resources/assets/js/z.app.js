$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

HttpRequest = {
    get: function(url, data, callback) {
        $.get(url, data || {}, function(response) {
            // Notify
            if (response.notification) {
                notify(response.notification);
            }
            // Callback
            $.isFunction(callback)&&callback.call(this, response);
        }).fail(function (response) {
            // Loader: Off
            loader_off();
            // Show error(s)
            AjaxForm.renderErrors(response, $(this));
        });
    },
    post: function(url, data, callback) {
        $.post(url, data || {}, function(response) {
            // Notify
            if (response.notification) {
                notify(response.notification);
            }
            // Callback
            $.isFunction(callback)&&callback.call(this, response);
        }).fail(function (response) {
            // Loader: Off
            loader_off();
            // Show error(s)
            AjaxForm.renderErrors(response, $(this));
        });
    },
    put: function(url, data, callback) {
        // Data
        data = $.extend(true, {}, {
            _method: "PUT"
        }, data || {});
        // Request
        HttpRequest.post(url, data, callback)
    },
    delete: function(url, data, callback) {
        // Data
        data = $.extend(true, {}, {
            _method: "DELETE"
        }, data || {});
        // Request
        HttpRequest.post(url, data, callback)
    }
};



App = {
    init: function() {
        App.loader();
        App.delete();
        App.lang();
        App.copy();
        App.validate();
        App.select2();
        App.tooltip();
        App.magnific();
        App.customFile();
        App.mask();
        App.pickADate();
        App.datePicker();
        App.dateRangePicker();
		ItemFilter.init();
    },
    loader: function() {
        $('[data-loader]').click(function () {
            loader_on();
        })
    },
    delete: function() {
        $("body").on("click", ".delete-link", function(e) {
            // Prevent default
            e.preventDefault();
            // Confirm
            var confirmed = confirm($(this).data('text'));
            if (confirmed) {
                // Parameters
                var url = $(this).data('action');
                var id = $(this).data('id');
                var callback = $(this).data('callback');
                // Hide
                $('#row'+id).hide();
                // Count
                $('[data-row-count]').text(parseInt($('[data-row-count]:eq(0)').text()) - 1);
                // Request
                HttpRequest.delete(url, {}, function (response) {
                    // Remove
                    $('#row' + response.data.uid).remove();
                    // Callback
                    $.isFunction(window[callback])&&window[callback].call(this, response);
                });
            }
        });
    },
    lang: function() {
        $(".dropdown-language .dropdown-item").on("click", function (e) {
            e.preventDefault();
            var $this = $(this);
            if ($(this).hasClass('active')) {
                return false;
            }
            $this.siblings(".active").removeClass("active");
            $this.addClass("active");
            var selectedLang = $this.text();
            var selectedFlag = $this.find(".flag-icon").attr("class");
            $("#dropdown-flag .selected-language").text(selectedLang);
            $("#dropdown-flag .flag-icon")
                .removeClass()
                .addClass(selectedFlag);
            var currentLanguage = $this.data("language");
            $('input[name="lang_id"]').val(currentLanguage);
            loader_on();
            $('form.change-lang-form').submit();
        });
    },
    copy: function() {
        $('body').on('click', '[data-document-copy]', function (e) {
            // Prevent default
            e.preventDefault();
            // Confirm
            var confirmed = confirm($(this).data('text'));
            if (confirmed) {
                // Loader: On
                loader_on();
                // Request
                HttpRequest.post($(this).attr('href'), {}, function (response) {
                    // Redirect
                    document.location = response.redirect;
                });
            }
        });
    },
    validate: function(form, opt) {
        // Parameters
        var $form = $(form || '.validate');
        var options = $.extend(true, {}, {
            rules: {},
            messages: {},
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            }
        }, opt || {});
        $form.each(function(){
            $(this).validate(options);
        });
    },
    select2: function() {
        select2init($("body"));
        select2ajax($("body"));
    },
    tooltip: function() {
        $('[data-toggle="tooltip"], [data-tooltip]').tooltip({
            // 'trigger' : 'hover click'
        });
    },
    magnific: function() {
        $(document).on('click', '[data-toggle="magnific"]', function(e) {
            e.preventDefault();
    
            $.magnificPopup.open({
                items: {
                    src: $(this).attr('href')
                },
                type: 'image',
                closeOnContentClick: true,
                image: {
                    verticalFit: true
                }
            });
        });
    },
    customFile: function() {
        $(document).on('change', 'input[type="file"]', function(e) {
            var files = [];
            $.each(e.target.files, function(key, file) {
                files.push(file.name);
            });
            $(this).next('label.custom-file-label').text(files.length ? files.join(', ') : $(this).next('label.custom-file-label').data('placeholder'));
        });
    },
    mask: function() {
        maskPlugin($("body"));
    },
    pickADate: function() {
        pickADatePlugin($("body"));
    },
    datePicker: function() {
        datePickerPlugin($("body"));
    },
    dateRangePicker: function() {
        dateRangePickerPlugin($("body"));
    }
};

ScopedDocument = {
    init: function() {
        ScopedDocument.open();
        ScopedDocument.close();
        ScopedDocument.complete();
    },
    open: function() {
        $('body').on('click', '[data-scoped-document-open]', function (e) {
            // Prevent default
            e.preventDefault();
            // Loader: On
            loader_on();
            // Request
            HttpRequest.post($(this).attr('href'), {}, function (response) {
                // Redirect
                document.location = response.redirect;
            });
        });
    },
    close: function() {
        $('body').on('click', '[data-scoped-document-close]', function (e) {
            // Prevent default
            e.preventDefault();
            // Loader: On
            loader_on();
            // Request
            HttpRequest.put($(this).attr('href'), {}, function (response) {
                // Reload
                document.location.reload();
            });
        });
    },
    complete: function() {
        $('body').on('click', '[data-scoped-document-complete]', function (e) {
            // Prevent default
            e.preventDefault();
            // Loader: On
            loader_on();
            // Request
            HttpRequest.post($(this).attr('href'), {}, function (response) {
                // Redirect
                document.location = response.redirect;
            });
        });
    }
};

$(document).ready(function() {
    App.init();
    ScopedDocument.init();
	Cart.init();
});

Cart = {
    add: function () {
        $("body").on("change", ".add-to-basket", function () {
			
			      jQuery.ajaxSetup({async:false});

            var this_state = this;
            var id = $(this_state).data('product-id');
            var quantity = $('#product_quantity_' + id).length > 0 ? $('#product_quantity_' + id).val() : 1;
            var CounterMin = parseInt($(this_state).data('min'));
            
            if (quantity < CounterMin) {
              alert('Min. količina ' + CounterMin);
              return;
            }
						
            if (validation.isNotEmpty(quantity) && validation.isNumber(quantity) && quantity > 0) {
                $.post('/cart/add/' + id + '/' + parseInt(quantity)).done(function (data) {
                    Cart.update_cart_header(data);
                });
            }
			      else if(quantity == 0)
            {
              $.post('/cart/remove/' + id).done(function (data) {
                Cart.update_cart_header(data);
              });
            }
        });
    },
    remove_from_cart: function () {
		
        $("body").on("click", ".remove-from-basket", function () {

            var this_state = this;
            var pid = $(this_state).data('product-id');
            
            loader_on();

			$.post('/cart/remove/' + pid).done(function (data) {
				$('#item-' + pid).remove();
				
                Cart.update_cart_header(data);
				Cart.update_cart_item_totals();
				
				if (data.items > 0) {
				    Cart.update_quick_estimate();
                } else {
                    $('div[data-quick-overview]').parent().remove();
                    
                    loader_off();
                }
			});
        });
    },
    update_quick_estimate: function () {
		if($('div[data-quick-overview]').length) {
			$.get('/cart/quick-overview').done(function (data) {
                $('div[data-quick-overview]').html(data);
                
                loader_off();
			});
		} else {
            loader_off();
        }
    },
    destroy_cart: function () {
        $.post('/cart/destroy').done(function (data) {
			Cart.update_cart_header(data);
			Cart.update_cart_item_totals();
			Cart.update_quick_estimate();
        });
    },
    update_cart: function () {
        $(document).on('change keyup', '.product-quantity', function () {
			
			var wrap = $(this);
   
			pid = wrap.data('product-id');
			var qty = wrap.val();
			
            $.post('/cart/update/' + pid + '/' + qty).done(function (data) {
                Cart.update_cart_header(data);
				Cart.update_cart_item_totals();
				Cart.update_quick_estimate();
            });
        });
    },
    update_cart_header: function (data) {
		$('[data-document-total-items]').text(data.items).attr('data-document-total-items', data.items);
		$('[data-document-subtotal]').text(data.subtotal);
    },
    update_cart_item_totals: function () {
		$( ".product-quantity" ).each(function() {
			
			  var quantity = $(this).val();
			  var price = $(this).data('price');
			  var total = quantity * price;
			  var id = $(this).data('product-id');
			  
			  $('#item-total-' + id).text(format_price(total));
		});
    },
    init: function () {
        Cart.add();
        Cart.update_cart();
		Cart.remove_from_cart();
    }
};

AjaxForm = {
    onSubmit: function (form) {
        // Parameters
        var js_this = document.getElementsByClassName((form ? form.substring(1) : '') || 'ajax-form');
        var $form = $(form || '.ajax-form');
        var callback = $form.data('callback');
        var dataType = $form.data('type') || 'json';
        // Loader: On
        loader_on();
        // Reset alerts
        AjaxForm.resetAlerts($form);
        // Ajax
        $.ajax({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: new FormData(js_this[0]), //$form.serialize(),
            dataType : dataType,
            contentType: false,
            processData: false,
            success: function (response) {
                // Loader: Off
                loader_off();
                // Render success response
                AjaxForm.renderSuccess(response, dataType, $form);
				
				if(response.close_modal == true)
				{
					// Modal: Close
					AjaxForm.closeModal();
				}

                // Check
                if (typeof response.notification !== 'undefined') {
                    notify(response.notification);
                }
                $.isFunction(window[callback])&&window[callback].call(this, response);
            },
            error: function (response) {
                // Loader: Off
                loader_off();
                // Reset alerts
                AjaxForm.resetAlerts($form);
                // Render error response
                AjaxForm.renderErrors(response, $form);
            }
        });
    },
    renderSuccess: function(response, dataType, $form) {
        // Reset alerts
        AjaxForm.resetAlerts($form);
        // Active modal
        var $modal = AjaxForm.getActiveModal();
        /*
        if ($modal.attr('id') === 'form-modal2') {
            // @ToDo
            return;
        }*/
        // Check: Data type
        if (dataType === 'json') {
            // Check: Html
            if (response.html) {
                var $el = $(response.html).addClass('ajax-success');
                // Wrapper
                var $wrapper = $('[data-ajax-form-body="' + response.wrapper + '"]');
                // Check: Action
                if ((response.action === 'store') && $wrapper.length) {
                    // Action: Insert
                    $wrapper.prepend($el);
                    $('[data-row-count]').text(parseInt($('[data-row-count]:eq(0)').text()) + 1);
                    $('[data-no-results]').removeClass('show');
                } else if (response.action === 'update') {
                    // Action: Update
                    $('#row' + (response.data.uid || response.data.id)).replaceWith($el);
                }
                setTimeout(function() {
                    $el.removeClass('ajax-success');
                }, 5000);
            }
        } else if (dataType === 'html') {
            AjaxForm.getActiveModal().find("[data-form-modal-content]:eq(0)").html(response);
        }
    },
    renderErrors: function (response, this_data) {
        var message = '';
        if (response.status === 422) {
            var errors = $.map(response.responseJSON.errors, function(value, key) {
                return '<li>' + value[0] + '</li>';
            });
            this_data.find('.alert.alert-danger:eq(0)').show().find('ul:eq(0)').html(errors.join(''));
            message = response.responseJSON.message;
        } else {
            message = response.responseJSON.message || response.statusText;
        }
        notify({
            type: 'error',
            message: message,
        });
    },
    resetAlerts: function ($form) {
        $form.find('.alert.alert-success').hide();
        $form.find('.alert.alert-danger').hide();
        $form.find('.alert.alert-danger ul').empty();
    },
    closeModal: function() {
        AjaxForm.getActiveModal().modal('hide');
    },
    onShowModal: function () {
        $('#form-modal1').on('show.bs.modal', function (event) {
            AjaxForm.loadModalContent($(this), event);
        }).on('hide.bs.modal', function(event) {
            AjaxForm.emptyModalContent($(this), event);
        });
        $('#form-modal2').on('show.bs.modal', function (event) {
            AjaxForm.loadModalContent($(this), event);
        }).on('hide.bs.modal', function(event) {
            AjaxForm.emptyModalContent($(this), event);
        });
    },
    loadModalContent: function($modal, event) {
        // Href
        var href = $(event.relatedTarget).data('href') || $(event.relatedTarget).attr('href');
        // Check
        if (typeof href === 'undefined') {
            return;
        }
        // Tooltip
        $(event.relatedTarget).tooltip('hide');
        // Loader: On
        loader_on();
        // Check
        if ($modal.attr('id') === 'form-modal2') {
            AjaxForm.hideFormModal(1);
        }
        // Load
        $modal.find("[data-form-modal-content]:eq(0)").load(href, function(response, status, xhr) {
            // Check: Error
            if (status === "error") {
                notify({
                    type: 'error',
                    message: xhr.statusText,
                });
            }
            // Loader: Off
            loader_off();
        });
    },
    emptyModalContent: function($modal, event) {
        // Check
        if (event.target.className.substring(0, 5) === 'modal') {
            // Empty
            $modal.find('[data-form-modal-content]:eq(0)').empty();
            // Check
            if ($modal.attr('id') === 'form-modal2') {
                AjaxForm.showFormModal(1);
            }
        }
        // Loader: Off
        loader_off();
    },
    hideFormModal: function(num) {
        $('#form-modal' + num).removeClass('show').hide();
        $('.modal-backdrop:eq(' + (num - 1) + ')').hide();
    },
    showFormModal: function(num) {
        $('#form-modal' + num).addClass('show').show();
        $('.modal-backdrop:eq(' + (num - 1) + ')').show();
    },
    getActiveModal: function() {
        return $('.modal.show:eq(0)');
    },
    init: function (form) {
        AjaxForm.onSubmit(form);
    }
};

function loader_on() {
    $('#loader').show();
}

function loader_off() {
    $('#loader').hide();
}

function notify(data) {
    toastr[data.type || 'info'](data.message, data.title || null, {
        newestOnTop: true,
        positionClass: 'toast-top-right',
        preventDuplicates: true,
        showDuration: 150,
        hideDuration: 150,
        timeOut: 5000,
    });
}

function select2plugin($selects, opt) {
    // Select: Each
    $selects.each(function() {
        // Options
        var defaults = {
            allowClear: ($(this).attr('data-allow-clear') === 'true'),
            width: '100%',
            dropdownParent: $('body'),
        };
        // Plugin options
        var pluginOptions = $(this).data('plugin-options') || {};
        // Options
        var options = $.extend(true, {}, defaults, pluginOptions, opt || {});
        // Init
        $(this).select2(options);
    });
}

function select2init($holder, opt) {
    // Holder: Find
    select2plugin($holder.find('select[data-plugin-selectTwo]'), opt);
}

function select2ajax($holder, opt) {
    // Options
    var options = $.extend(true, {}, {
        allowClear: true,
        // placeholder: '-',
        ajax: {
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.items,
                    pagination: false
                };
            },
            cache: true
        },
        minimumInputLength: 2
    }, opt || {});
    // Holder: Find
    select2plugin($holder.find('select[data-plugin-selectTwoAjax]'), options);
}

function maskPlugin($holder, opt) {
    // Defaults
    var defaults = {};
    // Each
    $holder.find('input[data-plugin-mask]').each(function() {
        // Plugin options
        var pluginOptions = $(this).data('plugin-options') || {};
        // Options
        var options = $.extend(true, {}, defaults, pluginOptions, opt || {});
        // Plugin: Init
        $(this).mask(options.mask, options);
    });
}

function pickADatePlugin($holder, opt) {
    // Defaults
    var defaults = {
        format: 'yyyy-mm-dd'
    };
    // Each
    $holder.find('input.pickadate').each(function() {
        // Plugin options
        var pluginOptions = $(this).data('plugin-options') || {};
        // Options
        var options = $.extend(true, {}, defaults, pluginOptions, opt || {});
        // Plugin: Init
        $(this).pickadate(options);
    });
}

function datePickerPlugin($holder, opt) {
    var defaults = {
        todayHighlight: true,
        weekStart: 1,
        autoclose: true
    };
    // Each
    $holder.find('[data-plugin-datepicker]').each(function() {
        // Plugin options
        var pluginOptions = $(this).data('plugin-options') || {};
        // Options
        var options = $.extend(true, {}, defaults, pluginOptions, opt || {});
        // Plugin: Init
        $(this).datepicker(options);
    });
}

function dateRangePickerPlugin($holder, opt) {
    var defaults = {
        todayHighlight: true,
        weekStart: 1,
        autoclose: true
    };
    // Each
    $holder.find('[data-plugin-daterangepicker]').each(function() {
        // Plugin options
        var pluginOptions = $(this).data('plugin-options') || {};
        // Options
        var options = $.extend(true, {}, defaults, pluginOptions, opt || {});
        // Plugin: Init
        $(this).datepicker(options);
    });
}

function documentReload() {
    // Loader: On
    loader_on();
    // Redirect after document is created
    document.location.reload();
}

function documentRedirect(response) {
    // Loader: On
    loader_on();
    // Redirect after document is created
    document.location = response.redirect;
}


ItemFilter = {
  init: function() {
  	if($('.pjax-container').length) {
	    ItemFilter.brands();
	    ItemFilter.badges();
	    ItemFilter.categories();
	    ItemFilter.refreshProducts();
	}
  },
  brands: function() {
    $('.brands-filter input:checkbox').change(function()
    {
        var selectedItems = new Array();
        $(".brands-filter input:checkbox:checked").each(function() {
            selectedItems.push($(this).val());
        });

        var data = selectedItems.join('.');
        $("input.options.brand").val(data);
        ItemFilter.generateFilterLink();
    });
  },
  badges: function() {
    $('.badges-filter input:checkbox').change(function()
    {
        var selectedItems = new Array();
        $(".badges-filter input:checkbox:checked").each(function() {
            selectedItems.push($(this).val());
        });

        var data = selectedItems.join('.');
        $("input.options.badge").val(data);
        ItemFilter.generateFilterLink();
    });
  },
  categories: function() {
    $('.categories-filter input:checkbox').change(function()
    {
		if($(this).is(":checked"))
		{
			$(this).siblings().find('input:checkbox').prop('checked', true);
		}
		else
		{
			$(this).siblings().find('input:checkbox').prop('checked', false);
		}

        var selectedItems = new Array();
        $(".categories-filter input:checkbox:checked").each(function() {
            selectedItems.push($(this).val());
        });

        var data = selectedItems.join('.');
        $("input.options.category").val(data);
        ItemFilter.generateFilterLink();
    });
  },
  generateFilterLink: function() {
    var link = '?';

    $("input.options").each(function() {
        var name = $(this).attr('name');
        var value = $(this).val();

        if(value.length > 0) {
            link = link + name + '=' + value + '&';
        }

    });

    loader_on();

    $.pjax.defaults.timeout = 5000;
    $.pjax.defaults.scrollTo = false;
    $.pjax({url: link, container: '.pjax-container'});
  },
  refreshProducts: function() {
    $(document).on('pjax:success', function() {
        loader_off();
    });
  }
};

function resetMassStockForm()
{
	$('#form-control-qty').val('');
}

var validation = {
    isEmailAddress:function(str) {
        var pattern =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        return pattern.test(str);  // returns a boolean
    },
    isNotEmpty:function (str) {
        var pattern =/\S+/;
        return pattern.test(str);  // returns a boolean
    },
    isNumber:function(str) {
        var pattern = /^\d+$/;
        return pattern.test(str);  // returns a boolean
    },
    isSame:function(str1,str2){
        return str1 === str2;  // returns a boolean
    }
};

function autoNumericPlugin($input, opt) {
    $input.each(function() {
        // Options
        var defaults = {
            aSep: '.',
            aDec: ',',
            altDec: '.',
            mDec: '2'
        };
        // Plugin options
        var pluginOptions = $(this).data('plugin-options') || {};
        // Options
        var options = $.extend(true, {}, defaults, pluginOptions, opt || {});
        // Init
        $(this).autoNumeric(options);
    });
}

function autoNumericInit($holder, opt) {
    autoNumericPlugin($holder.find('[data-plugin-autonumeric]'), opt);
}

$('#autocomplete').autocomplete({
	serviceUrl: '/shop/autocomplete',
	minChars: 2,
	onSelect: function(suggestion) {
	   window.location = suggestion.data;
	}
});

function format_price(price, price_suffix) {
    if (isNaN(price)) {
        price = 0;
    }
    price = price.toFixed(3);
    price += '';
    var x = price.split('.');
    var x1 = numberWithDots(x[0]);
    // var x1 = x[0];
    var x2 = x.length > 1 ? x[1] : '00';
    return x1 + ',' + x2 + '' + (price_suffix || '');
}

function numberWithDots(x) {
    return parseInt(x).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
