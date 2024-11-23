/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.scss';
import $ from 'jquery';
import jQuery from 'jquery';
window.$ = $;
window.jQuery = jQuery;
import Highcharts from 'highcharts';
import pdfmake from 'pdfmake';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-autofill-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-keytable-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-rowgroup-bs5';
import { language } from "./dataTableConfig.js";
import { deFormatter } from "./deFormatter.js";
import { Modal } from 'bootstrap';
import * as bootstrap from "bootstrap";

$('#calcMonthVariable').on("click", function (e) {
    e.preventDefault();
    document.getElementById("variableTable").style.opacity = "0.2";
    $('#loader').show();
    let href = $(this).attr('href');
    let xhr = new XMLHttpRequest()
    xhr.open('POST', href, true)
    xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8')
    xhr.send();

    xhr.onload = function () {
        if(xhr.status === 200) {
            $('#loader').hide();
            document.getElementById("variableTable").style.opacity = "1";
            location.reload();
        }
    }
})

$('#calcAllVariables').on("click", function (e) {
    e.preventDefault();
    document.getElementById("variableTable").style.opacity = "0.2";
    $('#loader').show();
    let href = $(this).attr('href');
    let xhr = new XMLHttpRequest()
    xhr.open('POST', href, true)
    xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8')
    xhr.send();

    xhr.onload = function () {
        if(xhr.status === 200) {
            $('#loader').hide();
            document.getElementById("variableTable").style.opacity = "1";
            location.reload();
        }
    }
})

jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
    return this.flatten().reduce( function ( a, b ) {
        var x = parseFloat(a) || 0;
        var y = parseFloat($(b).attr('data-order')) || 0;
        return x + y
    }, 0 );
} );

let intVal = function (i) {
    if (typeof i === 'string') {
        i = i.replace("€", '');
        i = i.replace(".", "");
        i = i.replace(",", ".");
        i = i * 1;
    }

    if (typeof i === 'number') {
        return i;
    }
    return 0;
};

let variableTable = new DataTable('#variableTable', {
    footerCallback: function () {
        let api = this.api();
        let sum = 0;
        let parser = new DOMParser();

        for(let i = 1; i <= 12; i++) {
            let pageTotalVar = 0;
            let data = api.column(i, { page: 'current' }).data();
            for(let j = 0; j <= data.length; j++) {
                let doc = parser.parseFromString(data[j], "text/html");
                doc.querySelectorAll("span").forEach(s => pageTotalVar += intVal(s.innerHTML));
            }
            sum += pageTotalVar;
            $(api.column(i).footer()).html(
                deFormatter.format(pageTotalVar)
            );
        }
        $(api.column(14).footer()).html(
            deFormatter.format(sum)
        );
        variableTablePopover();
    },
    createdRow: function (row, data, index) {
        let val = 0;
        let parser = new DOMParser();
        for(let i = 1; i <= 12; i++) {
            let doc = parser.parseFromString(data[i], "text/html");
            doc.querySelectorAll("span").forEach(s => val += intVal(s.innerHTML));
        }
        $('td', row).eq(14).append(deFormatter.format(val));
    },
    language,
    order: [
        [13, 'desc'],
        [0, 'asc'],
    ],
});

let minEl = $('#min');
let maxEl = $('#max');
// Custom range filtering function
variableTable.search.fixed('range', function (searchStr, data, index) {
    let min = parseInt(minEl.val(), 10);
    let max = parseInt(maxEl.val(), 10);
    let year = parseFloat(data[13]) || 0; // use data for the age column

    return (isNaN(min) && isNaN(max)) ||
        (isNaN(min) && year <= max) ||
        (min <= year && isNaN(max)) ||
        (min <= year && year <= max);
});

// Changes to the inputs will trigger a redraw to update the table
minEl.on('input', function () {
    variableTable.draw();
});
maxEl.on('input', function () {
    variableTable.draw();
});



