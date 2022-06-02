import React, { Component } from 'react';
import axios from 'axios'
import { Link } from 'react-router-dom'
import swal from 'sweetalert';
class FormDataList extends Component {
    constructor() {
        super()
        this.state = {
            formData: null,
            form: null,
            formDataInputValues: null,
            filterformInputs: [],
            role: null
        }
        this.handleDeleteFormDatum = this.handleDeleteFormDatum.bind(this)
        // this.DataTable = null;

    }

    handleDeleteFormDatum(event, formId, formDatumId) {
        event.preventDefault()
        // const {formDatas} = this.state
        swal({
            title: "Warning!",
            text: "Are you sure you wish to delete this form data?",
            icon: "warning",
            dangerMode: true,
        })
            .then(res => {
                if (res) {
                    axios.delete(`/entities-forms/${formId}/entities-form-data/${formDatumId}`)
                        .then(response => {
                            // redirect to the homepage
                            // let refinedFormDatas = formDatas.filter((formData) => {
                            //     return formDataId !== formData.id;
                            // });
                            // this.setState({
                            //     formDatas: refinedFormDatas
                            // });
                            window.location.reload();

                        })
                        .catch(error => {
                            console.log(error.response)
                        })
                }
            })

    }

    async componentDidMount() {
        try {
            const formId = this.props.match.params.form
            const formData = await axios.get(`/entities-forms/${formId}/entities-form-data`);
            let form = formData.data.form;
            if (formData) {
                const filterformInputs = form.inputs.filter((input) => {
                    if (input.element !== "Header") {
                        return input
                    }
                })
                let mappedFormdata = formData.data.formData;
                mappedFormdata.forEach((formDatum) => {
                    return formDatum.input_datas.forEach((inputData) => {
                        if (!form.inputs.find(input => input.field_name === inputData.name)) {
                            return inputData.value = ""
                        }
                        if (inputData.name.indexOf("dropdown_") > -1) {
                            let formInput = form.inputs.find(input => input.field_name === inputData.name)
                            formInput.options.map((option) => {
                                if (option.value == inputData.value) {

                                    return inputData.value = option.text
                                }
                            })
                        } else if (inputData.name.indexOf("tags_") > -1) {
                            inputData.value.map((v, index) => {
                                inputData.value[index] = v.text
                            })
                            return inputData.value;
                        } else if (inputData.name.indexOf("checkboxes_") > -1) {
                            let formInput = form.inputs.find(input => input.field_name === inputData.name)
                            formInput.options.map((option) => {
                                inputData.value.map((v, index) => {
                                    if (option.key == v) {

                                        inputData.value[index] = option.text
                                    }
                                })
                                return inputData.value;
                            })
                        } else if (inputData.name.indexOf("radiobuttons_") > -1) {
                            let formInput = form.inputs.find(input => input.field_name === inputData.name)
                            formInput.options.map((option) => {
                                if (typeof inputData.value !== "string") {
                                    inputData.value.map((v) => {
                                        if (option.key == v) {

                                            inputData.value = option.text
                                        }
                                    })
                                }
                                return inputData.value;
                            })
                        } else {
                            return inputData.value
                        }

                    })
                })
                this.setState({
                    formData: formData.data.formData,
                    form,
                    filterformInputs,
                    role: formData.data.role
                });
                $('.dataTable').DataTable();
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        } catch (error) {
            console.log('Error', error)
        }

    }

    render() {
        const { formData, form, filterformInputs, role } = this.state
        return (
            <>
                <div className="main-card mb-3 card">
                    <div className='card-header'>
                        {role && <>
                            {(role == "Field Staff") ?
                                <>View, add, edit or delete the Entities Tracking Form data Listed for {form.form_title}}</> :
                                <>View the Entities Tracking Form data Listed for&nbsp; <b><em>"{form.form_title}"</em></b>&nbsp; of&nbsp; <b>{form.clients[0].company_name}</b> </>
                            }
                        </>}

                    </div>
                    <div className="card-body">
                        {form && <>
                            {role == 'Field Staff' && <Link className='btn btn-primary btn-sm mr-3' to={`/entities-form/${form.id}/entities-form-data/create`} >
                                Create a New Entities Tracking Form Datum
                    </Link>}
                            <a className='btn btn-primary btn-sm mr-3' href={`/entities-form/`} >
                                Go To Entities tracking Form List
                    </a></>}
                        <div className="table-responsive">
                            {formData && <table className="table table-stripedfalse table-bordered dataTable">
                                <thead>
                                    <tr>
                                        <th>Form Filler Name</th>
                                        <th>Region</th>
                                        <th>Point (coordinate)</th>
                                        {
                                            filterformInputs.map((input, index) =>
                                                <th key={index} dangerouslySetInnerHTML={{ __html: input.label }}></th>
                                            )
                                        }
                                        {role == 'Field Staff' &&
                                            <th>Action</th>
                                        }
                                    </tr>
                                </thead>
                                <tbody>
                                    {formData.map((formDatum, index) =>
                                        <tr key={"tr" + index}>
                                            <td>{formDatum.form_filler.name} </td>
                                            <td>{formDatum.region.name} </td>
                                            <td>{formDatum.latitude}<br />{formDatum.longitude} </td>
                                            {formDatum.input_datas.map((inputData, index) =>
                                                <td key={"td" + index}>
                                                    {(typeof inputData.value == "object" && inputData.value) ? inputData.value.map((formDataInputValue, index) => <span key={index}>{formDataInputValue} <br /></span>) : null}
                                                    {(typeof inputData.value == "string") ? inputData.name.indexOf('camera_') > -1 ? <img className="img-fluid" src={inputData.value} alt="" /> : inputData.value : null}
                                                </td>
                                            )}
                                            {role == 'Field Staff' &&
                                                <td>
                                                    <div className='btn-group'>

                                                        <a href="#!" className='btn btn-primary btn-sm'>
                                                            <i className="fa fa-eye"></i>
                                                        </a>
                                                        <Link className='btn btn-secondary btn-sm' to={`/entities-form/${form.id}/entities-form-data/${formDatum.id}`}>
                                                            <i className="fa fa-pencil"></i>
                                                        </Link>
                                                        <a href="#!" onClick={(e) => this.handleDeleteFormDatum(e, form.id, formDatum.id)} className='btn btn-danger btn-sm'>
                                                            <i className="fa fa-trash-o"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            }
                                        </tr>
                                    )}
                                </tbody>
                            </table>}

                        </div>
                    </div>
                </div></>
        )
    }
}

export default FormDataList