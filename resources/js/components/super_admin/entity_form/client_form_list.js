import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { Link } from 'react-router-dom';
import Select from "react-select";
import { useHistory } from "react-router-dom";
import Index from '../entityRoute';

const ClientEntityList = () => {
    let history = useHistory();
    const [state, setState] = useState({
        loading: true,
    })
    const [entities, setEntities] = useState([])
    const [clients, setClients] = useState([])
    useEffect(() => {
        axios.get('/superadmin/client-entity-view').then(res => {
            const { all_entity_form, assigned_clients } = res.data
            if (assigned_clients) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setClients(assigned_clients)
                setEntities(all_entity_form)
                setState(prev => ({ ...prev, loading: false }))
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        })
    }, [])

    const handleReportChange = (new_entities, client_id) => {
        let new_list = []
        clients.map(client => {
            if (client.id == parseInt(client_id)) {
                new_list.push({ ...client, entity_form: new_entities ? [...new_entities] : [] })
            } else {
                new_list.push(client)
            }
        })
        const inputs = {
            entity_ids: new_entities ? JSON.stringify(new_entities.map(entity => entity.id)) : JSON.stringify([])
        }
        axios.post(`/superadmin/assign-entity-client/${client_id}`, inputs).then(res => {
            setClients(new_list)

        }).catch()
    }

    return (
        <>
            <div className="main-card card">
                <div className='card-header'>
                    <div className='card-title'>
                        Assign Report Form To Client Company
                    </div>
                    {/* <Index /> */}
                </div>
                <div className="card-body">

                    <div className="table">
                        {
                            !state.loading &&
                            <table className="table table-striped table-bordered dataTableReact">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Entity Form View Access</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        clients && clients.map((client, index) => {
                                            return <tr key={index}>
                                                <td>{client.name}</td>
                                                <td>
                                                    <Select
                                                        name={client.id}
                                                        placeholder="Select Entity Form"
                                                        value={client.entity_form}
                                                        options={entities}
                                                        onChange={e => handleReportChange(e, client.id)}
                                                        isMulti
                                                    />
                                                </td>
                                            </tr>
                                        })}
                                </tbody>
                            </table>
                        }
                    </div>
                </div>
            </div>
        </>
    )
}

export default ClientEntityList
