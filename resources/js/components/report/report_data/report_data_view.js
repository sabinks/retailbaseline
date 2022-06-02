import React, { useState, useEffect } from 'react';
import axios from 'axios'
import swal from 'sweetalert';
import { useHistory, useParams } from "react-router-dom";
import { statusConversion } from '../../../utils/functions'
let url = document.getElementById('route').getAttribute('url')
const ReportDataView = () => {
    let history = useHistory();
    const { id }= useParams()
    console.log(id)
    const [state, setState] = useState({
        id: id,
        title: '',
        status: '',
        loading: true,
        role: null,
        image_path: '',
        can_update: false,
        entity_name: '',
        url: 'http://localhost:8000'
    })
    const [reportData, setReportData] = useState([])
    const [formData, setFormData] = useState([])
    const [reportImage, setReportImage] = useState([])

    useEffect(() => {
        // let id = window.location.pathname.replace('/report-info/view/', '')
        console.log(id)
        getReportData(id)
    }, [])

    const getReportData = (report_id) => {
        axios.get(`/report-data/${report_id}`).then(res => {
            url = window.location.origin
            const { question, answer, image_path, status, role, title, can_update, entity_name } = res.data
            setReportData(answer)
            setFormData(question)
            setState(prev => ({ ...prev, id: report_id, title, status, url, entity_name, image_path, can_update, role, loading: false }))
        }).catch(err => {
            const { message } = err.response.data
            swal("Warning!", message, "error")
            setReportData([])
            setFormData([])
            setState(prev => ({ ...prev, loading: false }))
        })
    }
    const handleChange = (status) => {
        const input = {
            status: status
        }
        axios.patch(`/report-data/${state.id}`, input)
            .then(res => {
                const { message, report_detail, role } = res.data
                setState(prev => ({ ...prev, status: report_detail.status, loading: false, role }))
                swal("Success!", message, "success")
            }).catch(error => {
                const { message } = error.response.data
                swal("warning!", message, "error")
            })

    }

    return (
        <>
            {
                reportData.length > 0 ?
                    <div>
                        <div className="main-card card mb-1">
                            <div className="card-body pb-0 pt-2">
                                <h4>Report Data View</h4>
                            </div>
                            {
                                !state.loading &&
                                <div className="card-body pb-0 pt-2">
                                    <div className="row">
                                        <div className="col-md-3"><h6>Entity: <b>{state.entity_name}</b></h6></div>
                                        <div className="col-md-3"><h6>Title: <b>{state.title}</b></h6></div>
                                        <div className="col-md-3">Report Status: <b>{statusConversion(state.status)}</b></div>
                                        {
                                            state.can_update &&
                                            <div className="col-md-3">
                                                <button className="btn btn-sm btn-success mr-1 float-right" onClick={e => handleChange('approved')}>Approve</button>
                                                <button className="btn btn-sm btn-danger mr-1 float-right" onClick={e => handleChange('rejected')}>Reject</button>
                                            </div>
                                        }
                                    </div>
                                </div>
                            }
                        </div>

                        <div className="main-card card">
                            <div className="col-md-12 mb-0 pb-0 pt-2 float-left">
                                {
                                    formData.length > 0 && reportData.length > 0 &&
                                    formData.map((question, index) => {
                                        return <div key={index} className="">
                                            <div className="mb-2 card p-2" style={{ display: question.element != "Header" && 'none' }}>
                                                <div className="text-primary">{question.label}</div>
                                                {/* <div>Answer: {reportData[index]}</div> */}
                                            </div>
                                            <div className="mb-2 card p-2 text-primary" style={{ display: question.element != "Camera" && 'none' }}>
                                                <div className="mb-1">Q ) {question.label}</div>
                                                <div>
                                                    <img className="img-thumbnail" src={`${state.url}/${state.image_path}/${reportData[index]}`} alt={reportData[index]} />
                                                </div>
                                            </div>
                                            <div className="mb-2 card p-2" style={{ display: (question.element == "Camera" || question.element == "Header") && 'none' }}>
                                                <div className="mb-1 text-primary">Q ) {question.label}</div>
                                                <div className="text-success">A ) {reportData[index]}</div>
                                            </div>
                                        </div>
                                    })
                                }
                            </div>
                        </div>
                    </div>
                    :
                    <div className="alert alert-info" role="alert">
                        Data fill pending from field staff!
                    </div>
            }
        </>
    )
}

export default ReportDataView