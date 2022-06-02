import React, { useState, useEffect } from 'react';
import { getDate, getPreviousDate, getNextDate } from '../../utils/functions'
import swal from 'sweetalert'
import download from 'downloadjs'
import { Modal, Button } from 'react-bootstrap'
const StaffAttendance = () => {
    const [rotation, setRotation] = useState(0)
    const [state, setState] = useState({
        from_date: getDate(),
        to_date: getNextDate(),
        all: 0,
        file_type: 'csv'
    })
    const [image, setImage] = useState('')
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
            "pageLength": 25,
            "autoWidth": false,
            processing: true,
            serverSide: true,
            async: true,
            "ajax": {
                url: `/api/staff-attendance?from_date=${state.from_date}&to_date=${state.to_date}&all=${state.all}`,
                method: "GET",
                error: function (xhr, error, code) {
                    data_table.ajax.reload(null, false)
                }
            },
            "columns": [
                { data: "staff_name" },
                { data: "attendance_type" },
                { data: "attendance_detail" },
                { data: "region_name" },
                { data: "lat" },
                { data: "lng" },
                { data: "login_time" },
                { data: "remark" },
                { data: "options" },
            ]
        })
        $('.dataTables_wrapper .row:first-child>div:first-child').removeClass()
        $('.dataTables_filter').css("float", "left")
        data_table.draw()
    }, [state.from_date, state.to_date, state.all])

    const rotateRight = () => {
        let newRotation = rotation + 90
        if (newRotation >= 360) {
            newRotation = - 360;
        }
        setRotation(newRotation)
    }

    const rotateLeft = () => {
        let newRotation = rotation - 90
        if (newRotation >= 360) {
            newRotation = - 360;
        }
        setRotation(newRotation)
    }
    const handleChange = (e) => {
        let { name, value } = e.target
        setState(prev => ({
            ...prev, [name]: value, all: 0
        }))
    }
    const handleCheckboxChange = (e) => {
        let { checked } = e.target
        setState(prev => ({
            ...prev, all: checked ? 1 : 0
        }))
    }

    const downloadReport = (e) => {
        axios.get(`/api/staff-attendance-report?from_date=${state.from_date}&to_date=${state.to_date}&all=${state.all}`)
            .then(res => {
                let file = res.data;
                const file_name = `report.csv`
                download(file, file_name);
                handleClose()
            }).catch(error => {
                console.log(message)
                const { message } = error.response.data
                swal("Warning!", message, "error")
            })
    }
    useEffect(() => {
        window.reactStaffAttendance = (staff_image) => {
            handleShow()
            setImage(staff_image)
        }
    }, [])

    return (
        <>
            <div className="main-card card">
                <div className="card-header">
                    <div className='card-title col-md-4'>
                        Staff Attendance
                    </div>
                    <div className="form-check col-md-4">
                        <input className="mr-2" type="checkbox" checked={state.all} id="show-all" onChange={e => handleCheckboxChange(e)} />
                        <label className="form-check-label" htmlFor="show-all">Show all</label>
                    </div>
                    <div className="col-md-4">
                        <div className="row">
                            <div className="col-md-8">
                                <div className="row">
                                    <div className="col-md-5">From Date</div>
                                    <div className="col-md-7"><input className="" type="date" id="from_date" name="from_date" onChange={e => handleChange(e)} defaultValue={state.from_date} /></div>

                                    <div className="col-md-5">To Date</div>
                                    <div className="col-md-7"><input className="" type="date" id="to_date" name="to_date" onChange={e => handleChange(e)} defaultValue={state.to_date} /></div>
                                </div>

                            </div>
                            <div className="col-md-3 ml-2">
                                <a className="btn btn-success btn-sm mr-1" onClick={e => downloadReport(e)}><i className="fa fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="card-body">
                    <div className="table">
                        <table className="table table-striped table-bordered dataTableReact">
                            <thead>
                                <tr>
                                    <th>Staff Name</th>
                                    <th>Attendance</th>
                                    <th>Attendance Detail</th>
                                    <th>Region</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Login Time</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <Modal show={show} onHide={handleClose}>
                    <Modal.Body>
                        {
                            image && <img src={`/storage/images/staff_attendances/${image}`} style={{ transform: `rotate(${rotation}deg)`, width: "100%", marginBottom: '20px' }} />
                        }
                        <div className="d-flex justify-content-center">
                        <a className="btn btn-sm mr-1" onClick={e => rotateLeft()}><i className="fa fa-undo"></i></a>
                         <a className="btn btn-sm mr-1" onClick={e => rotateRight()}><i className="fa fa-repeat"></i></a>
                        </div>
                    </Modal.Body>
                </Modal>
            </div>
        </>
    )
}

export default StaffAttendance