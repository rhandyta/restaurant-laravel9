@extends('layout') @section('title', 'Financial Reports')

@section('content')
    <div class="row">
        <div class="col-12 col-md-8 mb-3">
            <h6>Daily Financial Report</h6>
            <form>
                <div class="input-group row">
                    <label for="daily" class="col-form-label col-12 col-md-2">Date</label>
                    <div class="col-12 col-md-5">
                        <input type="date" class="form-control" id="daily" name="daily" value="">
                    </div>
                </div>
            </form>
            <canvas id="myChart"></canvas>
        </div>
        <div class="col-12 col-md-4">
            <h6>Daily Financial Report</h6>
            <div class="card h-auto">
                <div class="card-body">
                   <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Items</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="body-daily-report">
                            <tr>
                                <td><span class="badge bg-success">Settlement</span></td>
                                <td class="font-semibold daily">0</td>
                                <td class="font-bold daily">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">Pending</span></td>
                                <td class="font-semibold daily">0</td>
                                <td class="font-bold daily">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Cancel</span></td>
                                <td class="font-semibold daily">0</td>
                                <td class="font-bold daily">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Deny</span></td>
                                <td class="font-semibold daily">0</td>
                                <td class="font-bold daily">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info">Expire</span></td>
                                <td class="font-semibold daily">0</td>
                                <td class="font-bold daily">Rp0</td>
                            </tr>
                            <tr>
                                <th colspan="2">Total Items</th>
                                <th>Total</th>
                            </tr>
                            <tr>
                                <th colspan="2" id="total_items_count_daily">0</th>
                                <th id="total_price_daily">0</th>
                            </tr>
                        </tbody>
                    </table>
                   </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 mb-3">
            <h6>Weekly Financial Report</h6>
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <label for="start-weekly" class="col-form-label col-12 col-md-5">Start Date</label>
                            <div class="col-12 col-md-7">
                                <input type="date" class="form-control" id="start-weekly" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label for="end-weekly" class="col-form-label col-12 col-md-5">End Date</label>
                            <div class="col-12 col-md-7">
                                <input type="date" class="form-control" id="end-weekly" value="">
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>
            <canvas id="myChart1"></canvas>
        </div>
        <div class="col-12 col-md-4">
            <h6>Weekly Financial Report</h6>
            <div class="card h-auto">
                <div class="card-body">
                   <div class="table-responsive">
                    <table class="table">
                        <tbody id="body-weekly-report">
                            <tr>
                                <td><span class="badge bg-success">Settlement</span></td>
                                <td class="font-semibold weekly">0</td>
                                <td class="font-bold weekly">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">Pending</span></td>
                                <td class="font-semibold weekly">0</td>
                                <td class="font-bold weekly">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Cancel</span></td>
                                <td class="font-semibold weekly">0</td>
                                <td class="font-bold weekly">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Deny</span></td>
                                <td class="font-semibold weekly">0</td>
                                <td class="font-bold weekly">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info">Expire</span></td>
                                <td class="font-semibold weekly">0</td>
                                <td class="font-bold weekly">Rp0</td>
                            </tr>
                            <tr>
                                <th colspan="2">Total Items</th>
                                <th>Total</th>
                            </tr>
                            <tr>
                                <th colspan="2" id="total_items_count_weekly">0</th>
                                <th id="total_price_weekly">0</th>
                            </tr>
                        </tbody>
                    </table>
                   </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 mb-3">
            <h6>Monthly Financial Report</h6>
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <label for="start-monthly" class="col-form-label col-12 col-md-5">Start Date</label>
                            <div class="col-12 col-md-7">
                                <input type="date" class="form-control" id="start-monthly" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label for="end-monthly" class="col-form-label col-12 col-md-5">End Date</label>
                            <div class="col-12 col-md-7">
                                <input type="date" class="form-control" id="end-monthly" value="">
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>
            <canvas id="myChart2"></canvas>
        </div>
        <div class="col-12 col-md-4">
            <h6>Monthly Financial Report</h6>
            <div class="card h-auto">
                <div class="card-body">
                   <div class="table-responsive">
                    <table class="table">
                        <tbody id="body-monthly-report">
                            <tr>
                                <td><span class="badge bg-success">Settlement</span></td>
                                <td class="font-semibold monthly">0</td>
                                <td class="font-bold monthly">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">Pending</span></td>
                                <td class="font-semibold monthly">0</td>
                                <td class="font-bold monthly">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Cancel</span></td>
                                <td class="font-semibold monthly">0</td>
                                <td class="font-bold monthly">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Deny</span></td>
                                <td class="font-semibold monthly">0</td>
                                <td class="font-bold monthly">Rp0</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info">Expire</span></td>
                                <td class="font-semibold monthly">0</td>
                                <td class="font-bold monthly">Rp0</td>
                            </tr>
                            <tr>
                                <th colspan="2">Total Items</th>
                                <th>Total</th>
                            </tr>
                            <tr>
                                <th colspan="2" id="total_items_count_monthly">0</th>
                                <th id="total_price_monthly">0</th>
                            </tr>
                        </tbody>
                    </table>
                   </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/chart.js/Chart.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/src/report.css') }}">
@endsection

@section('javascript')
    <script src="{{ asset('assets/extensions/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/src/report.js') }}"></script>
@endsection