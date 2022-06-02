import React, { Suspense, lazy } from 'react'
import ReactDOM from 'react-dom'
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import './../../../resources/sass/app.scss'
import OutwardItemDetail from './stock/outward_item_detail';
import UploadStock from './stock/upload_stock';

const CreateInputsList = lazy(() => import('./entities_form/CreateInputsList'));
const UpdateForm = lazy(() => import('./entities_form/UpdateInputsList'));
const FormsList = lazy(() => import('./entities_form/FormsList'));
const ViewForm = lazy(() => import('./entities_form/ViewForm'));
const CreateInputData = lazy(() => import('./entities_form_data/CreateInputData'));
const UpdateInputData = lazy(() => import('./entities_form_data/UpdateInputData'));
const FormDataList = lazy(() => import('./entities_form_data/FormDataList'));
const ViewFormData = lazy(() => import('./entities_form_data/ViewFormData'));
const EntityInfo = lazy(() => import('./pictorial_history/entity_history/info'));
const EntityHistory = lazy(() => import('./pictorial_history/entity_history/history'));
const MapLocation = lazy(() => import('./map_location/entity_location'));

const InfoReport = lazy(() => import('./report/report_data/info'));
const ListingReport = lazy(() => import('./report/report_data/report_listing'));
const ReportDetail = lazy(() => import('./report/report_data/report_detail'));
const CreateReport = lazy(() => import('./report/report_form/create'));
const ReportDataView = lazy(() => import('./report/report_data/report_data_view'));
const UpdateReport = lazy(() => import('./report/report_form/update'));
const GenerateReport = lazy(() => import('./report/report_data/generate_report'));
const Info = lazy(() => import('./generate/list'));
const ListReport = lazy(() => import('./report/report_form/list'));
const AssignReport = lazy(() => import('./report/report_form/assign'));
const AssignReportFormList = lazy(() => import('./report/report_form/assigned_form_list'));//regular report form assign to me
const AssignReportFormByMe = lazy(() => import('./report/report_form/assigned_form_by_me'));//regular report form list assign by me
const AssignForm = lazy(() => import('./assign/list'));
const RemoveAssignedForm = lazy(()=> import('./assign/remove'));

const ReportFormCreate = lazy(()=> import('./super_admin/report_form/create'));
const ReportFormUpdate = lazy(()=> import('./super_admin/report_form/update'));
const ReportFormList = lazy(()=> import('./super_admin/report_form/list'));
const ReportFormAssign = lazy(()=> import('./super_admin/report_form/staff_assign'));
const ReportDataList = lazy(()=> import('./super_admin/report_data/list'));
// const ReportFormClientAssign = lazy(() => import('./super_admin/report_form/client_assign'))
const ReportFormClientList = lazy(() => import('./super_admin/report_form/client_report_list'))

const ClientAssignedReportForms = lazy(() => import('./report/report_form/client_assigned_report_forms'))
const ClientAssignedReportList = lazy(() => import('./report/report_form/client_assigned_report_list'))

const EntityFormCreate = lazy(()=> import('./super_admin/entity_form/create'));
const EntityFormUpdate = lazy(()=> import('./super_admin/entity_form/update'));
const EntityFormList = lazy(()=> import('./super_admin/entity_form/list'));
const EntityFormAssign = lazy(()=> import('./super_admin/entity_form/staff_assign'));
const EntityDataList = lazy(()=> import('./super_admin/entity_data/list'));
// const EntityFormClientAssign = lazy(() => import('./super_admin/entity_form/client_assign'))
const EntityFormClientList = lazy(() => import('./super_admin/entity_form/client_form_list'))
const ClientAssignedEntityForms = lazy(() => import('../components/entities_form/client_assigned_entity_form'))
const ClientAssignedEntityList = lazy(() => import('../components/entities_form/client_assigned_entity_list'))

const StaffAttendance = lazy(() => import('../components/staff_attendance/index'))

const CategoryCreate = lazy(() => import('../components/stock/category/create'))
const CategoryList = lazy(() => import('../components/stock/category/list'))
const ItemCreate = lazy(() => import('../components/stock/item/create'))
const ItemList = lazy(() => import('../components/stock/item/list'))
const OpeningStock = lazy(() => import('./stock/opening_stock'))
const OpeningStockList = lazy(() => import('./stock/opening_stock_list'))
const InwardStock = lazy(() => import('../components/stock/inward_stock'))
const InwardStockList = lazy(() => import('../components/stock/inward_stock_list'))
const OutwardStockList = lazy(() => import('../components/stock/outward_stock_list'))
const StockRegister = lazy(() => import('../components/stock/stock_register'))
const BalanceSheet = lazy(() => import('../components/stock/balance_sheet'))

