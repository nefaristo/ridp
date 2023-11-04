@extends('layouts.main')

@section('title', trans("rdp.queries"))

@section('content' )
    @if(\Auth::user()->privilege>110)
        <div class="section" style="text-align:center">{{trans("rdp.queries")}}</div>
        <table style="margin:0px auto">
            <thead style="border-bottom: 1px solid grey"><tr>
                <td>{{trans("rdp.name")}}</td>    
                <td>{{trans("rdp.description")}}</td>
                <td></td>
            </tr></thead>
            <tbody style="border-bottom: 1px solid grey">
            @foreach($models as $model)
                <tr>
                    <td style="padding:5px;padding-right:10px;">
                        <b><a href="{{url("queries/edit/".$model->id)}}">{!!$model->name!!}</a></b>
                    </td>
                    <td style="padding:5px;padding-right:10px;">
                        {{App\SVLibs\utils::trimText($model->description,90)}}
                    </td>                
                    <td>
                        <a id='download_button' href="{{url("queries/download/".$model->id)}}" target="_blank">{{trans('rdp.query_result_download')}}</a>
                    </td>
                </tr>           
            @endforeach
            </tbody>
            <thead style="border-bottom: 1px solid grey"><tr>
                <td>
                    <a href="{{url("queries/edit/")}}">{!!trans("rdp.add")!!}</a>
                </td>    
                <td></td>
                <td></td>
            </tr></thead>            
        </table>
    @else
        <div class="section" style="text-align:center"><br>Le interrogazioni sono temporaneamentee disabilitate.</div> 
    @endif    
@endsection