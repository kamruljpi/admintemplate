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
	        	<div class="col-md-10">
              @if(isset($extraBtns) && count($extraBtns) > 0)
                @foreach($extraBtns as $exbtnkey => $exbtnvalue)
                    @if(isset($exbtnvalue['routeName']))
                      <a href="{{ Route($exbtnvalue['routeName']) }}" class="@if(isset($exbtnvalue['class'])) {{ $exbtnvalue['class'] }} @endif btn btn-success">@if(isset($exbtnvalue['title'])) {{ $exbtnvalue['title'] }} @else {{ $exbtnvalue['routeName'] }} @endif</a>
                    @endif
                @endforeach
              @endif
            </div>

	        	<div class="col-md-2">
	        		<div class="text-right">	  
                @if(isset($btnLists) && count($btnLists) > 0)
                  @foreach($btnLists as $btnkey => $btnvalue)
                      @if(isset($btnvalue['routeName']))
                        <a href="{{ Route($btnvalue['routeName']) }}" class="@if(isset($btnvalue['class'])) {{ $btnvalue['class'] }} @endif">@if(isset($btnvalue['title'])) {{ $btnvalue['title'] }} @else {{ $btnvalue['routeName'] }} @endif</a>
                      @endif
                  @endforeach
                @endif  
                @if($createBtnShow)
	        			  <a href="{{Route( $createRoute )}}">{{__("Create")}}</a>
                @endif
	        		</div>
	        	</div>
        	</div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="datatable2" class="table table-bordered table-hover">
            <thead>
            <tr>
              @if(isset($tableLists) && !empty($tableLists) && count($tableLists) > 0)
                @foreach($tableLists as $listkey => $listvalue)
                  @if(isset($listvalue['title']))
                    <th>{{ $listvalue['title'] }}</th>
                  @else
                    <th><?php echo ucwords(str_replace("_"," ",$listkey)); ?></th>
                  @endif
                @endforeach
                <th>Action</th>
              @else
                @if(isset($fillableLists))
                  @if($isFillable)
                    <?php 
                      echo "<th>".ucwords(str_replace("_", " ",$primaryKey))."</th>";
                    ?>
                  @endif
                  @foreach($fillableLists as $fillableList)
                      <?php 
                      echo "<th>".ucwords(str_replace("_", " ",$fillableList))."</th>";
                      ?>
                  @endforeach
                  <th>Action</th>
                @endif
              @endif
            </tr>
            </thead>
            <tbody>
            	@if(isset($details))
            		@php($i = 1)
            		@foreach($details as $key => $value)
            			<tr>
                    @if(isset($tableLists) && !empty($tableLists) && count($tableLists) > 0)
                        @foreach($tableLists as $listkey => $listvalue)
                          <td>{!! $value->$listkey !!}</td>
                        @endforeach
                        <td>
                          @if($editBtnShow)
                            <a href="{{route($editRoute,$value->$primaryKey)}}" class="btn btn-success">{{__('edit')}}</a>
                          @endif
                          @if($deleteBtnShow)
                            <a href="{{route($deleteRoute,$value->$primaryKey)}}" class="btn btn-danger">
                                {{ __('Delete') }}
                            </a>
                          @endif
                          @if(isset($perRowbtnLists) && count($perRowbtnLists) > 0)
                            @foreach($perRowbtnLists as $perRowbtnList)
                              @if(isset($perRowbtnList['routeName']))
                                <a href="{{route($perRowbtnList['routeName'],$value->$primaryKey)}}" class="btn @if(isset($perRowbtnList['class'])) {{ $perRowbtnList['class'] }} @else btn-success @endif">
                                  @if(isset($perRowbtnList['title']))
                                    {{ $perRowbtnList['title'] }}
                                  @else
                                    {{ $perRowbtnList['routeName'] }}
                                  @endif
                                </a>
                              @endif
                            @endforeach
                          @endif
                        </td>
                    @else
                      @if(isset($fillableLists) && !empty($fillableLists) && count($fillableLists) > 0)
                        @if($isFillable)
                          <td>{!! $value->$primaryKey !!}</td>
                        @endif
                        @foreach($fillableLists as $listkey)
                          <td>{!! $value->$listkey !!}</td>
                        @endforeach
                        <td>
                        @if($editBtnShow)
                          <a href="{{route($editRoute,$value->$primaryKey)}}" class="btn btn-success">{{__('edit')}}</a>
                        @endif
                        @if($deleteBtnShow)
                          <a href="{{route($deleteRoute,$value->$primaryKey)}}" class="btn btn-danger">
                              {{ __('Delete') }}
                          </a>
                        @endif
                        @if(isset($perRowbtnLists) && count($perRowbtnLists) > 0)
                          @foreach($perRowbtnLists as $perRowbtnList)
                            @if(isset($perRowbtnList['routeName']))
                              <a href="{{route($perRowbtnList['routeName'],$value->$primaryKey)}}" class="btn @if(isset($perRowbtnList['class'])) {{ $perRowbtnList['class'] }} @else btn-success @endif">
                                @if(isset($perRowbtnList['title']))
                                  {{ $perRowbtnList['title'] }}
                                @else
                                  {{ $perRowbtnList['routeName'] }}
                                @endif
                              </a>
                            @endif
                          @endforeach
                        @endif
                        </td>
                      @endif
                    @endif
            			</tr>
            		@endforeach
            	@endif
            </tbody>
          </table>
        </div>
        {{ $details->links() }}
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection

@section('script')
  @if($dataTable)
  	<script>
  	  $(function () {
  	    $('#datatable2').DataTable({
  	      "paging": true,
  	      "lengthChange": false,
  	      "searching": false,
  	      "ordering": true,
  	      "info": true,
  	      "autoWidth": false,
  	    });
  	  });
  	</script>
  @endif
@endsection