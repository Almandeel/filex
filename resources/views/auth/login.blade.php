<!DOCTYPE html>
<html dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> دخول</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap.min.css') }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('dashboard/css/font-awesome.min.css')}}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{ asset('dashboard/css/ionicons.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('dashboard/css/AdminLTE.min.css')}}">
        <!-- iCheck -->
        {{-- <link rel="stylesheet" href="{{ asset('dashboard/plugins/iCheck/square/blue.css')}}"> --}}
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <style>
            html,body {
                height:auto;
            }
            .login-page, .register-page {
                background-image: url('dashboard/img/background.jpg');
                background-size: cover;
                color: #fff
            }
            .login-box-body, .register-box-body {
                background: #b0e0ef;
                border: 3px solid #fff;
                border-radius: 10px;
            }
            .login-logo a{
                color: #6bcadc !important
            }
            .btn-default {
                background-color: #fff;
                color: #444;
                border-color: #fff;
            }
            .btn-default:hover {
                background-color: #fff !important;
            }
        </style>
    </head>
    <body class="hold-transition login-page">
            <div class="login-box">
                <div class="login-logo">
                    <a href="{{ url('/') }}"><b>{{ env('APP_NAME') }}</b></a>
                </div>
                <div class="login-box-body">
                    <form  method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group has-feedback">
                            <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" placeholder="اسم المستخدم" required>
                            <span class="fa fa-user form-control-feedback"></span>
                            @if ($errors->has('username'))
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="كلمة المرور" required min="6">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        {{-- <div class="form-group">
                            <label for="remember">تذكرني؟</label>
                            <input class="flat-red" name="remember" id="remember" type="checkbox" value="on">
                        </div> --}}
                            <button type="submit" class="btn btn-default btn-block btn-flat btn-block">دخول</button>
                    </form>

                </div>

            </div>

            <!-- jQuery 3 -->
            <script src="{{ asset('dashboard/js/jquery.min.js') }}"></script>
            <!-- Bootstrap 3.3.7 -->
            <script src="{{ asset('dashboard/js/bootstrap.min.js')}}"></script>
    </body>
</html>

