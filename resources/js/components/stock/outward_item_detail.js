import React, { useState, useEffect } from 'react'
import axios from 'axios'
import "react-responsive-carousel/lib/styles/carousel.min.css";
import { Carousel } from 'react-responsive-carousel';
import swal from 'sweetalert';
let url = document.getElementById('route').getAttribute('url')

function OutwardItemDetail() {
    let id = window.location.pathname.replace('/stock/outward-item-detail/', '')
    const [images, setImages] = useState([])
    const [noItem, setNoItem] = useState(true)
    const [outwardDetail, setOutwardDetail] = useState({})
    useEffect(() => {
        url = window.location.origin
        axios.get(`/stock/item/outward-item-detail/${id}`).then(res => {
            let { item_detail, image_list } = res.data
            setImages(image_list)
            setOutwardDetail(item_detail)
            setNoItem(false)
        }).catch(error => {
            setNoItem(true)
            const { message } = error.response.data
            swal("Warning!", message, "error")
        })
    }, [])

    return (
        <>{
            !noItem &&
            <div>
                <div className="main-card card mb-2">
                    <div className="card-header pb-0 pt-1">
                        <h6>Outward Item Detail</h6>
                    </div>
                    <div className="card-body">
                        <div className="row">
                            <div className="col-md-6">
                                <div><label htmlFor="name">Customer Name: </label>
                                    <span> {outwardDetail && outwardDetail.name}</span></div>
                                <div><label htmlFor="name">Customer Address: </label>
                                    <span> {outwardDetail && outwardDetail.address}</span></div>
                                <div><label htmlFor="name">Serial Number: </label>
                                    <span> {outwardDetail && outwardDetail.unique_id}</span></div>
                                <div><label htmlFor="name">Amount:</label>
                                    <span> {outwardDetail && outwardDetail.amount}</span></div>
                                <div><label htmlFor="name">Document Type:</label>
                                    <span> {outwardDetail && outwardDetail.document_type.name}</span></div>
                            </div>
                            <div className="col-md-6">
                                <div><label htmlFor="name">Filled Date: </label>
                                    <span> {outwardDetail && outwardDetail.filled_date}</span></div>
                                <div><label htmlFor="name">Sync Date: </label>
                                    <span> {outwardDetail && outwardDetail.sync_date}</span></div>
                                <div><label htmlFor="name">Item Name: </label>
                                    <span> {outwardDetail.item && outwardDetail.item.name}</span></div>
                                <div><label htmlFor="name">Filled By: </label>
                                    <span> {outwardDetail.staff && outwardDetail.staff.name}</span></div>

                            </div>
                        </div>
                    </div>
                </div>

                <div className="main-card card">
                    <div className="card-header pb-0 pt-1">
                        <h6>Images</h6>
                    </div>
                    <div className="card-body">
                        <Carousel infiniteLoop={true} emulateTouch={true} width={50}>
                            {
                                images.map((image, index) => {
                                    return <div key={index}>
                                        <img src={`${url}/storage/images/outward_stock/${image}`} />
                                    </div>
                                })

                            }
                        </Carousel>
                    </div>
                </div>
            </div>
        }
        </>
    )
}

export default OutwardItemDetail
