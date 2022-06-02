import React, { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { Modal, Button } from 'react-bootstrap'
function list() {
    const [items, setItems] = useState([])
    const [categories, setCategories] = useState([])
    const [item, setItem] = useState({
        id: '',
        name: '',
        description: '',
        category_id: '',
        // brand: '',
    })
    const [loading, setLoading] = useState(false)
    useEffect(() => {
        axios.get('/stock/item').then(res => {
            const { item_list } = res.data
            if (item_list){
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }

                setItems(item_list)
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                // $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                // $('.dataTables_filter').css("float", "left")
            }
        })
    }, [])
    useEffect(() => {
        axios.get(`/stock/category`)
            .then(res => {
                const { category_list } = res.data
                setCategories(category_list)
            })
    }, [])
    const [show, setShow] = useState(false);
    const handleClose = () => setShow(false)
    const handleShow = () => setShow(true)
    const openModal = (name, description, category_id, id) => {
        setItem(prev => ({
            ...prev, name, description, id, category_id
        }))
        handleShow()
    }
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
    const submitUpdate = () => {
        setLoading(true)
        axios.put(`/stock/item/${item.id}`, item)
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
    const handleDelete = (e, id) => {
        e.preventDefault()
        swal({
            title: "Warning!",
            text: "Are you sure to delete item?",
            icon: "warning",
            dangerMode: true,
        })
            .then(res => {
                if (res) {
                    setLoading(true)
                    axios.delete(`/stock/item/${id}`)
                        .then(res => {
                            const { message } = res.data
                            swal("Success!", message, "success")
                            setTimeout(() => {
                                window.location.replace('/stock/item-list')
                            }, 2000);
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
            <div className='card-header pb-0 pt-1'>
                <div className='card-title'>
                    Item List
                    </div>
                <div className="btn-wrapper btn-wrapper-multiple">
                    <Link className='btn btn-primary btn-sm mr-3 mb-2' to={'/stock/item-create'}>Create Item</Link>
                </div>
            </div>
            <div className="card-body">
                <Modal show={show} onHide={handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>Edit Category</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="form-group col-md-8">
                            <label>Name</label>
                            <div>
                                <input className="form-control" type="input" name="name" value={item.name} onChange={e => handleChange(e)} />
                            </div>
                        </div>
                        <div className="form-group col-md-8">
                            <label>Description</label>
                            <div>
                                <input className="form-control" type="input" name="description" value={item.description} onChange={e => handleChange(e)} />
                            </div>
                        </div>
                        {/* <div className="form-group col-md-8">
                            <label>Brand</label>
                            <div>
                                <input className="form-control" type="input" name="brand" value={item.brand} onChange={e => handleChange(e)} />
                            </div>
                        </div> */}
                        <div className="form-group col-md-6">
                            <label >Select Category</label>
                            <div>
                                <select className="form-control" id="" onChange={e => handleCategoryChange(e, e.target.value)} defaultValue={item.category_id}>
                                    {
                                        categories.map((category, index) => (
                                            <option key={index} value={category.id} >{category.name}</option>
                                        ))
                                    }
                                </select>
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
                                {/* <th>Brand</th> */}
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {items && items.map((item, index) =>
                                <tr key={index}>
                                    <td>{index + 1}</td>
                                    <td>{item.name}</td>
                                    <td>{item.description}</td>
                                    {/* <td>{item.brand}</td> */}
                                    <td>{item.categories.name}</td>
                                    <td>
                                        <div className='btn-group'>
                                            <a className="btn btn-success btn-sm mr-1" onClick={e => openModal(item.name, item.description, item.category_id, item.id)}><i className="fa fa-pencil"></i></a>
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

export default list