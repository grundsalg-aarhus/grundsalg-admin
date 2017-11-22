(function ($) {
    $(document).ready(function () {

        grundShowHidePriceFields();
        grundShowHideEtagem2();
        grundShowHideAnnoceres();
        grundShowHideSalgsFields();

        $('.js-price-table').show();

    });

    var grundShowHideSalgsFields = function() {
        var status = $('#app_grund_status').text().trim();
        var salgstype = $('#app_grund_salgstype').text().trim();

        if ((salgstype !== 'Auktion') && ((status === 'Ledig') || (status === 'Tilbud'))) {
            $('.js-resdate-wrapper').show();
            $('.js-tilbuddate-wrapper').show();
        } else {
            $('.js-resdate-wrapper').hide();
            $('.js-tilbuddate-wrapper').hide();
        }

        if (salgstype === 'Auktion') {
            $('.js-antagetbud-wrapper').show();
        } else {
            $('.js-antagetbud-wrapper').hide();
        }
    };

    var grundShowHideAnnoceres = function () {
        var annonceres = $('#app_grund_annonceres span').text().trim();

        if (annonceres !== 'Nej') {
            $('.js-datoannonce').show();
        } else {
            $('.js-datoannonce').hide();
        }
    }

    var grundShowHideEtagem2 = function () {
        var type = $('#app_grund_type').text().trim();

        if (type == 'Storparcel') {
            $('.js-maxetagem2').show();
        } else {
            $('.js-maxetagem2').hide();
        }
    }

    var grundShowHidePriceFields = function () {
        var salgsType = $('#app_grund_salgstype').text().trim();

        if (salgsType == 'Kvadratmeterpris' || salgsType == 'Etgm2') {

            $('.js-fastpris').hide();
            $('.js-minbud').hide();
            $('.js-auktion-wrapper').hide();

            $('.js-pris').show();
            $('.js-priskorrektion').show();

            // grundCalc();

        } else if (salgsType == 'Fastpris') {

            $('.js-priskorrektion').hide();
            $('.js-pris').hide();
            $('.js-minbud').hide();
            $('.js-auktion-wrapper').hide();

            $('.js-fastpris').show();

        } else if (salgsType == 'Auktion') {

            $('.js-priskorrektion').hide();
            $('.js-pris').hide();
            $('.js-fastpris').hide();

            $('.js-minbud').show();
            $('.js-auktion-wrapper').show();

            // grundCalcMinbud();

        }
    }

}(jQuery));