const dailyReport = document.querySelector('#myChart');
const inputDaily = document.querySelector('#daily');
const bodyDailyReport = document.querySelector('#body-daily-report');
const weeklyReport = document.querySelector('#myChart1');
const monthlyReport = document.querySelector('#myChart2');
const table = document.querySelector('.table');

const headers = {
  Accept: 'application/json',
  'Content-Type': 'application/json',
  'X-CSRF-TOKEN': csrfToken
}

const datasets = [
  'settlement',
  'pending',
  'cancel',
  'deny',
  'expire',
];

const borderColors = [
  {label: 'deny', color: 'rgb(255, 65, 54)'},      
  {label: 'cancel', color: 'rgb(255, 65, 54)'},      
  {label: 'settlement', color: 'rgb(92, 184, 92)'},      
  {label: 'expire', color: 'rgb(91, 192, 222)'},      
  {label: 'pending', color: 'rgb(0, 123, 255)'},    
]

const backgroundColors = [
  {label: 'deny', color: 'rgba(255, 65, 54, 0.2)'},      
  {label: 'cancel', color: 'rgba(255, 65, 54, 0.2)'},      
  {label: 'settlement', color: 'rgba(92, 184, 92, 0.2)'},      
  {label: 'expire', color: 'rgba(91, 192, 222, 0.2)'},      
  {label: 'pending', color: 'rgba(0, 123, 255, 0.2)'}
];

const colorMapping = {
  'deny': { backgroundColor: 'rgba(255, 65, 54, 0.2)', borderColor: 'rgb(255, 65, 54)' },
  'cancel': { backgroundColor: 'rgba(255, 65, 54, 0.2)', borderColor: 'rgb(255, 65, 54)' },
  'settlement': { backgroundColor: 'rgba(92, 184, 92, 0.2)', borderColor: 'rgb(92, 184, 92)' },
  'expire': { backgroundColor: 'rgba(91, 192, 222, 0.2)', borderColor: 'rgb(91, 192, 222)' },
  'pending': { backgroundColor: 'rgba(0, 123, 255, 0.2)', borderColor: 'rgb(0, 123, 255)' },
};

const dailyChart = new Chart(dailyReport, {
  type: 'pie',
  data: {
    labels: datasets.map((item) => item),
    datasets: [
    {
      data: [0,0,0,0,0],
      backgroundColor: Object.values(colorMapping).map((item) => item.backgroundColor),
      borderColor: Object.values(colorMapping).map((item) => item.borderColor),
      borderWidth: 1
    },
  ]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    },
    responsive: true
  }
});

const weeklyChart = new Chart(weeklyReport, {
  type: 'pie',
  data: {
    labels: datasets.map((item) => item),
    datasets: [
    {
      data: [0,0,0,0,0],
      backgroundColor: Object.values(colorMapping).map((item) => item.backgroundColor),
      borderColor: Object.values(colorMapping).map((item) => item.borderColor),
      borderWidth: 1
    },
  ]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    },
    responsive: true
  }
});

const monthlyChart = new Chart(monthlyReport, {
  type: 'pie',
  data: {
    labels: datasets.map((item) => item),
    datasets: [
    {
      data: [0,0,0,0,0],
      backgroundColor: Object.values(colorMapping).map((item) => item.backgroundColor),
      borderColor: Object.values(colorMapping).map((item) => item.borderColor),
      borderWidth: 1
    },
  ]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    },
    responsive: true
  }
});

const __updateChart = (chart, newData) => {
  const data = Object.entries(newData).map(([label, value]) => ({
    label,
    total_items: value.total_count,
  }));

  const bgColors = data.map(item => colorMapping[item.label].backgroundColor);
  const borderColors = data.map(item => colorMapping[item.label].borderColor);
  

  chart.data.labels = data.map(item => item.label);
  if(newData.length < 1) {
    chart.data.datasets[0].data = [0,0,0,0,0];
  } else {
    chart.data.datasets[0].data = data.map(item => item.total_items);
  }
  chart.data.datasets[0].backgroundColor = bgColors;
  chart.data.datasets[0].borderColor = borderColors;

  chart.update();
}

const __fetchDailyReport = async (date) => {
  try {
    const request = await fetch(`${SEGMENT_URL}/daily-reports`, {
      method: "POST",
      headers,
      body: JSON.stringify({
        date
      })
    });
    const response = await request.json();
    return response
  } catch (err) {
    throw new Error('something went wrong')
  }
}

