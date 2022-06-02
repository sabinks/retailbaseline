import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { Link } from 'react-router-dom'
import { dateConversion } from '../../../utils/functions';
function AssignedFormViewList() {
    const [state, setState] = useState({
        report_forms: [],
    })
    useEffect(() => {
        let id = window.location.pathname.replace('/client/report-form/', '')
        axios.get(`/clients/report-form/${id}`).then(res => {
            const { report_list } = res.data
            if (report_list) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setState(prev => ({ ...prev, report_list, loading: false }))
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        })
    }, [])

    return (
        <>
            <div className="main-card card mb-1">
                <div className="card-header">
                    <div className='card-title'>
                        Report Listing
                    </div>
                    {/* <Index /> */}
                </div>
                <div className="card-body">
                    <div className="table">
                        <table className="table table-striped table-bordered dataTableReact">
                            <thead>
                                <tr>
                                    <th>Entity Name</th>
                                    <th>Report Title</th>
                                    <th>Assigned Staff</th>
                                    <th>Filled Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {
                                    state.report_list && state.report_list.map((report, index) => {
                                        return <tr key={index}>
                                            <td>{report.entities.name}</td>
                                            <td>{report.report.title}</td>
                                            <td>{report.staff_detail.name}</td>
                                            <td>{dateConversion(report.filled_date)}</td>
                                            <td><Link className='btn btn-secondary btn-sm mr-1' to={`/report-info/detail/${report.id}`}><i className="fa fa-eye"></i></Link></td>
                                        </tr>
                                    })}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </>
    )
}

export default AssignedFormViewList
