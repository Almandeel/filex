@extends('layouts.dashboard.app', ['modal' => ['cheque']])

@section('title')
    عرض
@endsection

@section('content')
    @component('partials._breadcrumb')
        @slot('title', ['الشيكات المصرفية','عرض'])
        @slot('url', [route('cheques.index'), '#'])
        @slot('icon', ['users', 'eye'])
    @endcomponent
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3>{{ $cheque->number }}</h3>
                </div>
            </div>
        </div>
    </div>
<form action="{{ route('cheques.destroy', $cheque->id) }}" method="POST">
        @csrf
        {{ method_field('DELETE') }}
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header">
                        <table class="table table-borderd">
                            <tr>
                                <th>رقم الشيك</th>
                                <td>{{ $cheque->number }}</td>
                            </tr>
                            <tr>
                                <th>القمية</th>
                                <td>{{ $cheque->amount }}</td>
                            </tr>
                            <tr>
                                <th>الحالة</th>
                                <td>{{ $cheque->getStatus() }}</td>
                            </tr>
                            <tr>
                                <th>تاريخ الإستحقاق</th>
                                <td>{{ $cheque->due_date }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-footer">
                        @if(request()->delete)
                            @permission('cheques-delete')
                                <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> حذف </button>
                            @endpermission
                        @else
                            @permission('cheques-update')
                                <a class="btn btn-warning btn-xs showChequeModal update"
                                    data-action="{{ route('cheques.update', $cheque->id) }}"
                                    data-id="{{ $cheque->id }}"
                                    data-number="{{ $cheque->number }}"
                                    data-type="{{ $cheque->type }}"
                                    data-amount="{{ $cheque->amount }}"
                                    data-due_date="{{ $cheque->due_date }}"
                                ><i class="fa fa-edit"></i> تعديل </a>
                            @endpermission
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection