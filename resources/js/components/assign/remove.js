import React, {useState, useEffect} from 'react'
import axios from 'axios'
import { Link } from 'react-router-dom';
import swal from 'sweetalert';
import { Button, Modal } from 'react-bootstrap'

function remove() {
    const[list, setList]= useState([])

    useEffect(()=>{
        axios.get('/assigned-entity-tracking-form').then(res => {
            setList(res.data.forms)
            if (list) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        })
    },[])

    const removeAssign = (event, formId,assignedId) => {
        event.preventDefault()
        swal({
            title: "Warning!",
            text: "Are you sure to remove Entity Tracking form?",
            icon: "warning",
            dangerMode: true,
        })
        .then(res => {
            if (res) {
                axios.delete(`/remove-entity-tracking-form/${formId}/${assignedId}`)
                .then(res => {
                    const { message } = res.data
                    swal("Success!", message, "success")
                    setTimeout(() => {
                        window.location.replace('/assign-entities-form/remove')
                    }, 3000);
                })
                .catch(error => {
                    const { message } = error.response.data
                    swal("Warning!", message, "error")
                })
            }
        });
    }
    return (
        <>
            <div className="app-page-title">
                <div className="page-title-wrapper">
                    <div className="page-title-heading">
                        <div className="page-title-icon">
                            <i className="fa fa-user-o"></i>
                        </div>
                        <div>
                            Assigned Entity Tracking Forms
                        </div>
                    </div>
                </div>
            </div>
            <div className="main-card mb-2 card">
                <div className='card-header'>
                    <div className='card-title'>
                        List of Assigned Entity Tracking Forms
                    </div>
                    <div className="btn-wrapper btn-wrapper-multiple">
                        <button type="button" className="btn btn-sm btn-success">
                            <Link id="link_page" to={'/assign-entities-form'}>
                                Assign Entity Tracking Form 
                            </Link>
                        </button>
                    </div>
                </div>
                <div className="card-body">
                <table className="table table-striped table-bordered dataTableReact">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Report Form Title</th>
                                <th>Assigned Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {
                                list.length > 0 &&
                                list.map((form,index) => {
                                    return <tr key={index}>
                                        <td>{index+1}</td>
                                        <td>{form.form_title}</td>
                                        <td>{form.assigned}</td>
                                        <td>
                                            <button type="button" className="btn btn-sm btn-danger"
                                                onClick={ e=>removeAssign(e,form.form_id,form.assigned_id)}>
                                                <i title="Remove" className="fa fa-remove"></i>
                                            </button>
                                        </td>
                                    </tr>
                                })
                            }
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    )
}

export default remove
