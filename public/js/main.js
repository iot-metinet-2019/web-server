const navComponentDOM = document.getElementsByClassName("js-nav")[0];

const chartJSOptions = {
  scales: {
    yAxes: [
      {
        ticks: {
          beginAtZero: true
        }
      }
    ],
    xAxes: [
      {
        type: "time",
        time: {
          displayFormats: {
            millisecond: "h:mm:ss.SSS a"
          }
        }
      }
    ]
  },
  animation: {
    duration: 0
  },
  hover: {
    animationDuration: 0
  },
  responsiveAnimationDuration: 0
};

const mainComponent = document.getElementsByTagName("main")[0];

let sensors = [];

let sensorsLength;

let colors = new Array();

const apiUrl = window.location.origin;

function createXMLHttpRequestObject(){
  return new XMLHttpRequest();
}

function getData(resolve){
  const request = createXMLHttpRequestObject();
  request.onload = (e) => {
    if (request.readyState === 4) {
      if (request.status === 200) {
        resolve(JSON.parse(request.responseText));
      }
    }
  }
  request.open('GET', apiUrl + '/init', false);
  request.send(null);
}

function loadGraphs() {
  const httpRequest = new Promise((resolve) => getData(resolve));
  
  httpRequest.then((response) => {
    const model = response;
    
    sensors = [...model.sensors];
    sensorsLength = sensors.length;
  
    mainComponent.innerHTML = "";
    navComponentDOM.innerHTML = "";
    // navComponentDOM.innerHTML = "<li>Ajouter un capteur</li>";
  
    colors = ["#3e95cd"];
  
    for (let i = 0; i < sensorsLength; ++i) {
      const uniqClass = "js-chart-" + sensors[i].id;
      const uniqId = uniqClass + "id";
  
      mainComponent.innerHTML +=
        '<canvas id="' + uniqId + '" class="' + uniqClass + '"></canvas>';
      navComponentDOM.innerHTML +=
        '<a href="#' + uniqId + '"><li>' + sensors[i].name.charAt(0).toUpperCase() + sensors[i].name.slice(1) + "</li></a>";
  
      const canvas = document.getElementsByClassName(uniqClass)[0];
      const ctx = canvas.getContext("2d");
  
      const measureslength = model.measures.length;
  
      const temperatureDates = new Array();
      const temperatureValues = new Array();
  
      for (var j = 0; j < measureslength; ++j) {
        const measure = model.measures[j];
        const sensorMeasuresLength = measure.sensorMeasures.length;
        for (var k = 0; k < sensorMeasuresLength; ++k) {
          const sensorMeasure = measure.sensorMeasures[k];
          switch (sensorMeasure.sensor.name) {
            case "temperature":
              temperatureDates.push(measure.time);
              temperatureValues.push(sensorMeasure.value);
              break;
          }
        }
      }
  
      const myChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: temperatureDates,
          datasets: [
            {
              data: temperatureValues,
              label: "Température",
              borderColor: colors[i],
              fill: false
            }
          ]
        },
        options: chartJSOptions
      });
    }
  })
}

loadGraphs();

setInterval(() => {
  loadGraphs();
}, 1000);
