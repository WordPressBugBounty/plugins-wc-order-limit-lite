jQuery(document).ready(function ($) {
    'use strict';
    $('.wp-list-table , #wcol_tab , .form-table').on('change', '.enable-max-rule-limit', function () {
        if ($(this).is(':checked')) {
            $(this).parent().parent().parent().find('.wcol-rule-max-limit').parent().parent().removeClass('wcol-hidden');
        } else {
            $(this).parent().parent().parent().find('.wcol-rule-max-limit').parent().parent().addClass('wcol-hidden');
        }
    });
    $('.wp-list-table , #wcol_tab , .form-table').on('change', '.enable-time-limit', function () {
        if ($(this).is(':checked')) {
            $(this).closest('.wcol-rule-options').find('.wcol-rule-time-span').closest('tr').removeClass('wcol-hidden');
        } else {
            $(this).closest('.wcol-rule-options').find('.wcol-rule-time-span').closest('tr').addClass('wcol-hidden');
            $(this).closest('.wcol-rule-options').find('.wcol-rule-time-span').find('option[value="daily"]').attr('selected', true);
            $(this).closest('.wcol-rule-options').find('.wcol-rule-start-time').val('').closest('tr').addClass('wcol-hidden');
            $(this).closest('.wcol-rule-options').find('.wcol-rule-end-time').val('').closest('tr').addClass('wcol-hidden');
            $(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').addClass('wcol-hidden');
        }
    });

    $('.wp-list-table , #wcol_tab , .form-table').on('change', '.wcol-rule-time-span', function () {
        switch ($(this).val()) {
            case 'daily':
                $(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').val('').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').val('').closest('tr').addClass('wcol-hidden');
                break;
            case 'weekly':
                $(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').removeClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').val('').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').val('').closest('tr').addClass('wcol-hidden');
                break;
            case 'monthly':
                $(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').removeClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').val('').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').val('').closest('tr').addClass('wcol-hidden');
                break;
            case 'yearly':
                $(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').removeClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').val('').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').val('').closest('tr').addClass('wcol-hidden');
                break;
            case 'days':
                $(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').removeClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').val('').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').val('').closest('tr').addClass('wcol-hidden');
                break;
            default:
                $(this).closest('.wcol-rule-options').find('.wcol-rule-start-time, .wcol-rule-time-span-custom-from').closest('tr').removeClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-end-time, .wcol-rule-time-span-custom-to').closest('tr').removeClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-weekly-start-day').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-rule-days').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-monthly-start-date').closest('tr').addClass('wcol-hidden');
                $(this).closest('.wcol-rule-options').find('.wcol-yearly-start-day').closest('tr').addClass('wcol-hidden');
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
    $('.wp-list-table , #wcol_tab , .form-table').on('change', '.enable-users-limit', function () {
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
            $(this).parent().parent().parent().find('.wcol-rule-guest-users').parent().parent().addClass('wcol-hidden');
        }
    });

    $('.wp-list-table , #wcol_tab , .form-table').on('change', '.wcol-rule-user-type', function () {
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
    });
    $('.wp-list-table , #wcol_tab , .form-table').on('change', '.wcol-loop-checkbox', function () {
        if ($(this).is(':checked')) {
            $(this).parent().find('.wcol-loop-checkbox-hidden').val('on');
        } else {
            $(this).parent().find('.wcol-loop-checkbox-hidden').val('');
        }
    });
    $('.wp-list-table , #wcol_tab , .form-table').on('change', '.wcol-disable-rule-limit', function () {
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
        }
    });
    $('.wp-list-table , #wcol_tab , .form-table').on('change', '.across-all-orders-limit', function () {
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
    });
    $('.wp-list-table , #wcol_tab , .form-table , .form-wrap').on('change', '.enable-payment-limit', function () {
        if ($(this).is(':checked')) {
            $(this).parent().parent().parent().find('.wcol-payment-method').removeClass('wcol-hidden');
        } else {
            $(this).parent().parent().parent().find('.wcol-payment-method').addClass('wcol-hidden');
        }
    });

});