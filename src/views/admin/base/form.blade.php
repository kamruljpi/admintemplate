@extends('admintemplate::admin.layout.app',[
  'title' => "".$pageTitle." | ".config('app.name'),
  'content_header' => "".$pageTitle."",
  'breadcrumb' => [
      'items' => "<a href='#'>Home</a>",
      'active' => "".$pageTitle."",
    ],
  ])
@section('content')
  @include('admintemplate::admin.messages.error')
  @include('admintemplate::admin.messages.success')
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
		        	<div class="row">
			        	<div class="col-md-12">
							Form Title Here
			        	</div>
			        </div>
				</div>
				<div class="card-body">
		        	<div class="row">
			        	<div class="col-md-12">
							form Content Here
			        	</div>
			        </div>
				</div>
			</div>
		</div>
	</div>
@endsection