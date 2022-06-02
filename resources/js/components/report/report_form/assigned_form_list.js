import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { Link } from 'react-router-dom';

function assigned_form_list() {
    const[list, setList]= useState([])

    useEffect(()=>{
        axios.get('/report-form-assign-list').then(res => {
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

    return (
        <>
            <div className="app-page-title">
                <div className="page-title-wrapper">
                    <div className="page-title-heading">
                        <div className="page-title-icon">
                            <i className="fa fa-user-o"></i>
                        </div>
                        <div>
                           Regular Report Forms
                        </div>
                    </div>
                </div>
            </div>
            <div className="main-card mb-2 card">
                <div className='card-header'>
                    <div className='card-title'>
                        Report Forms Assigned to you
                    </div>
                </div>
                <div className="card-body">
                <table className="table table-striped table-bordered dataTableReact">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Report Form Title</th>
                                <th>Assigner Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {
                                list.length > 0 &&
                                list.map((form,index) => {
                                    return <tr key={index}>
                                        <td>{index+1}</td>
                                        <td>{form.title}</td>
                                        <td>{form.assigner}</td>
                                        <td>
                                            <button type="button" className="btn btn-sm btn-success">
                                                <Link id="link_page" to={`/report-form/${form.id}`}>
                                                <i className="fa fa-pencil"></i>
                                                </Link>
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

export default assigned_form_list
