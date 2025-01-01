// document.addEventListener("DOMContentLoaded", function () {
//   fetch("chartCashflow.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("chartCashflow").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading chartCashflow:", error));
//   if (document.readyState === "complete") {
//     chartCashflow();
//   }
// });
// document.addEventListener("DOMContentLoaded", function (event) {
//   chartCashflow();
// });

// function chartCashflow() {
//   $.ajax({
//     url: "chartCashflow.php",
//     type: "post",
//     data: {
//       flagCashflow: "daftar",
//     },
//     beforeSend: function () {},
//     success: function (data, status) {
//       $("#chartCashflow").html(data);
//     },
//   });
// }


// function cariDaftarCashflow() {
//   const searchQuery = $("#searchQuery").val();
//   console.log(searchQuery);
//   const limit = $("#limit").val();
//   if (searchQuery || limit) {
//     $.ajax({
//       url: "chartCashflow.php",
//       type: "post",
//       data: {
//         searchQuery: searchQuery,
//         limit: limit,
//         flagCashflow: "cari",
//       },
//       beforeSend: function () {},
//       success: function (data, status) {
//         $("#chartCashflow").html(data);
//       },
//     });
//   } else {
//     $.ajax({
//       url: "chartCashflow.php",
//       type: "post",
//       data: {
//         flagCashflow: "daftar",
//       },
//       beforeSend: function () {},
//       success: function (data, status) {
//         $("#chartCashflow").html(data);
//       },
//     });
//   }
// }



function notifikasi(status, pesan) {
  if (status === true) {
    toastr.success(pesan);
  } else {
    toastr.error(pesan);
  }
}
