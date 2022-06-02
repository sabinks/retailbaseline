import axios from 'axios'
import { Link } from 'react-router-dom'
import React, { useState, useEffect } from 'react';
import { ReactFormBuilder } from 'react-form-builder2';
import { formToolbar } from "../../utils/form";
import { useHistory } from "react-router-dom";
import swal from 'sweetalert';

function UpdateInputsList() {
    let history = useHistory();
    const [state, setState] = useState({
        form_title: '',
        client_id: '',
        toolbarItems: [...formToolbar],
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

    const hasErrorFor = (field) => {
        return !!state.errors ? state.errors[field] : false
    }

    const renderErrorFor = (field) => {
        if (hasErrorFor(field)) {
            return (
                field == 'inputs' ? <a className="alert alert-danger">{state.errors[field][0]}</a> : <a className="text-danger">{state.errors[field][0]}</a>
            )
        }
    }
    useEffect(() => {
        let formId = window.location.pathname.replace('/entities-form/', '')
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
                    $(".clientSelect").select2({
                        placeholder: "Select Client",
                        allowClear: true
                    });

                    $("#client_id").on("change", (event) => handleFieldChange(event));
                    // $("#client_id").val(state.client_id);

                    $("body").on('DOMSubtreeModified', ".Sortable", function () {
                        refactorDefaultDynamicFormUi()
                    });
                    refactorDefaultDynamicFormUi()

                    $('.Sortable').each(function () {

                        new MutationObserver(function () {
                            refactorDefaultDynamicFormUi()
                        }).observe(this, { childList: true, subtree: true })
                    });


                    $(".edit-form").each(function () {
                        new MutationObserver(function () {
                            $('.edit-form').find("label").filter(function () {
                                return $(this).text() === "Read only";
                            }).parent().hide();
                            $('.edit-form .dynamic-option-list .col-sm-2').filter(function () {
                                return $(this).text() === "Value";
                            }).hide();
                            $('.edit-form').find('label[for=optionsApiUrl]').parent().hide();
                            $('.edit-form').find('input[name^="value_"]').hide();
                            $('.edit-form').find('.rdw-editor-toolbar').hide();
                        }).observe(this, { childList: true, subtree: true });
                    });
                }
            });
    }, [])

    function refactorDefaultDynamicFormUi() {
        $('div.btn-school').addClass(['btn-outline-dark']);
        $('input[name=camera_F744BA9F-AB13-4012-BE55-F0467C30A534]').closest('.SortableItem.rfb-item').find('.is-isolated.fa.fa-trash-o').parent().hide();
        $('input[name=text_input_DD26AA9E-EEF0-454A-B961-7DEEEF974E35]').closest('.SortableItem.rfb-item').find('.is-isolated.fa.fa-trash-o').parent().hide();
        $('input[name=text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28]').closest('.SortableItem.rfb-item').find('.is-isolated.fa.fa-trash-o').parent().hide();
    }
    const onPost = ({ task_data }) => {
        task_data.filter((input) => {
            if (typeof input.static != undefined)
                return true
        })
        setInputData(task_data)
    }

    const handleClickUpdate = (event) => {
        event.preventDefault()
        const { formData, form_title, client_id } = state
        const formInputData = {
            form_title,
            client_id,
            inputs: JSON.stringify(inputData)
        }

        axios.put('/entities-forms/' + formData.id, formInputData)
            .then(response => {
                swal('Success!', 'Successfully Updated!', 'success')
                history.push('/entities-form')
            })
            .catch(error => {
                const { message } = error.response.data
                swal('Warning!', message ? message : 'Something went wrong!', 'error')
                setState(prev => ({
                    ...prev,
                    errors: error.response.data.errors
                }))
            })
    }
    return (
        <>
            {state.formData &&
                <>
                    <div className="main-card mb-3 card">
                        <div className='card-header'>
                            Update Form using Toolbar <br />
                        </div>
                        <div className="card-body">
                            <div className="row">
                                <div className="form-group col-lg-6 col-sm-6 col-12">
                                    <label htmlFor="form_title">Form Title</label>
                                    <input onChange={handleFieldChange} value={state.form_title} className="form-control" id="form_title" name='form_title' />
                                    { renderErrorFor('form_title') }
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
                                        {renderErrorFor('client_id')}
                                    </div>
                                }
                            </div>
                        </div>
                    </div>
                    <div className="main-card mb-3 card">
                        <div className='card-header'>
                            You can only change input field name and list  
                        </div>
                    </div>
                    {renderErrorFor('inputs')}
                    <ReactFormBuilder data={inputData} toolbarItems={state.toolbarItems} onPost={onPost} />
                </>
            }
            {(state.formData) ?
                <>
                    <Link className='btn btn-secondary pull-right mr-3' to={'/entities-form/'}>
                        Cancel
                    </Link>
                    <button onClick={handleClickUpdate} className="btn btn-primary pull-right" style={{ marginRight: '10px' }} id="button-update">Update Form</button></> :
                null
            }
        </>
    )
}

export default UpdateInputsList