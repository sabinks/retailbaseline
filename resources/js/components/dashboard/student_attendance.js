import React, { useState, useEffect } from 'react'
import ReactLoading from 'react-loading';
import { Pie } from 'react-chartjs-2';
function StudentAttendance() {
    const [attendance, setAttendance] = useState({
        absent_staff: 0,
        present_staff: 0,
        no_attendance: 0
    })
    const [data, setData] = useState({})
    const [loading, setLoading] = useState(true)
    useEffect(() => {
        axios.get('pie-chart-staff-attendance')
            .then(res => {
                const { absent_staff, present_staff, no_attendance } = res.data
                setAttendance({
                    present_staff, absent_staff, no_attendance
                })
                const temp_data = {
                    labels: ['Present', 'Absent', 'No Attendance'],
                    datasets: [
                        {
                            label: 'Staff Attendance',
                            data: [present_staff, absent_staff, no_attendance],
                            backgroundColor: [
                                'blue', 'orange', 'gray'
                            ],
                            borderColor: [
                                'blue', 'orange', 'gray'
                            ],
                            borderWidth: 1,
                            animation: false
                        },]
                }
                setData(temp_data)
                setLoading(false)
            })
    }, [])
    const options = {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
          },
          title: {
            display: true,
            text: 'Staff Attendance'
          }
        }
    }
    return (
        <div>
            {
                loading && <ReactLoading type='spin' color='gray' height={'25%'} width={'25%'} />
            }
            {
                (!loading && data) && <Pie data={data} options={options}/>
            }
        </div>
    )
}

export default StudentAttendance
