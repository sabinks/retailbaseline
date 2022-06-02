import React, { useState, useEffect } from 'react';
import axios from 'axios'
import { Link } from 'react-router-dom';
import Select from "react-select";
import { useHistory } from "react-router-dom";
import Index from '../entityRoute';

const StaffAssign = () => {
    let history = useHistory();
    const [state, setState] = useState({
        staff_list: [],
        show_staff: false,
        loading: true,
    })
    const [forms, setForms] = useState([])
    useEffect(() => {
        axios.get('/superadmin/assigned-form-staff').then(res => {
            const { forms, staff_list } = res.data
            if (forms) {
                let prevtableElemt = $('.dataTable');
                let prevtableElmt = $('.dataTable').attr('id');
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable();
                    prevTable.destroy();
                }
                setForms(forms)
                setState(prev => ({ ...prev, staff_list, loading: false }))
                let tableElemt = $('.dataTableReact');
                let table = $('.dataTableReact').DataTable();
                $('.dataTables_wrapper .row:first-child>div:first-child').remove()
                $('.dataTables_filter').css("float", "left")
            }
        })
    }, [])

    const handleStaffChange = (staff_list, form_id) => {
        let new_forms = []
        forms.map(form => {
            if (form.id == parseInt(form_id)) {
                new_forms.push({ ...form, staff_list: staff_list ? [...staff_list] : [] })
            } else {
                new_forms.push(form)
            }
        })
        const inputs = {
            staff_ids : staff_list ? JSON.stringify(staff_list.map(staff => staff.id)) : JSON.stringify([])
        }
        
        axios.post(`/superadmin/entity-assign-staff/${form_id}`, inputs).then(res => {
            setForms(new_forms)
        })
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
                                        <th>Form Title</th>
                                        <th>Form Assign</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        forms && forms.map((form, index) => {
                                            return <tr key={index}>
                                                <td>{form.form_title}</td>
                                                <td>
                                                    <Select
                                                        name={form.id}
                                                        placeholder="Select Staff"
                                                        value={form.staff_list}
                                                        options={state.staff_list}
                                                        onChange={e => handleStaffChange(e, form.id)}
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

export default StaffAssign
