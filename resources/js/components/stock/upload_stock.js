import React, { useState, useEffect } from 'react'
import axios from 'axios'
import swal from 'sweetalert'
function UploadStock() {
    const [items, setItems] = useState([])
    const [item, setItem] = useState({
        item_id: '',
        file: ''
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
        setItem(prev => ({
            ...prev, [name]: value
        }))
    }
    const handleCategoryChange = (e, value) => {
        setCategory(prev => ({
            ...prev, id: value
        }))
    }
    const onFileChange = (event) => {
        event.persist()
        setItem(prev => ({
            ...prev, file: event.target.files[0]
        }))
    }

    const handleSubmit = () => {
        setLoading(true)
        const formData = new FormData();
        formData.append('excel_file', item.file)
        formData.append('item_id', item.item_id)
        formData.append('category_id', category.id)
        axios.post(`/stock/upload-stock-item`, formData)
            .then(res => {
                console.log(res)
                const { message } = res.data
                swal("Warning!", message, "success")

                setTimeout(() => {
                    window.location.replace('/stock/upload-stock')
                }, 2000);
                setLoading(false)
            }).catch(error => {
                const { errors, message } = error.response.data
                if (errors) {
                    let error_message = ''
                    const { item_id, category_id, excel_file } = errors
                    console.log(item_id)
                    item_id ? error_message = item_id[0] : ''
                    category_id ? error_message += '\n' + category_id[0] : ''
                    excel_file ? error_message += '\n' + excel_file[0] : ''
                    swal("Warning!", error_message, "error")
                }
                if (message) {
                    swal("Warning!", message, "error")
                }


                setLoading(false)
            })
    }

    return (
        <div className="main-card card mb-2">
            <div className="card-body pb-0 pt-2">
                <h4>Upload Stock</h4>
            </div>
            <div className="card-body pb-0 pt-2">
                <div className="form-group col-md-6">
                    <label htmlFor="name">Category Name:</label>
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
                    <label htmlFor="name">Item Name: </label>
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
                    <label htmlFor="name">Upload Stock File:</label>
                    <div>
                        <input type="file" name="file" onChange={onFileChange} />
                    </div>

                </div>

                <div className="form-group col-md-6">
                    <button type="submit" disabled={loading} className="btn btn-primary" onClick={e => handleSubmit()}>Submit</button>
                </div>

                <div className="form-group col-md-6">
                    Note: <a href="/stock/download-stock-template-file" className="text-decoration-none">Download Template File</a>
                </div>
            </div>


        </div>
    )
}

export default UploadStock
