// $(document).ready(function () {
//   cashflowChart();
// });

// function cashflowChart() {
//   $.ajax({
//     url: "cashflowChart.php",
//     type: "post",
//     beforeSend: function () {},
//     success: function (data, status) {
//       $("#cashflowChart").html(data);
//     },
//   });
// }
$(function () {
  $('input[name="rentang"]').daterangepicker({
    opens: "left",
  });
  $("#rentang").on("apply.daterangepicker", function (event, picker) {
    $(this).val(
      picker.startDate.format("YYYY-MM-DD") +
        " - " +
        picker.endDate.format("YYYY-MM-DD")
    );

    changeCashflowChart();
  });
});

function changeCashflowChart() {
  const rentang = $("#rentang").val();
  if (rentang) {
    $.ajax({
      url: "index.php", // The URL to handle the request
      type: "POST",
      data: { rentang: rentang },
      dataType: "text",
      success: function (data) {
        console.log(data);
        const parsedData = JSON.parse(data); // Parse the returned JSON data

        // Destroy the existing chart if it exists
        recreateCanvas();
        updateChart(parsedData);
        // Update the chart with new data

        notifikasi(true, "Rentang waktu berhasil diubah");
      },
      error: function () {
        notifikasi(false, "Terjadi kesalahan saat mengubah rentang waktu");
      },
    });
  }
}

// Function to update the chart with new data
function updateChart(parsedData) {
  const ctx = document.getElementById("profitLossIncomeChart").getContext("2d");

  // Merge and sort unique dates
  const allDates = [...new Set([...parsedData.incomeDate, ...parsedData.outcomeDate])]
    .sort((a, b) => new Date(a) - new Date(b));

  // Create a mapping of dates to income and outcome data
  const incomeMap = parsedData.incomeDate.reduce((acc, date, index) => {
    acc[date] = { y: parsedData.incomeAmount[index] || 0, name: parsedData.incomeName[index] || "No Data" };
    return acc;
  }, {});

  const outcomeMap = parsedData.outcomeDate.reduce((acc, date, index) => {
    acc[date] = { y: parsedData.outcomeAmount[index] || 0, name: parsedData.outcomeName[index] || "No Data" };
    return acc;
  }, {});

  // Generate data points for each dataset, ensuring dates are aligned
  const incomeData = allDates.map((date) => ({
    x: date,
    y: incomeMap[date]?.y || 0,
    name: incomeMap[date]?.name || "No Data",
  }));

  const outcomeData = allDates.map((date) => ({
    x: date,
    y: outcomeMap[date]?.y || 0,
    name: outcomeMap[date]?.name || "No Data",
  }));

  const data = {
    labels: allDates, // Sorted labels
    datasets: [
      {
        label: "Income",
        data: incomeData,
        borderColor: "rgba(54, 162, 235, 1)",
        backgroundColor: "rgba(54, 162, 235, 0.2)",
        fill: true,
        tension: 0.4,
        parsing: {
          xAxisKey: "x",
          yAxisKey: "y",
        },
      },
      {
        label: "Outcome",
        data: outcomeData,
        borderColor: "rgba(255, 99, 132, 1)",
        backgroundColor: "rgba(255, 99, 132, 0.2)",
        fill: true,
        tension: 0.4,
        parsing: {
          xAxisKey: "x",
          yAxisKey: "y",
        },
      },
    ],
  };

  const options = {
    responsive: true,
    plugins: {
      legend: {
        position: "top",
      },
      title: {
        display: true,
        text: `Income & Outcome`,
      },
      tooltip: {
        callbacks: {
          label: function (context) {
            let label = context.dataset.label || "";
            if (context.raw.name) {
              label += ": " + context.raw.name + " - " + context.raw.y;
            } else {
              label += ": " + context.raw.y;
            }
            return label;
          },
        },
      },
    },
    scales: {
      x: {
        title: {
          display: true,
          text: "Date",
        },
      },
      y: {
        title: {
          display: true,
          text: "Amount Rp",
        },
      },
    },
  };

  // Create a new chart instance
  window.chart = new Chart(ctx, {
    type: "line",
    data: data,
    options: options,
  });
}


function recreateCanvas() {
  // Get the container that holds the canvas (e.g., <div id="chartContainer">)
  const chartContainer = document.getElementById("chartContainer");

  // Remove the old canvas (if it exists)
  const existingCanvas = document.getElementById("profitLossIncomeChart");
  if (existingCanvas) {
    chartContainer.removeChild(existingCanvas);
  }

  // Create a new canvas element
  const newCanvas = document.createElement("canvas");
  newCanvas.id = "profitLossIncomeChart"; // Set the ID of the new canvas
  chartContainer.appendChild(newCanvas); // Append the new canvas to the container
}

function notifikasi(status, pesan) {
  if (status === true) {
    toastr.success(pesan);
  } else {
    toastr.error(pesan);
  }
}
