//user chart
let userChartCanvas = $("#usersChart").get(0).getContext("2d");
let userData = {
    labels: [languages.free, languages.paid],
    datasets: [{
        data: [freeUsers, paidUsers],
        backgroundColor: ["#f56954", "#f39c12"],
    }, ],
};

let userOptions = {
    maintainAspectRatio: false,
    responsive: true,
    plugins: {
        labels: {
            fontColor: "#fff",
        },
    },
};

new Chart(userChartCanvas, {
    type: "doughnut",
    data: userData,
    options: userOptions,
});

//gender chart
let genderChartCanvas = $("#genderChart").get(0).getContext("2d");
let genderData = {
    labels: [languages.male, languages.female],
    datasets: [{
        data: [maleUsers, femaleUsers],
        backgroundColor: ["#f56954", "#f39c12"],
    }, ],
};

let genderOptions = {
    maintainAspectRatio: false,
    responsive: true,
    plugins: {
        labels: {
            fontColor: "#fff",
        },
    },
};

new Chart(genderChartCanvas, {
    type: "doughnut",
    data: genderData,
    options: genderOptions,
});

//income chart
let incomeChartData = {
    labels: [
        languages.jan,
        languages.feb,
        languages.mar,
        languages.apr,
        languages.may,
        languages.june,
        languages.jul,
        languages.aug,
        languages.sep,
        languages.oct,
        languages.nov,
        languages.dec,
    ],
    datasets: [{
        label: languages.income + currentYear,
        backgroundColor: "#f39c12",
        borderColor: "#f39c12",
        data: [],
    }, ],
};

for (let i = 1; i <= 12; i++) {
    incomeChartData.datasets[0].data[i - 1] = montlyIncome[i] || 0;
}

let incomeChartCanvas = $("#incomeChart").get(0).getContext("2d");
let incomeChart_ = $.extend(true, {}, incomeChartData);
incomeChart_.datasets[0] = incomeChartData.datasets[0];

let chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    datasetFill: false,
    plugins: {
        labels: {
            render: function() {
                return "";
            },
            fontColor: "#000",
        },
    },
};

new Chart(incomeChartCanvas, {
    type: "bar",
    data: incomeChart_,
    options: chartOptions,
});

//user registration
let userGraphData = {
    labels: [
        languages.jan,
        languages.feb,
        languages.mar,
        languages.apr,
        languages.may,
        languages.june,
        languages.jul,
        languages.aug,
        languages.sep,
        languages.oct,
        languages.nov,
        languages.dec,
    ],
    datasets: [{
        label: languages.user_registration + currentYear,
        backgroundColor: "#f39c12",
        borderColor: "#f39c12",
        data: [],
    }, ],
};

for (let i = 1; i <= 12; i++) {
    userGraphData.datasets[0].data[i - 1] = userGraph[i] || 0;
}

let userGraphCanvas = $("#userGraph").get(0).getContext("2d");
let userGraph_ = $.extend(true, {}, userGraphData);
userGraph_.datasets[0] = userGraphData.datasets[0];

new Chart(userGraphCanvas, {
    type: "bar",
    data: userGraph_,
    options: chartOptions,
});