import axios from 'axios'
import { Link } from 'react-router-dom'
import React, { Component, lazy, Suspense } from 'react';
import { ReactFormBuilder } from 'react-form-builder2';
import {formToolbar} from "../../utils/form";
import {defaultEntityFormInputs} from "../../utils/default_entity_form_inputs";
import swal from 'sweetalert';

class CreateInputsList extends Component {
    constructor () {
        super()
        this.state = {
            form_title:'',
            client_id: '',
            toolbarItems: formToolbar,
            inputData: {task_data:defaultEntityFormInputs},
            errors: [],
            role: null,
            companies: null
        }
        this.onPost= this.onPost.bind(this)
        this.handleFieldChange = this.handleFieldChange.bind(this)
        this.handleClickSave= this.handleClickSave.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    handleFieldChange (event) {
        this.setState({
          [event.target.name]: event.target.value
        })
    }

    hasErrorFor (field) {
        return !!this.state.errors?this.state.errors[field]:false
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                field == 'inputs'?<a className="alert alert-danger">{this.state.errors[field][0]}</a>:<a className="text-danger">{this.state.errors[field][0]}</a>
            )
        }
    }

    async componentDidMount(){
        try {
            const formCreate = await axios.get('/entities-forms/create')
            if (formCreate) {
                if(formCreate.data.role=="Super Admin"){
                    this.setState({
                        role: formCreate.data.role,
                        companies: formCreate.data.companies
                    });
                }else if(formCreate.data.role=="Admin" || formCreate.data.role=="Regional Admin"){
                    this.setState({
                        role: formCreate.data.role
                    });
                }

                $(".clientSelect").select2({
                    placeholder: "Select Client",
                    allowClear: true
                });

                $("#client_id").on("change",(event) => this.handleFieldChange(event) );



                function refactorDefaultDynamicFormUi() {
                    $('div.btn-school').addClass(['btn-outline-dark']);
                    $('input[name=camera_F744BA9F-AB13-4012-BE55-F0467C30A534]').closest('.SortableItem.rfb-item').find('.is-isolated.fa.fa-trash-o').parent().hide();
                    $('input[name=text_input_DD26AA9E-EEF0-454A-B961-7DEEEF974E35]').closest('.SortableItem.rfb-item').find('.is-isolated.fa.fa-trash-o').parent().hide();
                    $('input[name=text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28]').closest('.SortableItem.rfb-item').find('.is-isolated.fa.fa-trash-o').parent().hide();
                }
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
            }
        } catch (error) {
            console.log(error)
        }
 
    }
    
    onPost(data){
        let empty = false;
        data.task_data.map( (input) => {
            if(typeof input == 'undefined'){empty=true;return;}
        })
        if (!empty) {
            this.setState({
                inputData: data
            });
        }else{
            const { history } = this.props
            history.push('/entities-form/create')
        }
    }

    handleClickSave (event){
        event.preventDefault()

        const inputData = {
            form_title: this.state.form_title,
            client_id: this.state.client_id,
            inputs: this.state.inputData?JSON.stringify(this.state.inputData.task_data):null
        }
        const { history } = this.props

        axios.post('/entities-forms', inputData)
          .then(response => {
            // redirect to the homepage
            swal('Successful!!', 'From successfully created', 'success')
            history.push('/entities-form')
          })
          .catch(error => {
            console.log(error.response)
            swal('Oops...', 'Something went wrong!', 'error')
            this.setState({
                errors: error.response.data.errors
            })
          })

    }

    render () {
        const {inputData, toolbarItems, role, companies} = this.state
        return (
            <>
            {/* { (inputData)? inputData.task_data.map( (input, index) => 
                <div key={index}><ul><li>{input.element}</li> </ul></div>
            ):<p></p>} */}
            {(toolbarItems  && role !== 'Field Staff' && role !== null) && 
            <>
                <div className="main-card mb-3 card">
                    <div className='card-header'>
                        Create Form using Toolbar <br/>
                    </div>
                    <div className="card-body">
                        <div className="row">
                            <div className="form-group col-lg-12 col-sm-12 col-12">
                                <label htmlFor="form_title">Form Title</label>
                                <input onChange={this.handleFieldChange} value={this.state.form_title} className="form-control" id="form_title" name='form_title' />
                                {this.renderErrorFor('form_title')}
                            </div>
                            { role == 'Super Admin' && 
                            <div className="form-group col-lg-6 col-sm-6 col-12 ">
                                <select className="clientSelect form-control" value={this.state.client_id} id='client_id' name="client_id">
                                    <option></option>
                                    {companies.map(company=>
                                        <option key={company.id} value={company.id}>{company.company_name}</option>
                                    )}
                                </select>
                                {this.renderErrorFor('client_id')}
                            </div>
                            }
                        </div>
                    </div>
                </div>
                <div className="main-card mb-3 card">
                    <div className='card-header'>
                        You can only change the label name of default input fields
                    </div>
                </div>  
                {this.renderErrorFor('inputs')}
                <ReactFormBuilder url='/form_data/default_entity_form_inputs.json' toolbarItems={toolbarItems} onPost={this.onPost} />
            </>
            }
            <Link className='btn btn-secondary pull-right mr-3' to={'/entities-form/'}>
                Cancel
            </Link>
            {role !== 'Field Staff' && <button onClick={this.handleClickSave} className="btn btn-primary pull-right mr-3" style={{marginRight: '10px'}} id="button-save">Save Form</button>}
            </>
        )
    }
}

export default CreateInputsList