<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>{{ isset($title) ? $title : config('app.name')}}</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('media/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- IonIcons -->
  <link rel="stylesheet" href="{{ asset('media/css/ionicons.min.css') }}">
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('media/css/adminlte.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="{{ asset('media/plugins/jquery/jquery.min.js') }}"></script>
  <script type="text/javascript">
      var baseURL = '{{ url("/") }}';
  </script>
  <style type="text/css">
    .has-error .help-block, .help-block{
      color:red;
      padding-top: 10px;
    }
    .toggle.radius, .toggle-on.radius, .toggle-off.radius { border-radius: 20px; }
    .toggle.radius .toggle-handle { border-radius: 20px; }
  </style>
  @yield('style')
  @yield('js')
  @yield('stylesheet')
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to to the body tag
to get the desired effect
|---------------------------------------------------------|
|LAYOUT OPTIONS | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition sidebar-mini">
<div class="wrapper">
	<!-- Navbar -->
	@include('admintemplate::admin.partials.navbar')
	<!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('admintemplate::admin.partials.sidebar')
  <!-- / .Main Sidebar Container -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    	@include('admintemplate::admin.partials.content_header')
    <!-- /.content-header -->

    <!-- Main content -->
    @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  @include('admintemplate::admin.partials.footer')
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('media/plugins/jquery/jquery.min.js') }}"></script>
@yield('prescript')
<!-- Bootstrap -->
<script src="{{ asset('media/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{ asset('media/js/adminlte.js') }}"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{ asset('media/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('media/js/demo.js') }}"></script>
<script src="{{ asset('media/js/pages/dashboard3.js') }}"></script>
<script src="{{ asset('media/js/custom.js') }}"></script>
@yield('script')
<script type="text/javascript">
  function required() {
    var count = 0;
    var result = 0;
    $('form .required').each(function(event){
      var _name = $(this).prop("name").split('[]').join("");
      if(! $(this).val()) {
        $(".span"+_name).remove();
        var html = "<span class='help-block span"+_name+"'>";
            html += "<strong>";
            html += "The "+_name+" field is required.";
            html += "</strong>";
            html += "</span>";
        $(this).parent().addClass('has-error');
        $(this).parent().append(html);
      }else{
        $(this).parent().removeClass("has-error");
          $(".span"+_name).remove();
          result++
      }
      count++;
    });

    if(count == result) {
      var previous = "<input type='hidden' name='previous_url' value='{{url()->current()}}'>"
      $('form').append(previous);
      return true;
    }
    event.preventDefault();
  }
</script>
</body>
</html>
