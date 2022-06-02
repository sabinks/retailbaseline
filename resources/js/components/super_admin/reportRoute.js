import React from 'react'
import { Link } from 'react-router-dom'
function Index() {
    return (
        <div className="dropdown">
            <button className="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Menu
            </button>
            <div className="dropdown-menu pt-0 pb-0 dropdown-menu-right" aria-labelledby="dropdownMenu2">
                <Link className="btn-sm dropdown-item" to={`/super/report-form/create`} >Create Form</Link>
                <Link className="btn-sm dropdown-item" to={`/super/report-form/list`} >Form List</Link>
                <Link className="btn-sm dropdown-item" to={`/super/report-form/assign`} >Assign Form</Link>
                <Link className="btn-sm dropdown-item" to={`/super/report-data/list`} >Report Data List</Link>
                <Link className="btn-sm dropdown-item" to={`/super/report-form/client-list`} >Form Client Access</Link>
            </div>
        </div>
    )
}

export default Index
