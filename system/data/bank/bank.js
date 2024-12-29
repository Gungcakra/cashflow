// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarBank.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarBank").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarBank:", error));
//   if (document.readyState === "complete") {
//     daftarBank();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarBank();
});

function daftarBank() {
  $.ajax({
    url: "daftarBank.php",
    type: "post",
    data: {
      flagBank: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarBank").html(data);
      $("#pagination").html($(data).find("#pagination").html());
    },
  });
}


function deleteBank(id) {
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
        url: "prosesBank.php",
        type: "post",
        data: {
          idBank: id,
          flagBank: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarBank();
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
    url: "daftarBank.php",
    data: {
      flagBank: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarBank").html(data);
    },
  });
}

function prosesBank() {
  const formBank = $("#formBankInput")[0];
  const dataForm = new FormData(formBank);

  $.ajax({
    url: "../prosesBank.php",
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


function cariDaftarBank() {
  const searchQuery = $("#searchQuery").val();
  console.log(searchQuery);
  const limit = $("#limit").val();
  if (searchQuery || limit) {
    $.ajax({
      url: "daftarBank.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        limit: limit,
        flagBank: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarBank").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarBank.php",
      type: "post",
      data: {
        flagBank: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarBank").html(data);
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
