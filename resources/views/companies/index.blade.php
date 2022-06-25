@extends('companies.layout')
 
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('create') }}"> Create new company</a>
            </div>
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
   
    <table class="table table-bordered mt-3"  id="company_listing">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>email</th>
            <th>Website</th>
            <th width="250px">Action</th>
        </tr>
</thead>
       
    </table>
    <script>
$(document).ready(function() {
    console.log('data-table');
    var APP_URL = {!! json_encode(url('/')) !!}

    console.log(APP_URL + "/company_list");
    function fill_datatable(group_id, device_type, device_state){
        table = $('#company_listing').DataTable({
            "processing":true,
            //"serverSide":true,
            "ajax": {
                'url':APP_URL + "/company_list",
                'type':"GET",
                "data": function ( d ) {
                }
            }, 
            "dataSrc": "",
            "order": [[ 0, "asc" ]],
            "pagingType": "simple_numbers",
            "columns": [
                { "data": "sno", "name":"sno","orderable": false, "searchable": false },
                { "data": "name", "name":"name" },
                { "data": "email", "name":"email" },
                { "data": "website", "name":"website" },
                { "data": null, "name":"action", className: "action", "orderable": false, "searchable": false,
                    "render": function ( d ) {  
                        
                $html= '<a href="' + APP_URL + "/companies/" + d.id + "/edit" + '" class="btn btn-info btn-sm">Edit</a>';
                $html += '<button class="btn btn-danger btn-sm item-delete">Delete</button>';
                return $html;

            }
            }],
            "initComplete":function(){
                $( table.table().container() ).removeClass( 'form-inline' );
                $('.dataTable [data-toggle="popover"]').popover();
            }
        });
        
        
    }
    fill_datatable();

    $('#company_listing').on( 'click', '.item-delete', function () {
        var data = table.row( $(this).parents('tr') ).data();
        id = data["id"];
        console.log(id);
        status = data["status"];
        $.ajax({
                 url: APP_URL + "/company_delete/"+id,
                 type:  'GET' ,
                
                 async: true,
                 context: document.body,
                 error: function() {
                     console.log();
                 },
                 success: function(){
                    table.ajax.reload();
                 }
             });
    });
} );
</script>
      
@endsection
