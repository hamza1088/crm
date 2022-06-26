@extends('my_layout.header')
 
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('employee_create') }}"> Create new Employee</a>
            </div>
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="section mt-5">
    <table class="table table-bordered"  id="employee_listing">
    <thead>
        <tr>
            <th>No</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Company</th>
            <th width="250px">Actions</th>
        </tr>
</thead>
       
    </table>
    </div>

    <script>
$(document).ready(function() {
    console.log('data-table');
    var APP_URL = {!! json_encode(url('/')) !!}

    console.log(APP_URL + "/employee_list");
    function fill_datatable(group_id, device_type, device_state){
        table = $('#employee_listing').DataTable({
            "processing":true,
            //"serverSide":true,
            "ajax": {
                'url':APP_URL + "/employee_list",
                'type':"GET",
                "data": function ( d ) {
                }
            }, 
            "dataSrc": "",
            "order": [[ 0, "asc" ]],
            "pagingType": "simple_numbers",
            "columns": [
                { "data": "sno", "name":"sno","orderable": false, "searchable": false },
                { "data": "first_name", "name":"first_name" },
                { "data": "last_name", "name":"last_name" },
                { "data": "email", "name":"email" },
                { "data": "phone", "name":"phone" },
                { "data": "company", "name":"company" },
                { "data": null, "name":"action", className: "action", "orderable": false, "searchable": false,
                    "render": function ( d ) {  
                        
                $html= '<a href="' + APP_URL +  "/employee_edit/" +  d.id + '" class="btn btn-info btn-sm">Edit</a>';
                $html += '<button class="btn btn-danger btn-sm ml-2 item-delete1">Delete</button>';
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

    $('#employee_listing').on( 'click', '.item-delete1', function () {
        var data = table.row( $(this).parents('tr') ).data();
        id = data["id"];
        console.log(id);
        status = data["status"];
        $.ajax({
                 url: APP_URL + "/employee_delete/"+id,
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
</div>
@endsection
