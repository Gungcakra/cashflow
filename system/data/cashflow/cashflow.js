// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarCashflow.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarCashflow").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarCashflow:", error));
//   if (document.readyState === "complete") {
//     daftarCashflow();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarCashflow();
});

function daftarCashflow() {
  $.ajax({
    url: "daftarCashflow.php",
    type: "post",
    data: {
      flagCashflow: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarCashflow").html(data);
      $("#pagination").html($(data).find("#pagination").html());
    },
  });
}


function deleteCashflow(id) {
  Swal.fire({
    title: "Are You Sure?",
    text: "Once canceled, the process cannot be undone!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes!",
    cancelButtonText: "Cancel!",
  }).then(function (result) {
    if (result.isConfirmed) {
      $.ajax({
        url: "prosesCashflow.php",
        type: "post",
        data: {
          idCashflow: id,
          flagCashflow: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarCashflow();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error:", textStatus, errorThrown);
          Swal.fire("Error", "Something went wrong!", "error");
        },
      });
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Canceled", "Proses Canceled!", "error");
    }
  });
}

function loadPage(pageNumber) {
  const limit = $("#limit").val();
  $.ajax({
    type: "POST",
    url: "daftarCashflow.php",
    data: {
      flagCashflow: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarCashflow").html(data);
    },
  });
}

function prosesCashflow() {
  const formCashflow = $("#formCashflowInput")[0];
  const dataForm = new FormData(formCashflow);

  $.ajax({
    url: "../prosesCashflow.php",
    type: "post",
    enctype: "multipart/form-data",
    processData: false,
    contentType: false,
    data: dataForm,
    dataType: "json",
    success: function (data) {
      const { status, pesan } = data;
      notifikasi(status, pesan);
      if (status) {
        setTimeout(function() {
          window.location.href = "../";
        }, 500); // Delay the redirect to allow the notification to show
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", textStatus, errorThrown);
    },
  });
  
}


function cariDaftarCashflow() {
  const searchQuery = $("#searchQuery").val();
  console.log(searchQuery);
  const limit = $("#limit").val();
  if (searchQuery || limit) {
    $.ajax({
      url: "daftarCashflow.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        limit: limit,
        flagCashflow: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarCashflow").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarCashflow.php",
      type: "post",
      data: {
        flagCashflow: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarCashflow").html(data);
      },
    });
  }
}



function notifikasi(status, pesan) {
  if (status === true) {
    toastr.success(pesan);
  } else {
    toastr.error(pesan);
  }
}
