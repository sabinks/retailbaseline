import React, { useState, useEffect } from 'react';
import { Link, useHistory } from "react-router-dom";
import axios from 'axios'
import swal from 'sweetalert';
import download from 'downloadjs'
import { Modal, Button } from 'react-bootstrap'
import Select from "react-select";
import { getDate, slugify } from '../../../utils/functions'
import Index from '../entityRoute'
const ReportListing = () => {
    let history = useHistory();
    const [loading, setLoading] = useState(false)
    const [state, setState] = useState({
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
    const [forms, setForms] = useState([])
    const [show, setShow] = useState(false);
    const handleClose = () => setShow(false)
    const handleShow = () => setShow(true)
    useEffect(() => {
        axios.get('/superadmin/entity-form').then(res => {
            const { form_list } = res.data
            if (form_list) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setForms(form_list)
                setState(prev => ({ ...prev, loading: false }))
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        })
    }, [])
    const handleDeleteForm = (e, form_id) => {
        event.preventDefault()
        swal({
            title: "Warning!",
            text: "Sure delete form?",
            icon: "warning",
            dangerMode: true,
        })
            .then(res => {
                if (res) {
                    axios.delete(`/superadmin/entity-form/${form_id}`)
                        .then(res => {
                            const { message } = res.data
                            swal("Success!", message, "success")
                            setForms(forms.filter(form => form.id != form_id))

                            setTimeout(() => {
                                history.push('/super/entity-form/list')
                            }, 3000);
                        })
                        .catch(error => {
                            const { message } = error.response.data
                            const { status } = error.response
                            if (status == 500)
                                swal("Warning!", 'Error Occured!', "error")
                            else
                                swal("Warning!", message, "error")
                        })
                }
            });
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
        axios.post(`/superadmin/generate-entity-form-report`, data)
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
    const handleChange = (e) => {
        const { name, value } = e.target
        setState(state => ({
            ...state, [name]: value
        }))
    }
    const openModal = (title, form_id) => {
        setState(prev => ({
            ...prev, form_id, title
        }))
        handleShow()
    }

    return (
        <>
            <div className="main-card card mb-1">
                <div className="card-header">
                    <div className='card-title'>
                        Entity Form Listing
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
                                        <th>Form Title</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        forms && forms.map((form, index) => {
                                            return <tr key={index}>
                                                <td>{form.title}</td>
                                                <td className='btn-group'>
                                                    <Link className='btn btn-secondary btn-sm mr-1' to={`/super/entity-form/update/${form.id}`}>
                                                        <i className="fa fa-pencil"></i>
                                                    </Link>
                                                    <button title='Download Entity Report' className='btn btn-success btn-sm mr-1' onClick={e => openModal(form.title,form.id)}>
                                                        <i className="fa fa-save"></i>
                                                    </button>
                                                    <Link className='btn btn-primary btn-sm mr-1' to={`/super/entity-data/list/${form.id}`}>
                                                        <i className="fa fa-eye"></i>
                                                    </Link>
                                                    <button onClick={e => handleDeleteForm(e, form.id)} className='btn btn-danger btn-sm'>
                                                        <i className="fa fa-trash-o"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        })}
                                </tbody>
                            </table>
                        }
                    </div>
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
        </>
    )
}

export default ReportListing