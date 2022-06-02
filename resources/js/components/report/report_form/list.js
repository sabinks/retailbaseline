import React, { useState, useEffect } from 'react'
import axios from 'axios'
import { Link } from 'react-router-dom'
import swal from 'sweetalert'
import download from 'downloadjs'
import { getDate } from '../../../utils/functions'
import { Modal, Button } from 'react-bootstrap'
import Select from "react-select"
const ListReport = () => {
    const [state, setState] = useState({
        reports: [],
        role: null,
        title: '',
        file_type: 'csv',
        form_id: '',
        from_date: getDate(),
        to_date: getDate(),
        selected: []
    })
    const [reportStatus, setReportStatus] = useState([
        { id: 2, value: 'Pending', label: 'Pending' },
        { id: 3, value: 'Approved', label: 'Approved' },
        { id: 4, value: 'Rejected', label: 'Rejected' }
    ])
    const [show, setShow] = useState(false);
    const handleClose = () => setShow(false)
    const handleShow = () => setShow(true)
    const openModal = (title, form_id) => {
        setState(prev => ({
            ...prev, form_id, title
        }))
        handleShow()
    }
    useEffect(() => {
        axios.get('/report').then(res => {
            const { role, reports } = res.data
            if (reports) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }

                setState(prevState => ({ ...prevState, role: role, reports }))
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                // $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                // $('.dataTables_filter').css("float", "left")
            }
        })
    }, [])
    const handleChange = (e) => {
        const { name, value } = e.target
        setState(state => ({
            ...state, [name]: value
        }))
    }
    const handleStatusSelect = (list) => {
        setState(state => ({
            ...state, selected: list, select_status: list ? list.map(item => item.id) : []
        }))
    }
    const generateReport = () => {
        axios.post(`/generate-report-form-report`, state)
            .then(res => {
                let file = res.data;
                const file_name = `report.csv`
                download(file, file_name);
                handleClose()

            }).catch(error => {
                const { message } = error.response.data
                swal("Warning!", message, "error")
            })
    }
    const handleDeleteFormData = (event, formId) => {
        event.preventDefault()
        swal({
            title: "Warning!",
            text: "Are you sure to delete report form?",
            icon: "warning",
            dangerMode: true,
        })
            .then(res => {
                if (res) {
                    axios.delete(`/report/${formId}`)
                        .then(res => {
                            const { message } = res.data
                            swal("Success!", message, "success")
                            setTimeout(() => {
                                window.location.replace('/report-form')
                            }, 3000);
                        })
                        .catch(error => {
                            const { message } = error.response.data
                            swal("Warning!", message, "error")
                            // setTimeout(() => {
                            //     window.location.replace('/report-form')
                            // }, 3000);
                        })
                }
            });
    }

    return (
        <>
            <div className="main-card mb-2 card">
                <div className='card-header'>
                    <div className='card-title'>
                        Add, Edit, Delete Report Forms
                    </div>
                    <div className="btn-wrapper btn-wrapper-multiple">
                        <Link className='btn btn-primary btn-sm mr-3 mb-2' to={'/report-form/create'}>
                            Create Report Form
                        </Link>
                    </div>
                </div>
                <div className="card-body">
                    <Modal show={show} onHide={handleClose}>
                        <Modal.Header closeButton>
                            <Modal.Title>Filter</Modal.Title>
                        </Modal.Header>
                        <Modal.Body>
                            <div className="form-group col-md-8">
                                <label>From Date</label>
                                <div>
                                    <input className="form-control" type="date" id="from-date" name="from_date" value={state.from_date} onChange={e => handleChange(e)} />
                                </div>
                            </div>
                            <div className="form-group col-md-8">
                                <label>To Date</label>
                                <div>
                                    <input className="form-control" type="date" id="to-date" name="to_date" value={state.to_date} onChange={e => handleChange(e)} />
                                </div>
                            </div>
                            <div className="form-group col-md-8">
                                <label>To Date</label>
                                <div>
                                    <Select
                                        name="status"
                                        placeholder="Select one or multiple"
                                        value={state.selected}
                                        options={reportStatus}
                                        onChange={handleStatusSelect}
                                        isMulti
                                    />
                                </div>

                            </div>
                        </Modal.Body>
                        <Modal.Footer>
                            <Button variant="primary" size="sm" onClick={generateReport}>
                                Download Report
                            </Button>
                        </Modal.Footer>
                    </Modal>
                    <div className="table-responsive">
                        <table className="table table-striped table-bordered dataTableReact">
                            <thead>
                                <tr>
                                    <th>Form Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {state.reports && state.reports.map((report, index) =>
                                    <tr key={index}>
                                        <td>{report.title}</td>
                                        <td>
                                            <div className='btn-group'>
                                                <Link className='btn btn-secondary btn-sm mr-1' to={`/report-form/${report.id}`}>
                                                    <i className="fa fa-pencil"></i>
                                                </Link>
                                                <Link className='btn btn-primary btn-sm mr-1' to={`/report-info/listing/${report.id}`}>
                                                    <i className="fa fa-eye"></i>
                                                </Link>
                                                <a className="btn btn-success btn-sm mr-1" onClick={e => openModal(report.title, report.id)}><i class="fa fa-save"></i></a>
                                                <a href="#!" onClick={(e) => {
                                                    handleDeleteFormData(e, report.id)
                                                }} className='btn btn-danger btn-sm'>
                                                    <i className="fa fa-trash-o"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </>
    )
}

export default ListReport