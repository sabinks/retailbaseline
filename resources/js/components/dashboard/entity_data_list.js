import React, { useState, useEffect } from 'react'
import ReactLoading from 'react-loading';

function EntityDataList() {
    const [formLoading, setFormLoading] = useState(true)
    const [loading, setLoading] = useState(true)
    const [data, setData] = useState({
        total_approved: 0, total_pending: 0, total_rejected: 0
    })
    const [form, setForm] = useState({
        id: localStorage.getItem('formId') ? localStorage.getItem('catId') : 0
    })
    const [forms, setForms] = useState([])
    useEffect(() => {
        axios.get('/superadmin/entity-form').then(res => {
            const { form_list } = res.data
            setForms(form_list)
            setFormLoading(false)
        })
    }, [])
    useEffect(() => {
        axios.get(`/superadmin/entity-count/${form.id}`)
            .then(res => {
                const { total_approved, total_pending, total_rejected } = res.data
                setData({ total_approved, total_pending, total_rejected })
                setLoading(false)
            })
    }, [form.id])
    const handleCategoryChange = (e) => {
        const { value } = e.target
        localStorage.setItem('formId', value);
        setForm(prev => ({
            ...prev, id: value
        }))
    }
    return (
        <div>
            {
                loading && <ReactLoading type='spin' color='gray' height={'9%'} width={'9%'} />
            }
            {
                (!formLoading) && <>
                    <div className="row">
                        <div className="col-md-3">
                            Select Entity Form
                            <select className="form-control" name="id" onChange={e => handleCategoryChange(e)} value={form.id}>
                                <option value="" disable="true">Choose Form:</option>
                                <option value="0">Select All</option>
                                {
                                    forms && forms.map((form, index) => (
                                        <option key={index} value={form.id}>{form.title}</option>
                                    ))
                                }
                            </select>
                        </div>
                    </div>

                </>
            }
            {
                (!loading && data) && <>
                    <div className="row mt-3">
                        <div className="col-md-4">
                            <div className="card text-white bg-success mb-3 text-center" style={{ maxWidth: '18rem' }}>
                                <div className="card-header"><h5>Total Approved</h5></div>
                                <div className="card-body">
                                    <h1 className="">{data.total_approved}</h1>
                                    {/* <p className="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> */}
                                </div>
                            </div>
                        </div>
                        <div className="col-md-4">
                            <div className="card text-white bg-warning mb-3 text-center" style={{ maxWidth: '18rem' }}>
                                <div className="card-header"><h5>Total Pending</h5></div>
                                <div className="card-body">
                                    <h1 className="">{data.total_pending}</h1>
                                    {/* <p className="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> */}
                                </div>
                            </div>
                        </div>
                        <div className="col-md-4">
                            <div className="card text-white bg-danger mb-3 text-center" style={{ maxWidth: '18rem' }}>
                                <div className="card-header"><h5>Total Rejected</h5></div>
                                <div className="card-body">
                                    <h1 className="">{data.total_rejected}</h1>
                                    {/* <p className="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> */}
                                </div>
                            </div>
                        </div>
                    </div>
                </>
            }
        </div>
    )
}

export default EntityDataList
