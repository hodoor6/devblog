@extends('admin.layouts')
@section('content');
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Добавить пользователя
            <small>приятные слова..</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Добавляем пользователя</h3>
            </div>
            <div class="box-body">

                {{ Form::open(['route' => ['users.store'] ,'files' => true]) }}

{{--                <form method="POST" action="{{route('users.store')}}" enctype="multipart/form-data" >--}}
                    {{ csrf_field() }}
                @include('admin.error')
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Имя</label>
                        <input name="name" type="text" class="form-control" id="exampleInputEmail1" placeholder=""
                        value="{{old('name')}}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">E-mail</label>
                        <input name="email" type="text" class="form-control" id="exampleInputEmail1" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Пароль</label>
                        <input name="password" type="password" class="form-control" id="exampleInputEmail1" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Аватар</label>
                        <input name="avatar" type="file" id="exampleInputFile">

{{--                      {{  Form::file('photo')}}--}}
                        <p class="help-block">Какое-нибудь уведомление о форматах..</p>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <a href="{{route('users.index')}}" class="btn btn-default">Назад</a>
                <button class="btn btn-success pull-right">Добавить</button>
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
{{--        </form>--}}
        {{ Form::close() }}
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


@endsection