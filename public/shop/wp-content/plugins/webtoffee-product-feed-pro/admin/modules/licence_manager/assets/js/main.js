var wt_pf_licence=(function( $ ) {
	'use strict';
	var wt_pf_licence=
	{
		status_checked:false,
		Set:function()
		{
			this.list_data();
			this.activation();
		},
		check_status:function()
		{
			if($('.wt-pfd-tab-content[data-id="wt-licence"]').is(':visible'))
			{
				wt_pf_licence.do_status_check();
			}
			$('.wt-pfd-tab-head .nav-tab[href="#wt-licence"]').click(function(){
				if(wt_pf_licence.status_checked===false)
				{
					wt_pf_licence.do_status_check();
				}
			});
		},
		do_status_check:function()
		{
			wt_pf_licence.status_checked=true;
			if($('.wt_pf_licence_table tbody .licence_tr').length==0)
			{
				return false;
			}

			$('.wt_pf_licence_table .status_td, .wt_pf_licence_table .action_td').html('...');
			
			$.ajax({
				url:wt_pf_licence_params.ajax_url,
				data:{'action': 'wt_pf_licence_manager_ajax', 'wt_pf_licence_manager_action': 'check_status', '_wpnonce':wt_pf_licence_params.nonce},
				type:'post',
				dataType:"json",
				success:function(data)
				{
					wt_pf_licence.list_data();
				},
				error:function()
				{
					wt_pf_licence.list_data();
				}
			});
		},
		update_status_tab_icon:function()
		{			
			if($('.wt_pf_licence_table .status_td').length>0)
			{
				var status=true;
			}else
			{
				var status=false;
			}

			if(status)
			{
				$('[name="wt_pf_licence_product"] option').each(function(){
					var vl=$(this).val();
					var licence_tr=$('.wt_pf_licence_table .licence_tr[data-product="'+vl+'"]');
					if(licence_tr.length==0)
					{
						status=false;
					}
				});
			}

			if(status)
			{
				$('.wt_pf_licence_table .status_td').each(function(){
					var st=$(this).attr('data-status');
					if(st=='inactive' || st=='')
					{
						status=false;
					}
				});
			}

			var tab_icon_elm=$('.wt-pfd-tab-head .nav-tab[href="#wt-licence"] .dashicons')
			if(status)
			{
				tab_icon_elm.replaceWith(wt_pf_licence_params.tab_icons['active']);
			}else
			{
				tab_icon_elm.replaceWith(wt_pf_licence_params.tab_icons['inactive']);	
			}
		},
		list_data:function()
		{
			$.ajax({
				url:wt_pf_licence_params.ajax_url,
				data:{'action': 'wt_pf_licence_manager_ajax', 'wt_pf_licence_manager_action': 'licence_list', '_wpnonce':wt_pf_licence_params.nonce},
				type:'post',
				dataType:"json",
				success:function(data)
				{
					if(data.status==true)
					{
						$('.wt_pf_licence_list_container').html(data.html);
						wt_pf_licence.update_status_tab_icon();
						wt_pf_licence.deactivation();
						if(wt_pf_licence.status_checked===false)
						{
							wt_pf_licence.check_status();
						}
                                                if(data.license_status == true){
                                                    $('#wt-pf-license-act-window').hide();
                                                    $('#wt-pf-license-list-window').show();
                                                }else{
                                                    $('#wt-pf-license-act-window').show();
                                                    $('#wt-pf-license-list-window').hide();
                                                }
                                                
					}else
					{
						wt_pf_notify_msg.error(wt_pf_licence_params.msgs.unable_to_fetch);
					}
				},
				error:function()
				{
					wt_pf_notify_msg.error(wt_pf_licence_params.msgs.unable_to_fetch);
				}
			});
		},
		deactivation:function()
		{
			$('.wt_pf_licence_deactivate_btn').click(function(){
				if(confirm(wt_pf_licence_params.msgs.sure))
				{
					wt_pf_licence.do_deactivate($(this));
				}
			});
		},
		do_deactivate:function(btn)
		{
			var btn_txt_back=btn.html();
			btn.html(wt_pf_licence_params.msgs.please_wait).prop('disabled', true);
			var product=btn.attr('data-product');
			var action=btn.attr('data-action');
			$.ajax({
				url:wt_pf_licence_params.ajax_url,
				data:{'action': 'wt_pf_licence_manager_ajax', 'wt_pf_licence_manager_action': action, '_wpnonce':wt_pf_licence_params.nonce, 'wt_pf_licence_product':product},
				type:'post',
				dataType:"json",
				success:function(data)
				{
					if(data.status==true)
					{	
						wt_pf_notify_msg.success(data.msg);
						if(btn.parents('tbody').find('tr').length>1)
						{
							btn.parents('tr').remove();
						}else
						{
							wt_pf_licence.list_data();
						}
					}else
					{
						btn.html(btn_txt_back).prop('disabled', false);
						wt_pf_notify_msg.error(wt_pf_licence_params.msgs.error);
					}
				},
				error:function()
				{
					btn.html(btn_txt_back).prop('disabled', false);
					wt_pf_notify_msg.error(wt_pf_licence_params.msgs.error);
				}
			});
		},
		activation:function()
		{
			$('#wt_pf_licence_manager_form').submit(function(e){
				e.preventDefault();
				var this_form=$(this);
				var licence_key=$.trim(this_form.find('[name="wt_pf_licence_key"]').val());

				if(licence_key=="")
				{
					wt_pf_notify_msg.error(wt_pf_licence_params.msgs.key_mandatory);
					return false;
				}
				var btn=this_form.find('.wt_pf_licence_activate_btn');
				var btn_txt_back=btn.html();
				btn.html(wt_pf_licence_params.msgs.please_wait).prop('disabled', true);
				$.ajax({
					url:wt_pf_licence_params.ajax_url,
					data:this_form.serialize(),
					type:'post',
					dataType:"json",
					success:function(data)
					{
						btn.html(btn_txt_back).prop('disabled', false);
						if(data.status==true)
						{
							this_form[0].reset();
							wt_pf_notify_msg.success(data.msg);
							wt_pf_licence.list_data();
						}else
						{
							wt_pf_notify_msg.error(data.msg);
						}
					},
					error:function()
					{
						btn.html(btn_txt_back).prop('disabled', false);
						wt_pf_notify_msg.error(wt_pf_licence_params.msgs.error);
					}
				});
			});
		}
	}
	return wt_pf_licence;
	
})( jQuery );

jQuery(function() {			
	wt_pf_licence.Set();
});