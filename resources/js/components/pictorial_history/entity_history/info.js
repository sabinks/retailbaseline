import React, { useState, useEffect } from 'react'

function Info() {
    const [status, setStatus] = useState('all')
    useEffect(() => {
        window.reactDeleteEntityData = (entity_id) => {
            axios.delete(`entity-data/${entity_id}`)
                .then(res => {
                    const { message } = res.data
                    swal("Warning!", message, "success")
                }).catch(error => {
                    const { message } = error.response.data
                    swal("Warning!", message, "error")
                })
        }
    }, [])

    useEffect(() => {
        let data_table
        if ($.fn.dataTable.isDataTable('.dataTableReact')) {
            let data_table = $('.dataTableReact').DataTable().destroy();
        }
        data_table = $('.dataTableReact').DataTable({
            "lengthChange": false,
            "order": [
                [0, "desc"]
            ],
            "pageLength": 10,
            "autoWidth": false,
            processing: true,
            serverSide: true,
            async: true,
            "ajax": {
                url: `/all-entities/${status}`,
                method: "GET",
                error: function (xhr, error, code) {
                    data_table.ajax.reload(null, false)
                }
            },
            "columns": [
                { data: "entity_name" },
                { data: "form_title" },
                { data: "region" },
                { data: "latitude" },
                { data: "longitude" },
                { data: "options" }
            ]
        })
        $('.dataTables_wrapper .row:first-child>div:first-child').removeClass()
        $('.dataTables_filter').css("float", "left")
        data_table.draw()
    }, [status])

    return (
        <>
            {/* <div className="app-page-title">
                <div className="page-title-wrapper">
                    <div className="page-title-heading">
                        <div className="page-title-icon">
                            <i className="fa fa-user-o"></i>
                        </div>
                        <div>
                            All Entities
                        </div>
                    </div>
                </div>
            </div> */}
            <div className="main-card mb-2 card">
                <div className='card-header'>
                    <div className='card-title'>
                        List of all Entities
                    </div>
                    <div className="btn-wrapper btn-wrapper-multiple">
                        <button type="button" className="btn btn-sm btn-success" onClick={e => setStatus('approved')}>Approved</button>
                        <button type="button" className="btn btn-sm btn-primary" onClick={e => setStatus('all')}>All</button>
                    </div>
                </div>
                <div className="card-body">
                    <div className="table">
                        <table className="table table-striped table-bordered dataTableReact">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Entity Form</th>
                                    <th>Region</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </>
    )
}

export default Info
