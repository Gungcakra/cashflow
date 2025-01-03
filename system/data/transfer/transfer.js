// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarTransfer.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarTransfer").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarTransfer:", error));
//   if (document.readyState === "complete") {
//     daftarTransfer();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarTransfer();
});

function daftarTransfer() {
  $.ajax({
    url: "daftarTransfer.php",
    type: "post",
    data: {
      flagTransfer: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarTransfer").html(data);
      $("#pagination").html($(data).find("#pagination").html());
    },
  });
}


function deleteTransfer(id) {
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
        url: "prosesTransfer.php",
        type: "post",
        data: {
          idTransfer: id,
          flagTransfer: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarTransfer();
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
    url: "daftarTransfer.php",
    data: {
      flagTransfer: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarTransfer").html(data);
    },
  });
}

function prosesTransfer() {
  const formTransfer = $("#formTransferInput")[0];
  const dataForm = new FormData(formTransfer);

  $.ajax({
    url: "../prosesTransfer.php",
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
        }, 500); 
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", textStatus, errorThrown);
    },
  });
  
}


function cariDaftarTransfer() {
  const searchQuery = $("#searchQuery").val();
  console.log(searchQuery);
  const limit = $("#limit").val();
  if (searchQuery || limit) {
    $.ajax({
      url: "daftarTransfer.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        limit: limit,
        flagTransfer: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarTransfer").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarTransfer.php",
      type: "post",
      data: {
        flagTransfer: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarTransfer").html(data);
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
