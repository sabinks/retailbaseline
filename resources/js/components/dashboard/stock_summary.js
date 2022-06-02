import React, { useState, useEffect } from 'react'
import ReactLoading from 'react-loading';
import { Bar } from 'react-chartjs-2';

function StockSummary() {
    const [categories, setCategories] = useState([])
    const [data, setData] = useState({})
    const [loading, setLoading] = useState(true)
    const [category, setCategory] = useState({
        id: localStorage.getItem('catId') ? localStorage.getItem('catId') : 0
    })
    useEffect(() => {
        axios.get('/stock/category').then(res => {
            const { category_list } = res.data
            setCategories(category_list)
        })
    }, [])
    useEffect(() => {
        axios.get(`/stock/stock-register/${category.id}`)
            .then(res => {
                const { item_list } = res.data
                let labels = [], total_stock = [], outward_stock = [], remaining_stock = [];
                item_list.map(item => {
                    labels.push(item.name)
                    total_stock.push(item.sum_inward_stock.length ? item.sum_inward_stock[0].quantity : 0)
                    outward_stock.push(item.sum_outward_stock.length ? item.sum_outward_stock[0].quantity : 0)
                    remaining_stock.push((item.sum_inward_stock.length ? item.sum_inward_stock[0].quantity : 0) - (item.sum_outward_stock.length ? item.sum_outward_stock[0].quantity : 0))
                }
                )
                const data = {
                    labels: labels,
                    datasets: [
                        {
                            label: "Total Stock",
                            backgroundColor: "blue",
                            data: total_stock
                        },
                        {
                            label: "Outward Stock",
                            backgroundColor: "orange",
                            hoverBackgroundColor: "orange",
                            data: outward_stock
                        },
                        {
                            label: "Remaining Stock",
                            backgroundColor: "gray",
                            hoverBackgroundColor: "gray",
                            data: remaining_stock
                        }
                    ],
                }
                setData(data)
                setLoading(false)
            })
    }, [category.id])
    const handleCategoryChange = (e) => {
        const { value } = e.target
        localStorage.setItem('catId', value);
        setCategory(prev => ({
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
                text: 'Stock Summary'
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
                        Select Category
                        <select className="form-control" name="id" onChange={e => handleCategoryChange(e)} value={category.id}>
                            <option value="" disable="true">Choose Category:</option>
                            <option value="0">Select All</option>
                            {
                                categories.map((category, index) => (
                                    <option key={index} value={category.id}>{category.name}</option>
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

export default StockSummary
