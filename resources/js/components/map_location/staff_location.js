/* eslint-disable no-undef */
import React, { useState, useEffect } from 'react'
import axios from 'axios'
import { Link, useParams } from 'react-router-dom';
import { Map, Marker, GoogleApiWrapper, InfoWindow } from 'google-maps-react'
import Switch from 'react-input-switch';
import Select from 'react-select';
let GoogleAPIKey = document.getElementById('route').getAttribute('value')

let EntityLocation = () => {
    const { id } = useParams()
    const [entityForm, setEntityForm] = useState([])
    const [entityLocation, setEntityLocation] = useState([])
    const [assignedEntityLocation, setAssignedEntityLocation] = useState([])
    const [staffByRegion, setStaffByRegion] = useState([])
    const [selfEntityLocation, setSelfEntityLocation] = useState([])
    const [loading, setLoading] = useState(true)
    const [map, setMap] = useState({})
    let [showAssignedEntity, setShowAssignedEntity] = useState(true)
    const [role, setRole] = useState('None')
    const [value, setValue] = useState(1)
    const [newEntity, setNewEntity] = useState({
        id: '',
        name: '',
        lat: '',
        lng: ''
    })

    useEffect(() => {
        getEntityLocation(0)
    }, [])

    const getEntityLocation = (entity_id) => {
        axios.get(`/entities-location/${entity_id}`).then(res => {
            const { entities_location, assigned_entities_location, role, company_staff, entities_forms } = res.data
            setRole(role)
            if (role == 'Super Admin') {
                setEntityLocation(entities_location)
                setEntityForm(entities_forms)
                if(company_staff){
                    company_staff.map(region =>{
                        if(region.super_admin_staffs.length){
                            let staffs = []
                            region.super_admin_staffs.map(staff => {
                                staffs.push({
                                    value: staff.name, label: staff.name, id: staff.id
                                })
                            })
                            setStaffByRegion(prev =>([
                                ...prev, { label: region.name, options: staffs }
                            ]))
                        }
                    })
                }
            } else {
                setAssignedEntityLocation(assigned_entities_location)
                setSelfEntityLocation(entities_location)
                setEntityLocation([...assigned_entities_location, ...entities_location])
            }
        })
    }

    const getStaffLocation = (staff_id) => {
        axios.get(`/staff-current-location/${staff_id}`).then(res => {
            const { entities_location, assigned_entities_location, role, entities_forms } = res.data
            setRole(role)
            if (role == 'Super Admin') {
                setEntityLocation(entities_location)
                setEntityForm(entities_forms)
            } else {
                setAssignedEntityLocation(assigned_entities_location)
                setSelfEntityLocation(entities_location)
                setEntityLocation([...assigned_entities_location, ...entities_location])
            }
        })
    }

    useEffect(() => {
        const location = entityLocation.filter(entity => entity.id == id)[0]
        if (location) {
            setNewEntity(prev => ({
                ...prev, id: location.id, name: location.name, lat: location.lat, lng: location.lng, assigned: location.assigned
            }))
        }
        displayMarkers()
        setLoading(false)
    }, [entityLocation])

    useEffect(() => {
        if (showAssignedEntity) {
            setEntityLocation([...selfEntityLocation, ...assignedEntityLocation])
        } else {
            setEntityLocation([...selfEntityLocation])
        }
        displayMarkers()
    }, [showAssignedEntity])

    const displayMarkers = () => {
        let locations_markers = []
        if (entityLocation) {
            locations_markers.push(entityLocation.map((point, index) => {

                return <Marker key={index} id={index} position={{
                    lat: point.lat,
                    lng: point.lng
                }}
                    // style={{ backgroundColor: 'yellow', cursor: 'pointer' }}
                    title={point.name}
                    label={`${point.name}`}
                    scaledSize={google.maps.Size(15, 25)}
                    icon={point.assigned ? "http://maps.google.com/mapfiles/ms/icons/red.png" : "http://maps.google.com/mapfiles/ms/icons/green.png"}
                    // icon={"http://icons.iconarchive.com/icons/icons-land/vista-map-markers/32/Map-Marker-Marker-Outside-Chartreuse-icon.png"}
                    onClick={onMarkerClick}
                />
            }))
        }

        return locations_markers
    }

    const onMarkerClick = (props, marker, e) => {
        let new_point = props.position
        let near_point = entityLocation.filter(point => (Math.abs(Math.abs(parseFloat(point.lat)) - Math.abs(parseFloat(new_point.lat)))) <= 0.000009
            || Math.abs(Math.abs(parseFloat(point.lng)) - Math.abs(parseFloat(new_point.lng))) <= 0.000009)
        const displayData = {
            data: near_point
        }
        const temp = {
            selectedPlace: displayData,
            activeMarker: marker,
            showingInfoWindow: true
        }
        setMap(temp)
    }
    const handleChange = (e) => {
        setShowAssignedEntity(e.target.checked)
    }
    const handleFormSelection = (event, form_id) => {
        getEntityLocation(form_id)
    }
    const handleStaffSelection = (staff) => {
        getStaffLocation(staff.id)
    }
    return (
        <>
            <div className="row">
                <div className="col-md-6">
                    <div className="page-title-wrapper">
                        <div className="page-title-heading">
                            <div className="page-title-icon">
                                <h4><i className="fa fa-map"></i> Entity Location</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-md-6">
                    <span>Staff Location </span>
                    <Switch value={value} onChange={setValue} />
                    <span> Entity Location</span>
                    {
                        role != 'Super Admin' && value == 1 &&
                        <div className="float-right">
                            <input className="mr-2" type="checkbox" id="assignedEntity" checked={showAssignedEntity} onChange={handleChange} />
                            <span htmlFor="assignedEntity">Lemon's Entity Data</span>
                        </div>
                    }
                    {
                        role == 'Super Admin' && value == 1 &&
                        <div className="float-right">
                            <select className="form-control assign-to-role btn-sm" id="" onChange={e => handleFormSelection(e, e.target.value)} >
                                <option value="" disable="true">Select Entity Form</option>
                                {
                                    entityForm.map((form, index) => (
                                        <option key={index} value={form.id}>{form.form_title}</option>
                                    ))
                                }
                            </select>
                        </div>
                    }
                    {
                        role == 'Super Admin' && value == 0 &&
                        <div className="float-right col-md-5">
                             <Select
                                options={staffByRegion}
                                label="Select Staff"
                                formatGroupLabel={formatGroupLabel}
                                onChange={handleStaffSelection}
                            />
                        </div>
                    }
                </div>
            </div>
            <div>
                {
                    !loading &&
                    <Map google={window.google} zoom={newEntity.id ? 30 : 7} initialCenter={{
                        lat: '28.2559201',
                        lng: '84.3830657'
                    }}
                        center={{
                            lat: newEntity.lng ? newEntity.lat : '28.2559201',
                            lng: newEntity.lng ? newEntity.lng : '84.3830657'
                        }}
                        style={{ width: '1000px', height: '410px', 'top': '0.5rem' }}
                    >
                        {displayMarkers()}
                        <InfoWindow
                            marker={map.activeMarker}
                            visible={map.showingInfoWindow}>
                            <div>
                                {
                                    map.selectedPlace && (<h5>Entity List</h5>)
                                }
                                <ul className="list-group">
                                    {
                                        map.selectedPlace && map.selectedPlace.data.map((point, index) => (
                                            <li className="list-group-item pt-1 pb-1" key={index}>
                                                <b><a href={`/entities-history/${point.id}`} className="text-decoration-none"> {point.name}</a></b>
                                            </li>
                                        ))
                                    }
                                </ul>
                            </div>
                        </InfoWindow>
                    </Map>
                }
            </div>
        </>
    )
}
const groupStyles = {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
};
const groupBadgeStyles = {
    backgroundColor: '#EBECF0',
    borderRadius: '2em',
    color: '#172B4D',
    display: 'inline-block',
    fontSize: 12,
    fontWeight: 'normal',
    lineHeight: '1',
    minWidth: 1,
    padding: '0.16666666666667em 0.5em',
    textAlign: 'center',
  };
  const formatGroupLabel = data => (
    <div style={groupStyles}>
      <span>{data.label}</span>
      <span style={groupBadgeStyles}>{data.options.length}</span>
    </div>
  );
EntityLocation = GoogleApiWrapper({
    apiKey: GoogleAPIKey,

})(EntityLocation)

export default EntityLocation