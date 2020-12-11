//show unico com ajax - Inicio
$(document).on('click', '.btnshow', function(){
var id = $(this).attr('id');
var tr = $(this).closest('tr');
var table = $('#datatable-basic').DataTable();
$.ajax({
url:'{{$page["show"]}}',
mehtod:"get",
data:{id:id},
success:function(data)
{
	$('#varshow').html(data);
	$('#showModel').modal('show');
}
});
});
//show unico com ajax - Fim

// delete unico com ajax - Inicio
$(document).on('click', '.delete', function(){
var id = $(this).attr('id');
var tr = $(this).closest('tr');
var table = $('#datatable-basic').DataTable();
if(confirm("Tem certeza que deseja excluir esse registro?"))
{
	$.ajax({
	url:'{{$page["destroy"]}}',
	mehtod:"get",
	data:{id:id},
	success:function(data)
	{
		$.notify({
		icon: data.icon,
		title: data.title,
		message: data.message
	},{
	type: data.type
});
table.row(tr).remove().draw( false );
}
})
}
else
{
	return false;
}
});
//delete unico com ajax - Fim

//delete permanente com ajax - Inicio
$(document).on('click', '.del-perm', function(){
var id = $(this).attr('id');
var tr = $(this).closest('tr');
var table = $('#datatable-basic').DataTable();
if(confirm("Tem certeza que deseja excluir permanentemente esse registro?"))
{
	$.ajax({
	url:'{{$page["forceDelete"]}}',
	mehtod:"get",
	data:{id:id},
	success:function(data)
	{
		$.notify({
		icon: data.icon,
		title: data.title,
		message: data.message
	},{
	type: data.type
});
table.row(tr).remove().draw( false );
}
})
}
else
{
	return false;
}
});
//delete permanente com ajax - Fim

//restore unico com ajax - Inicio
$(document).on('click', '.restore', function(){
var id = $(this).attr('id');
var tr = $(this).closest('tr');
var table = $('#datatable-basic').DataTable();
if(confirm("Tem certeza que deseja restaurar esse registro?"))
{
	$.ajax({
	url:'{{$page["restore"]}}',
	mehtod:"get",
	data:{id:id},
	success:function(data)
	{
		$.notify({
		icon: data.icon,
		title: data.title,
		message: data.message
	},{
	type: data.type
});
table.row(tr).remove().draw( false );
}
})
}
else
{
	return false;
}
});
//restore unico com ajax - Fim