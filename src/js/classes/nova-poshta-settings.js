var NovaPoshtaSettings = (function ($) {

    var result = {};
    var areaInputName = $('#woocommerce_nova_poshta_shipping_method_area_name');
    var areaInputKey = $('#woocommerce_nova_poshta_shipping_method_area');
    var cityInputName = $('#woocommerce_nova_poshta_shipping_method_city_name');
    var cityInputKey = $('#woocommerce_nova_poshta_shipping_method_city');
    var warehouseInputName = $('#woocommerce_nova_poshta_shipping_method_warehouse_name');
    var warehouseInputKey = $('#woocommerce_nova_poshta_shipping_method_warehouse');

    var initAutocomplete = function () {
        areaInputName.autocomplete({
            source: function (request, response) {
                jQuery.ajax({
                    type: 'POST',
                    url: NovaPoshtaHelper.ajaxUrl,
                    data: {
                        action: NovaPoshtaHelper.getRegionsByNameSuggestionAction,
                        name: request.term
                    },
                    success: function (json) {
                        var data = JSON.parse(json);
                        response(jQuery.map(data, function (item) {
                            return {
                                label: item.description,
                                value: item.ref
                            }
                        }));
                    }
                })
            },
            focus: function (event, ui) {
                areaInputName.val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                areaInputName.val(ui.item.label);
                areaInputKey.val(ui.item.value);
                clearCity();
                clearWarehouse();
                return false;
            }
        });
        cityInputName.autocomplete({
            source: function (request, response) {
                jQuery.ajax({
                    type: 'POST',
                    url: NovaPoshtaHelper.ajaxUrl,
                    data: {
                        action: NovaPoshtaHelper.getCitiesByNameSuggestionAction,
                        name: request.term,
                        parent_area_ref: areaInputKey.val()
                    },
                    success: function (json) {
                        var data = JSON.parse(json);
                        response(jQuery.map(data, function (item) {
                            return {
                                label: item.description,
                                value: item.ref
                            }
                        }));
                    }
                })
            },
            focus: function (event, ui) {
                cityInputName.val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                cityInputName.val(ui.item.label);
                cityInputKey.val(ui.item.value);
                clearWarehouse();
                return false;
            }
        });
        warehouseInputName.autocomplete({
            source: function (request, response) {
                jQuery.ajax({
                    type: 'POST',
                    url: NovaPoshtaHelper.ajaxUrl,
                    data: {
                        action: NovaPoshtaHelper.getWarehousesBySuggestionAction,
                        name: request.term,
                        parent_area_ref: cityInputKey.val()
                    },
                    success: function (json) {
                        var data = JSON.parse(json);
                        response(jQuery.map(data, function (item) {
                            return {
                                label: item.description,
                                value: item.ref
                            }
                        }));
                    }
                })
            },
            focus: function (event, ui) {
                warehouseInputName.val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                warehouseInputName.val(ui.item.label);
                warehouseInputKey.val(ui.item.value);
                return false;
            }
        });
    };

    var clearCity = function () {
        cityInputName.val('');
        cityInputKey.val('');
    };

    var clearWarehouse = function () {
        warehouseInputName.val('');
        warehouseInputKey.val('');
    };

    result.init = function () {
        initAutocomplete();
    };

    return result;

}(jQuery));