import React, { useState, useEffect } from 'react';
import axios from 'axios'
import swal from 'sweetalert';
import { useHistory } from "react-router-dom";
import { statusConversionOther } from '../../utils/functions'
let url = document.getElementById('route').getAttribute('url')
const ViewFormData = () => {
    let history = useHistory();
    const [state, setState] = useState({
        id: '',
        title: '',
        status: '',
        loading: true,
        role: null,
        image_path: '',
        url: 'http://localhost:8000'
    })
    const [entityData, setEntityData] = useState([])
    const [formData, setFormData] = useState([])

    useEffect(() => {
        let id = window.location.pathname.replace('/entity-data-view/', '')
        getEntityFormData(id)
    }, [])

    const getEntityFormData = (entity_id) => {
        axios.get(`/entity-info-view/${entity_id}`).then(res => {
            url = window.location.origin
            const { answer, question, status, image_path, role, title, name } = res.data
            setEntityData(answer)
            setFormData(question)
            setState(prev => ({ ...prev, id: entity_id, status, url, image_path, role, name, loading: false, title }))
        }).catch(err => {
            const { message } = err.response.data
            swal("Warning!", message, "error")
        })
    }
    const handleChange = (changeStatus) => {
        axios.post(`/entity-data-approve-reject/${changeStatus}/${state.id}`)
            .then(res => {
                const { message, status } = res.data
                setState(prev => ({ ...prev, status, loading: false }))
                swal("Success!", message, "success")
            }).catch(error => {
                const { message } = error.response.data
                swal("Warning!", message, "warning")
            })
    }

    return (
        <>
            <div className="main-card card mb-1">
                <div className="card-body pb-0 pt-2">
                    <h4>Entity Data View</h4>
                </div>
                {
                    !state.loading &&
                    <div className="card-body pb-0 pt-2">
                        <div className="row">
                            <div className="col-md-3"><h5>Title: <b>{state.name}</b></h5></div>
                            <div className="col-md-4"><h5>Title: <b>{state.title}</b></h5></div>
                            <div className="col-md-3">Report Status: <b>{statusConversionOther(state.status)}</b></div>
                            <div className="col-md-2">
                                <button className="btn btn-sm btn-success mr-1 float-right" onClick={e => handleChange('accepted')}>Approve</button>
                                <button className="btn btn-sm btn-danger mr-1 float-right" onClick={e => handleChange('rejected')}>Reject</button>
                            </div>
                        </div>
                    </div>
                }
            </div>

            <div className="main-card card">
                <div className="col-md-12 mb-0 pb-0 pt-2 float-left">
                    {
                        formData.length > 0 &&
                        formData.map((question, index) => {
                            return <div key={index} className="">
                                <div className="mb-2 card p-2" style={{ display: question.element != "Header" && 'none' }}>
                                    <div className="text-primary">{question.label}</div>
                                    {/* <div>Answer: {entityData[index]}</div> */}
                                </div>
                                <div className="mb-2 card p-2 text-primary" style={{ display: question.element != "Camera" && 'none' }}>
                                    <div className="mb-1">Q ) {question.label}</div>
                                    <div>
                                        <img className="img-thumbnail" src={`${state.url}/${entityData[index]}`} alt={entityData[index]} />
                                    </div>
                                </div>
                                <div className="mb-2 card p-2" style={{ display: (question.element == "Camera" || question.element == "Header") && 'none' }}>
                                    <div className="mb-1 text-primary">Q ) {question.label}</div>
                                    <div className="text-success">A ) {entityData[index]}</div>
                                </div>
                            </div>
                        })
                    }
                </div>
            </div>
        </>
    )
}

export default ViewFormData