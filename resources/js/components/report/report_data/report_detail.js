import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { Link } from 'react-router-dom';
import swal from 'sweetalert';
import { useHistory } from "react-router-dom";
import { statusConversion } from '../../../utils/functions'
let url = document.getElementById('route').getAttribute('url')
const ReportListing = () => {
    let history = useHistory();
    const [state, setState] = useState({
        report_detail: null,
        role: null,
        loading: true,
        can_update: false,
        id: null
    })

    useEffect(() => {
        let id = window.location.pathname.replace('/report-info/detail/', '')
        getReportData(id)
    }, [])

    const getReportData = (report_id) => {
        axios.get(`/report-data-detail/${report_id}`).then(res => {
            const { report_detail, role, can_update } = res.data
            setState(prev => ({ ...prev, report_detail, can_update, loading: false, role }))
        })
    }

    const handleChange = (status) => {
        const input = {
            status: status
        }
        axios.patch(`/report-data/${state.report_detail.id}`, input)
            .then(res => {
                const { message, report_detail, role } = res.data
                setState(prev => ({ ...prev, report_detail, loading: false, role }))
                swal("Success!", message, "success")
            })
            .catch(error => {
                const { message } = error.response.data
                swal("warning!", message, "error")
            })
    }

    return (
        <>
            <div className="main-card card mb-1">
                <div className="card-body pb-0">
                    <h4>Report Detail:</h4>
                </div>
                <div className="card-body">
                    {
                        state.report_detail &&
                        <Link className='btn btn-secondary btn-sm' to={`/report-info/view/${state.report_detail.id}`}>
                            View Report Data
                        </Link>
                    }
                </div>
            </div>

            <div className="main-card card">
                {
                    !state.loading &&
                    <div className="card-body">
                        <div className="row mb-3">
                            <div className="col-md-6"><h5>Report Title: <b>{state.report_detail.report_title}</b></h5></div>
                            <div className="">Report Status: <b>{statusConversion(state.report_detail.status)}</b></div>
                            {
                                state.can_update &&
                                <div className="col-md-6">
                                    <button className="btn btn-sm btn-success mr-1" onClick={e => handleChange('approved')}>Approve</button>
                                    <button className="btn btn-sm btn-danger mr-1" onClick={e => handleChange('rejected')}>Reject</button>
                                </div>
                            }
                        </div>

                        <div className="row mb-3">
                            <div className="col-md-6 float-left">
                                <h5>Entity Detail:</h5>
                                <p className="mb-0">Entity Name: <b>{state.report_detail.entity_name}</b></p>
                                <p className="mb-0">Entity Latitude: <b>{state.report_detail.entity_latitude}</b></p>
                                <p className="mb-0">Entity longitude: <b>{state.report_detail.entity_longitude}</b></p>
                            </div>
                        </div>

                        <div className="row mt-2 mb-2">
                            <div className="col-md-6">{
                                state.report_detail.reportdata_data ? <h5>Filled By:</h5> : <h5>Assigned To:</h5>
                            }

                                <p className="mb-0">Staff Name: <b>{state.report_detail.staff_name}</b></p>
                                <p className="mb-0">Staff Email: <b>{state.report_detail.staff_email}</b></p>
                                <p className="mb-0">Staff Address: <b>{state.report_detail.staff_address}</b></p>
                            </div>
                            <div className="col-md-6">
                                {
                                    state.report_detail.staff_image ?
                                        <img src={`${url}/images/profiles/${state.report_detail.staff_image}`} width="300px" />
                                        : <img src={`${url}/images/user.png`} width="200px" />
                                }
                            </div>
                        </div>
                    </div>
                }
            </div>
        </>
    )
}

export default ReportListing