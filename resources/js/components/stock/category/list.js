import React, { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { Modal, Button } from 'react-bootstrap'
function list() {
    const [categories, setCategories] = useState([])
    const [category, setCategory] = useState({
        id: '',
        name: '',
        description: ''
    })
    const [loading, setLoading] = useState(false)
    useEffect(() => {
        axios.get('/stock/category').then(res => {
            const { category_list } = res.data
            if (category_list) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setCategories(category_list)

                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
            }
        })
    }, [])
    const [show, setShow] = useState(false);
    const handleClose = () => setShow(false)
    const handleShow = () => setShow(true)
    const openModal = (name, description, id) => {
        setCategory(prev => ({
            ...prev, name, description, id
        }))
        handleShow()
    }
    const handleChange = (event) => {
        const { name, value } = event.target
        setCategory(prev => ({
            ...prev, [name]: value
        }))
    }
    const submitUpdate = () => {
        setLoading(true)
        axios.put(`/stock/category/${category.id}`, category)
            .then(res => {
                const { message } = res.data
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
    const handleDelete = (e, id) => {
        e.preventDefault()
        swal({
            title: "Warning!",
            text: "Are you sure to delete category?",
            icon: "warning",
            dangerMode: true,
        })
            .then(res => {
                if (res) {
                    setLoading(true)
                    axios.delete(`/stock/category/${id}`)
                        .then(res => {
                            const { message } = res.data
                            swal("Success!", message, "success")
                            setTimeout(() => {
                                window.location.replace('/stock/category-list')
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
            <div className='card-header pb-0 pt-1'>
                <div className='card-title'>
                    Category List
                    </div>
                <div className="btn-wrapper btn-wrapper-multiple">
                    <Link className='btn btn-primary btn-sm mr-3 mb-2' to={'/stock/category-create'}>Create Category</Link>
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
                                <input className="form-control" type="input" name="name" value={category.name} onChange={e => handleChange(e)} />
                            </div>
                        </div>
                        <div className="form-group col-md-8">
                            <label>Description</label>
                            <div>
                                <input className="form-control" type="input" name="description" value={category.description} onChange={e => handleChange(e)} />
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {categories && categories.map((category, index) =>
                                <tr key={index}>
                                    <td>{index + 1}</td>
                                    <td>{category.name}</td>
                                    <td>{category.description}</td>
                                    <td>
                                        <div className='btn-group'>
                                            <a className="btn btn-success btn-sm mr-1" onClick={e => openModal(category.name, category.description, category.id)}><i className="fa fa-pencil"></i></a>
                                            <a href="#!" onClick={(e) => { handleDelete(e, category.id) }} 
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