function createDatePicker(isSingleDatePicker,style,paramid,functionResult)
{	
	var id = '#' + paramid; 
	    $(id).daterangepicker({
	    		 locale: {format: 'DD/MM/YYYY'},
                singleDatePicker: isSingleDatePicker,
                calender_style: style
            }, function (start, end, label) {
                functionResult(start,end);
        });
}