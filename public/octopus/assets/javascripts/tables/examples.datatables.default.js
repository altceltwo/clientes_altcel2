

(function( $ ) {

	'use strict';

	var datatableInit = function() {

		$('#datatable-default').dataTable({
			
				buttons: [
					{
						extends: 'pdf',
						sButtonText: 'PDF'
					},
					{
						extends: 'csv',
						sButtonText: 'CSV'
					},
					{
						extends: 'xls',
						sButtonText: 'Excel'
					},
					{
						extends: 'print',
						sButtonText: 'Print'
					}
				]
		});
		$('#datatable-default2').dataTable();
		$('#datatable-default3').dataTable();

	};

	$(function() {
		datatableInit();
	});

}).apply( this, [ jQuery ]);