import axios from 'axios'
import React, { useState, useEffect } from 'react';
import { ReactFormBuilder } from 'react-form-builder2';
import { useHistory } from "react-router-dom";

function ViewForm() {
    const [state, setState] = useState({
        form_title: '',
        client_id: '',
        formData: null,
        errors: [],
        companies: [],
        role: ''
    })
    const [inputData, setInputData] = useState([])
    const handleFieldChange = (event) => {
        const { name, value } = event.target
        setState(prev => ({
            ...prev,
            [name]: value
        }))
    }

    useEffect(() => {
        let formId = window.location.pathname.replace('/entities-form-view/', '')
        axios.get('/entities-forms/' + formId)
            .then(res => {
                const { role, companies, entitiesForm } = res.data
                if (entitiesForm) {
                    setState(prev => ({
                        ...prev,
                        form_title: entitiesForm.form_title,
                        client_id: entitiesForm.clients[0].id,
                        formData: entitiesForm,
                        companies: companies,
                        role
                    }))
                    setInputData(entitiesForm.inputs)
                
                    $(".react-form-builder-toolbar").hide()
                }
            });
    }, [])

  
    return (
        <>
            {state.formData &&
                <>
                    <div className="main-card mb-3 card">
                        <div className='card-header'>
                            View Form 
                            <br />
                        </div>
                        <div className="card-body">
                            <div className="row">
                                <div className="form-group col-lg-6 col-sm-6 col-12">
                                    <label htmlFor="form_title">Form Title</label>
                                    <input onChange={handleFieldChange} value={state.form_title} className="form-control" id="form_title" name='form_title' />
                                </div>
                                {
                                    state.role == 'Super Admin' &&
                                    <div className="form-group col-lg-6 col-sm-6 col-12 ">
                                        <label htmlFor="client_id">Select Company</label>
                                        <select className="form-control" value={state.client_id} id='client_id' name="client_id" onChange={handleFieldChange}>
                                            <option value="0" disabled>Select Client Company</option>
                                            {state.companies.map(company =>
                                                <option key={company.id} value={company.id}>{company.company_name}</option>
                                            )}
                                        </select>
                                    </div>
                                }
                            </div>
                        </div>
                    </div>
                    {/* <div className="main-card mb-3 card">
                        <div className='card-header'>
                            You can only view input fields
                        </div>
                    </div> */}
                    <ReactFormBuilder data={inputData} toolbarItems={[]}/>
                </>
            }
        </>
    )
}

export default ViewForm