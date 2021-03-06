@extends('admin.layouts')
@section('content');

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Добавить тег
            <small>приятные слова..</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Добавляем тег</h3>
            </div>
            {{ Form::open(['route' => 'tags.store']) }}
            <div class="box-body">
                @include('admin.error')
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="exampleInputEmail1">Название</label>
                        <input  name="title" type="text" class="form-control" id="exampleInputEmail1" placeholder="">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <a href="{{route('tags.index')}}" class="btn btn-default">Назад</a>
                <button class="btn btn-success pull-right">Добавить</button>
            </div>
            <!-- /.box-footer-->

            {{ Form::close() }}
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection