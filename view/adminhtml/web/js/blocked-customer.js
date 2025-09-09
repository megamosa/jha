define([
    'jquery',
    'domReady!'
], function ($) {
    'use strict';

    // Add blocked customer styling to order grid
    function addBlockedCustomerStyling() {
        $('.admin__data-grid-wrap .data-grid tbody tr').each(function() {
            var $row = $(this);
            var blockedIndicator = $row.find('.blocked-customer-indicator');
            
            if (blockedIndicator.length > 0) {
                $row.attr('data-blocked-customer', '1');
                $row.addClass('blocked-customer-row');
            }
        });
    }

    // Initialize when page loads
    $(document).ready(function() {
        addBlockedCustomerStyling();
        
        // Re-apply styling after grid updates
        $(document).on('DOMNodeInserted', '.admin__data-grid-wrap', function() {
            setTimeout(addBlockedCustomerStyling, 500);
        });
    });

    return {
        init: addBlockedCustomerStyling
    };
});
