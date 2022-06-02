import React, { useState, useEffect } from 'react'

function ItemBalanceSheet() {
    let id = window.location.pathname.replace('/stock/outward-stock-detail/', '')
    const [item, setItem] = useState({})
    const [balanceSheet, setBalanceSheet] = useState([])

    const [loading, setLoading] = useState(false)
    useEffect(() => {
        axios.get(`/stock/item/outward-stock-detail/${id}`).then(res => {
            const { balance_sheet, item_detail } = res.data
            if (balance_sheet) {
                let prevtableElmt = $('.dataTable').attr('id')
                if (prevtableElmt == 'DataTables_Table_0') {
                    let prevTable = $('.dataTable').DataTable()
                    prevTable.destroy();
                }
                setBalanceSheet(balance_sheet)

                $('.dataTableReact').DataTable({
                    "lengthChange": true,
                    "pageLength": 25,
                })
            }
            if (item_detail.length) {
                let item = item_detail[0]
                let data = {
                    name: item.name,
                    category: item.category,
                    description: item.description,
                    sum_opening_stock: item.sum_opening_stock.length ? item.sum_opening_stock[0].quantity : 0,
                    sum_inward_stock: item.sum_inward_stock.length ? item.sum_inward_stock[0].quantity : 0,
                    sum_outward_stock: item.sum_outward_stock.length ? item.sum_outward_stock[0].quantity : 0
                }
                setItem(data)
            }
        })

    }, [])

    return (
        <div className="main-card mb-2 card">
            <div className='card-header'>
                <div className='card-title'>
                    Item Balance Sheet
                </div>
                <div>
                    Name: {item.name}
                </div>
                <div>
                    Category: {item.category}
                </div>
            </div>
            <div className="card-body">
                <div className="table-responsive">
                    <table className="table table-striped table-bordered dataTableReact">
                        <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Entry Date</th>
                                <th>Stock Type</th>
                                <th>Particular</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            {balanceSheet && balanceSheet.map((item, index) =>
                                <tr key={index}>
                                    <td>{index + 1}</td>
                                    <td>{item.entry_date}</td>
                                    <td>{item.stock_type}</td>
                                    <td>{item.particular}</td>
                                    <td>{item.quantity}</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
                <div className="card-footer text-muted">
                    <div className="row">
                        <span className="col-md-6">Opening Stock Total: <b>{item.sum_opening_stock}</b></span>
                        <span className="col-md-6">Inward Stock Total: <b>{item.sum_inward_stock}</b></span>
                        <span className="col-md-6">Outward Stock Total: <b>{item.sum_outward_stock}</b></span>
                        <span className="col-md-6">Available Stock: <b>{parseInt(item.sum_opening_stock) + parseInt(item.sum_inward_stock) -
                            parseInt(item.sum_outward_stock)}</b></span>
                    </div>

                </div>
            </div>
        </div>
    )
}

export default ItemBalanceSheet