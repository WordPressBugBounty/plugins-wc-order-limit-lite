jQuery(document).ready(function ($) {
	'use strict';
	/* JS for tabs*/
	if ($('.wcol-select-rule-type').val() != "") {
		wcol_select2($('.wcol-select-rule-type').val());
	} else {
		$('.wcol-rule-table').hide();
	}
	$(document).on('change', '.wcol_rule_checkbox', function (e) {
		var id = $(this).data('id');
		if (this.checked) {
			$(".wcol_min_limit_" + id + "_field").removeClass("wcol-hidden");
			$('.wcol_max_limit_' + id + '_field').removeClass("wcol-hidden");
		} else {
			$(".wcol_min_limit_" + id + "_field").addClass("wcol-hidden");
			$('.wcol_max_limit_' + id + '_field').addClass("wcol-hidden");
		}
	});
	$("form :input").on('change', function () {
		$(this).closest('.wcol-settings').addClass('xs-changed');
		$('.xs-wcol').prop('disabled', false);
		if ($(".wcol-rule-checkbox:checked").length > 0) {
			$(".wcol-delete-selected").prop('disabled', false);
		} else {
			$(".wcol-delete-selected").prop('disabled', true);
		}

	});
	$("select").on('change', function () {
		$(this).closest('.wcol-settings').addClass('xs-changed');
		$('.xs-wcol').prop('disabled', false);
		if ($(".wcol-rule-checkbox:checked").length > 0) {
			$(".wcol-delete-selected").prop('disabled', false);
		} else {
			$(".wcol-delete-selected").prop('disabled', true);
		}
	});
	$('nav.wcol-nav a.nav-tab').on('click', function (e) {
		var url = $(this).attr('href');
		e.preventDefault();
		if ($('.wcol-settings').hasClass('xs-changed')) {
			jQuery('#wcol-modal').modal('toggle');
			$('#wcol-modal-close').on('click', function () {
				jQuery('#wcol-modal').modal('toggle');
				return;
			});

			$('#wcol-modal-sbp').on('click', function () {
				jQuery('#wcol-modal').modal('toggle');
				location.replace(url);
			});

			$('#wcol-modal-pwos').on('click', function () {
				jQuery('#wcol-modal').modal('toggle');
			});

		} else {
			location.replace(url);
		}
	});
	$('.wcol-select-product-type').on('change', function (e) {
		if ($('.wcol-select-items').hasClass('select2-hidden-accessible')) {
			$('.wcol-select-items').select2("destroy").trigger("change").html('');
		}
		if ($(this).val() === 'variable') {
			$('.variation-count').removeClass('wcol-hidden');
		} else {
			$('.variation-count').addClass('wcol-hidden');
		}
		wcol_select2($('.wcol-select-rule-type').val());

	});
	$('.wcol-select-rule-type').on('change', function () {
		if ($('.wcol-select-items').hasClass('select2-hidden-accessible')) {
			$('.wcol-select-items').select2("destroy").trigger("change").html('');
		}
		wcol_select2($(this).val());
	});
	$('.wcol-rule-payment-type').select2({
		width: "95%",
		multiple: true,
		allowClear: true
	});
	function wcol_select2(rule_type) {
		switch (rule_type) {
			case 'products':
				$('.wcol-select-items').select2({
					ajax: {
						url: ajaxurl,
						dataType: 'json',
						delay: 250,
						data: function (params) {
							return {
								q: params.term, // search term
								page: params.page,
								type: $('.wcol-select-product-type').val(),
								action: 'wcol_get_product',
								'wcol_nonce': wcol.nonce
							};
						},
						processResults: function (data, params) {
							params.page = params.page || 1;
							return {
								results: data.items,
								pagination: {
									more: (params.page * 10) < data.total_count
								}
							};
						},
						cache: true
					},
					placeholder: 'Select Products',
					minimumInputLength: 3,
					width: "95%",
					multiple: true,
					allowClear: true
				});
				$('.procat').show();
				$('.cus-edit').hide();
				$('.no-cus-edit').show();
				$('.ven-edit').hide();
				$('.wcol-product-type').removeClass('wcol-hidden');
				$('.wcol-select-parent-rule').removeClass('wcol-hidden');
				if ($('.wcol-select-product-type').val() === 'variable') {
					$('.variation-count').removeClass('wcol-hidden');
				} else {
					$('.variation-count').addClass('wcol-hidden');
				}
				$('.wcol-rule-table').show();
				break;
			case 'categories':
				$('.wcol-select-items').select2({
					ajax: {
						url: ajaxurl,
						dataType: 'json',
						delay: 250,
						data: function (params) {
							return {
								q: params.term, // search term
								page: params.page,
								action: 'wcol_get_categories',
								'wcol_nonce': wcol.nonce
							};
						},
						processResults: function (data, params) {
							params.page = params.page || 1;
							return {
								results: data.items,
								pagination: {
									more: (params.page * 10) < data.total_count
								}
							};
						},
						cache: true
					},
					placeholder: 'Select Categories',
					minimumInputLength: 3,
					width: "95%",
					multiple: true,
					allowClear: true
				});
				$('.procat').show();
				$('.cus-edit').hide();
				$('.no-cus-edit').show();
				$('.ven-edit').hide();
				$('.variation-count').addClass('wcol-hidden');
				$('.wcol-product-type').addClass('wcol-hidden');
				$('.wcol-select-parent-rule').removeClass('wcol-hidden');
				$('.wcol-rule-table').show();
				break;
			case 'vendor':
				$('.wcol-select-items').select2({
					ajax: {
						url: ajaxurl,
						dataType: 'json',
						delay: 250,
						data: function (params) {
							return {
								q: params.term, // search term
								page: params.page,
								action: 'wcol_get_wc_vendors',
								'wcol_nonce': wcol.nonce
							};
						},
						processResults: function (data, params) {
							params.page = params.page || 1;
							return {
								results: data.items,
								pagination: {
									more: (params.page * 10) < data.total_count
								}
							};
						},
						cache: true
					},
					placeholder: 'Select Vendors',
					minimumInputLength: 3,
					width: "95%",
					multiple: true,
					allowClear: true
				});
				$('.procat').hide();
				$('.no-cus-edit').show();
				$('.cus-edit').hide();
				$('.ven-edit').show();
				$('.variation-count').addClass('wcol-hidden');
				$('.wcol-product-type').addClass('wcol-hidden');
				$('.wcol-select-parent-rule').addClass('wcol-hidden');
				$('.wcol-rule-table').show();
				break;
			case 'customer':
				$('.wcol-select-items').removeAttr("multiple");
				$('.wcol-select-items').select2({
					placeholder: 'Select Customer Type',
					data: wcol_script_vars.customer_type,
					width: "95%",
				});
				$('.procat').hide();
				$('.no-cus-edit').hide();
				$('.cus-edit').show();
				$('.wcol-select-cus-type').hide();
				$('.ven-edit').hide();
				$('.variation-count').addClass('wcol-hidden');
				var cus_type = $('.wcol-select-items').val();
				if (cus_type == 'selective-user' || cus_type == 'roles') {
					$('.xswcol-cus-user').show();
				}
				$('.wcol-product-type').addClass('wcol-hidden');
				$('.wcol-select-parent-rule').addClass('wcol-hidden');
				$('.wcol-rule-table').show();
				break;
			default:
				$('.wcol-rule-table').hide();
				$('.wcol-rule-options').addClass('wcol-hidden');
				$('.wcol-show-more-options').removeClass('wcol-hidden');
				$('.wcol-hide-more-options').addClass('wcol-hidden');
				$('.variation-count').addClass('wcol-hidden');
				$('.wcol-product-type').addClass('wcol-hidden');
				$('.wcol-select-parent-rule').addClass('wcol-hidden');
				break;
		}
	}
	$('.wcol-select-users').select2({
		ajax: {
			url: ajaxurl,
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page,
					action: 'wcol_get_users',
					'wcol_nonce': wcol.nonce
				};
			},
			processResults: function (data, params) {
				params.page = params.page || 1;
				return {
					results: data.items,
					pagination: {
						more: (params.page * 10) < data.total_count
					}
				};
			},
			cache: true
		},
		placeholder: 'Select Users',
		minimumInputLength: 3,
		width: "95%",
		multiple: true
	});
	$('.wcol-select-roles').select2({
		placeholder: 'Select Roles',
		data: wcol_script_vars.user_roles,
		width: "95%",
		multiple: true
	});
	$('.wcol-select-guest-users').select2({
		placeholder: 'Select Guest Users',
		width: "95%",
		multiple: true
	});
	$('.wcol-select-memberships').select2({
		placeholder: 'Select Memberships',
		data: wcol_script_vars.memberships,
		width: "95%",
		multiple: true
	});
	$('.wcol-select-products').select2({
		ajax: {
			url: ajaxurl,
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page,
					action: 'wcol_get_product',
					'wcol_nonce': wcol.nonce
				};
			},
			processResults: function (data, params) {
				params.page = params.page || 1;
				return {
					results: data.items,
					pagination: {
						more: (params.page * 10) < data.total_count
					}
				};
			},
			cache: true
		},
		placeholder: 'Select Products',
		minimumInputLength: 3,
		width: "95%",
		multiple: true,
	});
	$('.wcol-select-items').on('change', function () {
		var check = $.inArray("-1", $(this).val());
		if (check != "-1") {
			$(this).val(['-1']).trigger('change.select2');
		}
		if ($('.wcol-select-rule-type').val() == 'customer') {
			if ($(this).val() == 'selective-users') {
				console.log('selective');
				$('.xswcol-cus-user-guest').addClass('wcol-hidden');
				$('.xswcol-cus-user-role').removeClass('wcol-hidden');
				$('.wcol-select-cus-users').show();
				$('.wcol-select-cus-roles').hide();
				$('.wcol-select-cus').hide();
			} else if ($(this).val() == 'roles') {
				$('.xswcol-cus-user-guest').addClass('wcol-hidden');
				$('.xswcol-cus-user-role').removeClass('wcol-hidden');
				$('.wcol-select-cus-users').hide();
				$('.wcol-select-cus-roles').show();
				$('.wcol-select-cus').hide();
			} else if ($(this).val() == 'guest-users') {
				$('.xswcol-cus-user-guest').removeClass('wcol-hidden');
				$('.xswcol-cus-user-role').addClass('wcol-hidden');
				$('.wcol-select-cus-users').hide();
				$('.wcol-select-cus-roles').hide();
				$('.wcol-select-cus').show();
			} else {
				$('.xswcol-cus-user-guest').addClass('wcol-hidden');
				$('.xswcol-cus-user-role').addClass('wcol-hidden');
				$('.wcol-select-cus-users').hide();
				$('.wcol-select-cus-roles').hide();
				$('.wcol-select-cus').show();
			}
		}
	});
	$('.wcol-show-more-options').on('click', function (e) {
		e.preventDefault();
		$('.wcol-rule-options').removeClass('wcol-hidden');
		$('.wcol-hide-more-options').removeClass('wcol-hidden');
		$('.wcol-show-more-options').addClass('wcol-hidden');
		$('.wcol-options-open').removeClass('wcol-hidden');
	});

	$('.wcol-hide-more-options').on('click', function (e) {
		e.preventDefault();
		$('.wcol-show-more-options').removeClass('wcol-hidden');
		$(this).addClass('wcol-hidden');
		$('.wcol-options-open').addClass('wcol-hidden');
		$('.wcol-rule-options').addClass('wcol-hidden');
		$('table.wcol-collapsed').removeClass('wcol-collapsed');
		$('div.wcol-collapsed').removeClass('wcol-collapsed');
	});
	$('.enable-cart-total-max-rule-limit').on('change', function () {
		if ($(this).is(':checked')) {
			$(this).parent().parent().parent().find('.wcol-rule-max-limit').parent().parent().removeClass('wcol-hidden');
		} else {
			$(this).parent().parent().parent().find('.wcol-rule-max-limit').parent().parent().addClass('wcol-hidden');
		}
	});
	$('.wp-list-table , #wcol_tab , .form-table , .form-wrap').on('change', '.enable-max-rule-limit', function () {
		if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
			if ($(this).is(':checked')) {
				$(this).parent().parent().find('.wcol-rule-max-limit').parent().removeClass('wcol-hidden');
			} else {
				$(this).parent().parent().find('.wcol-rule-max-limit').parent().addClass('wcol-hidden');
			}
		} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0 || window.location.href.indexOf('user-edit.php') > 0 || window.location.href.indexOf('profile.php') > 0) {
			if ($(this).is(':checked')) {
				$(this).parent().parent().find('.wcol-rule-max-limit').parent().removeClass('wcol-hidden');
			} else {
				$(this).parent().parent().find('.wcol-rule-max-limit').parent().addClass('wcol-hidden');
			}
		} else {
			if ($(this).is(':checked')) {
				$(this).parent().parent().parent().find('.wcol-rule-max-limit').parent().parent().removeClass('wcol-hidden');
			} else {
				$(this).parent().parent().parent().find('.wcol-rule-max-limit').parent().parent().addClass('wcol-hidden');
			}
		}
	});

	$('.wp-list-table , #wcol_tab , .form-table , .form-wrap').on('change', '.enable-time-limit', function () {
		if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
			if ($(this).is(':checked')) {
				$(this).closest('.wc-metabox').find('.wcol-rule-time-span').closest('.form-field').removeClass('wcol-hidden');

			} else {
				$(this).closest('.wc-metabox').find('.wcol-rule-time-span').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wc-metabox').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wc-metabox').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wc-metabox').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wc-metabox').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wc-metabox').find('.wcol-rule-start-time').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wc-metabox').find('.wcol-rule-end-time').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wc-metabox').find('.wcol-rule-start-time').val('');
				$(this).closest('.wc-metabox').find('.wcol-rule-end-time').val('');
				$(this).closest('.wc-metabox').find('.wcol-rule-time-span').find('option[value="daily"]').attr('selected', true);
			}
		} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0 || window.location.href.indexOf('user-edit.php') > 0 || window.location.href.indexOf('profile.php') > 0) {
			if ($(this).is(':checked')) {
				$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-time-span').closest('.form-field').removeClass('wcol-hidden');
			} else {
				$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-time-span').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wcol_single_cat_rule').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wcol_single_cat_rule').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wcol_single_cat_rule').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-start-time').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-end-time').closest('.form-field').addClass('wcol-hidden');
				$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-start-time').val('');
				$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-end-time').val('');
				$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-time-span').find('option[value="daily"]').attr('selected', true);
			}
		} else {
			if ($(this).is(':checked')) {
				$(this).closest('.wcol-rule-options').find('.wcol-rule-time-span').closest('tr').removeClass('wcol-hidden');
			} else {
				$(this).closest('.wcol-rule-options').find('.wcol-rule-time-span').closest('tr').addClass('wcol-hidden');
				$(this).closest('.wcol-rule-options').find('.wcol-rule-time-span').find('option[value="daily"]').attr('selected', true);
				$(this).closest('.wcol-rule-options').find('.wcol-rule-start-time').val('').closest('tr').addClass('wcol-hidden');
				$(this).closest('.wcol-rule-options').find('.wcol-rule-end-time').val('').closest('tr').addClass('wcol-hidden');
			}
		}
	});


	$('.wp-list-table , #wcol_tab , .form-table , .form-wrap').on('change', '.wcol-rule-time-span', function () {
		switch ($(this).val()) {
			case 'daily':
				if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
					$(this).closest('.wc-metabox').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-start-time').val('').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-end-time').val('').closest('.form-field').addClass('wcol-hidden');
				} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0 || window.location.href.indexOf('user-edit.php') > 0 || window.location.href.indexOf('profile.php') > 0) {
					$(this).closest('.wcol_single_cat_rule').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-start-time').val('').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-end-time').val('').closest('.form-field').addClass('wcol-hidden');
				} else {
					$(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').val('').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').val('').closest('tr').addClass('wcol-hidden');
				}
				break;
			case 'weekly':
				if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
					$(this).closest('.wc-metabox').find('.wcol-weekly-start-day').closest('.form-field').removeClass('wcol-hidden');

					$(this).closest('.wc-metabox').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-start-time').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-end-time').closest('.form-field').addClass('wcol-hidden');
				} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0 || window.location.href.indexOf('user-edit.php') > 0 || window.location.href.indexOf('profile.php') > 0) {
					$(this).closest('.wcol_single_cat_rule').find('.wcol-weekly-start-day').closest('.form-field').removeClass('wcol-hidden');

					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-start-time').val('').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-end-time').val('').closest('.form-field').addClass('wcol-hidden');
				} else {
					$(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').removeClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').val('').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').val('').closest('tr').addClass('wcol-hidden');
				}
				break;
			case 'monthly':
				if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
					$(this).closest('.wc-metabox').find('.wcol-monthly-start-date').closest('.form-field').removeClass('wcol-hidden');

					$(this).closest('.wc-metabox').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-start-time').val('').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-end-time').val('').closest('.form-field').addClass('wcol-hidden');
				} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0 || window.location.href.indexOf('user-edit.php') > 0 || window.location.href.indexOf('profile.php') > 0) {
					$(this).closest('.wcol_single_cat_rule').find('.wcol-monthly-start-date').closest('.form-field').removeClass('wcol-hidden');

					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-start-time').val('').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-end-time').val('').closest('.form-field').addClass('wcol-hidden');
				} else {
					$(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').removeClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').removeClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').val('').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').val('').closest('tr').addClass('wcol-hidden');
				}
				break;
			case 'yearly':
				if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
					$(this).closest('.wc-metabox').find('.wcol-yearly-start-day').closest('.form-field').removeClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-start-time').val('').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-end-time').val('').closest('.form-field').addClass('wcol-hidden');
				} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0 || window.location.href.indexOf('user-edit.php') > 0 || window.location.href.indexOf('profile.php') > 0) {
					$(this).closest('.wcol_single_cat_rule').find('.wcol-yearly-start-day').closest('.form-field').removeClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-start-time').val('').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-end-time').val('').closest('.form-field').addClass('wcol-hidden');
				} else {
					$(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').removeClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').val('').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').val('').closest('tr').addClass('wcol-hidden');
				}
				break;
			case 'days':
				if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
					$(this).closest('.wc-metabox').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-start-time').val('').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-end-time').val('').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-days').closest('.form-field').removeClass('wcol-hidden');
				} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0 || window.location.href.indexOf('user-edit.php') > 0 || window.location.href.indexOf('profile.php') > 0) {
					$(this).closest('.wcol_single_cat_rule').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-days').closest('.form-field').removeClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-start-time').val('').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-end-time').val('').closest('.form-field').addClass('wcol-hidden');
				} else {
					$(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').removeClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').val('').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').val('').closest('tr').addClass('wcol-hidden');
				}
				break;
			default:
				if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
					$(this).closest('.wc-metabox').find('.wcol-rule-start-time').closest('.form-field').removeClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-end-time').closest('.form-field').removeClass('wcol-hidden');

					$(this).closest('.wc-metabox').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wc-metabox').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
				} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0 || window.location.href.indexOf('user-edit.php') > 0 || window.location.href.indexOf('profile.php') > 0) {
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-start-time').closest('.form-field').removeClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-end-time').closest('.form-field').removeClass('wcol-hidden');

					$(this).closest('.wcol_single_cat_rule').find('.wcol-weekly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-monthly-start-date').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-yearly-start-day').closest('.form-field').addClass('wcol-hidden');
					$(this).closest('.wcol_single_cat_rule').find('.wcol-rule-days').closest('.form-field').addClass('wcol-hidden');
				} else {
					$(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').closest('tr').removeClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').closest('tr').removeClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').addClass('wcol-hidden');
					$(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').addClass('wcol-hidden');
				}
				break;
		}
	});

	$('.wp-list-table , #wcol_tab , .form-table').on('change', '.wcol-vendor-time', function () {
		if ($(this).is(':checked')) {
			$(this).parent().parent().parent().find('.wcol-rule-start-time').parent().parent().removeClass('wcol-hidden');
			$(this).parent().parent().parent().find('.wcol-rule-end-time').parent().parent().removeClass('wcol-hidden');
		} else {
			$(this).parent().parent().parent().find('.wcol-rule-start-time').parent().parent().addClass('wcol-hidden');
			$(this).parent().parent().parent().find('.wcol-rule-end-time').parent().parent().addClass('wcol-hidden');
		}
	});
	$('.wp-list-table , #wcol_tab , .form-table , .form-wrap').on('change', '.enable-users-limit', function () {
		if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
			$(this).parent().parent().find('.wcol-rule-user-type').parent().removeClass('wcol-hidden');
			var user_type = $(this).parent().parent().find('.wcol-rule-user-type').val();
			if ($(this).is(':checked')) {
				$(this).parent().parent().find('.wcol-rule-user-type').parent().removeClass('wcol-hidden');
				var user_type = $(this).parent().parent().find('.wcol-rule-user-type').val();
				if (user_type == 'specific-users') {
					$(this).parent().parent().find('.wcol-rule-users').parent().removeClass('wcol-hidden');
				} else if (user_type == 'specific-roles') {
					$(this).parent().parent().find('.wcol-rule-roles').parent().removeClass('wcol-hidden');
				}
			} else {
				$(this).parent().parent().find('.wcol-rule-user-type').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().addClass('wcol-hidden');
			}
		} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0) {
			$(this).parent().parent().find('.wcol-rule-user-type').parent().removeClass('wcol-hidden');
			var user_type = $(this).parent().parent().find('.wcol-rule-user-type').val();
			if ($(this).is(':checked')) {
				$(this).parent().parent().find('.wcol-rule-user-type').parent().removeClass('wcol-hidden');
				var user_type = $(this).parent().parent().find('.wcol-rule-user-type').val();
				if (user_type == 'specific-users') {
					$(this).parent().parent().find('.wcol-rule-users').parent().removeClass('wcol-hidden');
				} else if (user_type == 'specific-roles') {
					$(this).parent().parent().find('.wcol-rule-roles').parent().removeClass('wcol-hidden');
				}
			} else {
				$(this).parent().parent().find('.wcol-rule-user-type').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().addClass('wcol-hidden');
			}
		} else {
			if ($(this).is(':checked')) {
				$(this).parent().parent().parent().find('.wcol-rule-user-type').parent().parent().removeClass('wcol-hidden');
				var user_type = $(this).parent().parent().parent().find('.wcol-rule-user-type').val();
				if (user_type == 'specific-users') {
					$(this).parent().parent().parent().find('.wcol-rule-users').parent().parent().removeClass('wcol-hidden');
				} else if (user_type == 'specific-roles') {
					$(this).parent().parent().parent().find('.wcol-rule-roles').parent().parent().removeClass('wcol-hidden');
				}
			} else {
				$(this).parent().parent().parent().find('.wcol-rule-user-type').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-users').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-roles').parent().parent().addClass('wcol-hidden');
			}
		}
	});
	$('.wp-list-table , #wcol_tab , .form-table , .form-wrap').on('change', '.enable-payment-limit', function () {
		if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
			if ($(this).is(':checked')) {
				$(this).parent().parent().find('.wcol-payment-method').removeClass('wcol-hidden');
			} else {
				$(this).parent().parent().find('.wcol-payment-method').addClass('wcol-hidden');
			}

		} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0) {
			if ($(this).is(':checked')) {
				$(this).parent().parent().find('.wcol-payment-method').removeClass('wcol-hidden');
			} else {
				$(this).parent().parent().find('.wcol-payment-method').addClass('wcol-hidden');
			}
		} else {
			if ($(this).is(':checked')) {
				$('.wcol-payment-method').removeClass('wcol-hidden');
			} else {
				$('.wcol-payment-method').addClass('wcol-hidden');
			}
		}

	});
	$('.wp-list-table , #wcol_tab , .form-table , .form-wrap').on('change', '.wcol-rule-user-type', function () {

		if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
			if ($(this).val() == 'specific-users') {
				$(this).parent().parent().find('.wcol-rule-guest-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-users').parent().removeClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-memberships').parent().addClass('wcol-hidden');
			} else if ($(this).val() == 'specific-roles') {
				$(this).parent().parent().find('.wcol-rule-guest-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().removeClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-memberships').parent().addClass('wcol-hidden');
			} else if ($(this).val() == 'membership') {
				$(this).parent().parent().find('.wcol-rule-guest-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-memberships').parent().removeClass('wcol-hidden');
			} else if ($(this).val() == 'guest-users') {
				$(this).parent().parent().find('.wcol-rule-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-memberships').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-guest-users').parent().removeClass('wcol-hidden');
			} else {
				$(this).parent().parent().find('.wcol-rule-guest-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-memberships').parent().addClass('wcol-hidden');
			}
		} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0) {
			if ($(this).val() == 'specific-users') {
				$(this).parent().parent().find('.wcol-rule-guest-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-users').parent().removeClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-memberships').parent().addClass('wcol-hidden');
			} else if ($(this).val() == 'specific-roles') {
				$(this).parent().parent().find('.wcol-rule-guest-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().removeClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-memberships').parent().addClass('wcol-hidden');
			} else if ($(this).val() == 'membership') {
				$(this).parent().parent().find('.wcol-rule-guest-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-memberships').parent().removeClass('wcol-hidden');
			} else if ($(this).val() == 'guest-users') {
				$(this).parent().parent().find('.wcol-rule-guest-users').parent().removeClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-memberships').parent().addClass('wcol-hidden');
			} else {
				$(this).parent().parent().find('.wcol-rule-guest-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-users').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-roles').parent().addClass('wcol-hidden');
				$(this).parent().parent().find('.wcol-rule-memberships').parent().addClass('wcol-hidden');
			}
		} else {
			if ($(this).val() == 'specific-users') {
				$(this).parent().parent().parent().find('.wcol-rule-guest-users').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-users').parent().parent().removeClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-roles').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-memberships').parent().parent().addClass('wcol-hidden');
			} else if ($(this).val() == 'specific-roles') {
				$(this).parent().parent().parent().find('.wcol-rule-guest-users').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-roles').parent().parent().removeClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-users').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-memberships').parent().parent().addClass('wcol-hidden');
			} else if ($(this).val() == 'membership') {
				$(this).parent().parent().parent().find('.wcol-rule-guest-users').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-roles').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-users').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-memberships').parent().parent().removeClass('wcol-hidden');
			} else if ($(this).val() == 'guest-users') {
				$(this).parent().parent().parent().find('.wcol-rule-users').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-roles').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-memberships').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-guest-users').parent().parent().removeClass('wcol-hidden');
			} else {
				$(this).parent().parent().parent().find('.wcol-rule-guest-users').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-users').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-roles').parent().parent().addClass('wcol-hidden');
				$(this).parent().parent().parent().find('.wcol-rule-memberships').parent().parent().addClass('wcol-hidden');
			}
		}
	});

	$(".wcol-rule-start-time").flatpickr({
		enableTime: false
	});
	$(".wcol-rule-end-time").flatpickr({
		enableTime: false
	});
	$(".wcol-rule-time-span-custom-from").flatpickr({
		enableTime: false
	});
	$(".wcol-rule-time-span-custom-to").flatpickr({
		enableTime: false
	});

	$('.wp-list-table , #wcol_tab , .form-table , .form-wrap').on('change', '.wcol-loop-checkbox', function () {
		if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
			if ($(this).is(':checked')) {
				$(this).closest('p.form-field').find('.wcol-loop-checkbox-hidden').val('on');
			} else {
				$(this).closest('p.form-field').find('.wcol-loop-checkbox-hidden').val('');
			}
		} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0 || window.location.href.indexOf('user-edit.php') > 0 || window.location.href.indexOf('profile.php') > 0) {
			if ($(this).is(':checked')) {
				$(this).closest('p.form-field').find('.wcol-loop-checkbox-hidden').val('on');
			} else {
				$(this).closest('p.form-field').find('.wcol-loop-checkbox-hidden').val('');
			}
		} else {
			if ($(this).is(':checked')) {
				$(this).parent().find('.wcol-loop-checkbox-hidden').val('on');
			} else {
				$(this).parent().find('.wcol-loop-checkbox-hidden').val('');
			}
		}
	});
	$('.wcol_edit_rule').on('change', function () {
		if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
			if ($(this).is(':checked') && $(this).closest('.wc-metabox-content').find('.wcol_accomulative').val() != '') {
				$(this).closest('.wc-metabox').removeClass('wcol_accomulative_rule');
				$(this).closest('.wc-metabox-content').find('.wcol_accomulative').val('');
			} else {
				$(this).closest('.wc-metabox').addClass('wcol_accomulative_rule');
				$(this).closest('.wc-metabox-content').find('.wcol_accomulative').val('on');
			}
		} else {
			if ($(this).is(':checked') && $(this).closest('.wcol_single_cat_rule').find('.wcol_accomulative').val() != '') {
				$(this).closest('.wcol_single_cat_rule').removeClass('wcol_accomulative_rule');
				$(this).closest('.wcol_single_cat_rule').find('.wcol_accomulative').val('');
			} else {
				$(this).closest('.wcol_single_cat_rule').addClass('wcol_accomulative_rule');
				$(this).closest('.wcol_single_cat_rule').find('.wcol_accomulative').val('on');
			}
		}

	});
	$('#wcol_add_new_rule').on('click', function (e) {
		e.preventDefault();
		$(this).closest('form').addClass('xs-changed');
		var rule_type = $(this).data('id');
		if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
			$('#wcol_tab  .wc-metaboxes .wc-metabox').removeClass('open').addClass('closed').find('.wc-metabox-content').hide();
			$('.wcol-new').removeClass('wcol-new');
			var spinner = $(this).closest('#wcol_tab').find('.wcol_spinner');
			spinner.closest('form').css('pointer-events', 'none');
			spinner.addClass('wcol_is_active');
			var wcol_rid = $(".xswcol-spid").val();
			wcol_rid++;
			$.ajax({
				url: wcol_script_vars.ajax_url,
				type: 'post',
				data: { 'action': 'wcol_load_new_row', 'wcol_spid': wcol_rid, 'rule_type': rule_type, 'wcol_nonce': wcol.nonce },
				success: function (res) {
					$(".xswcol-spid").val(wcol_rid);
					$('#wcol_tab .wc-metaboxes').append(res);
					$('.wcol-new .wcol-rule-payment-type').select2({
						width: "95%",
						multiple: true
					});
					$('.wcol-new .wcol-select-users').select2({
						ajax: {
							url: ajaxurl,
							dataType: 'json',
							delay: 250,
							data: function (params) {
								return {
									q: params.term, // search term
									page: params.page,
									action: 'wcol_get_users',
									'wcol_nonce': wcol.nonce
								};
							},
							processResults: function (data, params) {
								params.page = params.page || 1;
								return {
									results: data.items,
									pagination: {
										more: (params.page * 10) < data.total_count
									}
								};
							},
							cache: true
						},
						placeholder: 'Select Users',
						minimumInputLength: 3,
						width: "95%",
						multiple: true
					});
					$('.wcol-new .wcol-select-roles').select2({
						placeholder: 'Select Roles',
						data: wcol_script_vars.user_roles,
						width: "95%",
						multiple: true
					});
					$('.wcol-new .wcol-select-guest-users').select2({
						placeholder: 'Select Guest Users',
						width: "95%",
						multiple: true
					});
					$(".wcol-new .wcol-rule-start-time").flatpickr({
						enableTime: true
					});
					$(".wcol-new .wcol-rule-end-time").flatpickr({
						enableTime: true
					});

				},
			}).always(function (jqXHR, textStatus, errorThrown) {
				if (textStatus !== 'success') {
					alert(errorThrown);
				}
				spinner.closest('form').css('pointer-events', 'auto');
				spinner.removeClass('wcol_is_active');
			});
		} else {
			$('.wcol_single_cat_rules .wcol_single_cat_rule').removeClass('wcol_single_cat_rule_open').find('.wcol_cat_panel').slideUp();
			$('.wcol-new').removeClass('wcol-new');
			var spinner = $(this).parent().find('.wcol_spinner');
			spinner.closest('form').css('pointer-events', 'none');
			spinner.addClass('wcol_is_active');
			var wcol_rid = $(".xswcol-spcid").val();
			wcol_rid++;
			$.ajax({
				url: wcol_script_vars.ajax_url,
				type: 'post',
				data: { 'action': 'wcol_load_new_row', 'wcol_spcid': wcol_rid, 'rule_type': rule_type, 'wcol_nonce': wcol.nonce },
				success: function (res) {
					$(".xswcol-spcid").val(wcol_rid);
					$('.wcol_single_cat_rules').append(res);
					$('.wcol-new .wcol-rule-payment-type').select2({
						width: "95%",
						multiple: true
					});
					$('.wcol-new .wcol-select-users').select2({
						ajax: {
							url: ajaxurl,
							dataType: 'json',
							delay: 250,
							data: function (params) {
								return {
									q: params.term, // search term
									page: params.page,
									action: 'wcol_get_users',
									'wcol_nonce': wcol.nonce
								};
							},
							processResults: function (data, params) {
								params.page = params.page || 1;
								return {
									results: data.items,
									pagination: {
										more: (params.page * 10) < data.total_count
									}
								};
							},
							cache: true
						},
						placeholder: 'Select Users',
						minimumInputLength: 3,
						width: "95%",
						multiple: true
					});
					$('.wcol-new .wcol-select-roles').select2({
						placeholder: 'Select Roles',
						data: wcol_script_vars.user_roles,
						width: "95%",
						multiple: true
					});
					$('.wcol-new .wcol-select-guest-users').select2({
						placeholder: 'Select Guest Users',
						width: "95%",
						multiple: true
					});
					$(".wcol-new .wcol-rule-start-time").flatpickr({
						enableTime: true
					});
					$(".wcol-new .wcol-rule-end-time").flatpickr({
						enableTime: true
					});
				},
			}).always(function (jqXHR, textStatus, errorThrown) {
				if (textStatus !== 'success') {
					alert(errorThrown);
				}
				spinner.closest('form').css('pointer-events', 'auto');
				spinner.removeClass('wcol_is_active');
			});
		}
	});

	$('.wcol_cat_accordion').on('click', function () {
		if ($(this).closest('.wcol_single_cat_rule').hasClass('wcol_single_cat_rule_open')) {
			$(this).closest('.wcol_single_cat_rule').removeClass('wcol_single_cat_rule_open');
			$(this).closest('.wcol_single_cat_rule').find('.wcol_cat_panel').slideUp();
		} else {
			$(this).closest('.wcol_single_cat_rule').addClass('wcol_single_cat_rule_open');
			$(this).closest('.wcol_single_cat_rule').find('.wcol_cat_panel').slideDown();
		}
	});

	$('.xswcol-rules').on('click', '.wcol-delete', function (e) {
		e.preventDefault();
		var rule_id = $(this).data('id');
		var delete_row = $(this).parent().parent();
		if (window.confirm("Are you sure want to delete rule ?")) {
			$.ajax({
				url: ajaxurl,
				type: 'post',
				data: { 'action': 'xswcol_delete_rule', '_wcol_save_rules_nonce': $('#_wcol_save_rules_nonce').val(), 'rule_id': rule_id },
				success: function (res) {
					if (res.status) {
						delete_row.remove();
					}
				}
			});
		}
	});

	$('.wcol-undo').on('click', function () {
		$(this).parent().parent().removeClass('wcol-deleted-rule').find('.wcol_deleted_rule_key').remove();
		$(this).removeClass('wcol-undo').addClass('wcol-delete').text('Delete');
	});

	$('.wp-list-table , #wcol_tab , .form-table , .form-wrap').on('change', '.wcol-disable-rule-limit', function () {

		if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
			if ($(this).is(':checked')) {
				$(this).closest('.wc-metabox-content').find('.across-all-orders-limit').addClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.enable-max-rule-limit').addClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.wcol-rule-max-limit').addClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.enable-time-limit').addClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.wcol-rule-time-span').addClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.enable-users-limit').addClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.wcol-rule-user-type').addClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.wcol-rule-min-limit').addClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.wcol-applied-on').addClass('wcol-disabled');
			} else {
				$(this).closest('.wc-metabox-content').find('.across-all-orders-limit').removeClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.enable-max-rule-limit').removeClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.wcol-rule-max-limit').removeClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.enable-time-limit').removeClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.wcol-rule-time-span').removeClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.enable-users-limit').removeClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.wcol-rule-user-type').removeClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.wcol-rule-min-limit').removeClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.wcol-applied-on').removeClass('wcol-disabled');
			}
		} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0) {
			if ($(this).is(':checked')) {
				$(this).closest('.wcol_cat_panel').find('.across-all-orders-limit').addClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.enable-max-rule-limit').addClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.wcol-rule-max-limit').addClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.enable-time-limit').addClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.wcol-rule-time-span').addClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.enable-users-limit').addClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.wcol-rule-user-type').addClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.wcol-rule-min-limit').addClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.wcol-applied-on').addClass('wcol-disabled');

			} else {
				$(this).closest('.wcol_cat_panel').find('.across-all-orders-limit').removeClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.enable-max-rule-limit').removeClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.wcol-rule-max-limit').removeClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.enable-time-limit').removeClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.wcol-rule-time-span').removeClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.enable-users-limit').removeClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.wcol-rule-user-type').removeClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.wcol-rule-min-limit').removeClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.wcol-applied-on').removeClass('wcol-disabled');
			}
		} else {
			if ($(this).is(':checked')) {
				$('.wcol-select-items').addClass('wcol-disabled');
				$('.wcol-rule-min-limit').addClass('wcol-disabled');
				$('.wcol-select-applied-on').addClass('wcol-disabled');
				$('.wcol-accomulative').addClass('wcol-disabled');
				$('.wcol-select-users').addClass('wcol-disabled');
				$('.wcol-select-roles').addClass('wcol-disabled');
				$('.wcol-customer-rule-limit').addClass('wcol-disabled');
				$('.across-all-orders-limit').addClass('wcol-disabled');
				$('.enable-max-rule-limit').addClass('wcol-disabled');
				$('.wcol-rule-max-limit').addClass('wcol-disabled');
				$('.enable-time-limit').addClass('wcol-disabled');
				$('.wcol-rule-time-span').addClass('wcol-disabled');
				$('.enable-users-limit').addClass('wcol-disabled');
				$('.wcol-rule-user-type').addClass('wcol-disabled');
				jQuery(this).closest('.wcol-exclude-row').closest('tr').find('.wcol-exclude').addClass('wcol-disabled');
				jQuery(this).closest('.wcol-exclude-row').closest('tr').find('.wcol-select-products').addClass('wcol-disabled');
				jQuery(this).closest('.wcol-exclude-row').closest('tr').find('.wcol-exclude-limit').addClass('wcol-disabled');
			} else {
				$('.wcol-select-items').removeClass('wcol-disabled');
				$('.wcol-rule-min-limit').removeClass('wcol-disabled');
				$('.wcol-select-applied-on').removeClass('wcol-disabled');
				$('.wcol-accomulative').removeClass('wcol-disabled');
				$('.wcol-select-users').removeClass('wcol-disabled');
				$('.wcol-select-roles').removeClass('wcol-disabled');
				$('.wcol-customer-rule-limit').removeClass('wcol-disabled');

				$('.across-all-orders-limit').removeClass('wcol-disabled');
				$('.enable-max-rule-limit').removeClass('wcol-disabled');
				$('.wcol-rule-max-limit').removeClass('wcol-disabled');
				$('.enable-time-limit').removeClass('wcol-disabled');
				$('.wcol-rule-time-span').removeClass('wcol-disabled');
				$('.enable-users-limit').removeClass('wcol-disabled');
				$('.wcol-rule-user-type').removeClass('wcol-disabled');
				jQuery(this).closest('.wcol-exclude-row').closest('tr').find('.wcol-exclude').removeClass('wcol-disabled');
				jQuery(this).closest('.wcol-exclude-row').closest('tr').find('.wcol-select-products').removeClass('wcol-disabled');
				jQuery(this).closest('.wcol-exclude-row').closest('tr').find('.wcol-exclude-limit').removeClass('wcol-disabled');
			}
		}
	});
	$('.wp-list-table , #wcol_tab , .form-table , .form-wrap').on('change', '.across-all-orders-limit', function () {
		if (window.location.href.indexOf('post.php') > 0 || window.location.href.indexOf('post-new') > 0) {
			if ($(this).val() != '1') {
				$(this).closest('.wc-metabox-content').find('.enable-max-rule-limit').prop('checked', true);
				$(this).closest('.wc-metabox-content').find('.enable-max-rule-limit').addClass('wcol-disabled').closest('.options_group').removeClass('wcol-hidden');
				$(this).closest('.wc-metabox-content').find('.enable-max-rule-limit-hidden').val('on');

				$(this).closest('.wc-metabox-content').find('.enable-time-limit').prop('checked', true);
				$(this).closest('.wc-metabox-content').find('.enable-time-limit').addClass('wcol-disabled').closest('.options_group').removeClass('wcol-hidden');;
				$(this).closest('.wc-metabox-content').find('.enable-time-limit-hidden').val('on');

				$(this).closest('.wc-metabox-content').find('.wcol-rule-max-limit').closest('.form-field').removeClass('wcol-hidden');
				$(this).closest('.wc-metabox-content').find('.wcol-rule-time-span').closest('.form-field').removeClass('wcol-hidden');

			} else {
				$(this).closest('.wc-metabox-content').find('.enable-max-rule-limit').removeClass('wcol-disabled');
				$(this).closest('.wc-metabox-content').find('.enable-time-limit').removeClass('wcol-disabled');
			}
		} else if (window.location.href.indexOf('term.php') > 0 || window.location.href.indexOf('edit-tags.php') > 0) {
			if ($(this).val() != '1') {
				$(this).closest('.wcol_cat_panel').find('.enable-max-rule-limit').prop('checked', true);
				$(this).closest('.wcol_cat_panel').find('.enable-max-rule-limit').addClass('wcol-disabled').closest('.options_group').removeClass('wcol-hidden');
				$(this).closest('.wcol_cat_panel').find('.enable-max-rule-limit-hidden').val('on');

				$(this).closest('.wcol_cat_panel').find('.enable-time-limit').prop('checked', true);
				$(this).closest('.wcol_cat_panel').find('.enable-time-limit').addClass('wcol-disabled').closest('.options_group').removeClass('wcol-hidden');;
				$(this).closest('.wcol_cat_panel').find('.enable-time-limit-hidden').val('on');

				$(this).closest('.wcol_cat_panel').find('.wcol-rule-max-limit').closest('.form-field').removeClass('wcol-hidden');
				$(this).closest('.wcol_cat_panel').find('.wcol-rule-time-span').closest('.form-field').removeClass('wcol-hidden');

			} else {
				$(this).closest('.wcol_cat_panel').find('.enable-max-rule-limit').removeClass('wcol-disabled');
				$(this).closest('.wcol_cat_panel').find('.enable-time-limit').removeClass('wcol-disabled');
			}
		} else {
			if ($(this).val() != '1') {
				$(this).closest('.wcol-rule-options').find('.enable-max-rule-limit').prop('checked', true);
				$(this).closest('.wcol-rule-options').find('.enable-max-rule-limit').addClass('wcol-disabled');
				$(this).closest('.wcol-rule-options').find('.enable-max-rule-limit-hidden').val('on');
				$(this).closest('.wcol-rule-options').find('.wcol-rule-max-limit').closest('tr').removeClass('wcol-hidden');

				$(this).closest('.wcol-rule-options').find('.enable-time-limit').prop('checked', true);
				$(this).closest('.wcol-rule-options').find('.enable-time-limit').addClass('wcol-disabled');
				$(this).closest('.wcol-rule-options').find('.enable-time-limit-hidden').val('on');
				$(this).closest('.wcol-rule-options').find('.wcol-rule-time-span').closest('tr').removeClass('wcol-hidden');

			} else {
				$(this).closest('.wcol-rule-options').find('.enable-max-rule-limit').removeClass('wcol-disabled');
				$(this).closest('.wcol-rule-options').find('.enable-time-limit').removeClass('wcol-disabled');

			}
		}
	});
	$('.wcol-settings').on('submit', function (e) {
		e.preventDefault();
		$('.xs-wcol').attr('disabled', 'disabled');
		$('.xs-wcol-spinner').addClass('is-active');
		$.ajax({
			url: wcol_script_vars.ajax_url,
			type: 'post',
			beforeSend: function () {
				$('.xs-wcol-spinner').addClass('is-active');
			},
			data: {
				'action': 'wc_order_limit_save_settings',
				'_wcol_save_rules_nonce': $('#_wcol_save_rules_nonce').val(),
				'data': $(this).serialize()
			},
			success: function (res) {
				if (res) {
					jQuery('.wcol-data-save-notice span').html('<strong>Success!</strong> Data Saved Successfully.');
					$('.wcol-data-save-notice').show();
				}
			},
			complete: function () {
				$('.wcol-settings').removeClass('xs-changed');
				$('.xs-wcol-spinner').removeClass('is-active');
				$('.xs-wcol').removeAttr('disabled');
				window.scrollTo(0, 0);
			}
		});

	});
	jQuery('.xs-wcol-notice-dismiss').on('click', function () {
		jQuery('.wcol-data-save-notice').hide();
	});
	$('#wcol_name , #wcol_email , #wcol_message').on('change', function (e) {
		if (!jQuery(this).val()) {
			jQuery(this).addClass("error");
		} else {
			jQuery(this).removeClass("error");
		}
	});
	jQuery('.xs-notice-dismiss,.notice-dismiss').on('click', function (e) {
		e.preventDefault();
		jQuery(this).parent().hide();
		jQuery(this).hide();
	});
	jQuery('#wcol-add-exclude-rule').on('click', function () {
		jQuery('table.wcol-collapsed').removeClass('wcol-collapsed');
		jQuery('.wcol-new').removeClass('wcol-new');
		var table = jQuery(this).parent().parent().parent().parent().find('tbody.wcol-main-body');
		var spinner = jQuery('.wcol_spinner');
		spinner.closest('form').css('pointer-events', 'none');
		spinner.addClass('wcol_is_active');
		var wcol_rid = jQuery(".xswcol-eid").val();
		jQuery.ajax({
			url: wcol_script_vars.ajax_url,
			type: 'post',
			data: { 'action': 'wcol_load_new_row', 'rule_type': 'exclude', 'wcol_eid': wcol_rid, 'wcol_nonce': wcol.nonce },
			success: function (res) {
				wcol_rid++;
				jQuery(".xswcol-eid").val(wcol_rid);
				jQuery(table).append(res);
				jQuery('.wcol-new .wcol-select-products').select2({
					ajax: {
						url: ajaxurl,
						dataType: 'json',
						delay: 250,
						data: function (params) {
							return {
								q: params.term, // search term
								page: params.page,
								action: 'wcol_get_product',
								'wcol_nonce': wcol.nonce
							};
						},
						processResults: function (data, params) {
							params.page = params.page || 1;
							return {
								results: data.items,
								pagination: {
									more: (params.page * 10) < data.total_count
								}
							};
						},
						cache: true
					},
					placeholder: 'Select Products',
					minimumInputLength: 3,
					width: "95%",
					multiple: true,
				});
				$("form :input").on('change', function () {
					$(this).closest('.wcol-settings').addClass('xs-changed');
					$('.xs-wcol').prop('disabled', false);
					if ($(".wcol-rule-checkbox:checked").length > 0) {
						$(".wcol-delete-selected").prop('disabled', false);
					} else {
						$(".wcol-delete-selected").prop('disabled', true);
					}
				});
				$("select").on('change', function () {
					$(this).closest('.wcol-settings').addClass('xs-changed');
					$('.xs-wcol').prop('disabled', false);
					if ($(".wcol-rule-checkbox:checked").length > 0) {
						$(".wcol-delete-selected").prop('disabled', false);
					} else {
						$(".wcol-delete-selected").prop('disabled', true);
					}
				});

			}
		}).always(function (jqXHR, textStatus, errorThrown) {
			if (textStatus !== 'success') {
				alert(errorThrown);
			}
			spinner.closest('form').css('pointer-events', 'auto');
			spinner.removeClass('wcol_is_active');
		});
	});
	jQuery('.wp-list-table').on('click', '.wcol-exclude', function (e) {
		if (jQuery(this).is(':checked')) {
			jQuery(this).parent().parent().find('.wcol-exclude-limit').addClass('wcol-hidden');
		} else {
			jQuery(this).parent().parent().find('.wcol-exclude-limit').removeClass('wcol-hidden');
		}
	});
	jQuery('.wcol-delete-selected').on('click', function (e) {
		e.preventDefault();
	});

	jQuery('.wcol-delete-rule').on('click', function (e) {
		e.preventDefault();
		jQuery('.wcol-delete-selected').parent().parent().parent().find('.wcol-cb input').each(function () {
			if (jQuery(this).is(':checked')) {
				jQuery(this).parent().parent().remove();
			}
		});
		jQuery('.wcol-rule-button-disable').prop('disabled', true);
		jQuery('.wcol-rule-modal-spinner').addClass('wcol_is_active');
		jQuery.ajax({
			url: wcol_script_vars.ajax_url,
			type: 'post',
			beforeSend: function () {
				jQuery('.xs-wcol-spinner').addClass('wcol_is_active');
			},
			data: {
				'action': 'wc_order_limit_save_settings',
				'_wcol_save_rules_nonce': $('#_wcol_save_rules_nonce').val(),
				'data': jQuery('.wcol-settings').serialize()
			},
			success: function (res) {
				if (res) {
					jQuery('.wcol-data-save-notice span').html('<strong>Success!</strong> Rule Deleted Successfully.');
					jQuery('.wcol-data-save-notice').show();
				}
			},
			complete: function () {
				jQuery('#xswcol-rule-modal').modal('toggle');
				jQuery('.wcol-settings').removeClass('xs-changed');
				jQuery('.wcol-rule-modal-spinner').removeClass('wcol_is_active');
				jQuery('.wcol-rule-button-disable').prop('disabled', false);
			}
		});
	});
	jQuery('.store-enable-time-limit').on('change', function (e) {
		if (jQuery(this).is(':checked')) {
			var store_time = jQuery('.wcol-store-time-span').val();
			jQuery('.store-time-span').removeClass('wcol-hidden');
			if (store_time) {
				jQuery('.store-time-' + store_time + '-span').removeClass('wcol-hidden');
			}
		} else {
			jQuery('.store-time-span').addClass('wcol-hidden');
			jQuery('.store-time-yearly-span').addClass('wcol-hidden');
			jQuery('.store-time-weekly-span').addClass('wcol-hidden');
			jQuery('.store-time-monthly-span').addClass('wcol-hidden');
			jQuery('.store-time-custom-span').addClass('wcol-hidden');
		}
	});
	jQuery('.wcol-store-time-span').on('change', function (e) {
		var store_time = jQuery(this).val();
		if (store_time) {
			jQuery('.store-time-yearly-span').addClass('wcol-hidden');
			jQuery('.store-time-weekly-span').addClass('wcol-hidden');
			jQuery('.store-time-monthly-span').addClass('wcol-hidden');
			jQuery('.store-time-custom-span').addClass('wcol-hidden');
			jQuery('.store-time-' + store_time + '-span').removeClass('wcol-hidden');
		}
	});
	jQuery(".wcol-store-start-time").flatpickr({
		enableTime: false
	});
	jQuery(".wcol-store-end-time").flatpickr({
		enableTime: false
	});
});
