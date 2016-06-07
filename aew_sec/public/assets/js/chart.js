	function gerateGraphic(name, type)
{
	if(type == 'PieChart')
	{
		google.setOnLoadCallback(PieChart(name));
	}

	if(type == 'BarChart')
	{
		google.setOnLoadCallback(BarChart(name));
	}
}

function PieChart(name){
	var graphic = $(name).attr('name');
	var nHeight = $(name).attr('height');
	var nWidth  = $(name).attr('width');
    var title   = $('input#'+graphic+'[name=title]').val();
    
    var options = {
        title: title,
        is3D: true,
        width: nWidth,
        height: nHeight,
    };

    var chart = new google.visualization.PieChart(document.getElementById(graphic+'-imagem'));
    var data = new google.visualization.DataTable();
    
    data.addColumn('string', $('input#'+graphic+'[name=subtitle]').attr('axisX'));
    data.addColumn('number', $('input#'+graphic+'[name=subtitle]').attr('axisY'));

	rows = $('input#'+graphic+'[name=data]').length;
    data.addRows(rows);

    i = 0;
    $('input#'+graphic+'[name=data]').each(function(){
        data.setValue(i, 0, $(this).attr('title'));
        data.setValue(i, 1, $(this).val());
        i++;
    });
    
    chart.draw(data, options);
}

function BarChart(name)
{
	var graphic = $(name).attr('name');
	var nHeight = $(name).attr('height');
	var nWidth  = $(name).attr('width');

	var title    = $('input#'+graphic+'[name=title]').val();
	var subtitle = $('input#'+graphic+'[name=subtitle]').attr('title');
	var axisX    = $('input#'+graphic+'[name=subtitle]').attr('axisX');
	var axisY    = $('input#'+graphic+'[name=subtitle]').attr('axisY');

	var options = {
        title: title,
		legend: { position: 'none' },
        width: nWidth,
        height: nHeight,
        hAxis: { title : axisX, minValue: 0, textStyle: {fontSize: 12, bold: true, color: '#767676'} },
        vAxis: { title : axisY, textStyle: {fontSize: 12, bold: true, color: '#767676'} },
		chartArea: { width : '40%'},
		bar: {groupWidth: "90%"},
	};

	var chart = new google.visualization.BarChart(document.getElementById(graphic+'-imagem'));
    var data = new google.visualization.DataTable();

	data.addColumn('string', axisX);
  	data.addColumn('number', axisY);
	data.addColumn({type:'string', role:'style'});

	rows = $('input#'+graphic+'[name=data]').length;
    data.addRows(rows);

    i = 0;
    $('input#'+graphic+'[name=data]').each(function(){
        data.setValue(i, 0, $(this).attr('title'));
        data.setValue(i, 1, $(this).val());
        //data.setValue(i, 2, $(this).attr('color'));
        i++;
    });

	chart.draw(data, options);
}

google.load("visualization", "1", {packages:["corechart"]});

jQuery(document).ready(function($){ // No Conflict
	$('div.graphic[onload]').trigger('onload');
});
