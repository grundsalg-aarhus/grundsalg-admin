(function ($) {
    /**
     * Find elements by field names.
     */
    var find = function(names, context) {
        if (!$.isArray(names)) {
            names = [names];
        }
        var selectors = [];
        for (var i = 0, name; name = names[i]; i++) {
            selectors.push('[class$="_' + name + '"]');
            selectors.push('[class="js-' + name + '"]');
        }
        return $(selectors.join(','), context || null);
    },
    findControl = function(names, context) {
        return find(names, context).find('input, select');
    },
    getValue = function(name, context) {
        return findControl(name, context).val();
    },
    /**
     * Show elements by field names.
     */
    show = function(names) {
        find(names).show();
    },
    /**
     * Hide elements by field names.
     */
    hide = function(names) {
        find(names).hide();
    },
    calculateRow = function(row) {
        var context = $(row);
        var amount = parseInt(getValue('antalkorr1', context));
        var value = parseInt(getValue('akrkorr1', context));
        var total = amount * value;
        findControl('totalkorr1', context).grundFloatToString(total);
    };

    $(document).ready(function () {
        // Set here because readonly not properly supported by EasyAdmin
        $('.js-readonly input').prop('readonly', true);

        grundShowHidePriceFields();
        grundShowHideEtagem2();
        grundShowHideAnnoceres();
        grundSetReadOnlyDatoannonce();
        grundShowHideSalgsFields();

        $('#grund_collection_grunde').on('easyadmin.collection.item-added', function(event) {
            var newRow = $(this).children().last();
            grundShowHideEtagem2();
            grundShowHidePriceFields();
            findControl(['antalkorr1', 'akrkorr1'], newRow).on('change keyup', function (event) {
                var row = $(this).closest('tr');
                calculateRow(row);
            }).trigger('keyup');
        });

        $('[id$=_annonceres]').on('click', grundShowHideAnnoceres);
        $('.js-calc-minbud').on('click', grundCalcMinbud);
        $('.js-pris-calc input').on('keyup', grundCalc);
        $('[id$=_type]').on('change', grundShowHideEtagem2);
        $('[id$=_salgstype]').on('change', grundShowHidePriceFields);
        $('[id$=_auktionstartdato]').on('change', grundSyncAuktionDates);
        $('[id$=_auktionslutdato]').on('change', grundSyncAuktionDates);

        $('.js-price-table').show();

        $('.js-resslut').on('click', function () {
            var resstart = $('[id$=_resstart]').val();
            var resslut = moment(resstart).add(14, 'd').format('YYYY-MM-DD');
            $('[id$=_resslut]').val(resslut);
        });

        $('.js-tilbudslut').on('click', function () {
            var tilbudstart = $('[id$=_tilbudstart]').val();
            var tilbudslut = moment(tilbudstart).add(28, 'd').format('YYYY-MM-DD');
            $('[id$=_tilbudslut]').val(tilbudslut);
        });

        $('.js-today').on('click', function () {
            var dateInput = $(this).parent().parent().find('input');
            dateInput.val(moment().format('YYYY-MM-DD'));
        });

        $('.js-accept').on('click', function () {
            var dateInput = $(this).parent().parent().find('input');
            dateInput.val(moment().format('YYYY-MM-DD'));

            grundCalc();
        });

        $('.js-resstart').on('click', function () {
            var dateInput = $(this).parent().parent().find('input');
            dateInput.val(moment().format('YYYY-MM-DD'));

            var reslut = moment().add(14, 'd').format('YYYY-MM-DD');
            $('[id$=_resslut]').val(reslut);
        });

        $('.js-tilbudstart').on('click', function () {
            var dateInput = $(this).parent().parent().find('input');
            dateInput.val(moment().format('YYYY-MM-DD'));

            var reslut = moment().add(28, 'd').format('YYYY-MM-DD');
            $('[id$=_tilbudslut]').val(reslut);
        });

    });

    var grundShowHideSalgsFields = function() {
        var status = $('[id$=_status]').val();
        var salgstype = $('[id$=_salgstype]').val();

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

        if ((status === 'Ledig') || (status === 'Tilbud') || (status === 'Auktion slut')) {
            $('.js-salgsdatoer-wrapper').show();
            $('.js-koeber-wrapper').show();
        } else {
            $('.js-salgsdatoer-wrapper').hide();
            $('.js-koeber-wrapper').hide();
        }
    };

    var grundSyncAuktionDates = function() {
        var start = moment( $('[id$=_auktionstartdato]').val() );
        $('.js-auktion-wrapper span.form-control').first().text( start.format('DD/MM/YYYY') );

        var slut = moment( $('[id$=_auktionslutdato]').val() );
        $('.js-auktion-wrapper span.form-control').last().text( slut.format('DD/MM/YYYY') );
    }

    var grundShowHideAnnoceres = function () {
        var annonceres = $('[id$=_annonceres]').is(":checked");

        if (annonceres) {
            $('.js-datoAnnonce').show();
        } else {
            $('.js-datoAnnonce').hide();
        }
    }

    var grundSetReadOnlyDatoannonce = function() {
        var status = $('[id$=_status]').val();
        var datoAnnonceInput = $('[id$=_datoAnnonce]');

        if ((status === 'Fremtidig') || (status === 'Annonceret')) {
            datoAnnonceInput.prop('readonly', true);
        } else {
            datoAnnonceInput.prop('readonly', false);
        }
    }

    var grundShowHideEtagem2 = function () {
        var type = $('[id$=_type]').val();

        if (type == 'Storparcel') {
            show('maxetagem2');
        } else {
            hide('maxetagem2');
        }
    }

    var grundShowHidePriceFields = function () {
        var salgsType = $('[id$=_salgstype]').val();

        if (salgsType == 'Kvadratmeterpris' || salgsType == 'Etgm2') {
            show(['priskorrektion1', 'minbud', 'antalkorr1', 'akrkorr1', 'totalkorr1']);
            hide(['fastpris', 'minbud']);

            $('.js-auktion-wrapper').hide();
            $('.js-pris').show();
            // grundCalc();

        } else if (salgsType == 'Fastpris') {
            show('fastpris');
            hide(['priskorrektion1', 'antalkorr1', 'akrkorr1', 'totalkorr1', 'minbud']);

            $('.js-pris').hide();
            $('.js-auktion-wrapper').hide();
        } else if (salgsType == 'Auktion') {
            show('minbud');
            hide(['priskorrektion1', 'antalkorr1', 'akrkorr1', 'totalkorr1', 'fastpris']);

            $('.js-pris').hide();
            $('.js-auktion-wrapper').show();

            // grundCalcMinbud();

        }
    }

    var grundCalcMinbud = function () {
        var salgsType = $('[id$=_salgstype]').val();
        var type = $('[id$=_type]').val();

        var arealInput = $('[id$=_areal]');
        var arealvejInput = $('[id$=_arealvej]');
        var arealkoteletInput = $('[id$=_arealkotelet]');
        var bruttoarealInput = $('[id$=_bruttoareal]');
        var prism2Input = $('[id$=_prism2]');
        var minBudInput = $('[id$=_minbud]');
        var maxetagem2Input = $('[id$=_maxetagem2]');

        var areal = arealInput.grundParseFloat();
        var arealvej = arealvejInput.grundParseFloat();
        var arealkotelet = arealkoteletInput.grundParseFloat();
        var prism2 = prism2Input.grundParseFloat();
        var minbud = minBudInput.grundParseFloat();
        var maxetagem2 = maxetagem2Input.grundParseFloat();

        var bruttoareal = areal - arealvej - arealkotelet;
        bruttoarealInput.grundFloatToString(bruttoareal);

        if (salgsType == 'Auktion') {
            if (prism2 > 0) {
                if (type === 'Storparcel') {
                    minbud = maxetagem2 * prism2;
                } else {
                    minbud = bruttoareal * prism2;
                }
                minBudInput.grundFloatToString(minbud);
            }
        }
    }

    var grundCalc = function () {
        var salgsType = $('[id$=_salgstype]').val();

        var arealInput = $('[id$=_areal]');
        var arealvejInput = $('[id$=_arealvej]');
        var arealkoteletInput = $('[id$=_arealkotelet]');
        var bruttoarealInput = $('[id$=_bruttoareal]');
        var prism2Input = $('[id$=_prism2]');
        var prisInput = $('[id$=_pris]');
        var prisExKorrInput = $('#vis_pris_ex_korr');
        var minBudInput = $('[id$=_minbud]');

        var areal = arealInput.grundParseFloat();
        var arealvej = arealvejInput.grundParseFloat();
        var arealkotelet = arealkoteletInput.grundParseFloat();
        var bruttoareal = bruttoarealInput.grundParseFloat();
        var prism2 = prism2Input.grundParseFloat();
        var pris = prisInput.grundParseFloat();
        var prisExKorr = prisExKorrInput.grundParseFloat();
        var minbud = minBudInput.grundParseFloat();

        var bruttoareal = areal - arealvej - arealkotelet;
        bruttoarealInput.grundFloatToString(bruttoareal);

        if (salgsType == 'Kvadratmeterpris' || salgsType == 'Etgm2') {

            var antalkorr1Input = $('[id$=_antalkorr1]')
            var akrkorr1Input = $('[id$=_akrkorr1]');
            var priskorrektion1_totalInput = $('[id$=priskorrektion1_total]');

            var antalkorr2Input = $('[id$=_antalkorr2]')
            var akrkorr2Input = $('[id$=_akrkorr2]');
            var priskorrektion2_totalInput = $('[id$=priskorrektion2_total]');

            var antalkorr3Input = $('[id$=_antalkorr3]')
            var akrkorr3Input = $('[id$=_akrkorr3]');
            var priskorrektion3_totalInput = $('[id$=priskorrektion3_total]');

            var antalkorr1 = antalkorr1Input.grundParseFloat();
            var akrkorr1 = akrkorr1Input.grundParseFloat();

            var antalkorr2 = antalkorr2Input.grundParseFloat();
            var akrkorr2 = akrkorr2Input.grundParseFloat();

            var antalkorr3 = antalkorr3Input.grundParseFloat();
            var akrkorr3 = akrkorr3Input.grundParseFloat();

            if (!isNaN(antalkorr1) && !isNaN(akrkorr1)) {
                var priskorrektion1 = antalkorr1 * akrkorr1;
                priskorrektion1_totalInput.grundFloatToString(priskorrektion1);
            }

            if (!isNaN(antalkorr2) && !isNaN(akrkorr2)) {
                var priskorrektion2 = antalkorr2 * akrkorr2;
                priskorrektion2_totalInput.grundFloatToString(priskorrektion2);
            }

            if (!isNaN(antalkorr3) && !isNaN(akrkorr3)) {
                var priskorrektion3 = antalkorr3 * akrkorr3;
                priskorrektion3_totalInput.grundFloatToString(priskorrektion3);
            }

            if (prism2 > 0) {
                prisExKorr = bruttoareal * prism2;
                prisExKorrInput.grundFloatToString(prisExKorr);

                pris = prisExKorr + priskorrektion1 + priskorrektion2 + priskorrektion3;
                prisInput.grundFloatToString(pris);
            }

        } else if (salgsType == 'Fastpris') {
            var fastprisInput = $('[id$=_fastpris]');
            var fastpris = fastprisInput.val();

            var acceptInput = $('[id$=_accept]');
            var accept = acceptInput.val();

            var salgsprisumomsInput = $('[id$=_salgsprisumoms]');
            var salgsprisumoms = salgsprisumomsInput.val();

            if (fastpris && accept) {
                salgsprisumoms = 0.8 * fastpris;
                salgsprisumomsInput.grundFloatToString(salgsprisumoms);
            }

        }
    }


}(jQuery));

jQuery.fn.extend({
    grundParseFloat: function () {
        var value = $(this).val().replace(',', '.');
        var floatValue = parseFloat(value);
        if (isNaN(floatValue)) {
            floatValue = 0;
        }
        return floatValue;
    },
    grundFloatToString: function (value) {
        if (isNaN(value)) {
            value = 0.0;
        }
        var result = value.toFixed(2).toString().replace('.', ',');
        this.val(result);

        return result;
    }
});
