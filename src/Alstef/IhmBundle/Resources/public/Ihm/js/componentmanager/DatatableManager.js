class DatatableManager {
  constructor() {
    this.dataLang = null;
    this.selectLang();
    this.dataTableObject = null;
  };

  createDataTable(idDataTable, data, columns) {

    this.dataTableObject = $('#'+idDataTable).DataTable({
      data: data,
      columns: columns,
      searching: true,
      columnDefs: [ {
        "targets": '_all',
        "searchable": true
      } ],
      language: this.dataLang,
      scrollCollapse: true,
      scrollX: true,
      scrollY: '45vh',
      //paging: false
      headerCallback: function( nHead, aData, iStart, iEnd, aiDisplay ) {
        $('#'+idDataTable).DataTable().columns().iterator('column', function ( settings, column) {
          if (settings.aoColumns[ column ].id!== undefined) {
            $($('#'+idDataTable).DataTable().column(column).header()).attr('id', settings.aoColumns[ column ].id);
          }
        });
      },
      pageLength: 10,
    });

    setTimeout(this.dataTableObject.draw, 20);
    $('#datatable_wrapper > div.dataTables_scroll > div.dataTables_scrollHead > div > table > thead th').each(
      function () {
        var title = $(this).text();
        $(this).append( '<input class="search" type="text" placeholder="..." />' );
      }
    );

    // Apply the search
    this.dataTableObject.columns().eq(0).each(
      function(colIdx) {
        var that = this;

        $( 'input', that.column(colIdx).header()).on('keyup change', function() {
          if ( that.search() !== this.value || that.search() === "") {
            that.column(colIdx).search(this.value).draw();
          }
        });

        $('input', that.column(colIdx).header()).on('click', function(e) {
          e.stopPropagation();
        });
      } );

    this.eventSelectRow(idDataTable);
  };

  selectLang() {
    this.dataLang = traductions_datatables;
  };

  eventSelectRow(idDataTable) {
    var refDataManager = this;
    $('#'+idDataTable+' tbody').on( 'click', 'tr', function () {
      if ( $(this).hasClass('row_selected') ) {
        $(this).removeClass('row_selected');
      } else {
        refDataManager.dataTableObject.$('tr.row_selected').removeClass('row_selected');
        $(this).addClass('row_selected');
      }
    } );
  };
}
