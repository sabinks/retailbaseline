import axios from 'axios'
import React, { Component } from 'react';
import { ReactFormGenerator } from 'react-form-builder2';

class CreateInputData extends Component {
    constructor () {
        super()
        this.state = {
            name:'',
            image:'',
            address:'',
            latitude:'',
            longitude:'',
            form: null,
            JSON_QUESTION_DATA: null,
            errors: [],
            uploadImage: null
        }
        this.images = []
        this.onHandleSubmit= this.onHandleSubmit.bind(this)
        this.handleFieldChange = this.handleFieldChange.bind(this)
        this.handleImageChange = this.handleImageChange.bind(this)
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
            const form = await axios.get(`/entities-forms/${formId}`)
            if (form) {
                this.setState({
                    form: form.data,
                    JSON_QUESTION_DATA: form.data.inputs
                });
                $('div.btn-school').addClass(['btn-outline-dark']);
                $('.btn-agree').addClass(['btn-success','mr-1']);
                $('.btn-cancel').addClass('btn-secondary');
            }

            this.state.JSON_QUESTION_DATA.map((input)=>{
                if(input.element == 'Camera'){
                $(`input[name=${input.field_name}]`).change((e)=>{
                    this.handleImageChange(e, input.field_name)
                });
                    
                }
            })
        } catch (error) {
            console.log(error.response)
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

    onHandleSubmit(data){
        // let formattedData = data.map(inputData=>{
        //     let obj = {};
        //     obj[inputData.name] = inputData.value
        //     return obj;
        // });
        const {form} = this.state
        let formData = new FormData();
        this.images.map((inputData)=>{
            formData.append(inputData.name, inputData.value);
        })
        formData.append("input_datas", JSON.stringify(data));
        formData.append("name", this.state.name);
        formData.append("image", this.state.image);
        formData.append("address", this.state.address);
        formData.append("longitude", this.state.longitude);
        formData.append("latitude", this.state.latitude);

        const config = {     
            headers: { 'content-type': 'multipart/form-data' }
        }
        const { history } = this.props
        axios.post(`/entities-forms/${form.id}/entities-form-data`, formData, config)
          .then(response => {
            // redirect to the homepage
            history.push(`/entities-form/${form.id}/entities-form-data`)
          })
          .catch(error => {
            console.log(error)
            this.setState({
                errors: error.response.data.errors
            })
          })
        
        
    }

    render () {
        const {form, JSON_QUESTION_DATA} = this.state

        return (
        <>
                    
                    {/* { (JSON_QUESTION_DATA)? JSON_QUESTION_DATA.map( (input, index) => 
                        <div key={index}><ul><li>{input.element}</li> </ul></div>
                    ):<p></p>} */}
                    <div className="main-card mb-3 card">
                        <div className='card-header'>
                            Add entity tracking form data for {form && <>{form.form_title}</>}
                            
                        </div>
                        <div className="card-body">
                            {form && <form encType='multipart/form-data'>
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
                                    <img className="img-fluid" src="" id="profile-img-tag"/>
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
                            {form && <ReactFormGenerator
                                action_name="Save"  
                                onSubmit={this.onHandleSubmit}
                                data={JSON_QUESTION_DATA} // Question data
                                answer_data={{}}
                                back_action={`/entities-form/${form.id}/entities-form-data/`}
                            />}
                        </div>
                    </div>
        </>
        )
    }
}

export default CreateInputData