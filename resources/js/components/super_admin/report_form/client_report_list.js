import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { Link } from 'react-router-dom';
import Select from "react-select";
import { useHistory } from "react-router-dom";
import Index from '../reportRoute';

const ClientReportList = () => {
    let history = useHistory();
    const [state, setState] = useState({
        loading: true,
    })
    const [reports, setReports] = useState([])
    const [clients, setClients] = useState([])
    useEffect(() => {
        axios.get('/superadmin/client-report-view').then(res => {
            const { reports, assigned_client } = res.data
            if (assigned_client) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setClients(assigned_client)
                setReports(reports)
                setState(prev => ({ ...prev, loading: false }))
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        })
    }, [])

    const handleReportChange = (new_reports, client_id) => {
        let new_list = []
        clients.map(client => {
            if (client.id == parseInt(client_id)) {
                new_list.push({ ...client, report_form: new_reports ? [...new_reports] : [] })
            } else {
                new_list.push(client)
            }
        })
        const inputs = {
            report_ids: new_reports ? JSON.stringify(new_reports.map(report => report.id)) : JSON.stringify([])
        }
        axios.post(`/superadmin/assign-report-client/${client_id}`, inputs).then(res => {
            setClients(new_list)
        }).catch()
    }

    return (
        <>
            <div className="main-card card">
                <div className='card-header'>
                    <div className='card-title'>
                        Assign Report Form To Client Company
                    </div>
                    {/* <Index /> */}
                </div>
                <div className="card-body">

                    <div className="table">
                        {
                            !state.loading &&
                            <table className="table table-striped table-bordered dataTableReact">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Report Form View Access</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        clients && clients.map((report, index) => {
                                            return <tr key={index}>
                                                <td>{report.name}</td>
                                                <td>
                                                    <Select
                                                        name={report.id}
                                                        placeholder="Select Report Form"
                                                        value={report.report_form}
                                                        options={reports}
                                                        onChange={e => handleReportChange(e, report.id)}
                                                        isMulti
                                                    />
                                                </td>
                                            </tr>
                                        })}
                                </tbody>
                            </table>
                        }
                    </div>
                </div>
            </div>
        </>
    )
}

export default ClientReportList
