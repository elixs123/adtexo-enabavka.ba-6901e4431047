function replaceStr(str, find, replace) {
    for (var i = 0; i < find.length; i++) {
        str = str.replace(new RegExp(find[i], 'gi'), replace[i]);
    }
    return str;
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

AjaxForm = {
    onSubmit: function (form) {
		var js_this = document.getElementsByClassName((form ? form.substring(1) : '') || 'ajax-form');
        var this_data = $(form || '.ajax-form');
        var callback = this_data.data('callback');
        var dataType = this_data.data('type') || 'json';
        loader_on();
        AjaxForm.showErrorBox();
        $.ajax({
            method: this_data.attr('method'),
            url: this_data.attr('action'),
            data: new FormData(js_this[0]), //this_data.serialize(),
            dataType : dataType,
		    contentType: false,
		    //cache: false,
		    processData: false,
            success: function (response) { 
                loader_off();
                AjaxForm.renderSuccess(response, dataType, this_data);
                AjaxForm.closeModal();
                if (typeof response.notification !== 'undefined') {
                    notify(response.notification);
                }
                $.isFunction(window[callback])&&window[callback].call(this, response);
            },
            error: function (response) {
                loader_off();
                AjaxForm.showErrorBox();
                AjaxForm.renderErrors(response, this_data);
            }
        });
    },
    renderSuccess: function(response, dataType, this_data) {
        this_data.find('.alert.alert-danger').hide();

        if (dataType === 'json') {
            if (response.html) {
                var $el = $(response.html).addClass('ajax-success');
                if ((response.action === 'store') && $('[data-ajax-insert]').length) {
                    $('[data-ajax-insert]').prepend($el);
                    $('[data-row-count]').text(parseInt($('[data-row-count]:eq(0)').text()) + 1);
                } else if (response.action === 'update') {
                    $('#row' + (response.data.uid || response.data.id)).replaceWith($el);
                }
                setTimeout(function() {
                    $el.removeClass('ajax-success');
                }, 5000);
            }
        } else if (dataType === 'html') {
            $(".modal-body").html(response);
        }
    },
    renderErrors: function (response, this_data) {
        $.each(response.responseJSON.errors, function (index, value)
        {
            this_data.prev('.alert.alert-danger').show().find('ul:eq(0)').append('<li>' + value[0] + '</li>');
        });
    },
    showErrorBox: function () {
        
        $('.alert.alert-success').hide();
        $('.alert.alert-danger').hide();
        $('.alert.alert-danger ul').empty();
        
    },
    closeModal: function() {
        $('#form-modal').modal('hide');
    },
    onShowModal: function () {
        
        $('#form-modal').on('show.bs.modal', function (event) {
            
            var button = $(event.relatedTarget);
            var href = button.data('href');
            
            if (typeof href === 'undefined') {
              return;
            }
            
            loader_on();

            $("#form-modal .modal-body").load(href, function() {
                loader_off();
            });
        });
        
       $('#form-modal').on('hide.bs.modal', function (e) {
            //$('body').removeClass('modal-open');
           if (e.target.className.substring(0, 5) === 'modal') {
                $('.modal-backdrop').remove();
                $("#form-modal .modal-body").empty();
           }
            loader_off();
        });         
    },    
    init: function (form) {
        AjaxForm.onSubmit(form);
    }
};

$(document ).ready(function() {
    
    $('a[data-logout]').click(function(e) {
        e.preventDefault();
    
        if (typeof deleteToken === "function") {
            deleteToken($(this).attr('href'));
        } else {
            document.location = $(this).attr('href');
        }
    });
    
    notifications();
    
    var $filters_form = $('.filters-form');
    $('.btn-filters').click(function() {
        $filters_form.toggleClass('open');
        $('input[name="filters"]').val(($filters_form.hasClass('open')) ? 1 : 0);
    });
    
    if($('.validate').length) 
    {        
        $('.validate').each(function(){
            $(this).validate({
                errorPlacement: function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });                
    }

    if($('.datepicker-component').length) {
        $('.datepicker-component').datepicker({
            autoclose: true,
            weekStart: 1
        });
    }
    
    if($('.timepicker').length) 
    {
        $('.timepicker').timepicker();

        $('.timepicker').timepicker().on('changeTime.timepicker', function (e) {
            var hours = e.time.hour, //Returns a string
                    min = e.time.minute,
                    merdian = e.time.merdian;

            if (hours.length == 1) {
                $('.timepicker').timepicker('setTime', '0' + hours + ':' + min + ' ' + merdian);
            }
        });
    }

    highlight();
});

// Loader
function loader_on() {
    $('#loader').show();
}

function loader_off() {
    $('#loader').fadeOut(300);
}

(function ($) {
    $.select2ajax = function (holder, opt) {
        // Parameters
        var $holder = $(holder);
        var options = opt || {};
        // Select2
        function select2() {
            $holder.select2({
                allowClear: true,
				dropdownParent: $('.modal-content'),
                width: '100%',
                ajax: {
                    url: $holder.data('ajax-url'),
                    dataType: 'json',
                    type: $holder.data('ajax-method'),
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
            });
        }
        // Init
        function init() {
            // Select2
            select2();
        }
        init();
    };
    $.fn.select2ajax = function (opt) {
        // Options
        var options = opt || {};
        // Each
        return this.each(function () {
            // New
            return new $.select2ajax(this, options);
        })
    }
})(jQuery);

function select2ajaxInit(opt) {
    $('select[data-init-plugin="select2ajax"]').select2ajax(opt || {});
}

function select2init($holder) {
    $holder.find('select[data-init-plugin="select2"]').each(function() {
        $(this).select2({
            allowClear: ($(this).attr('data-allow-clear') === 'true'),
			dropdownParent: $('.modal-content'),
            width: '100%'
        }).on('select2-opening', function() {
            $.fn.scrollbar && $('.select2-results').scrollbar({
                ignoreMobile: false
            })
        });
    });
}

function notify(data) {
    $('body').pgNotification({
        style: 'simple',
        message: data.message,
        position: 'bottom-left',
        timeout: 5000,
        type: data.type || 'info'
    }).show();
}

$( "body" ).on( "click", ".delete-link", function(e) {
    e.preventDefault();
    var confirmed = confirm($(this).data('text'));
    if (confirmed) {
        var url = $(this).data('action');
        var id = $(this).data('id');
        $('#row'+id).hide();
        $('[data-row-count]').text(parseInt($('[data-row-count]:eq(0)').text()) - 1);
        $.post( url, { _method: "DELETE" } ,function( data ) {
            $('#row'+id).remove();
            notify(data.notification);
        });
    }
});

function switcheryInit($holder) {
    $($holder).find('[data-init-plugin="switchery"]').each(function() {
        var el = $(this);
        new Switchery(el.get(0), {
            color: (el.data("color") != null ?  $.Pages.getColor(el.data("color")) : $.Pages.getColor('success')),
            size : (el.data("size") != null ?  el.data("size") : "default")
        });
    });
}

(function ($) {
    $.notification = function (holder, opt) {
        // Parameters
        var $holder = $(holder);
        var options = opt || {};
        var id = $holder.data('id');
        var status = $holder.data('status');
        // Toggle
        function toggle() {
            var $a = $holder.find('a[data-notification-toggle]');
            $a.tooltip({
                placement: 'left',
                title: function() {
                    return (status === 'read') ? 'Označi kao nepročitano' : 'Označi kao pročitano';
                }
            });
            $a.click(function(e) {
                e.preventDefault();
                status = (status === 'read') ? 'unread' : 'read';
                change();
                $a.tooltip('hide');
                $.post( $a.attr('href'), { _method: "PUT" } ,function( data ) {
                    //
                });
            });
        }
        // Change
        function change() {
            $('[data-notification][data-id="' + id + '"]').toggleClass('unread').attr('data-status', status);
            count();
        }
        // Count
        function count() {
            var num = $('.notification-body[data-notification-wrapper]').find('[data-notification][data-status="unread"]').length;
            $('[data-notification-num]').text(num);
            $('[data-notifications][data-num]').attr('data-num', num);
        }
        // Init
        function init() {
            toggle();
        }
        init();
        // Bind: Read
        $holder.bind('notification:read', function(e) {
            status = 'read';
            $holder.toggleClass('unread').attr('data-status', status);
            count();
        });
        // Bind: Count
        $holder.bind('notification:count', function(e) {
            count();
        });
    };
    $.fn.notification = function (opt) {
        // Options
        var options = opt || {};
        // Each
        return this.each(function () {
            // New
            return new $.notification(this, options);
        })
    }
})(jQuery);

function notifications() {
    $('[data-notification-panel]').find('[data-notification]').notification();
    
    $('a[data-notification-all-read]').click(function(e) {
        e.preventDefault();
        
        $('[data-notification][data-status="unread"]').each(function() {
            $(this).trigger('notification:read');
        });
        
        $(this).tooltip('hide');
        
        $.post( $(this).attr('href'), { _method: "PUT" } ,function( data ) {
            //
        });
    });
}

function add_notification(data) {
    var template = $('.mustache[data-template="notification"]').html();
    
    Mustache.parse(template);
    
    var html = Mustache.render(template, data);
    $('.notification-body.scrollable.scroll-content[data-notification-wrapper]').prepend(html);
    $('[data-id="n' + data.id + '"]').notification().trigger('notification:count');
    
    notify({
        message: data.message,
        type: 'info',
    });
}

function get_hash() {
    return document.URL.substr(document.URL.indexOf('#')+1);
}

function parse_hash(hash, key) {
    var parsed = {};
    var loc1 = hash.split('&');
    for (var i = 0; i < loc1.length; i++) {
        var loc2 = loc1[i].split('=');
        parsed[loc2[0]] = loc2[1];
    }
    if (typeof key === "undefined") {
        return parsed;
    }
    
    return parsed[key];
}

function highlight() {
    var hl = parse_hash(get_hash(), 'hl')
    if (typeof hl === "undefined") {
        return;
    }
    
    var $el = $('#row' + hl);
    
    $el.addClass('ajax-highlighted');
    
    loader_on();
    
    setTimeout(function() {
        $el.find('a[data-target="#form-modal"]').trigger('click');
        // $el.removeClass('ajax-success');
    }, 300);

}

(function () {
  $('.table-responsive').on('shown.bs.dropdown', function (e) {
    var $table = $(this),
        $menu = $(e.target).find('.dropdown-menu'),
        tableOffsetHeight = $table.offset().top + $table.height(),
        menuOffsetHeight = $menu.offset().top + $menu.outerHeight(true);

    if (menuOffsetHeight > tableOffsetHeight)
      $table.css("padding-bottom", menuOffsetHeight - tableOffsetHeight);
  });

  $('.table-responsive').on('hide.bs.dropdown', function () {
    $(this).css("padding-bottom", 0);
  })
})();

Invoice = {
    addItem: function (name) {
        $( document ).on( 'click', '.add-more-btn', function (e) {
 
            e.preventDefault();
            var number_of_items = $('#more-list tbody tr').size();					

			var tr = '<tr class="odd invoice-item">' + $('.init-html').html().replace(RegExp(name + '\\[0\\]', 'gim'), name + "[" + number_of_items + "]") + '</tr>';

            $('#more-list tbody').append(tr);
           
           Invoice.enableFromElements();
		   Invoice.calcTotals();
            
        });  
    },
    removeItem: function () {        
        $( document ).on( 'click', '.remove-more-btn', function (e) {
            e.preventDefault();
            $(this).parent().parent().remove();
			Invoice.calcTotals();
        });        
    },   
    disableFromElements: function () {        
         $("#more-list .init-html :input, #more-list .init-html select").prop("disabled", true); 
		 
    },  
    enableFromElements: function () {        
         $("#more-list tr :input, #more-list tr select").prop("disabled", false);
			//$("#more-list select").select2("destroy");
			//$("#more-list select").select2();		 
    }, 
    calcItemTotal: function () {        

		$(document).on('change', '#more-list tr input', function()
		{
			var tr = $(this).parent().parent();
			
			var qty = numeral(tr.find('.qty').val()).value();
			var tax_rate = numeral(tr.find('.tax_rate').val()).value();
			var discount_rate = numeral(tr.find('.discount_rate').val()).value();
			var price = numeral(tr.find('.price').val()).value();
			
			var total = qty * price;	
			var discount = discount_rate > 0 ? total * discount_rate / 100 : 0;
			var total_discount = total - discount;
			var tax = tax_rate > 0 ? total_discount * tax_rate / 100 : 0;			
			var total_with_tax = total_discount + tax; 	

			tr.find('.total').text(numeral(total).format('0,0.00'));
			tr.find('.total_discount').text(numeral(total_discount).format('0,0.00'));
			tr.find('.total_with_tax').text(numeral(total_with_tax).format('0,0.00'));
			
			Invoice.calcTotals();

		});
	 
    }, 	
    calcTotals: function () {        

		var total_with_tax_sum = 0;
		var total_discount_sum = 0;
		var total_sum = 0;
		var discount_sum = 0;	

		$( ".invoice-item > .total" ).each(function( index ) {		
			total_sum = total_sum + numeral($( this ).text()).value();
			console.log(numeral($( this ).text()).value());
		});

		$( ".invoice-item .total_discount" ).each(function( index ) {		
			total_discount_sum = total_discount_sum + numeral($( this ).text()).value();
		});	
		
		$( ".invoice-item .total_with_tax" ).each(function( index ) {		
			total_with_tax_sum = total_with_tax_sum + numeral($( this ).text()).value();
		});
		
		discount_sum = total_sum - total_discount_sum;
		tax_sum = total_with_tax_sum - total_discount_sum;	
		
		$('.invoice-totals .discount').text(numeral(discount_sum).format('0,0.00'));
		$('.invoice-totals .total_discount').text(numeral(total_discount_sum).format('0,0.00'));
		$('.invoice-totals .tax').text(numeral(tax_sum).format('0,0.00'));
		$('.invoice-totals .total_with_tax').text(numeral(total_with_tax_sum).format('0,0.00'));
		$('.invoice-totals .total').text(numeral(total_sum).format('0,0.00'));
	 
    },       
    init: function (name) {   
		Invoice.calcItemTotal();
        Invoice.disableFromElements();
        Invoice.addItem(name);
        Invoice.removeItem();         
    }
};

Invoice.init('item');
