import React, { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { ReactFormBuilder } from 'react-form-builder2';
import { formToolbar } from '../../../utils/form';
import swal from 'sweetalert';
import axios from 'axios'
const ReportView = () => {
    const [state, setState] = useState({
        title: '',
        toolbarItems: [...formToolbar],
        errors: [],
        role: null,
    })
    const [loading, setLoading] = useState(true)
    const [inputData, setInputData] = useState([])

    useEffect(() => {
        let id = window.location.pathname.replace('/report-info/', '')
        axios.get(`/report-detail/${id}`).then(res => {
            const { data, title, id } = res.data.report
            setState({
                ...state, title, id
            })
            setInputData(
                JSON.parse(data)
            )

            $(".clientSelect").select2({
                placeholder: "Select Client",
                allowClear: true
            });

            $("#client_id").on("change",(event) => handleFieldChange(event) );

            $("body").on('DOMSubtreeModified', ".Sortable", function() {
                refactorDefaultDynamicFormUi()
            });
            refactorDefaultDynamicFormUi()

            $('.Sortable').each(function() {
                new MutationObserver(function() {
                    refactorDefaultDynamicFormUi()
                }).observe(this, {childList: true, subtree: true})
            });

            $(".edit-form").each( function() {
                new MutationObserver(function() {
                    $('.edit-form').find("label").filter(function() {
                        return $(this).text() === "Read only";
                    }).parent().hide();
                    $('.edit-form .dynamic-option-list .col-sm-2').filter(function() {
                        return $(this).text() === "Value";
                    }).hide();
                    $('.edit-form').find('label[for=optionsApiUrl]').parent().hide();
                    $('.edit-form').find('input[name^="value_"]').hide();
                    $('.edit-form').find('.rdw-editor-toolbar').hide();
                }).observe(this, {childList: true, subtree: true});
            });
            setLoading(false)
        }).catch(error => {            
            const {message} = error.response.data
            swal("Warning!", message, "error")
            // setTimeout(() => {
            //     window.location.replace('/report-info')
            // }, 3000);
        })
    },[])

    function refactorDefaultDynamicFormUi() {
        $('div.btn-school').addClass(['btn-outline-dark']);
        $('input[name=camera_F744BA9F-AB13-4012-BE55-F0467C30A534]').closest('.SortableItem.rfb-item').find('.is-isolated.fa.fa-trash-o').parent().hide();
        $('input[name=text_input_DD26AA9E-EEF0-454A-B961-7DEEEF974E35]').closest('.SortableItem.rfb-item').find('.is-isolated.fa.fa-trash-o').parent().hide();
        $('input[name=text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28]').closest('.SortableItem.rfb-item').find('.is-isolated.fa.fa-trash-o').parent().hide();
    }
    const handleFieldChange = (event) => {
        const { name, value } = event.target
        setState({
            ...state, [name]: value
        })
    }

    const hasErrorFor = (field) => {
        return !!state.errors ? state.errors[field] : false
    }

    const onPost = ({task_data}) => {
        task_data.filter((input) => {
            if(typeof input.static != undefined)
                return true
        })
        setInputData(task_data)
    }

    const handleClickSave = (event) => {
        event.preventDefault()

        const input = {
            title: state.title,
            data: inputData ? JSON.stringify(inputData) : null
        }

        axios.patch(`/report/${state.id}`, input)
            .then(res => {
                const {message} =res.data
                swal("Success!", message, "success")
                setTimeout(() => {
                    window.location.replace('/report-form')
                }, 3000);
            })
            .catch(error => {
                setState({
                    ...state, errors: error.response.data.errors
                })
            })

    }

    const renderErrorFor = (field) => {
        if (hasErrorFor(field)) {
            return (
                field == 'inputs' ? <a className="alert alert-danger">{state.errors[field][0]}</a> : <a className="text-danger">{state.errors[field][0]}</a>
            )
        }
    }
    
    return (
        <div>
            {
                !loading &&
                <>
                    <div className="main-card mb-3 card">
                        <div className='card-header'>
                            Create Form using Toolbar <br />
                        </div>
                        <div className="card-body">
                            <div className="row">
                                <div className="form-group col-lg-12 col-sm-12 col-12">
                                    <label htmlFor="report_title">Report Title</label>
                                    <input onChange={handleFieldChange} value={state.title} className="form-control" id="title" name='title' />
                                    {renderErrorFor('title')}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="main-card mb-3 card">
                        <div className='card-header'>
                        You can only change input field name and list
                        </div>
                    </div>
                    {renderErrorFor('data')}
                    <ReactFormBuilder data={inputData} toolbarItems={state.toolbarItems} onPost={onPost} />
                </>
            }
        </div>
    )
}

export default ReportView