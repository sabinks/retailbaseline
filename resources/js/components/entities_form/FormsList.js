import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { Modal, Button } from 'react-bootstrap'
import Select from "react-select";
import { getDate, slugify } from '../../utils/functions'
import swal from 'sweetalert';
import { Link } from 'react-router-dom';
import download from 'downloadjs'
function FormsList() {
    const [state, setState] = useState({
        forms: null,
        formFormDataExist: null,
        role: null,
        submittedEntitiesForms: null,
        loading: true,
        title: '',
        file_type: 'csv',
        form_id: '',
        from_date: getDate(),
        to_date: getDate(),
        selected: []
    })
    const [reportStatus, setReportStatus] = useState([
        { id: 1, value: 'Filled', label: 'Filled' },
        { id: 2, value: 'Approved', label: 'Approved' },
        { id: 3, value: 'Rejected', label: 'Rejected' }
    ])
    const [show, setShow] = useState(false);
    const [loading, setLoading] = useState(false)
    const handleClose = () => setShow(false)
    const handleShow = () => setShow(true)
    useEffect(() => {
        async function callFunction() {
            try {
                const forms = await axios.get('/entities-forms')
                if (forms) {
                    let prevtableElemt = $('.dataTable');
                    let prevtableElmt = $('.dataTable').attr('id');
                    if (prevtableElmt == 'DataTables_Table_0') {
                        let prevTable = $('.dataTable').DataTable();
                        prevTable.destroy();
                    }
                    if (forms.data.role == 'Admin' || forms.data.role == 'Regional Admin') {
                        setState(prev => ({
                            ...prev,
                            forms: forms.data.forms,
                            formFormDataExist: forms.data.formFormDataExist,
                            role: forms.data.role
                        }))
                    }
                    let tableElemt = $('.dataTableReact');
                    let table = $('.dataTableReact').DataTable();
                    $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                    $('.dataTables_filter').css("float", "left")
                }
            } catch (error) {
                console.log(error)
            }
        }
        callFunction()

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
        let data = {
            select_status: state.select_status,
            form_id: state.form_id,
            from_date: state.from_date,
            to_date: state.to_date,
            file_type: state.file_type
        }
        axios.post(`/generate-entity-form-report`, data)
            .then(res => {
                let file = res.data;
                const file_name = `${slugify(state.title)}_report.csv`
                download(file, file_name);
                handleClose()
                setLoading(false)
            }).catch(error => {
                const { message } = error.response.data
                swal("Warning!", message, "error")
                setLoading(false)
            })
    }
    const openModal = (title, form_id) => {
        setState(prev => ({
            ...prev, form_id, title
        }))
        handleShow()
    }

    return (
        <>
            <div className="main-card mb-3 card">
                <div className='card-header'>
                    {
                        state.role == 'Super Admin' ?
                            <>View, add, edit or delete the Entities Tracking Form</>
                            : <>View, add, edit, or delete the Entities Tracking Form</>
                    }
                    <div className="btn-wrapper btn-wrapper-multiple">
                        <button type="button" className="btn btn-sm btn-success">
                            {
                                state.forms && <>
                                    <Link id="link_page" to={'/entities-form/create'}>
                                        Create Entities Tracking Form
                                        </Link>
                                </>
                            }
                        </button>
                    </div>
                </div>
                <div className="card-body">

                    <div className="table-responsive">
                        {
                            (state.forms || state.submittedEntitiesForms) ? <table className="table table-striped table-bordered dataTableReact">
                                <thead>
                                    <tr>
                                        <th>Creator Name</th>
                                        <th>Form Title</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    {
                                        state.forms.map((form, index) =>
                                            <tr key={index}>
                                                <td>{form.form_creator.name}</td>
                                                <td>{form.form_title}</td>
                                                <td>
                                                    {
                                                        (state.role == 'Admin' || state.role == "Regional Admin") && <>
                                                            <div className='btn-group'>
                                                                {
                                                                    <Link className='btn btn-secondary btn-sm mr-1' to={`/entities-form/${form.id}`}>
                                                                        <i className="fa fa-pencil"></i>
                                                                    </Link>
                                                                }
                                                                <button title='Download Entity Report' className='btn btn-success btn-sm mr-1' onClick={e => openModal(form.form_title, form.id)}>
                                                                    <i className="fa fa-save"></i>
                                                                </button>
                                                            </div>
                                                            <Link title='View' className='btn btn-secondary btn-sm mr-1' to={`/entities-form-view/${form.id}`}>
                                                                <i className="fa fa-eye"></i>
                                                            </Link>
                                                        </>
                                                    }
                                                </td>
                                            </tr>
                                        )
                                    }
                                </tbody>
                            </table> :
                                <table className="table table-striped table-bordered dataTable">
                                    <thead>
                                        <tr>
                                            <th>Creator Name</th>
                                            <th style={{ width: '30%' }}>Staffs Associated</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                        }
                    </div>
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
                </div>
            </div>
        </>
    )
}

export default FormsList