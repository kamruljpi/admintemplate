<div class="content-header">
  <div class="container-fluid">
		@if(isset($content_header) || isset($breadcrumb))
	    <div class="row mb-2">
	      <div class="col-sm-6">
	        <h1 class="m-0 text-dark">{{ isset($content_header) ? $content_header : '' }}</h1>
	      </div><!-- /.col -->
	      <div class="col-sm-6">
	        <ol class="breadcrumb float-sm-right">
	        		@if(isset($breadcrumb) && is_array($breadcrumb))
	        			@if(isset($breadcrumb['items']) && is_array($breadcrumb['items']))
	            			@foreach($breadcrumb['items'] as $item)
	            	  		<li class="breadcrumb-item">{!!$item !!}</li>
	            			@endforeach
	            		@elseif(isset($breadcrumb['items']) && !is_array($breadcrumb['items']))
	            	  	<li class="breadcrumb-item">{!!$breadcrumb['items'] !!}</li>
	        			@endif
	          		<li class="breadcrumb-item active">{!!isset($breadcrumb['active']) ? $breadcrumb['active'] : ''!!}</li>
	        		@endif
	        </ol>
	      </div><!-- /.col -->
	    </div><!-- /.row -->
		@endif
  </div><!-- /.container-fluid -->
</div>