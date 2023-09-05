@extends('admintemplate::admin.layout.app',[
	'title' => 'AdminLTE 3 | Dashboard 3', // optional
	'content_header' => 'Dashboard v3', // optional
	'breadcrumb' => [
			'items' => ["<a href='#'>Home</a>","<a href='#'>Home</a>","<a href='#'>Home</a>"] // optional
			//or
			'items' => "<a href='#'>Home</a>", // optional

			'active' => "Dashboard v3", //optional
		],
	])
@section('content')
@endsection

@section('script')
@endsection