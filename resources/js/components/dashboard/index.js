import React, { useState, useEffect } from 'react'
import { Fragment } from 'react'
import StockSummary from './stock_summary'
import StudentAttendance from './student_attendance'
import EntityDataList from './entity_data_list'
import DailyReportDataCount from './daily_report_data_count'
function Dashboard() {
    const [user, setUser] = useState({
        name: '',
        role: ''
    })
    const [loading, setLoading] = useState(true)
    useEffect(() => {
        axios.get('user-details')
            .then(res => {
                const { name, role } = res.data
                setUser({ name: name, role: role });
                setLoading(false)
            })
    }, [])
    useEffect(() => {

    }, [])

    return (
        <Fragment>
            <div className="app-page-title">
                <div className="page-title-wrapper">
                    <div className="page-title-heading">
                        <div className="page-title-icon">
                            <i className="fa fa-tachometer"></i>
                        </div>
                        <div>
                            Welcome {user.name}
                        </div>
                    </div>
                </div>
            </div>
            <div className="main-card mb-3 card">
                {/* <div className='card-header'>
                </div> */}
                <div className="card-body">
                    {
                        (user.role == "Admin" || user.role == "Regional Admin" || user.role == "Supervisor") &&
                        <>
                            <div className="row">
                                <div className="col-md-3">
                                    <StudentAttendance />
                                </div>
                                <div className="col-md-2">
                                </div>
                                <div className="col-md-6">
                                    <StockSummary />
                                </div>
                            </div>
                            <br/>
                            <div className="row">
                                <div className="col-md-9">
                                    <DailyReportDataCount />
                                </div>
                            </div>
                        </>
                    }

                    {
                        user.role == "Super Admin" &&
                        <>
                            <div className="row">
                                <div className="col-md-3 justify-content-center">
                                    <StudentAttendance />
                                </div>
                                <div className="col-md-2">
                                </div>
                                <div className="col-md-6">
                                    <EntityDataList />
                                </div>
                            </div>
                            <br/>
                            <div className="row">
                                <div className="col-md-9">
                                    <DailyReportDataCount />
                                </div>
                            </div>
                        </>
                    }
                </div>
            </div>
        </Fragment>
    )
}

export default Dashboard