const Dashboard = lazy(() => import ('../components/dashboard/index'))
let AppRoute = () => {

    return (
        <Router>
            <Suspense fallback={<div>Loading...</div>}>
                <Switch>
                    <Route exact path='/' component={Dashboard} />
                    <Route exact path='/entities-form/' component={FormsList} />
                    <Route exact path='/entities-form/create' component={CreateInputsList} />
                    <Route exact path='/entities-form/:id' component={UpdateForm} />
                    <Route exact path='/entities-form-view/:id' component={ViewForm} />
                    <Route exact path='/entities-form/:form/entities-form-data' component={FormDataList} />
                    <Route exact path='/entities-form/:form/entities-form-data/create' component={CreateInputData} />
                    <Route exact path='/entities-form/:form/entities-form-data/:formData' component={UpdateInputData} />
                    <Route exact path='/entity-data-view/:id' component={ViewFormData} />
                    <Route exact path='/entities-history' component={EntityInfo} />
                    <Route exact path='/entities-history/:id' component={EntityHistory} />
                    <Route exact path='/map-location' component={MapLocation} />
                    <Route exact path='/map-location/:id' component={MapLocation} />

                    <Route exact path='/report-info' component={InfoReport} />
                    <Route exact path='/report-info/listing' component={ListingReport} />
                    <Route exact path='/report-info/listing/:id' component={ListingReport} />
                    <Route exact path='/report-form/create' component={CreateReport} />
                    <Route exact path='/report-info/detail/:id' component={ReportDetail} />
                    <Route exact path='/report-info/view/:id' component={ReportDataView} />
                    <Route exact path='/report-form/:id' component={UpdateReport} />
                    <Route exact path='/report-generate' component={GenerateReport} />
                    <Route exact path='/generate-report' component={Info} />
                    <Route exact path='/report-assign' component={AssignReport} />
                    <Route exact path='/report-form-assigned' component={AssignReportFormList} />
                    <Route exact path='/report-form-assign-by-you' component={AssignReportFormByMe} />
                    <Route exact path='/report-form' component={ListReport} />
                    <Route exact path='/assign-entities-form' component={AssignForm} />
                    <Route exact path='/assign-entities-form/remove' component={RemoveAssignedForm} />

                    {/* Client new */}
                    <Route exact path='/client/report-form/assigned-list' component={ClientAssignedReportForms} />
                    <Route exact path='/client/report-form/:id' component={ClientAssignedReportList} />
                    <Route exact path='/client/entity-form/assigned-list' component={ClientAssignedEntityForms} />
                    <Route exact path='/client/entity-form/:id' component={ClientAssignedEntityList} />

                    {/* Super Admin New */}
                    <Route exact path='/super/report-form/create' component={ReportFormCreate} />
                    <Route exact path='/super/report-form/update/:id' component={ReportFormUpdate} />
                    <Route exact path='/super/report-form/assign' component={ReportFormAssign} />
                    <Route exact path='/super/report-form/list' component={ReportFormList} />
                    <Route exact path='/super/report-form/client-list' component={ReportFormClientList} />
                    <Route exact path='/super/report-data/list' component={ReportDataList} />
                    <Route exact path='/super/report-data/list/:id' component={ReportDataList} />
                    
                    <Route exact path='/super/entity-form/create' component={EntityFormCreate} />
                    <Route exact path='/super/entity-form/update/:id' component={EntityFormUpdate} />
                    <Route exact path='/super/entity-form/assign' component={EntityFormAssign} />
                    <Route exact path='/super/entity-form/list' component={EntityFormList} />
                    <Route exact path='/super/entity-form/client-list' component={EntityFormClientList} />
                    <Route exact path='/super/entity-data/list' component={EntityDataList} />
                    <Route exact path='/super/entity-data/list/:id' component={EntityDataList} />
                    {/* <Route exact path='/super/report-data/client-assign' component={ReportFormClientAssign} /> */}
                    {/* <Route exact path='/super/entity-data/client-assign' component={EntityFormClientAssign} /> */}

                    <Route exact path='/staff-attendance' component={StaffAttendance} />

                    <Route exact path='/stock/category-create' component={CategoryCreate} />
                    <Route exact path='/stock/category-list' component={CategoryList} />

                    <Route exact path='/stock/item-create' component={ItemCreate} />
                    <Route exact path='/stock/item-list' component={ItemList} />
                    <Route exact path='/stock/upload-stock' component={UploadStock} />
                    
                    {/* <Route exact path='/stock/opening' component={OpeningStock} /> */}
                    {/* <Route exact path='/stock/opening-list' component={OpeningStockList} /> */}
                    {/* <Route exact path='/stock/inward' component={InwardStock} /> */}
                    <Route exact path='/stock/inward-list' component={InwardStockList} />
                    <Route exact path='/stock/outward-list' component={OutwardStockList} />
                    <Route exact path='/stock-register' component={StockRegister} />
                    <Route exact path='/stock/balance-sheet/:id' component={BalanceSheet} />
                    <Route exact path='/stock/outward-item-detail/:id' component={OutwardItemDetail} />

                </Switch>
            </Suspense>
        </Router>
    )
}

ReactDOM.render(<AppRoute />, document.getElementById('route'))