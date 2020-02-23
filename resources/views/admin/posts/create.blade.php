@extends('admin.layouts')
@section('content');
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Добавить статью
            <small>приятные слова..</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Добавляем статью</h3>
            </div>
            <div class="box-body">

                {{ Form::open(['route' => ['posts.store'] ,'files' => true]) }}

                           {{ csrf_field() }}
                @include('admin.error')
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Название</label>
                        <input name="title" type="text" class="form-control" id="exampleInputEmail1" placeholder="" value="{{old('title')}}">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile">Лицевая картинка</label>
                        <input name="image" type="file" id="exampleInputFile">

                        <p class="help-block">Какое-нибудь уведомление о форматах..</p>
                    </div>
                    <div class="form-group">
                        <label>Категория</label>

                       {{ Form::select('category_id', $categories, null, ['class'=>"form-control select2", 'placeholder' => 'Выберете категорию...'])}}
                    </div>

                    <div class="form-group">
                        <label>Теги</label>
                        {{ Form::select('tags[]',$tags, null, [  'multiple'=>'multiple','class'=>"form-control select2", 'data-placeholder' => 'Выберете теги...'])}}

                    </div>
                    <!-- Date -->
                    <div class="form-group">
                        <label>Дата:</label>

                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="date" type="text" class="form-control pull-right" id="datepicker" {{old('date')}}>
                        </div>
                        <!-- /.input group -->
                    </div>

                    <!-- checkbox -->
                    <div class="form-group">
                        <label>
                            <input name="status" type="checkbox" class="minimal">
                        </label>
                        <label>
                            Рекомендовать
                        </label>
                    </div>

                    <!-- checkbox -->
                    <div class="form-group">
                        <label>
                            <input name="is_featured" type="checkbox" class="minimal">
                        </label>
                        <label>
                            Черновик
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Полный текст</label>
                        <textarea name="content" cols="30" rows="10" class="form-control">{{old('content')}}</textarea>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <a class="btn btn-default" href="{{route('posts.index')}}">
                Назад
                </a>
                <button class="btn btn-success pull-right">Добавить</button>
            </div>
        {{ Form::close() }}
               <!-- /.box-footer-->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection