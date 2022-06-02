import axios from 'axios'
import React, { Component } from 'react';
import { ReactFormGenerator } from 'react-form-builder2';

class UpdateInputData extends Component {
    constructor () {
        super()
        this.state = {
            name:'',
            image:'',
            address:'',
            latitude:'',
            longitude:'',
            JSON_QUESTION_DATA: null,
            JSON_ANSWER_DATA: null,
            formId: null,
            formDatum: null,
            errors: [],
            uploadImage: null
        }
        this.images = []
        this.onHandleSubmit= this.onHandleSubmit.bind(this)
        this.handleFieldChange = this.handleFieldChange.bind(this)
        this.handleImageChange = this.handleImageChange.bind(this);
        this.handleInputImageChange = this.handleInputImageChange.bind(this)
        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    handleFieldChange (event) {
        this.setState({
          [event.target.name]: event.target.value
        })
    }

    hasErrorFor (field) {
        return !!this.state.errors[field]
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <a className="text-danger">{this.state.errors[field][0]}</a>
            )
        }
    }

    async componentDidMount(){
        try {
            const formId = this.props.match.params.form
            const formDatumId = this.props.match.params.formData
            const formDatumResponse = await axios.get(`/entities-forms/${formId}/entities-form-data/${formDatumId}`);
            const formDatum = formDatumResponse.data;
            if (formDatum ) {
                this.setState({
                    formId: formId,
                    formDatum,
                    JSON_QUESTION_DATA: formDatum.entities_form.inputs ,
                    JSON_ANSWER_DATA: formDatum.input_datas,
                    name:formDatum.name,
                    image:formDatum.image,
                    address:formDatum.address,
                    latitude:formDatum.latitude,
                    longitude:formDatum.longitude,
                });

                this.state.JSON_QUESTION_DATA.map((input)=>{
                    if(input.element == 'Camera'){
                    $(`input[name=${input.field_name}]`).change((e)=>{
                        this.handleImageChange(e, input.field_name)
                    });
                        
                    }
                })
                $('div.btn-school').addClass(['btn-outline-dark']);
                $('.btn-agree').addClass(['btn-success','mr-1']);
                $('.btn-cancel').addClass('btn-secondary');
            }
        } catch (error) {
            console.log(error)
        }
 
    }

    handleImageChange(e, fieldName){
        const file = e.target.files[0];
        let tempArr = [...this.images]
        if (tempArr.length > 0) {
            if(tempArr.find(input => input.name === fieldName)){
                tempArr.map((tr,index)=>{
                    if (tr.name===fieldName) {
                        this.images[index].value =file
                    }
                })
            }else{
                this.images.push({name: fieldName, value: file});
            }
        }else{
            this.images.push({name: fieldName, value: file});
        }
    }

    handleInputImageChange(event){
        const file = event.target.files[0];
        var fileName = event.target.value.split("\\").pop();
        this.setState({
            [event.target.name]: file,
            uploadImage: fileName
        })
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function (e) {
                    $('#profile-img-tag').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        readURL(event.target)
    }

    onHandleSubmit (data){
        const {formDatum, formId } = this.state
        const { history } = this.props
        let formInputData = new FormData();
        this.images.map((inputData)=>{
            formInputData.append(inputData.name, inputData.value);
        })
        formInputData.append("input_datas", JSON.stringify(data));
        formInputData.append("_method", 'PUT');
        formInputData.append("name", this.state.name);
        formInputData.append("image", this.state.image);
        formInputData.append("address", this.state.address);
        formInputData.append("longitude", this.state.longitude);
        formInputData.append("latitude", this.state.latitude);

        const config = {     
            headers: { 'content-type': 'multipart/form-data' }
        }
        axios.post(`/entities-forms/${formId}/entities-form-data/${formDatum.id}`, formInputData)
          .then(response => {
            // redirect to the homepage
            history.push(`/entities-form/${formId}/entities-form-data`)
          })
          .catch(error => {
            console.log(error.response)
            this.setState({
                errors: error.response.data.errors
            })
          })

    }

    render () {
        const {formDatum, formId, JSON_QUESTION_DATA, JSON_ANSWER_DATA} = this.state
        return (
        <>
                   
                    {/* { (JSON_QUESTION_DATA)? JSON_QUESTION_DATA.map( (input, index) => 
                        <div key={index}><ul><li>{input.element}</li> </ul></div>
                    ):<p></p>} */}
                    <div className="main-card mb-3 card">
                    <div className='card-header'>
                            Update entities tracking form data for {formDatum && <>{formDatum.entities_form.form_title}</>}
                            
                        </div>
                        <div className="card-body">
                        {formDatum && <form encType='multipart/form-data'>
                            <div className="row">
                                <div className="form-group col-lg-6 col-sm-6 col-12">
                                    <label htmlFor="name">Vendor Name</label>
                                    <input onChange={this.handleFieldChange} value={this.state.name} id='name' name='name' className="form-control" />
                                    {this.renderErrorFor('name')}
                                </div>
                                <div className='form-group col-lg-3 col-sm-3 col-6'>
                                    <label htmlFor="image">Entity Image</label>
                                    <div className="custom-file">
                                        <input onChange={this.handleInputImageChange} type="file" className="custom-file-input" id="image" name="image"/>
                                    
                                        <label className="custom-file-label" htmlFor="image">
                                            <span className={`d-inline-block text-truncate ${this.state.uploadImage?'selected':null}`}>{this.state.uploadImage?this.state.uploadImage:"Choose an image..."}</span>
                                        </label>
                                        {this.renderErrorFor('image')}
                                    </div>
                                </div>
                                <div className='form-group col-lg-3 col-sm-3 col-6'>
                                    <img className="img-fluid" src={this.state.image} id="profile-img-tag"/>
                                </div>
                                <div className="form-group col-lg-6 col-sm-6 col-12">
                                    <label htmlFor="address">Address</label>
                                    <input onChange={this.handleFieldChange} value={this.state.address} id='address' name='address' className="form-control" />
                                    {this.renderErrorFor('address')}
                                </div>
                                <div className="form-group col-lg-6 col-sm-6 col-12">
                                    <label htmlFor="latitude">Latitude</label>
                                    <input onChange={this.handleFieldChange} type='number' value={this.state.latitude} id='latitude' name='latitude' className="form-control" />
                                    {this.renderErrorFor('latitude')}
                                </div>
                                <div className="form-group col-lg-6 col-sm-6 col-12">
                                    <label htmlFor="longitude">Longitude</label>
                                    <input onChange={this.handleFieldChange} type='number' value={this.state.longitude} id='longitude' name='longitude' className="form-control" />
                                    {this.renderErrorFor('longitude')}
                                </div>
                            </div>
                            </form>}
                            {formDatum && <ReactFormGenerator 
                                action_name="Update"
                                onSubmit={this.onHandleSubmit}    
                                data={JSON_QUESTION_DATA} // Question data
                                answer_data={JSON_ANSWER_DATA} // answer data
                                back_action={`/entities-form/${formId}/entities-form-data/`}
                            />}
                        </div>
                    </div>
        </>
        )
    }
}

export default UpdateInputData