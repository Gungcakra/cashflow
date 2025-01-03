// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarGoal.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarGoal").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarGoal:", error));
//   if (document.readyState === "complete") {
//     daftarGoal();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarGoal();
});

function daftarGoal() {
  $.ajax({
    url: "daftarGoal.php",
    type: "post",
    data: {
      flagGoal: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarGoal").html(data);
      $("#pagination").html($(data).find("#pagination").html());
    },
  });
}


function deleteGoal(id) {
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
        url: "prosesGoal.php",
        type: "post",
        data: {
          idGoal: id,
          flagGoal: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarGoal();
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
    url: "daftarGoal.php",
    data: {
      flagGoal: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarGoal").html(data);
    },
  });
}

function prosesGoal() {
  const formGoal = $("#formGoalInput")[0];
  const dataForm = new FormData(formGoal);

  $.ajax({
    url: "../prosesGoal.php",
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


function cariDaftarGoal() {
  const searchQuery = $("#searchQuery").val();
  console.log(searchQuery);
  const limit = $("#limit").val();
  if (searchQuery || limit) {
    $.ajax({
      url: "daftarGoal.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        limit: limit,
        flagGoal: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarGoal").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarGoal.php",
      type: "post",
      data: {
        flagGoal: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarGoal").html(data);
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
