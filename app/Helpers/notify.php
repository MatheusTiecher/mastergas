<?php 

if (! function_exists('notifySuccess')) {
	function notifySuccess($message)
	{
		return $exnotify = json_encode([
			'status' => 'Sucesso',
			'icon' => 'fas fa-check',
			'title' => '<strong>Salvo: </strong>',
			'message' => $message,
			'type' => 'success'
		]);
	}
}

if (! function_exists('notifyWarning')) {
	function notifyWarning($message)
	{
		return $exnotify = json_encode([
			'status' => 'Aviso',
			'icon' => 'fas fa-exclamation-triangle',
			'title' => '<strong>Aviso: </strong>',
			'message' => $message,
			'type' => 'warning'
		]);
	}
}

if (! function_exists('notifyInfo')) {
	function notifyInfo($message)
	{
		return $exnotify = json_encode([
			'status' => 'Informativo',
			'icon' => 'fas fa-info-circle',
			'title' => '<strong>Informativo: </strong>',
			'message' => $message,
			'type' => 'info'
		]);
	}
}

if (! function_exists('notifyDanger')) {
	function notifyDanger($message)
	{
		return $exnotify = json_encode([
			'status' => 'Erro',
			'icon' => 'fas fa-times',
			'title' => '<strong>Erro: </strong>',
			'message' => $message,
			'type' => 'danger'
		]);
	}
}

if (! function_exists('notifyDestroyTemp')) {
	function notifyDestroyTemp()
	{
		return response()->json([
			'status' => 'Sucesso',
			'icon' => 'fas fa-trash-alt',
			'title' => '<strong>Sucesso:</strong>',
			'message' => 'Excluido com sucesso!',
			'type' => 'success'
		]);
	}
}

if (! function_exists('notifyForceDelete')) {
	function notifyForceDelete()
	{
		return response()->json([
			'status' => 'Sucesso',
			'icon' => 'fas fa-trash-alt',
			'title' => '<strong>Sucesso:</strong>',
			'message' => 'Removido permanentemente com sucesso!',
			'type' => 'success'
		]);
	}
}

if (! function_exists('notifyRestore')) {
	function notifyRestore()
	{
		return response()->json([
			'status' => 'Sucesso',
			'icon' => 'fas fa-recycle',
			'title' => '<strong>Sucesso:</strong>',
			'message' => 'Restaurado com sucesso!',
			'type' => 'success'
		]);  
	}
}

if (! function_exists('notifyAjaxNotFound')) {
	function notifyAjaxNotFound()
	{
		return response()->json([
			'status' => 'Erro',
			'icon' => 'fas fa-times',
			'title' => '<strong>Erro:</strong>',
			'message' => 'Registro não existe mais!',
			'type' => 'danger'
		]);  
	}
}

if (! function_exists('notifyAjaxInfo')) {
	function notifyAjaxInfo()
	{
		return response()->json([
			'status' => 'Informativo',
			'icon' => 'fas fa-info-circle',
			'title' => '<strong>Informativo:</strong>',
			'message' => 'Esse registro não pode ser alterado!',
			'type' => 'info'
		]);  
	}
}

if (! function_exists('notifyAjax403')) {
	function notifyAjax403()
	{
		return response()->json([
			'status' => 'Aviso',
			'icon' => 'fas fa-info-circle',
			'title' => '<strong>Informativo:</strong>',
			'message' => 'Não autorizado!',
			'type' => 'warning'
		]);  
	}
}

// para se caso adicionar uma funcao com o mesmo nome

// if (! function_exists('dateToCarbonDateTimeStart')) {

// }

?>