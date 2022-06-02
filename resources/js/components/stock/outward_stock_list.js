import React, { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { Modal, Button } from 'react-bootstrap'
import swal from 'sweetalert';
import download from 'downloadjs'
function outward_stock_list() {
    const [items_list, setItemsList] = useState([])
    const [items, setItems] = useState([])
    const [categories, setCategories] = useState([])
    const [outwardStock, setOutwardStock] = useState({
        category_id: '',
        from_date: '',
        to_date: '',
        bulk_image: 0
    })
    const [category, setCategory] = useState({
        id: 0
    })
    const [item, setItem] = useState({
        id: 0
    })
    const [loading, setLoading] = useState(false)
    useEffect(() => {
        axios.get(`/stock/outward-stock/${category.id}`).then(res => {
            const { item_list } = res.data
            if (item_list) {
                // let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setItemsList(item_list)
                $('.dataTableReact').DataTable({
                    "lengthChange": true,
                    "pageLength": 25,
                })
            }
        })

    }, [category.id])
    useEffect(() => {
        axios.get('/stock/category').then(res => {
            const { category_list } = res.data
            let list = [
                { id: 0, name: 'Select All', description: 'Select All' },
                ...category_list]
            setCategories(list)
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
    const [show, setShow] = useState(false);
    const handleClose = () => setShow(false)
    const handleShow = () => setShow(true)
    const openModal = (category_id) => {
        setOutwardStock(prev => ({
            ...prev, category_id
        }))
        handleShow()
    }
    const handleCheckboxChange = (e) => {
        let { checked } = e.target
        setOutwardStock(prev => ({
            ...prev, bulk_image: checked ? 1 : 0
        }))
    }
    const handleChange = (event) => {
        const { name, value } = event.target
        setOutwardStock(prev => ({
            ...prev, [name]: value
        }))
    }
    const handleCategoryChange = (e) => {
        const { value } = e.target
        setCategory(prev => ({
            ...prev, id: value
        }))
    }
    const handleItemChange = (e) => {
        const { value } = e.target
        setItem(prev => ({
            ...prev, id: value
        }))
    }
    const downloadReport = () => {
        setLoading(true)
        const { from_date, to_date, bulk_image } = outwardStock

        axios.get(`/stock/generate-outward-stock-report?category_id=${category.id ? category.id : ''}&item_id=${item.id ? item.id : ''}&from_date=${from_date}&to_date=${to_date}&bulk_image=${bulk_image}`, {
            responseType: 'arraybuffer',
            headers: {
                'content-type': 'application/zip',
            }
        })
            .then(res => {
                let file = res.data;
                const file_name = `report.zip`
                download(file, file_name)
                setLoading(false)
            }).catch(error => {
                const { status } = error.response
                if (status == 422) {
                    let error_message = ''
                    if (category.id == 0) {
                        error_message += '\n' + 'Please select category.'
                    }
                    if (item.id == 0) {
                        error_message += '\n' + 'Please select item.'
                    }
                    if (from_date == '') {
                        error_message += '\n' + 'Please select from date.'
                    }
                    if (to_date == '') {
                        error_message += '\n' + 'Please select to date.'
                    }
                    swal("Warning!", error_message, "error")
                }
                if (status == 404) {
                    swal("Warning!", 'No record found.', "error")
                }
                setLoading(false)
            })
    }

    const handleDelete = (e, id) => {
        console.log(id);
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
                    axios.delete(`/stock/outward-stock/${id}`)
                        .then(res => {
                            const { message } = res.data
                            swal("Success!", message, "success")
                            setTimeout(() => {
                                window.location.replace('/stock/outward-list')
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
            <div className='card-header pb-1 pt-1'>
                <div className='card-title col-md-8'>
                    Outward Stock
                </div>
                <div className="col-md-3">
                    <select className="form-control" name="id" onChange={e => handleCategoryChange(e, e.target.value)} value={category.id}>
                        <option value="" disable="true">Please choose one</option>

                        {
                            categories.map((category, index) => (
                                <option key={index} value={category.id}>{category.name}</option>
                            ))
                        }
                    </select>
                </div>

                <div className="col-md-1">
                    <a className="btn btn-success btn-sm mr-1" onClick={e => openModal(category.id)}><i className="fa fa-pencil"></i></a>
                </div>

            </div>
            <div className="card-body">
                <Modal show={show} onHide={handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>Generate Outward Stock Report</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="form-group col-md-8">
                            <label htmlFor="name">Category Name:</label>
                            <select className="form-control" id="" onChange={e => handleCategoryChange(e, e.target.value)}
                                value={category.id}>
                                <option value="" disable="true">Please select category</option>

                                {
                                    categories.map((category, index) => (
                                        <option key={index} value={category.id}>{category.name}</option>
                                    ))
                                }
                            </select>
                        </div>
                        {
                            items.length > 0 && <div className="form-group col-md-8">
                                <label htmlFor="name">Item Name:</label>
                                <select className="form-control" id="" onChange={e => handleItemChange(e, e.target.value)}
                                    value={item.id}>
                                    <option value="" disable="true">Please select item</option>
                                    {
                                        items.map((item, index) => (
                                            <option key={index} value={item.id}>{item.name}</option>
                                        ))
                                    }
                                </select>
                            </div>
                        }
                        <div className="form-group col-md-8">
                            <label>From Date</label>
                            <div>
                                <input className="form-control" type="date" name="from_date" value={outwardStock.from_date} onChange={e => handleChange(e)} />
                            </div>
                        </div>
                        <div className="form-group col-md-8">
                            <label>To Date</label>
                            <div>
                                <input className="form-control" type="date" name="to_date" value={outwardStock.to_date} onChange={e => handleChange(e)} />
                            </div>
                        </div>
                        <div className="form-check col-md-8">
                            <input type="checkbox" className="form-check-input" id="bulkImage" name="bulk_image" checked={outwardStock.bulk_image} onChange={e => handleCheckboxChange(e)} />
                            <label className="form-check-label" for="bulkImage">Bulk Image Download</label>
                        </div>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button disabled={loading} variant="primary" size="sm" onClick={e => downloadReport()}>
                            Download Report
                        </Button>
                    </Modal.Footer>
                </Modal>
                <div className="table-responsive">
                    <table className="table table-striped table-bordered dataTableReact">
                        <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Customer Name</th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Unique ID</th>
                                <th>Filled By</th>
                                <th>Filled Date</th>
                                <th>Sync Date</th>
                                <th>Document Type</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {items_list && items_list.map((outward, index) =>
                                <tr key={index}>
                                    <td>{index + 1}</td>
                                    <td>{outward.name}</td>
                                    <td>{outward.item.name}</td>
                                    <td>{outward.item.category.name}</td>
                                    <td>{outward.unique_id}</td>
                                    <td>{outward.staff.name}</td>
                                    <td>{outward.filled_date}</td>
                                    <td>{outward.sync_date}</td>
                                    <td>{outward.document_type.name}</td>
                                    <td>{outward.amount ? outward.amount : '-'}</td>
                                    <td>
                                        <div className='btn-group'>
                                            <Link className="btn btn-success btn-sm mr-1" to={`/stock/outward-item-detail/${outward.id}`}><i className="fa fa-eye"></i></Link>

                                            {/* <a className="btn btn-success btn-sm mr-1" onClick={e => openModal(item.quantity, item.entry_date, item.particular, item.id)}><i className="fa fa-pencil"></i></a> */}
                                            <a href="#!" onClick={(e) => { handleDelete(e, outward.id) }}
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

export default outward_stock_list