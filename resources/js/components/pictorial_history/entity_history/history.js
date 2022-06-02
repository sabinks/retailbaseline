import React, { useState, useEffect } from 'react'
import axios from 'axios'
import { Link } from 'react-router-dom';
// import "react-responsive-carousel/lib/styles/carousel.min.css"; // requires a loader
import { Carousel } from 'react-responsive-carousel';
import ReactPaginate from 'react-paginate';
import swal from 'sweetalert';
let url = document.getElementById('route').getAttribute('url')
function History() {
    const [entity, setEntity] = useState({
        id: '',
        name: '',
        latitude: '',
        longitude: '',
        images: [],
        region: {},
        staff: {},
        url: 'http://localhost:8000'
    })
    const [reports, setReport] = useState([])
    const [loading, setLoading] = useState(true)
    const [reportLoad, setReportLoad] = useState(true)
    const [paginate, setPaginate] = useState({ offset: 0, page: 1 })
    useEffect(() => {
        setEntity(prev => ({ ...prev, id }))
        let id = window.location.pathname.replace('/entities-history/', '')
        getHistoryData(id)
    }, [])

    const getHistoryData = (entity_id) => {
        axios.get(`/all-entities/${entity_id}`).then(res => {
            url = window.location.origin
            const { form_filler, region, name, latitude, longitude, input_datas } = res.data.entity
            let images = input_datas ? JSON.parse(input_datas).filter((input, index) => input.name.includes('camera')).filter(image => image.value != 'null') : []
            setEntity(prev => ({
                ...prev, id: entity_id, name, latitude, longitude, url, images,
                region, staff: form_filler
            }))
            setLoading(false)
        })
    }

    function handleGetReport(page = 1) {
        axios.get(`/get-report-images/${entity.id}?page=${page}`).then(res => {
            const { report_data } = res.data
            const { current_page, from, to, total, per_page } = report_data
            if(report_data.data.length){
                setReport(report_data.data)
                setPaginate(prev => ({
                    ...prev,
                    page: current_page, from, to, total, per_page, page_count: Math.ceil(total / per_page),
                }))
            }else{
                swal("Warning!", 'No report found.', "error")
            }
            setReportLoad(false)
        })
    }
    const handlePageClick = (data) => {
        let offset = Math.ceil(data.selected * paginate.per_page);
        let page = offset / paginate.per_page + 1
        setPaginate(prev => ({
            ...prev, page
        }))
        handleGetReport(page);
    }

    return (
        <>
            {/* <div className="app-page-title">
                <div className="page-title-wrapper">
                    <div className="page-title-heading">
                        <div className="page-title-icon">
                            <i className="fa fa-user-o"></i>
                        </div>
                        <div>
                            Pictorial History
                        </div>
                    </div>
                </div>
            </div> */}
            <div className="main-card mb-2 card">
                <div className='card-header'>
                    <div className='card-title'>
                        Pictorial History of ' {entity.name} '
                    </div>
                    <div className="btn-wrapper btn-wrapper-multiple">
                        {/* <button type="button" className="btn btn-sm btn-success">
                            <Link id="link_page" to={'/entities-history'}>
                                All Entities
                            </Link>
                        </button> */}
                        <button type="button" className="btn btn-sm btn-info">
                            {/* <a id="link_page" href={`/entity-data-view/${entity.id}`}>View Data</a> */}
                            <Link id="link_page" to={`/entity-data-view/${entity.id}`}>
                                View Data
                            </Link>
                        </button>
                    </div>
                </div>
                <div className="card-body">
                    {
                        !loading ? (
                            entity.images.length > 0 ? 
                            <>
                                <h6>Entity Images</h6>
                                <Carousel>
                                    {
                                        entity.images.map((image, index) => {
                                            return <div key={index}>
                                                <img src={`${url}/${image.value}`} width="300px" />
                                                {/* <p className="legend">Legend 1</p> */}
                                            </div>
                                        })
                                    }
                                </Carousel>
                            </> : <h6>No Entity Images</h6>
                        ) :
                           ('')
                    }
                    <div className="row mt-1">
                        {/* <div className="col-md-6 text-center">
                            <img id="entity-image" className="mb-2" src={`${entity.url}/${entity.image}`}></img>
                        </div> */}
                        <div className="col-md-6">
                            <div className="row">
                                <div className="col-sm-6 history-info1">
                                    <p>Name</p>
                                    <p>Latitude</p>
                                    <p>Longitude</p>
                                    <p>Recorded By</p>
                                    <p>Region</p>
                                </div>
                                <div className="col-sm-6 history-info2">
                                    <p>{entity.name}</p>
                                    <p>{entity.latitude}</p>
                                    <p>{entity.longitude}</p>
                                    <p>{entity.staff.name}</p>
                                    <p>{entity.region.name}</p>
                                </div>
                            </div>
                        </div>
                        <p id="link_page">
                            <Link to={`/map-location/${entity.id}`}>
                                <i class="fa fa-map-marker"> View On Map</i>
                            </Link>
                        </p>
                    </div>

                </div>
                <div className="card-body">
                    <button onClick={handleGetReport} ype="button" className="btn btn-sm btn-info">Report Information</button>
        
                    {
                        !reportLoad ? (
                            reports &&
                            reports.map((data, index) => {
                                return <div key={index} className="mt-2">
                                    <h6><b>Report Title: </b><Link to={`/report-info/view/${data.id}`}>{data.report.title}</Link><small className="float-right">Filled Date: {data.filled_date}</small></h6>
                                    <Carousel> 
                                        {
                                            data.report_images.map((image, index) => {
                                                return <div key={index}>
                                                    <img src={`${url}/storage/images/report_data_images/${image.image_name}`} width="300px" />
                                                </div>
                                            })
                                        }
                                    </Carousel>
                                </div>
                            })
                        ) : (reports && <div></div>)
                    }
                    {
                        reports.length > 0 &&
                        <div className="mt-2 d-flex justify-content-center" id="react-paginate">
                            <ReactPaginate
                                previousLabel={'<<'}
                                nextLabel={'>>'}
                                breakLabel={'...'}
                                breakClassName={'break-me'}
                                pageCount={paginate.page_count}
                                marginPagesDisplayed={2}
                                pageRangeDisplayed={2}
                                onPageChange={handlePageClick}
                                containerClassName={'pagination'}
                                subContainerClassName={'pages pagination'}
                                activeClassName={'active-page'}
                            />
                        </div>
                    }
                </div>
            </div>
        </>
    )
}

export default History
