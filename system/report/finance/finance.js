// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarFinance.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarFinance").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarFinance:", error));
//   if (document.readyState === "complete") {
//     daftarFinance();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarFinance();
});

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

    cariDaftarFinance();
  });

});

function daftarFinance() {
  $.ajax({
    url: "daftarFinance.php",
    type: "post",
    data: {
      flagFinance: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarFinance").html(data);
      $("#pagination").html($(data).find("#pagination").html());
    },
  });
}





function cariDaftarFinance() {
  const searchQuery = $("#searchQuery").val();
  const rentang = $("#rentang").val();
  if (searchQuery || rentang) {
    $.ajax({
      url: "daftarFinance.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        rentang: rentang,
        flagFinance: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarFinance").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarFinance.php",
      type: "post",
      data: {
        flagFinance: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarFinance").html(data);
      },
    });
  }
}

function generateReport() {
  const rentang = $("#rentang").val();
  const form = document.createElement("form");
  form.method = "POST";
  form.action = "pdf/";
  form.target = "_blank";

  const searchQueryInput = document.createElement("input");

  const rentangInput = document.createElement("input");
  rentangInput.type = "hidden";
  rentangInput.name = "rentang";
  rentangInput.value = rentang;
  form.appendChild(rentangInput);

  const flagFinanceInput = document.createElement("input");
  flagFinanceInput.type = "hidden";
  flagFinanceInput.name = "flagFinance";
  flagFinanceInput.value = "cari";
  form.appendChild(flagFinanceInput);

  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);
}



function notifikasi(status, pesan) {
  if (status === true) {
    toastr.success(pesan);
  } else {
    toastr.error(pesan);
  }
}
