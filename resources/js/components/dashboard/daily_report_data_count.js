import React, {useState, useEffect } from 'react'
import ReactLoading from 'react-loading';
import { Bar } from 'react-chartjs-2';
function DailyReportDataCount() {
    const [loading, setLoading] = useState(true)
    const [data, setData] = useState([])
    const [forms, setForms] = useState([])
    const [form, setForm] = useState({
        id: localStorage.getItem('reportFormId') ? localStorage.getItem('reportFormId') : 0
    })
    useEffect(() => {
        axios.get('/report-forms').then(res => {
            const { form_list, assigned_report_forms_list } = res.data
            setForms([...form_list, ...assigned_report_forms_list])
        })
    }, [])

    useEffect(() => {
        axios.get(`daily-report-count/${form.id}`)
            .then(res => {
                const { data, date } = res.data
                let days = [], counts = [], weekdays = []
                if(data){
                    data.map(item => {
                        days.push(item.day + '-' + item.weekday.slice(0,3))
                        weekdays.push(item.day)
                        counts.push(item.count)
                    })
                }
                const temp_data = {
                    labels: days,
                    datasets: [
                        {
                            label: `Report Data Count - (${date})`,
                            backgroundColor: "blue",
                            data: counts
                        },
                    ],
                }
                setData(temp_data)
                setLoading(false)
            })
    }, [form.id])
    const handleFormChange = (e) => {
        const { value } = e.target
        localStorage.setItem('reportFormId', value);
        setForm(prev => ({
            ...prev, id: value
        }))
    }
    const options = {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Daily Report Data Count'
            }
        }
    }
    return (
        <div>
            {
                loading && <ReactLoading type='spin' color='gray' height={'10%'} width={'10%'} />
            }

            {
                (!loading && data) && <>
                    <div className="col-md-3">
                        Select Report Form
                        <select className="form-control" name="id" onChange={e => handleFormChange(e)} value={form.id}>
                            <option value="" disable="true">Choose Report Form:</option>
                            <option value="0">Select All</option>
                            {
                                forms.length > 0 && forms.map((form, index) => (
                                    <option key={index} value={form.id}>{form.title}</option>
                                ))
                            }
                        </select>
                    </div>
                    <Bar data={data} width={null} height={null} options={options} />
                </>
            }
        </div>
    )
}

export default DailyReportDataCount
