import React, { useState, useEffect } from 'react';
import axios from 'axios'
import swal from 'sweetalert';
import { slugify } from '../../../utils/functions'
import download from 'downloadjs'
const GenerateReport = () => {

    const [state, setState] = useState({
        title: '',
        id: '',
        role: '',
        loading: true,
        from_date: '',
        to_date: '',
        all_report_lists: [],
        url: 'http://locahost:8000',
    })
    const [loading, setLoading] = useState(false)
    useEffect(() => {
        let url = window.location.origin
        axios.get(`all-report-list`)
            .then(res => {
                const { role, all_report_lists } = res.data
                setState(prev => ({ ...prev, role, all_report_lists, loading: false }))
            }).catch(error => {
                swal("Warning!", 'No Report Found.', "error")
            })
    }, [])

    const handleChange = (e) => {
        const { name, value } = e.target
        setState(state => ({
            ...state, [name]: value
        }))
    }
    const handleGenerateReport = () => {
        if (!state.from_date || !state.to_date || !state.id) {
            swal("Warning!", 'Parameter missing.', "error")
        }
        else {
            const input = {
                file_type: 'csv',
                from_date: state.from_date,
                to_date: state.to_date,
            }
            setLoading(true)
            axios.post(`/generate-report/${state.id}`, input)
                .then(res => {
                    let file = res.data;
                    let report = state.all_report_lists.filter(report => report.id == state.id)
                    const file_name = `${state.id}_${slugify(report[0].title)}.csv`
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
        <div className="main-card card">
            <div className='card-header'>
                Generate Report
            </div>
            <div className="card-body">
                <div className="form-group col-md-6">
                    <label>Select Report Form</label>
                    {
                        state.all_report_lists.length > 0 &&
                        <select name="id" className="form-control" onChange={e => handleChange(e)}>
                            <option value="" disable="true">Select One Report Form</option>
                            {
                                state.all_report_lists.map(report => {
                                    return <option value={report.id} key={report.id}>{report.title}</option>
                                })
                            }
                        </select>
                    }
                </div>
                <div className="form-group col-md-6">
                    <label>From Date</label>
                    <div>
                        <input className="form-control" type="date" id="from-date" name="from_date" onChange={e => handleChange(e)} />
                    </div>
                </div>
                <div className="form-group col-md-6">
                    <label>To Date</label>
                    <div>
                        <input className="form-control" type="date" id="to-date" name="to_date" onChange={e => handleChange(e)} />
                    </div>
                </div>
                <div className="form-group col-md-6">
                    <button className="btn btn-primary mr-1" disabled={loading} onClick={e => handleGenerateReport(e)}>Generate Report</button>
                    {/* <a href={`${state.url}/generate-report/${state.id}/${state.assigned_date}/xlsx`} className="btn btn-sm btn-secondary mr-1">Generate XLSX Report</a> */}
                </div>
            </div>
        </div>
    )
}

export default GenerateReport