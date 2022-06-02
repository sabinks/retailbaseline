import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { Link } from "react-router-dom";
import { slugify, dateConversion, getDate } from '../../../utils/functions';
import swal from 'sweetalert';
import download from 'downloadjs'
import { Modal, Button } from 'react-bootstrap'

function AssignedFormViewForms() {
    const [loading, setLoading] = useState(false)
    const [state, setState] = useState({
        report_forms_list: [],
        report_title: '',
        file_type: 'csv',
        form_id: '',
        from_date: getDate(),
        to_date: getDate()
    })
    const [status, setStatus] = useState('all')
    const [show, setShow] = useState(false);

    const handleClose = () => setShow(false)
    const handleShow = () => setShow(true)
    useEffect(() => {
        axios.get('/clients/report-form/assigned-list').then(res => {
            const { report_forms_list } = res.data
            if (report_forms_list) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setState(prev => ({ ...prev, report_forms_list, loading: false }))
                let tableElemt = $('.dataTableReact');
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        })
    }, [])

    const handleGenerateReport = (e, form_id) => {
        setState(prev => ({
           ...prev, form_id
        }))
        handleShow()
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

    const handleChange = (e) => {
        const { name, value } = e.target
        setState(state => ({
            ...state, [name]: value
        }))
    }
    return (
        <>
            <div className="main-card card mb-1">
                <div className="card-header">
                    <div className='card-title'>
                        Report Form Listing
                    </div>
                    {/* <Index /> */}
                </div>
                <div className="card-body">
                    <div className="table">
                        <table className="table table-striped table-bordered dataTableReact">
                            <thead>
                                <tr>
                                    <th>Report Title</th>
                                    <th>Assigned Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {
                                    state.report_forms_list && state.report_forms_list.map((form, index) => {
                                        return <tr key={index}>
                                            <td><Link to={`/client/report-form/${form.id}`}>{form.title}</Link></td>
                                            <td>{dateConversion(form.pivot.created_at)}</td>
                                            <td>
                                                <button title='Download Report' className='btn btn-success btn-sm' onClick={e => handleGenerateReport(e, form.id)}>
                                                    <i className="fa fa-save"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    })}
                            </tbody>
                        </table>
                    </div>
                </div>
                <Modal show={show} onHide={handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>Select Date</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="form-group col-md-6">
                            <label>From Date</label>
                            <div>
                                <input className="form-control" type="date" id="from-date" name="from_date" value={state.from_date} onChange={e => handleChange(e)} />
                            </div>
                        </div>
                        <div className="form-group col-md-6">
                            <label>To Date</label>
                            <div>
                                <input className="form-control" type="date" id="to-date" name="to_date" value={state.to_date} onChange={e => handleChange(e)} />
                            </div>
                        </div>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="primary" size="sm" onClick={generateReport}>
                            Download Report
                        </Button>
                    </Modal.Footer>
                </Modal>
            </div>
        </>
    )
}

export default AssignedFormViewForms
