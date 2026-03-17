var $ = jQuery.noConflict();
jQuery(document).ready(function ($) {
/*--------------------------------------------------------------------- mask phone*/
  $("input[type='tel']").mask("+38 (999) 999-99-99"); 
/*--------------------------------------------------------------------- mail sent*/
  document.addEventListener(
    "wpcf7mailsent",
    function (event) {
      var id = event.detail.contactFormId;
      if ( id == 2821 ||  id == 2702 ||  id == 2699 ||  id == 8 ) {
        $('.succes').trigger('click');
        $('.mfp-close').trigger('click'); 
      }
    },
    false
  );
/*--------------------------------------------------------------------- scu copy*/
  $('span.sku').click(function(){
    var textToCopy = $(this).text();
    var tempInput = $('<input>');
    $('body').append(tempInput);
    tempInput.val(textToCopy).select();
    document.execCommand('copy');
    tempInput.remove();
    alert('Артикул скопійовано: ' + textToCopy);
  });

/*--------------------------------------------------------------------- qty added*/
  $('.archive .added-wrapper .qty').on('change', function(e) {
    $(e.target).closest('.added-wrapper').find('.ajax_add_to_cart').get(0).setAttribute('data-quantity', e.target.value);
    console.log(e.target.value, $(e.target).closest('.added-wrapper').find('.ajax_add_to_cart').data('quantity'));
  });

  $('.archive .added-wrapper .ajax_add_to_cart').on('click', function(e) {
    $(e.target).closest('.added-wrapper').find('.quantity').addClass('hidden');
  });

/*--------------------------------------------------------------------- custom filter woocommerce product*/
  var wrapCurrentFilterDOM = document.getElementsByClassName("wcpf-front-element-5262")[0] || document.getElementsByClassName("wcpf-front-element-2642")[0];
  if(wrapCurrentFilterDOM && !wrapCurrentFilterDOM.classList.contains('wcpf-status-disabled')) {
    wrapCurrentFilterDOM.classList.add('wcpf-status-disabled'); //.wcpf-status-disabled
    $('#custom-wc-filter .tab__panel').each(function(i, item) {
      var slug = this.dataset.itemKey;

      if (!$(`.wcpf-checkbox-item[data-item-key="${slug}"]`).get(0)) {
        this.classList.add('hidden');
      }
    });
    $('#custom-wc-filter .tab__title').text($(wrapCurrentFilterDOM).find('.wcpf-field-title .text').text());
    $('#custom-wc-filter').css('display', '');

    $(wrapCurrentFilterDOM).find('.wcpf-checkbox-item.checked').each(function(i, item) {
      var slug = this.dataset.itemKey;

      $(`.tab__panel[data-item-key="${slug}"]`).addClass('active');
    });

    $('.tab__panel').on('click', function(e) {
      var slug = this.dataset.itemKey;

      this.classList.toggle('active');
      $(`.wcpf-checkbox-item[data-item-key="${slug}"] input[type="checkbox"]`).click();

      setTimeout(function() {
        var wrapCurrentFilterDOM = document.getElementsByClassName("wcpf-front-element-5262")[0] || document.getElementsByClassName("wcpf-front-element-2642")[0];
        wrapCurrentFilterDOM.classList.add('wcpf-status-disabled');
      }, 3000);
    });
  }

/*--------------------------------------------------------------------- cart page update event*/
	$(document.body).on('updated_cart_totals', function() {
		if(!(woocommerce_params && woocommerce_params.ajax_url)) return;

		var data = {
      action: 'update_free_delivery',
    };

    $.post(woocommerce_params.ajax_url, data, function(response) {
      $('#discount-shipping-block').html(response);
    });
  });

/*--------------------------------------------------------------------- checkout page update field by type */
	$(document).on('change', '[name="billing_type"]', function(e) {
		var $wrapFields = $('#billing_company_name_field, #billing_edrpou_field, #billing_ipn_field');
		$wrapFields.find('.optional').text('*');
		if( e.target.value == 'Юр-особа') {
			$('form.woocommerce-checkout').removeAttr('novalidate');
			$wrapFields.each(function() {
				$this = $(this);
				$this.removeClass('hidden');
				$this.find('input').attr('required', true);
			});
		}
		if( e.target.value == 'Фіз-особа') {
			$('form.woocommerce-checkout').attr('novalidate', 'novalidate');
			$wrapFields.each(function() {
				$this = $(this);
				$this.addClass('hidden');
				$this.find('input').removeAttr('required');
			});
		}
	});

/*--------------------------------------------------------------------- fixed menu */
	$('.subcategory-block').on('mouseleave', function(e) {
		$(this).removeClass('active');
	});

/************************************** GTAG ANALITICS  */
	var timerSearch = 0;
	$('#woocommerce-product-search-field-1').on('input', function(e) {
		clearTimeout(timerSearch);
		var inputValue = e.target.value;
		timerSearch = setTimeout(function() {
			if(inputValue) {
				gtag('event', 'search_action', {
	        'event_category': 'search',
	        'event_label': inputValue,
	        send_to: "G-RCB4FJ1LTQ", // GA
	      });
			}
		}, 800);
	});
});