let benefitTable = new DataTable('#benefitTable', {
    language,
});

let goodiesTable = new DataTable('#goodiesTable', {
    language,
});

let employeeTable = new DataTable('#employeeTable', {
    footerCallback: function (row, data, start, end, display) {
      let api = this.api();

        let intVal = function (i) {
            // return typeof i === 'string' ? i.replace(/[\€,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            if (typeof i === 'string') {
                i = i.replace("€", '');
                i = i.replace(".", "");
                i = i.replace(",", ".");
                i = i * 1;
            }

            if (typeof i === 'number') {
                return i;
            }
            return 0;
        };

        // Total over all pages
        let totalVar = api
            .column(10)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

        let totalFix = api
            .column(9)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

        // Total over this page
        let pageTotalVar = api
            .column(10, { page: 'current' })
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
        let pageTotalFix = api
            .column(9, { page: 'current' })
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);

        let totalDelta = totalFix/12 - totalVar;
        let pageTotalDelta = pageTotalFix/12 - pageTotalVar;

        // Update footer
        $(api.column(10).footer()).html(
            deFormatter.format(pageTotalVar) + ' ( ' + deFormatter.format(totalVar) + ' Total)'
        );
        $(api.column(0).footer()).html(
            'Delta Fix-Var: ' + deFormatter.format(pageTotalDelta) + ' ( ' + deFormatter.format(totalDelta) + ' Total)'
        );
    },
    language,
    columns: [
        {
            className: 'dt-control',
            orderable: false,
            data: null,
            defaultContent: ''
        },
        { data: 'Personalnummer' },
        { data: 'Name' },
        { data: 'Vorname' },
        { data: 'Eintritt' },
        { data: 'Grenzstundensatz' },
        { data: 'Incentivsatz' },
        { data: 'Zielgehalt' },
        { data: 'Variabler Anteil' },
        { data: 'Fixer Anteil' },
        { data: 'Variable' },
        { data: 'Benefits'},
        { data: 'Riffle'},
        { data: 'Goodies'},
        { data: 'Bonus'},
        { data: 'Action' },
    ],
    columnDefs: [
        {
            target: 5,
            visible: false,
        },
        {
            target: 6,
            visible: false,
        },
        {
            target: 11,
            visible: false,
        },
        {
            target: 12,
            visible: false,
        },
        {
            target: 13,
            visible: false,
        },
        {
            target: 14,
            visible: false,
        },
    ],
    order: [[1, 'asc']]
});

function format(d) {
    // `d` is the original data object for the row

    let stringArray = '<dl>';
    if(d.Grenzstundensatz) {
        stringArray += getDtFromValue('Grenzstundensatz', d.Grenzstundensatz);
    }

    if(d.Incentivsatz) {
        stringArray += getDtFromValue('Incentivsatz', d.Incentivsatz);
    }

    if(d.Riffle) {
        stringArray += getDtFromValue('Riffle', d.Riffle);
    }

    if(d.Benefits) {
        stringArray += getDtFromValue('Benefits', d.Benefits);
    }

    if(d.Goodies) {
        stringArray += getDtFromValue('Goodies', d.Goodies);
    }

    if(d.Bonus) {
        stringArray += getDtFromValue('Bonus', d.Bonus);
    }

    stringArray += '</dl>';
    return stringArray;
}

function getDtFromValue(string, type) {
    return (
        '<dt>' + string + '</dt>' +
        '<dd>' +
        type +
        '</dd>'
    );
}

// Add event listener for opening and closing details
employeeTable.on('click', 'td.dt-control', function (e) {
    let tr = e.target.closest('tr');
    let row = employeeTable.row(tr);

    if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
    }
    else {
        // Open this row
        row.child(format(row.data())).show();
    }
});

