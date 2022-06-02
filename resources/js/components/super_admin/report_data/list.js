import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { useParams } from "react-router-dom";
import swal from 'sweetalert';
import Index from '../reportRoute'
const ReportListing = () => {
    let params = useParams();
    let { id } = params
    const [state, setState] = useState({
        report_list: [],
        role: null,
        form_id: id ? id : 0
    })

    const [status, setStatus] = useState('all')
    useEffect(() => {
        window.reactDeleteReportData = ($report_id) => {
            axios.delete(`/superadmin/report-data/${$report_id}`)
                .then(res => {
                    const { message } = res.data
                    swal("Warning!", message, "success")
                }).catch(error => {
                    const { message } = error.response.data
                    swal("Warning!", message, "error")
                })
        }
    }, [])
    useEffect(() => {
        if(id == undefined){
            setState(prev => ({
                ...prev, form_id : 0
            }))
        }else{
            setState(prev => ({
                ...prev, form_id : id
            }))
        }
    }, [id])
    useEffect(() => {
        let data_table
        if ($.fn.dataTable.isDataTable('.dataTableReact')) {
            let data_table = $('.dataTableReact').DataTable().destroy();
        }
        data_table = $('.dataTableReact').DataTable({
            "lengthChange": false,
            "order": [
                [0, "desc"]
            ],
            "pageLength": 10,
            "autoWidth": false,
            processing: true,
            serverSide: true,
            async: true,
            "ajax": {
                url: `/report-list/${status}/${state.form_id}`,
                method: "GET",
                error: function (xhr, error, code) {
                    data_table.ajax.reload(null, false)
                }
            },
            "columns": [
                { data: "title" },
                { data: "entity_name" },
                { data: "assigned_staff" },
                { data: "assigned_date" },
                { data: "filled_date" },
                { data: "status" },
                { data: "options" }
            ]
        })
        $('.dataTables_wrapper .row:first-child>div:first-child').removeClass()
        $('.dataTables_filter').css("float", "left")
        data_table.draw()
    }, [status, state.form_id])

    const handleBulkApprove = () => {
        axios.get(`/superadmin/report-data-bulk-approve/${state.form_id}`).then(res => {
            const { role, message } = res.data
            setState(prev => ({ ...prev, role, message }))
            swal("Success!", message, "success")
        }).catch(error => {
            const { message } = error.response.data
            swal("warning!", message, "error")
        })
    }
   

    return (
        <>
            <div className="main-card card">
                <div className="card-header">
                    <div className='card-title'>
                        Report Listing
                    </div>
                    {/* <Index /> */}
                </div>

                <div className="card-body pb-0">
                    <button className="btn btn-sm btn-info mr-1" onClick={e => setStatus('all')}>All</button>
                    <button className="btn btn-sm btn-secondary mr-1" onClick={e => setStatus('assigned')}>Assigned</button>
                    <button className="btn btn-sm btn-primary mr-1" onClick={e => setStatus('pending')}>Pending</button>
                    <button className="btn btn-sm btn-success mr-1" onClick={e => setStatus('approved')}>Approved</button>
                    <button className="btn btn-sm btn-danger mr-1" onClick={e => setStatus('rejected')}>Rejected</button>
                    <button className="btn btn-sm btn-danger mr-1 float-right" onClick={handleBulkApprove}>Bulk Approve</button>
                </div>

                <div className="card-body">
                    <div className="table">
                        <table className="table table-striped table-bordered dataTableReact">
                            <thead>
                                <tr>
                                    <th>Report Title</th>
                                    <th>Entity Name</th>
                                    <th>Assigned Staff</th>
                                    <th>Assigned Date</th>
                                    <th>Filled Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </>
    )
}

export default ReportListing