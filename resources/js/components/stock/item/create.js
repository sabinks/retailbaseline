import React, { useState, useEffect } from 'react'
import axios from 'axios'
import swal from 'sweetalert';
function create() {
    const [item, setItem] = useState({
        name: '',
        description: '',
        category_id: '',
        brand: ''
    })
    const [categories, setCategories] = useState([])
    const [loading, setLoading] = useState(false)
    const handleChange = (event) => {
        const { name, value } = event.target
        setItem(prev => ({
            ...prev, [name]: value
        }))
    }
    const handleCategoryChange = (e, value) => {
        setItem(prev => ({
            ...prev, category_id: value
        }))
    }

    useEffect(() => {
        axios.get(`/stock/category`)
            .then(res => {
                const { category_list } = res.data
                setCategories(category_list)
            }).catch(error => {
                const { message } = error.response.data
                swal("Warning!", message, "error")
                setLoading(false)
            })
    }, [])

    const handleSubmit = () => {
        setLoading(true)
        axios.post(`/stock/item`, item)
            .then(res => {
                const { message } = res.data
                swal("Warning!", message, "success")
                setLoading(false)
                setTimeout(() => {
                    window.location.replace('/stock/item-list')
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
                <h4>Create Item</h4>
            </div>
            <div className="card-body pb-0 pt-2">

                <div className="form-group col-md-6">
                    <label htmlFor="name">Name</label>
                    <input type="input" className="form-control" name="name"
                        placeholder="Enter item name"
                        onChange={e => handleChange(e)} />
                </div>
                <div className="form-group col-md-6">
                    <label htmlFor="description">Description</label>
                    <input type="input" className="form-control" name="description"
                        placeholder="Enter item description"
                        onChange={e => handleChange(e)} />
                </div>
                {/* <div className="form-group col-md-6">
                    <label htmlFor="brand">Brand</label>
                    <input type="input" className="form-control" name="brand"
                        placeholder="Enter item brand"
                        onChange={e => handleChange(e)} />
                </div> */}
                <div className="form-group col-md-6">
                    <label htmlFor="category">Select Category</label>
                    <select className="form-control" id="" onChange={e => handleCategoryChange(e, e.target.value)} >
                        <option value="" disable="true">Please choose one</option>
                        {
                            categories.map((category, index) => (
                                <option key={index} value={category.id}>{category.name}</option>
                            ))
                        }
                    </select>
                </div>

                <button type="submit" disabled={loading} className="btn btn-primary" onClick={e => handleSubmit()}>Submit</button>
            </div>

        </div>
    )
}

export default create
