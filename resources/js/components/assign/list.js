import React, { useState, useEffect } from 'react'
import axios from 'axios'
import { Link } from 'react-router-dom';
import swal from 'sweetalert';
import Select from "react-select";
import { useHistory } from "react-router-dom";
function list() {
    let history = useHistory();
    const [state, setState] = useState({
        staff_list: [],
        selected_staff: [],
        loading: true,
        showHide: false,
        forms: [],
        regions: [],
        formid: 0,
    })
    useEffect(() => {
        axios.get('/list-entity-tracking-form').then(res => {
            const { forms, regions } = res.data
            setState(prev => ({
                ...prev,
                forms, regions
            }))
        })
    }, [])

    const setFormId = (event, formid) => {
        setState(prev => ({
            ...prev,
            formid
        }))
    }

    const handleStaffChange = (list) => {
        setState(state => ({
            ...state, selected_staff: list ? list : []
        }))
    }

    const assignForm = () => {
        const input = {
            form_id: state.formid,
            user_ids: JSON.stringify(state.selected_staff.map(i => i.id)),
        }
        axios.post('/assign-entity-track-form', input)
            .then(res => {
                const { message } = res.data
                swal("Success!", message, "success")
                // setTimeout(() => {
                //     window.location.replace('/assign-entities-form/remove')
                // }, 3000);
            })
            .catch(error => {
                const { message } = error.response.data
                swal("Warning!", message, "error")
                // setTimeout(() => {
                //     window.location.replace('/assign-entities-form')
                // }, 3000);
            })
    }

    const handleNamesOfAssignTo = (event, regionId) => {
        event.preventDefault()
        axios.get(`/entity-track-form-assign-to/${regionId}`)
            .then(res => {
                const staff_list = res.data.staffs
                setState(prev => ({ ...prev, staff_list: staff_list, selected_staff: [] }))
            })
    }

    return (
        <>
            <div className="app-page-title">
                <div className="page-title-wrapper">
                    <div className="page-title-heading">
                        <div className="page-title-icon">
                            <i className="fa fa-user-o"></i>
                        </div>
                        <div>
                            Entity Tracking Forms
                    </div>
                    </div>
                </div>
            </div>
            <div className="main-card mb-2 card">
                <div className='card-header'>
                    <div className='card-title'>
                        Assign Entity Tracking form(s) to Filed Staff
                </div>
                    <div className="btn-wrapper btn-wrapper-multiple">
                        <button type="button" className="btn btn-sm btn-success">
                            <Link id="link_page" to={'/assign-entities-form/remove'}>
                                Assigned List
                        </Link>
                        </button>
                    </div>
                </div>
                <div className="card-body">
                    <div className="row">
                        <div className="col-12">
                            <div className="row mb-3">
                                <div className="col-md-3">
                                    <select className="form-control form-title" onChange={e => setFormId(e, e.target.value)}>
                                        <option value="" disable="true">Select Entity Tracking Form</option>
                                        {state.forms.map((form, index) => (
                                            <option key={index} value={form.id}>{form.form_title}</option>
                                        ))}
                                    </select>
                                </div>
                                <div className="col-md-3">
                                    <select className="form-control assign-to-role" id="triggerName" onChange={e => handleNamesOfAssignTo(e, e.target.value)} >
                                        <option value="" disable="true">Select Region </option>
                                        {state.regions.map((region, index) => (
                                            <option key={index} value={region.id}>
                                                {region.name}</option>
                                        ))}
                                    </select>
                                </div>
                            </div>
                            <div className="row">
                                <div className='form-group col-md-12' >
                                    <Select
                                        name="filters"
                                        placeholder="Select staff from list"
                                        value={state.selected_staff}
                                        options={state.staff_list}
                                        onChange={handleStaffChange}
                                        isMulti
                                    />
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-md-3">
                                    {/* <input id="count" className="p-1 mr-1" type="number" min="1" placeholder="Visit Count" onChange={ e=>setCount(e, e.target.value)}/> */}
                                    <button type="submit" className="btn btn-primary" onClick={assignForm} disabled={!(state.formid && state.selected_staff.length > 0)}>Assign Staff</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default list
