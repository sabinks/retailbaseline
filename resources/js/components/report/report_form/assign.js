import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { Link } from 'react-router-dom';
import swal from 'sweetalert';
function assign() {

    const [reports, setReports] = useState([])
    const [roles, setRoles] = useState([])
    const [names, setNames] = useState([])
    const [reportid, setReportid] = useState(0)
    const [assignid, setAssignid] = useState(0)

    useEffect(() => {
        axios.get('/my-report-list').then(res => {
            setReports(res.data.reports)
            if(res.data.reports.length!=0){
                setRoles(res.data.submit_to)
            }
            else{
                setRoles([])
            }
        })
    }, [])
    
    const setReportId = (event,reportid) =>{
        setReportid(reportid)
    }
    const setAssignId = (event,assignid) =>{
        setAssignid(assignid)
    }

    const handleNamesOfAssignTo = (event, roleId) => {
        // event.preventDefault()
        axios.get(`/my-report-list/${roleId}`)
        .then(res => {
            setNames(res.data.names)
        })
    }

    const assignForm = () => {
        const input = {
            assigned_id: assignid,
            report_id: reportid
        }
        axios.post('/my-report-list', input)
            .then(res => {
                const { message } = res.data
                swal("Success!", message, "success")
            })
            .catch(error => {
                const { message } = error.response.data
                swal("Warning!", message, "error")
            })
    }

    return(
        <>
            <div className="app-page-title">
                <div className="page-title-wrapper">
                    <div className="page-title-heading">
                        <div className="page-title-icon">
                            <i className="fa fa-user-o"></i>
                        </div>
                        <div>
                            Regular Report Forms
                        </div>
                    </div>
                </div>
            </div>
            <div className="main-card mb-2 card">
                <div className='card-header'>
                    <div className='card-title'>
                        Assign , Remove Regular Report From(s)
                    </div>
                    <div className="btn-wrapper btn-wrapper-multiple">
                        <button type="button" className="btn btn-sm btn-success">
                            <Link id="link_page" to={'report-form-assigned'}>
                                Assigned to You
                            </Link>
                        </button>
                        {/* <a href="/report-form-assigned-list">
                            <i class="metismenu-icon fa fa-map"></i>
                            Report Form List
                        </a> */}
                        <button type="button" className="btn btn-sm btn-success">
                            <Link id="link_page" to={'report-form-assign-by-you'}>
                                Assigned By you
                            </Link>
                        </button>
                    </div>
                </div>
                <div className="card-body">
                    <div className="row">
                        <div className="col-12">
                            {/* <form > */}
                                <div className="row">
                                    <div className="col-md-3">
                                    <select className="form-control form-title" onChange={ e=>setReportId(e, e.target.value)}>
                                        <option value="" disable="true">Select Report Form</option>
                                        {reports.map((form, index) => (
                                            <option key={index} value={form.id}>{form.title}</option>
                                        ))}
                                    </select>
                                    </div>
                                    <div className="col-md-3">
                                        <select className="form-control assign-to-role" id="triggerName" onChange={e => handleNamesOfAssignTo(e, e.target.value)} >
                                            <option value="" disable="true">Select Designation </option>
                                            {roles.map((role, index) => (
                                                <option key={index} value={role.id}>
                                                    {role.name}</option>
                                            ))}
                                        </select>
                                    </div>
                                    <div className="col-md-3">
                                        <select className="form-control person-name"  onChange={ e=>setAssignId(e, e.target.value)}>
                                            <option value="" disable="true">Select User</option>
                                            {names.map((name, index) => (
                                                <option key={index} value={name.id}>{name.name}</option>
                                            ))}
                                        </select>
                                    </div>
                                    <div className="col-md-3">
                                        {/* <button type="button" class="btn btn-success">Assign Form</button> */}
                                        <button type="submit" className="btn btn-sm btn-primary" onClick={assignForm} disabled={!(reportid && assignid)}>Assign Form</button>
                                    </div>
                                </div>
                            {/* </form> */}
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default assign