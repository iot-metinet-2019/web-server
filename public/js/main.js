(function()
{
    const API_URL = window.location.origin;
    const MAIN_ELEMENT = document.getElementsByTagName("main")[0];
    const NAV_ELEMENT = document.getElementsByClassName("js-nav-ul")[0];

    const CHART_OPTIONS = {
        scales: {
            yAxes: [{ticks: {beginAtZero: true}}],
            xAxes: [{
                type: "time",
                time: {displayFormats: {millisecond: "h:mm:ss.SSS a"}}
            }]
        },

        animation: {duration: 0},
        hover: {animationDuration: 0},
        responsiveAnimationDuration: 0
    };

    var sensors = [];
    var times = [];
    var lastTime = 0;

    function initGraphs()
    {
        for(var sensor of sensors)
        {
            var canvas = document.createElement('canvas');
            MAIN_ELEMENT.appendChild(canvas);

            sensor.canvas = canvas;
            sensor.ctx = canvas.getContext('2d');
            sensor.values = [];

            sensor.chart = new Chart(sensor.ctx, {
                type: "line",
                data: {
                    labels: times,
                    datasets: [
                        {
                            data: sensor.values,
                            label: sensor.name,
                            borderColor: "#3e95cd",
                            fill: false
                        }
                    ]
                },

                options: CHART_OPTIONS
            });

            var li = document.createElement('li');
            li.innerHTML = sensor.name;

            NAV_ELEMENT.appendChild(li);
        }
    }

    function updateGraphs()
    {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function()
        {
            if(this.readyState !== 4)
                return;

            if(this.status !== 200)
                throw new Error("Error " + this.status + " retrieving data");

            var data = JSON.parse(this.responseText);
            lastTime = Math.round(Date.parse(data[data.length - 1].time) / 1000);

            for(var measure of data)
            {
                times.push(measure.time);

                for(var sensorMeasure of measure.sensorMeasures)
                    for(var sensor of sensors)
                        if(sensor.id === sensorMeasure.sensor.id)
                            sensor.values.push(sensorMeasure.value);
            }

            for(var sensor of sensors)
                sensor.chart.update();
        }

        xhr.open('GET', API_URL + '/get-data/' + lastTime, true);
        xhr.send();
    }

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function()
    {
        if(this.readyState !== 4)
            return;

        if(this.status !== 200)
            throw new Error("Error " + this.status + " retrieving sensors list");

        sensors = JSON.parse(this.responseText);

        initGraphs();
        updateGraphs();
        
        setInterval(() => {
            updateGraphs();
        }, 5000);
    }

    xhr.open('GET', API_URL + '/get-sensors', true);
    xhr.send();
})();
