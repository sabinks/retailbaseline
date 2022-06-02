import React, { useState, useEffect } from 'react'
import axios from 'axios'
import { Link } from 'react-router-dom';
import swal from 'sweetalert';
import Select from "react-select";
import { useHistory } from "react-router-dom";
import { slugify } from '../../utils/functions'
import download from 'downloadjs'
function list() {
    let history = useHistory();
    const [state, setState] = useState({
        companies: [],
        forms:[],
        formid: '',
        loading: true,
        showHide: false,
        from_date: '',
        to_date: '',
        companyid: 0,
        typeid: 0,
    })
    const [loading, setLoading] = useState(false)
    useEffect(() => {
        axios.get('/list-company').then(res => {
            const { companies } = res.data
            setState(prev => ({
                ...prev,
                companies
            }))
        })
    }, [])

    const setCompanyId = (event, companyid) => {
        setState(prev => ({
            ...prev,
            companyid
        }))
    }

    const handleFormType = (event, typeid) => {
        setState(prev => ({
            ...prev,
            typeid
        }))
        if (!state.companyid) {
            swal("Warning!", 'Please Select Company.', "error")
        }
        else{
            axios.get(`/form-list/${state.companyid}/${typeid}`)
            .then(res => {
                const { forms } = res.data
                setState(prev => ({
                    ...prev,
                    forms
                }))
            })
        }
    }

    const handleChange = (e) => {
        const { name, value } = e.target
        setState(state => ({
            ...state, [name]: value
        }))
    }

    const setFormId = (event, formid) => {
        setState(prev => ({
            ...prev,
            formid
        }))
    }

    const handleGenerateReport = () => {
        setLoading(true)
        if (!state.from_date || !state.to_date || !state.formid) {
            swal("Warning!", 'Parameter missing.', "error")
        }
        else {
            const input = {
                file_type: 'csv',
                from_date: state.from_date,
                to_date: state.to_date,
                company_id:state.companyid,
                type_id:state.typeid,
            }

            axios.post(`/download-report/${state.formid}`, input)
                .then(res => {
                    let file = res.data;    
                    let report = state.forms.filter(report => report.id == state.formid)
                    const file_name = `${state.formid}_${slugify(report[0].title)}.csv`
                    download(file, file_name);
                    setLoading(false)
                }).catch(error => {
                    const { message } = error.response.data
                    swal("Warning!", message, "error")
                    setLoading(false)
                })
        }
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
                            Generate Report
                    </div>
                    </div>
                </div>
            </div>
            <div className="main-card mb-2 card">
                <div className='card-header'>
                    <div className='card-title'>
                        Generate Report of A Company
                </div>
                    <div className="btn-wrapper btn-wrapper-multiple">
                        
                    </div>
                </div>
                <div className="card-body">
                    <div className="row">
                        <div className="col-12">
                            <div className="row mb-3">
                                <div className="col-md-4 mt-1">
                                    <select className="form-control form-title" onChange={e => setCompanyId(e, e.target.value)}>
                                        <option value="" disable="true">Select Company</option>
                                        {state.companies.map((company, index) => (
                                            <option key={index} value={company.id}>{company.company_name}</option>
                                        ))}
                                    </select>
                                </div>
                                <div className="col-md-4 mt-1">
                                    <select className="form-control assign-to-role" id="triggerName" onChange={e => handleFormType(e, e.target.value)} >
                                        <option value="" disable="true">Select Form Type </option>
                                        <option  value="1">Entity Form</option>
                                        <option  value="2">Report Form</option>
                                    </select>
                                </div>
                                <div className="col-md-4 mt-1">
                                    <select className="form-control form-title"  onChange={e => setFormId(e, e.target.value)}>
                                        <option value="" disable="true">Select Form</option>
                                        {state.forms.map((form, index) => (
                                            <option key={index} value={form.id}>{form.title}</option>
                                        ))}
                                    </select>
                                </div>
                            </div>
                            
                            <div className="row mt-2">
                                <div className="form-group col-md-4">
                                    <label>From Date</label>
                                    <div>
                                        <input className="form-control" type="date" id="from-date" name="from_date" onChange={e => handleChange(e)} />
                                    </div>
                                </div>
                                <div className="form-group col-md-4">
                                    <label>To Date</label>
                                    <div>
                                        <input className="form-control" type="date" id="to-date" name="to_date" onChange={e => handleChange(e)} />
                                    </div>
                                </div>
                                <div className="form-group col-md-4 mt-4">
                                    <button type="submit" className="btn btn-primary mr-1" onClick={e => handleGenerateReport(e)}
                                    disabled={!(state.typeid && state.companyid && state.from_date && state.to_date) || loading}>Generate Report</button>
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
