const dailyReport = document.querySelector('#myChart');
const inputDaily = document.querySelector('#daily');
const bodyDailyReport = document.querySelector('#body-daily-report');

const weeklyReport = document.querySelector('#myChart1');
const inputStartDateWeekly = document.querySelector('#start-weekly')
const inputEndDateWeekly = document.querySelector('#end-weekly');
const bodyWeeklyReport = document.querySelector('#body-weekly-report');

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

const __fetchWeeklyReport = async (startDate, endDate) => {
  try {
    const request = await fetch(`${SEGMENT_URL}/weekly-reports`, {
      method: "POST",
      headers,
      body: JSON.stringify({
        startDate,
        endDate
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

const __manipulateWeeklyReport = (res, startDate, endDate) => {

  __updateChart(weeklyChart, res.data)
  
  bodyWeeklyReport.innerHTML = "";

  Object.keys(res.data).map((item) => {
    let newElement = "";
    switch(item) {
      case 'settlement': 
        newElement = `
                        <tr>
                          <td><span class="badge bg-success">Settlement</span></td>
                          <td class="font-semibold weekly" data-id="settlement">0</td>
                          <td class="font-bold weekly">Rp0</td>
                        </tr>
                      `
        bodyWeeklyReport.insertAdjacentHTML('beforeend', newElement);
        break;
      case 'pending': 
        newElement = `
                        <tr>
                          <td><span class="badge bg-primary">Pending</span></td>
                          <td class="font-semibold weekly" data-id="pending">0</td>
                          <td class="font-bold weekly">Rp0</td>
                        </tr>
                      `
        bodyWeeklyReport.insertAdjacentHTML('beforeend', newElement);
        break;
      case 'cancel': 
        newElement = `
                        <tr>
                          <td><span class="badge bg-danger">Cancel</span></td>
                          <td class="font-semibold weekly" data-id="cancel">0</td>
                          <td class="font-bold weekly">Rp0</td>
                        </tr>
                      `
        bodyWeeklyReport.insertAdjacentHTML('beforeend', newElement);
        break;
      case 'deny': 
        newElement = `
                        <tr>
                          <td><span class="badge bg-danger">Deny</span></td>
                          <td class="font-semibold weekly" data-id="deny">0</td>
                          <td class="font-bold weekly">Rp0</td>
                        </tr>
                      `
        bodyWeeklyReport.insertAdjacentHTML('beforeend', newElement);
        break;
      case 'expire': 
        newElement = `
                        <tr>
                          <td><span class="badge bg-info">Expire</span></td>
                          <td class="font-semibold weekly" data-id="expire">0</td>
                          <td class="font-bold weekly">Rp0</td>
                        </tr>
                      `
        bodyWeeklyReport.insertAdjacentHTML('beforeend', newElement);
        break;
      default: 
        newElement = `
                        <tr>
                          <td><span class="badge bg-primary">Hub</span></td>
                          <td class="font-semibold weekly" data-id="hub">0</td>
                          <td class="font-bold weekly">Rp0</td>
                        </tr>
                      `
        bodyWeeklyReport.insertAdjacentHTML('beforeend', newElement);
        break;
    }
  })

  newElement = `<tr>
                  <th colspan="2">Total Items</th>
                  <th>Total</th>
                </tr>
                <tr>
                  <th colspan="2" id="total_items_count_weekly">0</th>
                  <th id="total_price_weekly">0</th>
                </tr>
                `
  bodyWeeklyReport.insertAdjacentHTML('beforeend', newElement);

  const count = document.querySelectorAll('.font-semibold.weekly')
  const sum = document.querySelectorAll('.font-bold.weekly')
  const totalItems = document.querySelector('#total_items_count_weekly')
  const totalPrice = document.querySelector('#total_price_weekly')
  Object.values(res.data).map((item, index) => {
    if(count.item(index).getAttribute('data-id') == Object.keys(res.data)[index]){
      count.item(index).textContent = item.total_count
    }
  })

  count.forEach((item, index) => item.textContent = Object.values(res.data)[index].total_count)
  sum.forEach((item, index) => item.textContent = `Rp${convertRupiah(Object.values(res.data)[index].total_sum)}`)
  totalItems.textContent = res.total_items
  totalPrice.textContent = `Rp${convertRupiah(res.total_sum)}`
  
  inputStartDateWeekly.value = formatDate(startDate);
  inputEndDateWeekly.value = formatDate(endDate);

}
 
document.addEventListener('DOMContentLoaded', async () => {

  const currentDate = new Date();
  const formattedDate = formatDate(currentDate);
  const lastWeekDate = new Date(currentDate);
  lastWeekDate.setDate(currentDate.getDate() - 7);
  const lastWeekFormattedDate = formatDate(lastWeekDate);

  __fetchDailyReport(formatDate(formattedDate)).then((res) => {

    inputDaily.setAttribute('max', formattedDate)
    __manipulateDailyReport(res, formatDate(formattedDate))

  }).catch(err => {
    console.error(err)
    errorToast('something went wrong')
  })

  __fetchWeeklyReport(lastWeekFormattedDate, formatDate(formattedDate)).then((res) => {
    
    inputStartDateWeekly.setAttribute('max', formattedDate)
    inputEndDateWeekly.setAttribute('min', lastWeekFormattedDate)
    inputEndDateWeekly.setAttribute('max', formattedDate)
    __manipulateWeeklyReport(res, lastWeekFormattedDate, formatDate(formattedDate))

  }).catch(err => {
    console.error('error', err)
    errorToast(err)
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

  async function weeklyReportHandler(e) {
    try {
      const selectedDate = new Date(this.value);
      const nextWeekDate = new Date(selectedDate);
      nextWeekDate.setDate(selectedDate.getDate() + 7);
      
      if(this.getAttribute('id') == 'start-weekly'){
        inputEndDateWeekly.setAttribute('min', formatDate(this.value));
        inputEndDateWeekly.setAttribute('max', formatDate(nextWeekDate));
  
        if(selectedDate != currentDate && selectedDate.getMonth() < currentDate.getMonth()) {
          inputEndDateWeekly.setAttribute('max', formatDate(nextWeekDate));
          console.log('first')
        }
  
        if (currentDate.getDate() - selectedDate.getDate() < 7 && selectedDate.getMonth() == lastWeekDate.getMonth() && formatDate(selectedDate) != formatDate(currentDate)) {
          inputEndDateWeekly.setAttribute('max', formatDate(currentDate));
          console.log('two')
        }
  
        if (formatDate(selectedDate) === formatDate(currentDate)) {
          inputEndDateWeekly.setAttribute('max', this.value);
          console.log('tri')
        }
        // if(selectedDate.getMonth() < nextWeekDate.getMonth()) {
        //   inputEndDateWeekly.setAttribute('max', formatDate(nextWeekDate));
        //   console.log('first')
        // }
  
        // if (currentDate.getDate() - selectedDate.getDate() < 7 && selectedDate.getMonth() == nextWeekDate.getMonth() && formatDate(selectedDate) != formatDate(currentDate)) {
        //   inputEndDateWeekly.setAttribute('max', formatDate(currentDate));
        //   console.log('two')
        // }
  
        // if (formatDate(selectedDate) === formatDate(currentDate)) {
        //   inputEndDateWeekly.setAttribute('max', this.value);
        //   console.log('tri')
        // }
      } 

      if(this.getAttribute('id') == 'end-weekly') {
        const weekly = await __fetchWeeklyReport(inputStartDateWeekly.value, inputEndDateWeekly.value);
        if(weekly.status_code !== 200) {
          throw new Error('something went wrong');
        }
        __manipulateWeeklyReport(weekly, inputStartDateWeekly.value, inputEndDateWeekly.value)
      }
    } catch(err) {
      errorToast(err)
    }
  }
  
  inputDaily.addEventListener('change', dailyReportHandler)
  
  inputStartDateWeekly.addEventListener('change', weeklyReportHandler);
  inputEndDateWeekly.addEventListener('change', weeklyReportHandler);
  
  return () => {
    inputDaily.removeEventListener('change', dailyReportHandler)
    inputStartDateWeekly.removeEventListener('change', weeklyReportHandler);
    inputEndDateWeekly.removeEventListener('change', weeklyReportHandler);
  }

})