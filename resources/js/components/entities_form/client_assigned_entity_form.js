import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { Link, useHistory } from "react-router-dom";
import { slugify, dateConversion } from '../../utils/functions';
import swal from 'sweetalert';
import download from 'downloadjs'

function AssignedFormViewForms() {
    let history = useHistory();
    const [state, setState] = useState({
        entity_form_list: [],
    })
    const [loading, setLoading] = useState(false)
    useEffect(() => {
        axios.get('/clients/entity-form/assigned-list').then(res => {
            const { entity_form_list } = res.data
            if (entity_form_list) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setState(prev => ({ ...prev, entity_form_list, loading: false }))
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        })
    }, [])
    const handleGenerateReport = (form_id) => {
        const input = {
            file_type: 'csv',
        }
        setLoading(true)
        axios.post(`/generate-entity-form-report/${form_id}`, input)
            .then(res => {
                let file = res.data;
                const file_name = `${slugify(state.entity_form_list.filter(form => form.id == form_id)[0].form_title)}_report.csv`
                download(file, file_name);
                setLoading(false)
            }).catch(error => {
                const { message } = error.response.data
                swal("Warning!", message, "error")
                setLoading(false)
            })
    }


return (
    <>
        <div className="main-card card mb-1">
            <div className="card-header">
                <div className='card-title'>
                    Entity Form Listing
                    </div>
                {/* <Index /> */}
            </div>
            <div className="card-body">
                <div className="table">
                    <table className="table table-striped table-bordered dataTableReact">
                        <thead>
                            <tr>
                                <th>Form Title</th>
                                <th>Assigned Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {
                                state.entity_form_list && state.entity_form_list.map((form, index) => {
                                    return <tr key={index}>
                                        <td><Link to={`/client/entity-form/${form.id}`}>{form.form_title}</Link></td>
                                        <td>{dateConversion(form.pivot.created_at)}</td>
                                        <td>
                                            <button title='Download entity report' className='btn btn-success btn-sm' onClick={e => handleGenerateReport(form.id)}>
                                                <i className="fa fa-save"></i>
                                            </button>
                                        </td>
                                    </tr>
                                })}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </>
)
}

export default AssignedFormViewForms
