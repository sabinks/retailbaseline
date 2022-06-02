import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { Link } from 'react-router-dom';
import swal from 'sweetalert';
import Select from "react-select";
import { useHistory } from "react-router-dom";
import { Button, Modal } from 'react-bootstrap'
import Index from '../reportRoute';

const ClientReportList = () => {
    let history = useHistory();
    const [state, setState] = useState({
        reports: null,
        role: null,
        staff_id: '',
        entitygroup_id: '',
        selected_entity: [],
        entities_list: [],
        entity_groups: [],
        staff_list: [],
        show_staff: false,
        assign_date: '',
        loading: true,
        showHide: false
    })
    const [selectReport, setSelectReport] = useState({
        title: '',
        id: '',
        show_form: false
    })
    const [reportDetail, setReportDetail] = useState([])
    const [reports, setReports] = useState([])
    const [clients, setClients] = useState([])
    useEffect(() => {
        axios.get(`/superadmin/client-report-view`).then(res => {
            const {reports, assigned_client} = res.data
            setClients(assigned_client)
            setReports(reports)

        })
        axios.get('/superadmin/report-data').then(res => {
            const { role, reports, region_list, assigned_reports, entity_groups } = res.data
            if (reports) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setState(prev => ({ ...prev, role, reports, region_list, assigned_reports, entity_groups, loading: false }))
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        })
    }, [])

    useEffect(() => {
        if (state.selected_entity) {
            if (state.selected_entity.length > 0) {
                const input = {
                    entity_list: JSON.stringify(state.selected_entity.map(i => i.id))
                }
                axios.post('/superadmin/all-staff-list', input).then(res => {
                    const { staff_list } = res.data
                    setState(state => ({
                        ...state, staff_list, show_staff: true
                    }))
                })
            } else {
                setState(state => ({
                    ...state, staff_list: [], show_staff: false
                }))
            }
        } else {
            setState(state => ({
                ...state, staff_list: [], showStaff: false
            }))
        }
    }, [state.selected_entity])

    const handleEntityGroupChange = (e, id) => {
        const { value } = e.target
        axios.get(`/superadmin/get-entity-list/${value}`).then(res => {
            const { entities_list } = res.data
            setState(state => ({
                ...state, entities_list, selected_entity: entities_list, entitygroup_id: value
            }))
        })
    }

    const handleClose = (e) => {
        setState(state => ({
            ...state, staff_id: null, report_id: null, selected_entity: []
        }))
        setSelectReport({ title: null, id: null, show_form: false })
    }

    const handleEntityChange = (list) => {
        setState(state => ({
            ...state, selected_entity: list
        }))
    }

    const assignStaff = (e, id) => {
        const { value } = e.target
        setState(state => ({
            ...state, staff_id: value, report_id: id
        }))
    }
    const handleChange = (e) => {
        const { name, value } = e.target
        setState(state => ({
            ...state, [name]: value
        }))
    }

    const handleSelectedReport = (e, title, id) => {
        setSelectReport({ title, id, show_form: true })
    }

    const handleShowModal = async (e, title, reportdata_id, entitygroup_id) => {
        await handleCheckReport(reportdata_id, entitygroup_id)
        setSelectReport({ title, id: reportdata_id, show_form: false })
        setState(prev => ({ ...prev, showHide: true }))
    }

    const handleHideModal = (e) => {
        setState(prev => ({ ...prev, showHide: false }))
    }

    const handleCheckReport = async (reportdata_id, entitygroup_id) => {
        await axios.get(`/superadmin/report-detail/${reportdata_id}/${entitygroup_id}`).then(res => {
            const { report_detail } = res.data
            setReportDetail([...report_detail])
        })
    }

    const getReportData = (params) => {
        axios.get('/superadmin/report-data').then(res => {
            return res;
        })
    }

    return (
        <>
            <Modal show={state.showHide} dialogClassName="custom-dialog-css">
                <Modal.Header closeButton onClick={(e) => handleHideModal(e)}>
                    <Modal.Title>{selectReport.title}</Modal.Title>
                </Modal.Header>
                <Modal.Body className="pl-1 pr-1">
                    <table className="table table-striped table-bordered dataTableReact">
                        <thead>
                            <tr>
                                <th>Assigned Staff</th>
                                <th>Assigned Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            {
                                reportDetail.length > 0 &&
                                reportDetail.map((report, index) => {
                                    return <tr key={index}>
                                        <td>{report.staff_detail.name}</td>
                                        <td>{report.assigned_date}</td>
                                    </tr>
                                })
                            }
                        </tbody>
                    </table>
                </Modal.Body>
            </Modal>

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
                                        <th>Report Form Title</th>
                                        <th>Entity Group</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        state.assigned_reports.map((report, index) => {
                                            return <tr key={index}>
                                                <td>{report.title}</td>
                                                <td>
                                                    {
                                                        report.entity_groups.map((data, key) => {
                                                            return <span key={key}>
                                                                <button className="btn btn-sm btn-primary" onClick={(e) => handleShowModal(e, report.title, report.id, data.report_entity_group.id)}>
                                                                    {data.report_entity_group ? data.report_entity_group.group_name : '-'}
                                                                    <span className="ml-2">{data.staff_count}</span>
                                                                    {/* <span className="badge badge-info badge-pill ml-2">{data.assign_count}</span> */}
                                                                </button>
                                                            </span>
                                                        })
                                                    }
                                                </td>
                                                <td>
                                                    <Link className='btn btn-secondary btn-sm mr-1' to={`/report-form/${report.id}`}>
                                                        <i className="fa fa-eye"></i>
                                                    </Link>
                                                    <button className="btn btn-sm btn-primary mr-1" onClick={e => handleSelectedReport(e, report.title, report.id)}><i className="fa fa-plus"></i></button>
                                                    {/* <Link className='btn btn-success btn-sm mr-1' to={`/report-generate/${report.id}`}>
                                                        <i className="fa fa-file"></i>
                                                    </Link> */}
                                                </td>
                                            </tr>
                                        })}
                                </tbody>
                            </table>
                        }
                    </div>

                    <div>
                        <div className="card" style={{ display: !selectReport.show_form && 'none' }}>
                            <div className="card-body">
                                <div className="row mb-2">
                                    <div className="col-md-6"><h5 className="card-title">{selectReport.title}</h5></div>
                                    <div className="col-md-6">
                                        <div className='btn btn-sm btn-danger float-right' onClick={e => handleClose()}>
                                            <i className="fa fa-times"></i>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="form-group col-md-6">
                                        {
                                            state.entity_groups.length > 0 &&
                                            <select name="region" className="form-control" onChange={e => handleEntityGroupChange(e, selectReport.id)}>
                                                <option value="" disable="true">Select Entity Group</option>
                                                {
                                                    state.entity_groups.map(entity => {
                                                        return <option value={entity.id} key={entity.id}>{entity.group_name}</option>
                                                    })
                                                }
                                            </select>
                                        }
                                    </div>
                                    <div className="form-group col-md-6">
                                        {
                                            state.staff_list.length > 0 &&
                                            <select name="staff" className="form-control" onChange={e => assignStaff(e, selectReport.id)}>
                                                <option value="" disable="true">Select Staff From List</option>
                                                {
                                                    state.staff_list.map(staff => {
                                                        return <option value={staff.id} key={staff.id}>{staff.name}</option>
                                                    })
                                                }
                                            </select>

                                        }
                                        {
                                            (state.show_staff && state.staff_list.length < 1) &&
                                            <div className="alert alert-danger" role="alert">
                                                No field staff found for this region!
                                        </div>
                                        }
                                    </div>
                                </div>
                                <div className="row">
                                    <div className='form-group col-md-12' >
                                        <Select
                                            name="filters"
                                            placeholder="Filters"
                                            value={state.selected_entity}
                                            options={state.entities_list}
                                            onChange={handleEntityChange}
                                            isMulti
                                        />
                                    </div>
                                </div>
                                <div className="form-group row">
                                    <label htmlFor="assigned-date" className="col-2 col-form-label"><b>Assign Date</b></label>
                                    <div className="col-3">
                                        <input className="form-control" type="date" id="assigned-date" name="assign_date" onChange={e => handleChange(e)} />
                                    </div>
                                </div>
                                <div className='form-group'>
                                    <button className="btn btn-sm btn-primary" onClick={assignReport} disabled={!state.staff_id}>Assign Report</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default ClientReportList
