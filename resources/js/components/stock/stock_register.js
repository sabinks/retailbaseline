import React, { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { Modal, Button } from 'react-bootstrap'
import download from 'downloadjs'
function list() {
    const [items, setItems] = useState([])
    const [categories, setCategories] = useState([])
    const [item, setItem] = useState({
        id: '',
        name: '',
        description: '',
        report: 0,
        from_date: '',
        to_date: ''
    })
    const [reportType, setReportType] = useState([
        { id: 1, name: 'All' },
        { id: 2, name: 'Opening Stock' },
        { id: 3, name: 'Inward Stock' },
        { id: 4, name: 'Outward' }
    ])
    const [category, setCategory] = useState({
        id: 0
    })
    const [loading, setLoading] = useState(false)
    useEffect(() => {
        axios.get(`/stock/stock-register/${category.id}`).then(res => {
            const { item_list } = res.data
            if (item_list) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                let data = item_list.map(item => ({
                    id: item.id, name: item.name, description: item.description, category: item.category,
                    sum_inward_stock: item.sum_inward_stock.length ? item.sum_inward_stock[0].quantity : 0,
                    sum_outward_stock: item.sum_outward_stock.length ? item.sum_outward_stock[0].quantity : 0,
                }))

                setItems(data)

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
    const openModal = (name, id) => {
        setItem(prev => ({
            ...prev, name, id
        }))
        handleShow()
    }
    const handleChange = (event) => {
        const { name, value } = event.target
        setItem(prev => ({
            ...prev, [name]: value
        }))
    }
    const handleCategoryChange = (e) => {
        const { value } = e.target
        setCategory(prev => ({
            ...prev, id: value
        }))
    }

    const generateReport = () => {
        setLoading(true)
        axios.post(`/stock/generate-item-report/${item.id}`, item)
            .then(res => {
                // consolog(res.data)
                let file = res.data;
                const file_name = `report.csv`
                download(file, file_name);
                setLoading(false)
            }).catch(error => {
                const { message } = error.response.data
                swal("Warning!", message, "error")
                setLoading(false)
            })
    }

    const downloadItemCategoryReport = (event) => {
        setLoading(true)
        axios.get(`/stock/generate-item-stock-category-report/${category.id}`)
            .then(res => {
                let file = res.data
                const file_name = `report.csv`
                download(file, file_name)
                setLoading(false)
            }).catch(error => {
                const { message } = error.response.data
                swal("Warning!", message, "error")
                setLoading(false)
            })
    }

    return (
        <div className="main-card mb-2 card">
            <div className='card-header pb-1 pt-1'>
                <div className='card-title col-md-8'>
                    Stock Register
                </div>
                <div className="col-md-3">
                    <select className="form-control" name="id" onChange={e => handleCategoryChange(e)}>
                        <option value="" disable="true">Choose Category:</option>
                        <option value="0">Select All</option>
                        {
                            categories.map((category, index) => (
                                <option key={index} value={category.id}>{category.name}</option>
                            ))
                        }
                    </select>
                </div>
                <div className="col-md-1">
                    <a className="btn btn-success btn-sm mr-1" onClick={e => downloadItemCategoryReport()}><i className="fa fa-save"></i></a>
                </div>
            </div>
            <div className="card-body">
                <Modal show={show} onHide={handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>Generate Report</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <div className="form-group col-md-8">
                            <label>Item Name: {item.name}</label>
                        </div>
                        <div className="form-group col-md-8">
                            <label>Choose Report Type:</label>
                            <select className="form-control" name="report" onChange={e => handleChange(e)}>
                                <option value="" disable="true">Please choose one</option>
                                {
                                    reportType.map((report, index) => (
                                        <option key={index} value={report.id}>{report.name}</option>
                                    ))
                                }
                            </select>
                        </div>
                        <div className="form-group col-md-8">
                            <label>From Date:</label>
                            <div>
                                <input className="form-control" type="date" id="from-date" name="from_date" value={item.from_date} onChange={e => handleChange(e)} />
                            </div>
                        </div>
                        <div className="form-group col-md-8">
                            <label>To Date:</label>
                            <div>
                                <input className="form-control" type="date" id="to-date" name="to_date" value={item.to_date} onChange={e => handleChange(e)} />
                            </div>
                        </div>

                    </Modal.Body>
                    <Modal.Footer>
                        <Button disabled={loading} variant="primary" size="sm" onClick={e => generateReport()}>
                            Generate Report
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

                                <th>In Stock</th>
                                <th>Out Stock</th>
                                <th>Stock Available</th>
                                {/* <th>Action</th> */}
                            </tr>
                        </thead>
                        <tbody>
                            {items && items.map((item, index) =>
                                <tr key={index}>
                                    <td>{index + 1}</td>
                                    <td>{item.name}</td>
                                    <td>{item.description}</td>
                                    <td>{item.category}</td>

                                    <td>{item.sum_inward_stock}</td>
                                    <td>{item.sum_outward_stock}</td>
                                    <td>{
                                        parseInt(item.sum_inward_stock) -
                                        parseInt(item.sum_outward_stock)
                                    }</td>
                                    {/* <td>
                                        <div className='btn-group'>
                                            <Link className="btn btn-success btn-sm mr-1" to={`/stock/balance-sheet/${item.id}`}><i className="fa fa-eye"></i></Link>
                                            <a className="btn btn-success btn-sm mr-1"
                                                onClick={e => openModal(item.name, item.id)}><i className="fa fa-save"></i>
                                            </a>
                                        </div>
                                    </td> */}
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