@extends('layouts.myapp')
@section('title','Entities Tracking Form')
@section('content')
<!-- <div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-user-o"></i>
            </div>
            <div>
               Entities Tracking Form
            </div>
        </div>
    </div>
</div> -->
<div id="assign-form">
    <div class="main-card mb-3 card">
        <div class='card-header'>
            Assign {{$data['form']->form_title}} To staffs <br/>
        </div>
        <div class="alert alert-secondary" role="alert">
            <span>Note: Select/Unselect field staff to assign entity form.</span>
        </div>
        @if($errors->any())
        <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
        @endif
        <div class="card-body">
        <form method="post" action={{url('entities-forms/'.$data['form']->id.'/assign')}} enctype="multipart/form-data" >
            @csrf
        <table class="table table-striped table-bordered assignEntitiesStaffDataTable">
                <thead>
                    <tr>
                        <th style="width: 10%;">
                            Un/Select Field Staff
                            {{-- <input name="select_all" value="1" class="select-checkbox" id="example-select-all" type="checkbox" /> --}}
                        </th>
                        <th style="width: 20%;">Field Staff Name</th> 
                        <th style="width: 55%;">Tasks Assigned</th>
                        <!-- <th style="width: 15%;">No. of Entity Visit</th> -->
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
                {{-- 
                {fieldStaffs.map( (fieldStaff, findex) => 
                    <tr key={findex}>
                        <td>{fieldStaff.id} </td>
                        <td>{fieldStaff.name} </td>
                        <td>
                
                            <div class="badge-group">
                                {fieldStaff.assigned_entities_forms.map((assignedform, aeindex)=>
                                    <span key={aeindex} class="badge badge-pill badge-primary">{ `${assignedform.form_title} entity form created by ${assignedform.form_creator.name} assigned by ${formAssigner[findex][aeindex].name}`}</span>
                                )}
                                {}
                            </div>
                        </td>
                        <td>
                        {this.renderErrorFor('staff_id')}
                        <input type="checkbox" onChange={(e)=>this.handleFieldChange(e,findex)} name="staff_id" value={fieldStaff.id} checked={ isChecked[findex]  }/>
                        </td>
                    </tr>
                )}
                </tbody> --}}
            </table>
                
            <div class='row'>
                <div class='form-group col-12 mb-0'>
                    <button  type="submit" class="btn btn-sm btn-success mr-3">Assign Staff(s)</button>
                        <a class='btn btn-secondary mr-3' href="{{url('/entities-form/')}}">
                            Cancel
                        </a>
                </div>
            </div>
        </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
    <script >
        $(document).ready(function() {
            var data = {!! json_encode($data, JSON_HEX_TAG) !!};
            var filteredData = (data) => {
                        var return_data = new Array();
                        data.fieldStaffs.map((fieldStaff, findex)=>{
                            let tasksAssignedBadgeHtml = '<div class="badge-group">';
                            fieldStaff.assigned_entities_forms.map((assignedform, aeindex)=>{
                                // tasksAssignedBadgeHtml += `<span class="badge badge-pill badge-primary"> <b>${assignedform.form_title} </b> entity tracking form created by '${assignedform.form_creator.companies[0].company_name}'<br/> for <em><b>${data.formClientName[findex][aeindex].company_name}</b><br></em> with ${data.formEntityVisitCount[findex][aeindex]} entity visit </span>`;
                                tasksAssignedBadgeHtml += `<span class="badge badge-pill badge-primary"> <b>${assignedform.form_title} </b> entity tracking form created by '${assignedform.form_creator.companies[0].company_name}' for <em><b>${data.formClientName[findex][aeindex].company_name}</b><br></em></span>`;
                            });
                            // fieldStaff.assigned_forms.map((assignedform, aindex)=>{
                            //     tasksAssignedBadgeHtml += `<span class="badge badge-pill badge-primary">${assignedform.form_title} form created by ${assignedform.form_creator.name} assigned by ${normalFormAssigner[findex][aindex].name}</span>`
                            // });
                            tasksAssignedBadgeHtml += `</div>`;
                            let actionOnStaff = `
                                <div class='form-group col-lg-6 col-sm-6 col-12'>
                                    <label for="entity_visit_count"></label>
                                    <input type="number" class="form-control" style="width: 250%;" min=1 id="staff_id-${fieldStaff.id}-entity_visit_count" 
                                    value="1" id="entity_visit_count">
                                </div>`;
                            return_data.push([
                                 fieldStaff.id,
                                 fieldStaff.name,
                                 tasksAssignedBadgeHtml,
                                 actionOnStaff
                            ]);
                        });
                        
                        return return_data;
                    };
            var filteredDataValue=filteredData(data);
            table = $('.assignEntitiesStaffDataTable').DataTable({
                'data': filteredDataValue,
                'columnDefs': [{
                    'targets': 0,
                    'searchable':false,
                    'orderable':false,
                    // 'className' : 'select-checkbox',
                    'render': function (data, type, full, meta){
                        return '<input type="checkbox" class="select-checkbox" name="staff_ids[]" value="' 
                            + $('<div/>').text(data).html() + '">';
                        }
                }],
                // 'select': {
                //     'style':    'multi',
                //     'selector': 'td:first-child'
                // },
                'order': [1, 'asc'],
                "createdRow": function(row, data, dataIndex){
                    $(row).attr("id", "tblRow_" + data[0]);
                },
            });
             // Handle click on "Select all" control
            // $('#example-select-all').on('click', function(){
                // Check/uncheck all checkboxes in the table
                // var rows = table.rows({ 'search': 'applied' }).nodes();
                // $('input[type="checkbox"]', rows).prop('checked', this.checked);
            // });

            // Handle click on checkbox to set state of "Select all" control
            // $('.assignEntitiesStaffDataTable tbody').on('change', 'input[type="checkbox"]', function(){
                // If checkbox is not checked
                // if(!this.checked){
                    // var el = $('#example-select-all').get(0);
                    // If "Select all" control is checked and has 'indeterminate' property
                    // if(el && el.checked && ('indeterminate' in el)){
                        // Set visual state of "Select all" control 
                        // as 'indeterminate'
                        // el.indeterminate = true;
                    // }
                // }
            // });
            
            var selectedStaffsId = data.assignedStaffsId;
            var selectedStaffsEntityVisitCount = data.assignedStaffsEntityVisitCount;
            $('input[name^=staff_ids]').each(function(){
                let inputCheckboxValue = $(this).val();
                let selectedStaffId = selectedStaffsId.find( selectedStaffId =>  selectedStaffId==inputCheckboxValue );
                if ( selectedStaffId ){
                    $(this).prop("checked", true);
                    let entityViCoInput = $(`#staff_id-${selectedStaffId}-entity_visit_count`);
                    entityViCoInput.attr( 'name', `staff_id[${selectedStaffId}][entity_visit_count]` )
                    .val(selectedStaffsEntityVisitCount[selectedStaffId]);
                }
            });
            $('input[name^=staff_ids]').click( function (event){
                let staffId = event.target.value;
                let checked = event.target.checked;
              
                if (checked) {
                    $(`#staff_id-${staffId}-entity_visit_count`).attr( 'name', `staff_id[${staffId}][entity_visit_count]` );

                }else{
                    $(`#staff_id-${staffId}-entity_visit_count`).removeAttr( 'name');

                }
            });
        });
    </script>
@endpush