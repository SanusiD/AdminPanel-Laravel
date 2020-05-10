<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <link rel="stylesheet" href="css/main.css">
    <title>Admin Panel</title>
</head>
<body>
    <div class="head">
        <h1>ADMIN PANEL</h1>
    </div>
    <div class="container">
        <div class="totals">
            <div class="card-totals">
                <h3>Total Sales</h3>
                <h1 class="number">${{$salestotal}}</h1>
            </div>

            <div class="card-totals">
                <h3>Total Orders</h3>
                <h1 class="number">{{$totalorder}}</h1>
            </div>

            <div class="card-totals">
                <h3>Total Users</h3>
                <h1 class="number">{{$totalUsersCount}}</h1>
            </div>
        </div>

        <div class="overview">
            <div class="top">
                <h3>Sales Overview</h3>
                <div class="filter">
                    <form action="/" method="post">
                        {{ csrf_field() }}
                        <select name="year" id="year" >
                            @foreach ($yearly as $year)
                                <option value="{{$year->year}}" >{{$year->year}}</option>
                            @endforeach
                        </select>
                        <input type="submit" value="Filter">
                    </form>
                </div>
            </div>
            {{-- <canvas id="myChart"></canvas>  --}}
            <div class="canvas">
                <canvas id="mySecondChart" aria-label="Sales, Tax and Shipping"></canvas>
            </div>

        </div>

        <div class="graphs">
            <div class="card-graphs">
                <div class="canvas">
                    <h3>Top Buying Customers</h3>
                    <canvas id="top5Customers" aria-label="Top Buying Customers"></canvas>
                </div>
            </div>
            <div class="card-graphs">
                <h3>Province Sales</h3>
                <div class="canvas">
                    <canvas id="provinceSales" aria-label="Province Sales"></canvas>
                </div>
            </div>
        </div>

        <div class="activity">
            <div class="card-activity">
                <h3>Recent Activity</h3>
                <hr>
                @foreach ($data as $data)
                <h5>ID: {{$data->order_id}}</h5>
                 <p>{{$data->cust_fname}} from {{$data->cust_province}} has made a purchase of ${{$data->grand_total}}</p>
                
                <hr>
                @endforeach
            </div>
            <div class="card-activity">
                <section>
                    <div class="tbl-header">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <thead>
                                <tr>
                                    <th>Firstname</th>
                                    <th>Province</th>
                                    <th>City</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tbl-content">
                        <table cellpadding="0" cellspacing="0" border="0" style="scrollbar-color: #87ceeb #ff5621;">
                            <tbody>
                                @foreach ($totalUsers as $User)
                                    <tr>
                                        <td>{{$User->cust_fname}}</td>
                                        <td>{{$User->cust_province}}</td>
                                        <td>{{$User->cust_city}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>
</html>

<script>
// Month Array for Chart
var month = ["Nothing",
             "January", 
             "February", 
             "March",
             "April",
             "May",
             "June",
             "July",
             "August",
             "September",
             "October",
             "November", 
             "December"
            ]
 
//Declaring variables to store the month infomation
var yearFilter = <?php echo $yearFilter ?>;
var monthName = [];
var monthSales = [];
var monthShipping = [];
var monthtax = [];
console.log({monthName,monthtax,monthShipping,monthSales})
for (let i = 0; i < yearFilter.length; i++) {
                monthName[i] = month[yearFilter[i].month];
                monthtax[i] = yearFilter[i].tax.toFixed(2);
                monthShipping[i] = yearFilter[i].shipping.toFixed(2);
                monthSales[i] = yearFilter[i].total_sales.toFixed(2);
            }


//Declaring variables to store the Province infomation
var provinces = <?php echo $provinces ?>;
var provinceName = [];
var provinceSales = [];
for (let i = 0; i < provinces.length; i++) {
                provinceName[i] = provinces[i].cust_province;
                provinceSales[i] = provinces[i].total_sales.toFixed(2);
            }
//Declaring variables to store the Top customer infomation
var topcustomers = <?php echo $topcustomers ?>;
var customerName = [];
var customerSales = [];
for (let i = 0; i < topcustomers.length; i++) {
                customerName[i] = topcustomers[i].cust_fname;
                customerSales[i] = topcustomers[i].total_sales.toFixed(2);
            }
// //Declaring variables to store the Yearly infomation
// var yearlySales = <?php echo $yearly_sales ?>;
// var years = [];
// var totalSales = [];
// var shipping = [];
// var tax = [];
// for (let i = 0; i < yearlySales.length; i++) {
//                 years[i] = yearlySales[i].year;
//                 tax[i] = yearlySales[i].tax.toFixed(2);
//                 shipping[i] = yearlySales[i].shipping.toFixed(2);
//                 totalSales[i] = yearlySales[i].total_sales.toFixed(2);
//             }
// console.log({years,totalSales,shipping,tax});


      //PROVINCE SALES CHART
var ctx = document.getElementById('provinceSales').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: provinceName,
        datasets: [{
            label: 'Amount in Sales (CAD $)',
            data: provinceSales,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
            ],
            borderWidth: 1
        }]
    },
    
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        responsive: true,
        maintainAspectRatio: false
    }
});
// TOP CUSTOMERS CHART
var ctx = document.getElementById('top5Customers').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: customerName,
        datasets: [{
            label: 'Amount in Sales (CAD $)',
            data: customerSales,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    },
    
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        responsive: true,
        maintainAspectRatio: false
    }
});   

    //SALES OVERVIEW CHART
var ctx = document.getElementById('mySecondChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: monthName,
        datasets: [{
            label: 'Amount in Sales (CAD $)',
            data: monthSales,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        },
        {
            label: 'Amount in Shipping (CAD $)',
            data:monthShipping,
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        },
        {
            label: 'Amount in Tax (CAD $)',
            data:monthtax,
            backgroundColor: [
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    },
    
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>