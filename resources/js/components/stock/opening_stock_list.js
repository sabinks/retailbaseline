import React, { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { Modal, Button } from 'react-bootstrap'
function opening_stock_list() {
    const [items, setItems] = useState([])
    const [categories, setCategories] = useState([])
    const [inwardStock, setInwardStock] = useState({
        id: '',
        quantity: '',
        entry_date: '',
        particular: ''
    })
    const [category, setCategory] = useState({
        id: 0
    })
    const [loading, setLoading] = useState(false)
    useEffect(() => {
        axios.get(`/stock/open-stock/${category.id}`).then(res => {
            const { item_list } = res.data
            if (item_list) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setItems(item_list)
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
            }
        })

    }, [category.id])
    useEffect(() => {
        axios.get('/stock/category').then(res => {
            const { category_list } = res.data
            setCategories(category_list)
        })
    }, [])
    const [show, setShow] = useState(false);
    const handleClose = () => setShow(false)
    const handleShow = () => setShow(true)
    const openModal = (quantity, entry_date, particular, id) => {
        setInwardStock(prev => ({
            ...prev, quantity, entry_date, particular, id
        }))
        handleShow()
    }
    const handleChange = (event) => {
        const { name, value } = event.target
        setInwardStock(prev => ({
            ...prev, [name]: value
        }))
    }
    const handleCategoryChange = (e) => {
        const { value } = e.target
        setCategory(prev => ({
            ...prev, id: value
        }))
    }
    const submitUpdate = () => {
        setLoading(true)
        axios.put(`/stock/open-stock/${inwardStock.id}`, inwardStock)
            .then(res => {
                const { message } = res.data
                swal("Warning!", message, "success")
                setLoading(false)
                setTimeout(() => {
                    window.location.replace('/stock/opening-list')
                }, 2000);
            }).catch(error => {
                const { message } = error.response.data
                swal("Warning!", message, "error")
                setLoading(false)
            })
    }
    const handleDelete = (e, id) => {
        e.preventDefault()
        swal({
            title: "Warning!",
            text: "Are you sure to delete inward stock?",
            icon: "warning",
            dangerMode: true,
        })
            .then(res => {
                if (res) {
                    setLoading(true)
                    axios.delete(`/stock/open-stock/${id}`)
                        .then(res => {
                            const { message } = res.data
                            swal("Success!", message, "success")
                            setTimeout(() => {
                                window.location.replace('/stock/opening-list')
                            }, 3000);
                        })
                        .catch(error => {
                            const { message } = error.response.data
                            swal("Warning!", message, "error")
                            setLoading(false)
                        })
                }
            });
    }


    return (
        <div className="main-card mb-2 card">
            <div className='card-header'>
                <div className='card-title'>
                    Opening Stock
                </div>
                <div>
                    <select className="form-control" name="id" onChange={e => handleCategoryChange(e)}>
                        <option value="" disable="true">Please choose one</option>
                        <option value="0">Select All</option>
                        {
                            categories.map((category, index) => (
                                <option key={index} value={category.id}>{category.name}</option>
                            ))
                        }
                    </select>
                </div>
            </div>
            <div className="card-body">
                <Modal show={show} onHide={handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>Edit Inward Stock</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="form-group col-md-8">
                            <label>Quantity</label>
                            <div>
                                <input className="form-control" type="input" name="quantity" value={inwardStock.quantity} onChange={e => handleChange(e)} />
                            </div>
                        </div>
                        <div className="form-group col-md-8">
                            <label>Entry Date</label>
                            <div>
                                <input className="form-control" type="date" name="entry_date" value={inwardStock.entry_date} onChange={e => handleChange(e)} />
                            </div>
                        </div>
                        <div className="form-group col-md-8">
                            <label>Particular</label>
                            <div>
                                <input className="form-control" type="input" name="particular" value={inwardStock.particular} onChange={e => handleChange(e)} />
                            </div>
                        </div>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button disabled={loading} variant="primary" size="sm" onClick={e => submitUpdate()}>
                            Update
                            </Button>
                    </Modal.Footer>
                </Modal>
                <div className="table-responsive">
                    <table className="table table-striped table-bordered dataTableReact">
                        <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Inward Stock</th>
                                <th>Entry Date</th>
                                <th>Particular</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {items && items.map((item, index) =>
                                <tr key={index}>
                                    <td>{index + 1}</td>
                                    <td>{item.item.name}</td>
                                    <td>{item.item.description}</td>
                                    <td>{item.item.category.name}</td>
                                    <td>{item.quantity}</td>
                                    <td>{item.entry_date}</td>
                                    <td>{item.particular}</td>
                                    <td>
                                        <div className='btn-group'>
                                            <a className="btn btn-success btn-sm mr-1" onClick={e => openModal(item.quantity, item.entry_date, item.particular, item.id)}><i className="fa fa-pencil"></i></a>
                                            <a href="#!" onClick={(e) => { handleDelete(e, item.id) }}
                                                disabled={loading}
                                                className='btn btn-danger btn-sm'>
                                                <i className="fa fa-trash-o"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    )
}

export default opening_stock_list