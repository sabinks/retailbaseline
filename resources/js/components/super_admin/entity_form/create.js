import React, { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { ReactFormBuilder } from 'react-form-builder2';
import { formToolbar } from '../../../utils/form';
import swal from 'sweetalert';
import axios from 'axios'
import { useHistory } from "react-router-dom";
import Index from '../entityRoute'
function FormCreate() {
    let history = useHistory();
    const [state, setState] = useState({
        title: '',
        entity_id: '',
        creator_id: null,
        client_id: null,
        toolbarItems: [...formToolbar],
        errors: [],
        role: null,
        company: null,
        entities_list: []
    })
    const [inputData, setInputData] = useState([])
    useEffect(() => {
        // $("body").on('DOMSubtreeModified', ".Sortable", function() {
        //     refactorDefaultDynamicFormUi()
        // });
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
    }, [])

    function refactorDefaultDynamicFormUi() {
        $('div.btn-school').addClass(['btn-outline-dark']);
        $('input[name=text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28]').closest('.SortableItem.rfb-item').find('.is-isolated.fa.fa-trash-o').parent().hide();
    }
    const handleFieldChange = (event) => {
        const { name, value } = event.target
        setState(state => ({
            ...state, [name]: value
        }))
    }

    const onPost = ({ task_data }) => {
        task_data.filter((input) => {
            if (typeof input.static != undefined)
                return true
        })
        setInputData(task_data)
    }

    const handleClickSave = (event) => {
        event.preventDefault()

        const inputs = {
            form_title: state.title,
            data: inputData ? JSON.stringify(inputData) : null
        }

        axios.post('/superadmin/entity-form', inputs)
            .then(res => {
                const { message } = res.data
                swal("Success!", message, "success")
                setTimeout(() => {
                    history.push('/super/entity-data/list')
                }, 3000);
            })
            .catch(error => {
                console.log(error.response)
                const { message } = error.response.data
                swal("Warning!", message, "error")
                setState({
                    ...state, errors: error.response.data.errors
                })
            })

    }

    return (
        <div>
            {
                state.toolbarItems &&
                <>
                    <div className="main-card mb-1 card">

                        <div className="card-header">
                            <div className='card-title'>
                                Create Form using Toolbar
                            </div>
                            {/* <Index /> */}
                        </div>

                        <div className="card-body">
                            <div className="row">
                                <div className="form-group col-lg-12 col-sm-12 col-12">
                                    <label htmlFor="report_title">Entity Form Title</label>
                                    <input onChange={handleFieldChange} value={state.title} className="form-control" id="title" name='title' />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="main-card mb-1 card">
                        <div className='card-header'>
                            You can only change the label name of default input fields
                        </div>
                    </div>

                    <ReactFormBuilder url='/form_data/default_entity_form_inputs.json' toolbarItems={state.toolbarItems} onPost={onPost} />
                </>
            }
            <Link className='btn btn-secondary pull-right mr-3' to={'/super-report'}>
                Cancel
            </Link>
            <button onClick={handleClickSave} className="btn btn-primary pull-right mr-3" style={{ marginRight: '10px' }} id="button-save">
                Save Form
            </button>
        </div>
    )
}

export default FormCreate
