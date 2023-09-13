const dailyReport = document.querySelector('#myChart');
const inputDaily = document.querySelector('#daily');
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

const backgroundColors = {
  settlement: 'rgba(92, 184, 92, 0.2)', 
  pending: 'rgba(0, 123, 255, 0.2)',   
  cancel: 'rgba(255, 65, 54, 0.2)',    
  deny: 'rgba(255, 65, 54, 0.2)',      
  expire: 'rgba(91, 192, 222, 0.2)',   
};

const borderColors = {
  settlement: 'rgb(92, 184, 92)', 
  pending: 'rgb(0, 123, 255)',    
  cancel: 'rgb(255, 65, 54)',     
  deny: 'rgb(255, 65, 54)',      
  expire: 'rgb(91, 192, 222)',    
}

const dailyChart = new Chart(dailyReport, {
  type: 'pie',
  data: {
    labels: datasets.map((item) => item),
    datasets: [
    {
      data: [0,0,0,0,0],
      backgroundColor: Object.values(backgroundColors),
      borderColor: Object.values(borderColors),
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
      backgroundColor: Object.values(backgroundColors),
      borderColor: Object.values(borderColors),
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
      backgroundColor: Object.values(backgroundColors),
      borderColor: Object.values(borderColors),
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
  chart.data.datasets[0].data = Object.values(newData)
  chart.update()
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

  __updateChart(dailyChart, res.data_count)
  const count = document.querySelectorAll('.font-semibold.daily')
  const sum = document.querySelectorAll('.font-bold.daily')
  const totalItems = document.querySelector('#total_items_count_daily')
  const totalPrice = document.querySelector('#total_price_daily')
  count.forEach((item, index) => item.textContent = Object.values(res.data_count)[index])
  sum.forEach((item, index) => item.textContent = `Rp${convertRupiah(Object.values(res.data_sum)[index])}`)
  totalItems.textContent = `${convertRupiah(res.total_items)}`
  totalPrice.textContent = `Rp${convertRupiah(res.total_sum)}`
  inputDaily.value = formatDate(date);

}
 
document.addEventListener('DOMContentLoaded', async () => {

  __fetchDailyReport(formatDate(new Date())).then((res) => {
    __manipulateDailyReport(res, new Date())
  }).catch(err => errorToast('something went wrong'))

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