import React, { useState, useEffect } from 'react'
import axios from 'axios'
import swal from 'sweetalert';
function create() {
    const [category, setCategory] = useState({
        name: '',
        description: '',
    })
    const [loading, setLoading] = useState(false)
    const handleChange = (event) => {
        const {name, value} = event.target 
        setCategory(prev => ({
            ...prev, [name]: value
        }))
    }
    
    const handleSubmit = () => {
        setLoading(true)
        axios.post(`/stock/category`, category)
        .then(res => {
            const {message} = res.data
            swal("Warning!", message, "success")
            setLoading(false)
            setTimeout(() => {
                window.location.replace('/stock/category-list')
            }, 2000);
        }).catch(error => {
            const { message } = error.response.data
            swal("Warning!", message, "error")
            setLoading(false)
        })
    }

    return (
        <div className="main-card card mb-1">
            <div className="card-body pb-0 pt-2">
                <h4>Create Category</h4>
            </div>
            <div className="card-body pb-0 pt-2">

                <div className="form-group col-md-6">
                    <label for="name">Name</label>
                    <input type="input" className="form-control" name="name"
                        placeholder="Enter category name" 
                        onChange={e => handleChange(e)}/>
                </div>
                <div className="form-group col-md-6">
                    <label for="description">Description</label>
                    <input type="input" className="form-control" name="description" 
                    placeholder="Enter category description" 
                    onChange={e => handleChange(e)}/>
                </div>
            
                <button type="submit"  disabled={loading} className="btn btn-primary" onClick={ e => handleSubmit()}>Submit</button>
            </div>

        </div>
    )
}

export default create
