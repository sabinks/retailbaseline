import React, { Component } from 'react';
import axios from 'axios'
import { Link } from 'react-router-dom'
import swal from 'sweetalert';
class FormsList extends Component {
    constructor () {
        super()
        this.state = {
            forms: null,
        }
        this.handleDeleteFormData= this.handleDeleteFormData.bind(this)
    }

    handleDeleteFormData (event, formId){
        event.preventDefault()

        swal({
            title: "Warning!",
            text: "Sure delete form?",
            icon: "warning",
            dangerMode: true,
        })
            .then(res => {
                if (res) {
                    axios.delete(`/forms/${formId}`)
                    .then(response => {
                      // redirect to the homepage
                      // let refinedFormDatas = formDatas.filter((formData) => {
                      //     return formDataId !== formData.id;
                      // });
 
                      // this.setState({
                      //     formDatas: refinedFormDatas
                      // });
                      window.location.reload();
                    })
                    .catch(error => {
                      console.log(error.response)
                    })
                }
            });
     

    }

    async componentDidMount(){
        try {
            const forms = await axios.get('/forms')
            if (forms) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                this.setState({
                    forms: forms.data.forms
                });
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        } catch (error) {
            console.log(error)
        }
        
    }

    render () {
        const { forms} = this.state
        return (
        <>
        <div className="main-card mb-3 card">
            <div class='card-header'>
                <div class='card-title'>
                    View, add, edit or delete the Forms Listed
                </div>
                <div class="btn-wrapper btn-sm btn-wrapper-multiple">
                    {forms && <><Link className='btn btn-primary btn-sm mr-3' to={'/dynamic-form/create'}>
                            Create New Form
                        </Link>
                    </>}
                </div>
            </div>
            <div className="card-body">
                {/* {forms && <><Link className='btn btn-primary btn-sm mr-3' to={'/dynamic-form/create'}>
                        Create New Form
                    </Link>
                    </>} */}
                <div className="table-responsive">
                    {forms? <table className="table table-striped table-bordered dataTableReact">
                        <thead>
                            <tr>
                                <th>Id</th> 
                                <th>Creator Name</th> 
                                <th style={{width: '30%'}}>Staffs Associated</th>
                                <th>Form Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        {forms.map( (form, index) => 
                            <tr key={index}>
                                <td>{index+1} </td>
                                <td>{form.form_creator.name} </td>
                                <td>
                        
                                    <div className="badge-group">
                                        {form.staffs.map((staff, index)=><span key={index} className="badge badge-pill badge-primary">{staff.name}</span>)}
                                        
                                    </div>
                                </td>
                                <td>{form.form_title} </td>
                                <td>
                                    <div className='btn-group'>
                                        {/* <a href="#!" className='btn btn-primary btn-sm'>
                                            <i className="fa fa-eye"></i>
                                        </a> */}
                                        {(!form.staffs.length>0&&!form.form_datas.length>0) && 
                                            <Link className='btn btn-secondary btn-sm' to={`/dynamic-form/${form.id}`}>
                                                <i className="fa fa-pencil"></i>
                                            </Link>
                                        }
                                        <Link className='btn btn-info btn-sm' to={`/dynamic-form/${form.id}/assign`}>
                                            <i className="fa fa-address-book"></i>
                                        </Link>
                                        <a className='btn btn-success btn-sm' href={`/dynamic-form/${form.id}/form-data`}>
                                            <i className="fa fa-plus"></i>
                                        </a>
                                        {(!form.staffs.length>0&&!form.form_datas.length>0) && <a href="#!" onClick={(e) => this.handleDeleteFormData(e, form.id)} className='btn btn-danger btn-sm'>
                                            <i className="fa fa-trash-o"></i>
                                        </a>}
                                    </div>
                                </td>
                            </tr>
                        )}
                        </tbody>
                    </table>:
                    <table className="table table-striped table-bordered dataTable">
                    <thead>
                        <tr>
                            <th>Creator Name</th> 
                            <th style={{width: '30%'}}>Staffs Associated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    </table>
                    }
                </div>
            </div>
        </div>
        </>
        )
    }
}

export default FormsList