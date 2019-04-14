<!DOCTYPE html>
<html lang="en">
@include('fragments.header')
<body>
@include('fragments.nav')
<!-- Page Content -->
<div class="container">
    <div class="row">
        @yield('content')
    </div>
    <!-- /.row -->
</div>
<!-- /.container -->
@include('fragments.footer')
<script src="js/app.js"></script>
</body>
</html>