const __manipulateDailyReport = (res, date) => {

  __updateChart(dailyChart, res.data)
  
  bodyDailyReport.innerHTML = "";

  Object.keys(res.data).map((item) => {
    let newElement = "";
    switch(item) {
      case 'settlement': 
        newElement = `
                        <tr>
                          <td><span class="badge bg-success">Settlement</span></td>
                          <td class="font-semibold daily" data-id="settlement">0</td>
                          <td class="font-bold daily">Rp0</td>
                        </tr>
                      `
        bodyDailyReport.insertAdjacentHTML('beforeend', newElement);
        break;
      case 'pending': 
        newElement = `
                        <tr>
                          <td><span class="badge bg-primary">Pending</span></td>
                          <td class="font-semibold daily" data-id="pending">0</td>
                          <td class="font-bold daily">Rp0</td>
                        </tr>
                      `
        bodyDailyReport.insertAdjacentHTML('beforeend', newElement);
        break;
      case 'cancel': 
        newElement = `
                        <tr>
                          <td><span class="badge bg-danger">Cancel</span></td>
                          <td class="font-semibold daily" data-id="cancel">0</td>
                          <td class="font-bold daily">Rp0</td>
                        </tr>
                      `
        bodyDailyReport.insertAdjacentHTML('beforeend', newElement);
        break;
      case 'deny': 
        newElement = `
                        <tr>
                          <td><span class="badge bg-danger">Deny</span></td>
                          <td class="font-semibold daily" data-id="deny">0</td>
                          <td class="font-bold daily">Rp0</td>
                        </tr>
                      `
        bodyDailyReport.insertAdjacentHTML('beforeend', newElement);
        break;
      case 'expire': 
        newElement = `
                        <tr>
                          <td><span class="badge bg-info">Expire</span></td>
                          <td class="font-semibold daily" data-id="expire">0</td>
                          <td class="font-bold daily">Rp0</td>
                        </tr>
                      `
        bodyDailyReport.insertAdjacentHTML('beforeend', newElement);
        break;
      default: 
        newElement = `
                        <tr>
                          <td><span class="badge bg-primary">Hub</span></td>
                          <td class="font-semibold daily" data-id="hub">0</td>
                          <td class="font-bold daily">Rp0</td>
                        </tr>
                      `
        bodyDailyReport.insertAdjacentHTML('beforeend', newElement);
        break;
    }
  })
  
  newElement = `<tr>
                  <th colspan="2">Total Items</th>
                  <th>Total</th>
                </tr>
                <tr>
                  <th colspan="2" id="total_items_count_daily">0</th>
                  <th id="total_price_daily">0</th>
                </tr>
                `
  bodyDailyReport.insertAdjacentHTML('beforeend', newElement);

  const count = document.querySelectorAll('.font-semibold.daily')
  const sum = document.querySelectorAll('.font-bold.daily')
  const totalItems = document.querySelector('#total_items_count_daily')
  const totalPrice = document.querySelector('#total_price_daily')
  Object.values(res.data).map((item, index) => {
    if(count.item(index).getAttribute('data-id') == Object.keys(res.data)[index]){
      count.item(index).textContent = item.total_count
    }
  })

  count.forEach((item, index) => item.textContent = Object.values(res.data)[index].total_count)
  sum.forEach((item, index) => item.textContent = `Rp${convertRupiah(Object.values(res.data)[index].total_sum)}`)
  totalItems.textContent = res.total_items
  totalPrice.textContent = `Rp${convertRupiah(res.total_sum)}`
  inputDaily.value = formatDate(date);

}
 
document.addEventListener('DOMContentLoaded', async () => {

  __fetchDailyReport(formatDate(new Date())).then((res) => {
    __manipulateDailyReport(res, formatDate(new Date()))
  }).catch(err => {
    console.error(err)
    errorToast('something went wrong')
  })

  async function dailyReportHandler(e) {
    try { 
      const daily = await __fetchDailyReport(this.value);
      if(daily.status_code !== 200) {
        throw new Error('something went wrong');
      }
      __manipulateDailyReport(daily, this.value)
    } catch (err) {
      errorToast(err)
    }
  }


  inputDaily.addEventListener('change', dailyReportHandler)

  return () => {
    inputDaily.removeEventListener('change', dailyReportHandler)
  }

})