jQuery(document).ready(function() {
    let $hoursWrapper = $('.js-wrapper-hours');
    $hoursWrapper.on('click', '.js-remove-hours', function(e) {
        e.preventDefault();
        $(this).closest('.js-item-hours')
            .fadeOut()
            .remove();
    });
    $hoursWrapper.on('click', '.js-add-hours', function(e) {
        e.preventDefault();
        // Get the data-prototype explained earlier
        let prototype = $hoursWrapper.data('prototype');
        // get the new index
        let index = $hoursWrapper.data('index');
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        let newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $hoursWrapper.data('index', index + 1);
        // Display the form in the page before the "new" link
        $(this).after(newForm);
    });

    let $goodiesWrapper = $('.js-wrapper-goodies');
    $goodiesWrapper.on('click', '.js-remove-goodies', function(e) {
        e.preventDefault();
        $(this).closest('.js-item-goodies')
            .fadeOut()
            .remove();
    });
    $goodiesWrapper.on('click', '.js-add-goodies', function(e) {
        e.preventDefault();
        // Get the data-prototype explained earlier
        let prototype = $goodiesWrapper.data('prototype');
        // get the new index
        let index = $goodiesWrapper.data('index');
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        let newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $goodiesWrapper.data('index', index + 1);
        // Display the form in the page before the "new" link
        $(this).after(newForm);
    });

    let $riffleWrapper = $('.js-wrapper-riffle');
    $riffleWrapper.on('click', '.js-remove-riffle', function(e) {
        e.preventDefault();
        $(this).closest('.js-item-riffle')
            .fadeOut()
            .remove();
    });
    $riffleWrapper.on('click', '.js-add-riffle', function(e) {
        e.preventDefault();
        // Get the data-prototype explained earlier
        let prototype = $riffleWrapper.data('prototype');
        // get the new index
        let index = $riffleWrapper.data('index');
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        let newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $riffleWrapper.data('index', index + 1);
        // Display the form in the page before the "new" link
        $(this).after(newForm);
    });

    let $bonusWrapper = $('.js-wrapper-bonus');
    $bonusWrapper.on('click', '.js-remove-bonus', function(e) {
        e.preventDefault();
        $(this).closest('.js-item-bonus')
            .fadeOut()
            .remove();
    });
    $bonusWrapper.on('click', '.js-add-bonus', function(e) {
        e.preventDefault();
        // Get the data-prototype explained earlier
        let prototype = $bonusWrapper.data('prototype');
        // get the new index
        let index = $bonusWrapper.data('index');
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        let newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $bonusWrapper.data('index', index + 1);
        // Display the form in the page before the "new" link
        $(this).after(newForm);
    });

    let $benefitsWrapper = $('.js-wrapper-benefits');
    $benefitsWrapper.on('click', '.js-remove-benefits', function(e) {
        e.preventDefault();
        $(this).closest('.js-item-benefits')
            .fadeOut()
            .remove();
    });
    $benefitsWrapper.on('click', '.js-add-benefits', function(e) {
        e.preventDefault();
        // Get the data-prototype explained earlier
        let prototype = $benefitsWrapper.data('prototype');
        // get the new index
        let index = $benefitsWrapper.data('index');
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        let newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $benefitsWrapper.data('index', index + 1);
        // Display the form in the page before the "new" link
        $(this).after(newForm);
    });
});

// let chartElement = document.querySelector('.js-chart-data');
let chartElement = document.querySelector('.js-chart-data');
if (chartElement) {
    let chartData = JSON.parse(chartElement.getAttribute('data-chart'));
    Highcharts.setOptions({
        lang: {
            decimalPoint: ","
        }
    });
    Highcharts.chart('variable-chart', {
        chart: {
            type: 'line',
            styledMode: true
        },
        title: {
            text: 'Gesamtvariable Monate'
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
        },
        yAxis: {
            title: {
                text: 'Variable'
            },
        },
        tooltip: {
            pointFormat: "{series.name}: {point.y:.2f} €",
        },
        series: chartData
    });
}

function variableTablePopover() {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
}
