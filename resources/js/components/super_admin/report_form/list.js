import React, { useState, useEffect } from 'react';
import axios from 'axios'
import swal from 'sweetalert';
import download from 'downloadjs'
import { Modal, Button } from 'react-bootstrap'
import { getDate } from '../../../utils/functions'
import Select from "react-select";
const ReportListing = () => {

    const [state, setState] = useState({
        report_title: '',
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
    const [status, setStatus] = useState('all')
    const [show, setShow] = useState(false);

    const handleClose = () => setShow(false)
    const handleShow = () => setShow(true)

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
                url: `/superadmin/report-form`,
                method: "GET",
                error: function (xhr, error, code) {
                    data_table.ajax.reload(null, false)
                }
            },
            "columns": [
                { data: "label" },
                { data: "options" }
            ]
        })
        $('.dataTables_wrapper .row:first-child>div:first-child').removeClass()
        $('.dataTables_filter').css("float", "left")
        data_table.draw()
    }, [status])

    useEffect(() => {
        window.reactDownloadReport = (report_id, report_title) => {
            handleShow()
            setState(prev => ({
                ...prev, form_id: report_id
            }))
        }

        window.reactDeleteReport = (report_id) => {
            axios.delete(`/superadmin/report-form/${report_id}`)
                .then(res => {
                    swal("Warning!", message, "error")
                }).catch(error => {
                    const { message } = error.response.data
                    swal("Warning!", message, "error")
                })
        }
    }, [])
    
    const generateReport = () => {
        axios.post(`/superadmin/generate-report-form-report`, state)
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
    const handleStatusSelect = (list) => {
        setState(state => ({
            ...state, selected: list, select_status: list ? list.map(item => item.id) : []
        }))
    }

    return (
        <>
            <div className="main-card card mb-1" id="superadmin-report-list">
                <div className="card-header">
                    <div className='card-title'>
                        Report Form Listing
                    </div>
                </div>

                <div className="card-body">
                    <div className="table">
                        <table className="table table-striped table-bordered dataTableReact">
                            <thead>
                                <tr>
                                    <th>Report Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div id="download-report"></div>
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