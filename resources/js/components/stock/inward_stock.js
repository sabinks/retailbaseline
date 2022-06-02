import React, { useState, useEffect } from 'react'
import axios from 'axios'
import swal from 'sweetalert';

function inward() {
    const [items, setItems] = useState([])
    const [item, setItem] = useState({
        item_id: '',
        entry_date:'',
        quantity: '',
        particular: '',
    })
    const [category, setCategory] = useState({
        id: ''
    })
    const [categories, setCategories] = useState([])
    const [loading, setLoading] = useState(false)
    useEffect(() => {
        axios.get(`/stock/category`)
            .then(res => {
                const { category_list } = res.data
                setCategories(category_list)
            })
    }, [])
    useEffect(() => {
        if (category.id) {
            axios.get(`/stock/item/category/${category.id}`)
                .then(res => {
                    const { item_list } = res.data
                    setItems(item_list)
                })
        }
    }, [category.id])

    const handleChange = (event) => {
        const { name, value } = event.target
        console.log(name, value)
        setItem(prev => ({
            ...prev, [name]: value
        }))
    }
    const handleCategoryChange = (e, value) => {
        setCategory(prev => ({
            ...prev, id: value
        }))
    }


    const handleSubmit = () => {
        setLoading(true)
        axios.post(`/stock/inward-stock`, item)
            .then(res => {
                const { message } = res.data
                swal("Warning!", message, "success")
                setLoading(false)
                setTimeout(() => {
                    window.location.replace('/stock-register')
                }, 2000);
            }).catch(error => {
                const { message } = error.response.data
                // console.log(error.response.data.errors)
                swal("Warning!", 'Form filling error!', "error")
                setLoading(false)
            })
    }

    return (
        <div className="main-card card mb-1">
            <div className="card-body pb-0 pt-1">
                <h4>Create Item Stock</h4>
            </div>
            <div className="card-body pb-0 pt-2">
                <div className="form-group col-md-6">
                    <label htmlFor="name">Category</label>
                    <select className="form-control" id="" onChange={e => handleCategoryChange(e, e.target.value)} >
                        <option value="" disable="true">Please select category</option>
                        {
                            categories.map((category, index) => (
                                <option key={index} value={category.id}>{category.name}</option>
                            ))
                        }
                    </select>
                </div>
                <div className="form-group col-md-6">
                    <label htmlFor="name">Name</label>
                    <select className="form-control" name="item_id" onChange={e => handleChange(e)} >
                        <option value="" disable="true">Please choose one</option>
                        {
                            items.map((item, index) => (
                                <option key={index} value={item.id}>{item.name}</option>
                            ))
                        }
                    </select>
                </div>
                <div className="form-group col-md-6">
                    <label htmlFor="quantity">Quantity</label>
                    <input type="input" className="form-control" name="quantity"
                        placeholder="Enter opening quantity"
                        onChange={e => handleChange(e)} />
                </div>
                <div className="form-group col-md-6">
                    <label htmlFor="brand">Entry Date</label>
                    <input type="date" className="form-control" name="entry_date"
                        placeholder="Enter entry date"
                        onChange={e => handleChange(e)} />
                </div>
                <div className="form-group col-md-6">
                    <label htmlFor="brand">Particular</label>
                    <input type="input" className="form-control" name="particular"
                        placeholder="Enter enter particular"
                        onChange={e => handleChange(e)} />
                </div>


                <button type="submit" disabled={loading} className="btn btn-primary" onClick={e => handleSubmit()}>Submit</button>
            </div>

        </div>
    )
}

export default inward
