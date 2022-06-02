import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { useHistory } from "react-router-dom";
import { Link } from 'react-router-dom'
import { dateConversion } from '../../utils/functions';
function AssignedFormViewList() {
    let history = useHistory();
    const [state, setState] = useState({
        entity_list: [],
    })
    useEffect(() => {
        let id = window.location.pathname.replace('/client/entity-form/', '')
        axios.get(`/clients/entity-form/${id}`).then(res => {
            const { entity_list } = res.data
            if (entity_list) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setState(prev => ({ ...prev, entity_list, loading: false }))
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        })
    }, [])

    return (
        <>
            <div className="main-card card mb-1">
                <div className="card-header">
                    <div className='card-title'>
                        Entity Listing
                    </div>
                    {/* <Index /> */}
                </div>
                <div className="card-body">
                    <div className="table">
                        <table className="table table-striped table-bordered dataTableReact">
                            <thead>
                                <tr>
                                    <th>Entity Name</th>
                                    <th>Form Title</th>
                                    <th>Filled Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {
                                    state.entity_list && state.entity_list.map((entity, index) => {
                                        return <tr key={index}>
                                            <td>{entity.name}</td>
                                            <td>{entity.entities_form.form_title}</td>
                                            <td>{dateConversion(entity.created_at)}</td>
                                            <td>
                                                <Link className='btn btn-secondary btn-sm mr-1' to={`/entities-history/${entity.id}`}><i className="fa fa-eye"></i></Link>
                                                <Link className='btn btn-success btn-sm mr-1' to={`/map-location/${entity.id}`}><i className="fa fa-map-marker"></i></Link>
                                            </td>

                                            {/* '<a class="btn btn-success btn-sm mr-1" href="/map-location/' . $this->id . '"><i class="fa fa-map-marker"></i></a>' */}
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

export default AssignedFormViewList
